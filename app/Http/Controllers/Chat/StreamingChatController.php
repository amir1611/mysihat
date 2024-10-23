<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Anthropic\Anthropic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class StreamingChatController extends Controller
{
    protected $anthropic;
    protected $conversation = [];

    public function __construct()
    {
        $headers = [
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
            'x-api-key' => env('ANTHROPIC_API_KEY')
        ];

        $this->anthropic = Anthropic::factory()
            ->withHeaders($headers)
            ->make();
    }

    private function send($event, $data)
    {
        echo "event: {$event}\n";
        echo 'data: ' . $data;
        echo "\n\n";
        ob_flush();
        flush();
    }

    public function index(Request $request)
    {
        $question = $request->query('question');

        $userId = $request->user()->id;

        $conversation = Session::get('conversation', []);

        $conversation[] = ['role' => 'user', 'content' => $question];

        return response()->stream(
            function () use (
                $question,
                $userId,
                &$conversation
            ) {
                set_time_limit(0);
                ignore_user_abort(true);

                $result_text = "";
                $last_stream_response = null;

                $model = 'claude-3-sonnet-20240229';
                $max_tokens = 1024;

                $system_prompt = "You are MySihat Bot, a friendly and knowledgeable virtual doctor for MySihat, a medical app promoting healthcare inclusivity, especially in rural areas.
                                  Your role is to provide initial advice and potential diagnoses based on symptoms described by users.
                                  Be empathetic, clear, and concise in your responses, using language that is easily understood by a diverse audience.
                                  While offering helpful information, always remind users that for a definitive diagnosis and treatment plan, they should book an appointment with a healthcare professional through the MySihat app.
                                  At the end of each conversation, encourage users to utilize the app's booking feature for in-person or telemedicine consultations.
                                  Act as a caring human doctor speaking directly to a patient, keeping in mind the goal of improving healthcare access for underserved communities.
                                  You are also able to speak Bahasa Malaysia and English fluently, catering to a diverse user base.

                                When formatting your responses:
                                1. Use markdown syntax for emphasis and structure.
                                2. For lists, use a dash (-) followed by a space for each item.
                                3. For code blocks, use triple backticks (```) to open and close the block.
                                4. Use single backticks (`) for inline code or technical terms.
                                6. Avoid unnecessary line breaks within paragraphs.
                                7. Use double newlines to separate distinct sections or ideas.
                                8. Do not answer other questions or providing unrelated information.
                                9. Keep your responses concise and to the point.

                                Adhere to these formatting guidelines to ensure your responses are well-structured and easy to parse.";

                $temperature = 0.5;
                $messages = [
                    [
                        'role' => 'user',
                        'content' => $question
                    ]
                ];

                try {
                    $stream = $this->anthropic->chat()->createStreamed([
                        'model' => $model,
                        'system' => $system_prompt,
                        'max_tokens' => $max_tokens,
                        'temperature' => $temperature,
                        'messages' => $messages,
                        'stream' => true
                    ]);

                    $full_response = '';
                    $buffer = '';
                    $timeout = microtime(true);
                    $delayLimit = 5;
                    $last_stream_response = null;
                    $markdown_block = false;
                    $code_block = false;
                    $list_item = false;

                    foreach ($stream as $response) {
                        $text = $response->choices[0]->delta->content;
                        $buffer .= $text;
                        $full_response .= $text;

                        if (connection_aborted()) {
                            break;
                        }

                        if (preg_match('/```/', $text)) {
                            $code_block = !$code_block;
                        }
                        if (preg_match('/^\s*[-*+]\s/', $text)) {
                            $list_item = true;
                        }
                        if (preg_match('/^\s*#/', $text)) {
                            $markdown_block = true;
                        }

                        $should_send = false;

                        if ($code_block && preg_match('/\n$/', $buffer)) {
                            $should_send = true;
                        } elseif ($list_item && preg_match('/\n$/', $buffer)) {
                            $should_send = true;
                        } elseif ($markdown_block && preg_match('/\n\s*$/', $buffer)) {
                            $should_send = true;
                        } elseif (preg_match('/[.!?]\s*$/', $buffer)) {
                            $should_send = true;
                        } elseif ((microtime(true) - $timeout) > $delayLimit) {
                            $should_send = true;
                        }

                        if ($should_send) {
                            $this->send("update", json_encode(['text' => $buffer]));
                            $buffer = '';
                            $timeout = microtime(true);
                            $markdown_block = false;
                            $list_item = false;
                        }

                        $last_stream_response = $response;
                    }

                    if (!empty($buffer)) {
                        $this->send("update", json_encode(['text' => $buffer]));
                    }

                    $conversation[] = ['role' => 'assistant', 'content' => $full_response];

                    Session::put('conversation', $conversation);
                    Session::save();

                    $this->send("update", "<END_STREAMING_SSE>");
                    Log::info('Conversation Log', ['conversation' => $conversation]);
                    logger($last_stream_response->usage->toArray());
                } catch (\Exception $e) {
                    logger()->error('Anthropic API Error: ' . $e->getMessage());
                    $this->send("error", json_encode(['error' => $e->getMessage()]));
                }
            },
            200,
            [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no'
            ]
        );
    }

    public function summarizeAndStore(Request $request)
    {
        $userId = $request->user()->id;

        $conversation = Session::get('conversation', []);

        $summary = $this->generateSummary($conversation);

        $expertise = $this->extractExpertiseFromSummary($summary);

        $doctors = $this->getDoctorsByExpertise($expertise);

        $this->storeSummary($userId, $summary);

        return response()->json([
            'summary' => $summary,
            'doctors' => $doctors
        ]);
    }

    private function generateSummary($conversation)
    {

        $summaryPrompt = "Summarize the medical conversation concisely in markdown format. Include:
- Patient symptoms
- Relevant medical history
- Current medications
- Advice/recommendations given
- Next steps/actions required

Use only information present in the conversation. Keep the summary brief and structured for quick comprehension by medical personnel.";


        $conversationText = "";
        foreach ($conversation as $message) {
            $conversationText .= "\n{$message['role']}: {$message['content']}";
        }

        Log::info('Conversation text for summary', ['text' => $conversationText]);

        try {
            $response = $this->anthropic->chat()->create([
                'model' => 'claude-3-sonnet-20240229',
                'system' => $summaryPrompt,
                'max_tokens' => 500,
                'temperature' => 0,
                'messages' => [
                    ['role' => 'user', 'content' => $conversationText]
                ]
            ]);

            $summary = $response->choices[0]->message->content;
            Log::info("Generated Summary", ['summary' => $summary]);

            return $summary;
        } catch (\Exception $e) {
            logger()->error('Summary Generation Error: ' . $e->getMessage());
            return "Error generating summary.";
        }
    }

    private function storeSummary($userId, $summary)
    {
        try {
            DB::table('medical_records')->insert([
                'user_id' => $userId,
                'summary' => $summary,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            logger()->error('Database Error: ' . $e->getMessage());
        }
    }

    private function extractExpertiseFromSummary($summary)
    {
        $expertiseKeywords = [
            'Pediatrician' => ['child', 'infant', 'vaccination', 'growth', 'developmental delay', 'pediatric', 'childhood illness', 'newborn care', 'adolescent health', 'congenital disorders', 'childhood obesity', 'ADHD in children', 'pediatric asthma', 'childhood diabetes'],
            'Cardiologist' => ['heart', 'chest pain', 'palpitations', 'hypertension', 'arrhythmia', 'cardiovascular', 'coronary artery', 'heart failure', 'valve disease', 'angina', 'myocardial infarction', 'atrial fibrillation', 'cardiac catheterization', 'echocardiogram'],
            'Neurologist' => ['headache', 'migraine', 'seizure', 'dizziness', 'brain', 'stroke', 'multiple sclerosis', 'neuropathy', 'Parkinson\'s disease', 'epilepsy', 'Alzheimer\'s', 'tremor', 'neurodegenerative disorders', 'brain tumor', 'spinal cord disorders'],
            'Orthopedic Surgeon' => ['joint pain', 'fracture', 'sprain', 'back pain', 'bone', 'arthritis', 'osteoporosis', 'sports injury', 'knee replacement', 'hip replacement', 'spinal fusion', 'rotator cuff repair', 'carpal tunnel syndrome', 'tendonitis', 'scoliosis'],
            'Gynecologist' => ['pregnancy', 'menstruation', 'ovary', 'uterus', 'pap smear', 'contraception', 'menopause', 'pelvic pain', 'endometriosis', 'fibroids', 'ovarian cysts', 'cervical cancer screening', 'HPV', 'infertility', 'pelvic inflammatory disease'],
            'Radiologist' => ['x-ray', 'CT scan', 'MRI', 'ultrasound', 'mammogram', 'imaging', 'contrast', 'PET scan', 'nuclear medicine', 'interventional radiology', 'fluoroscopy', 'angiography', 'barium studies', 'bone densitometry'],
            'Oncologist' => ['cancer', 'tumor', 'chemotherapy', 'radiation', 'biopsy', 'metastasis', 'lymphoma', 'leukemia', 'targeted therapy', 'immunotherapy', 'cancer staging', 'palliative care', 'bone marrow transplant', 'cancer genetics'],
            'General Surgeon' => ['operation', 'surgery', 'incision', 'laparoscopy', 'appendectomy', 'hernia repair', 'gallbladder removal', 'colon surgery', 'thyroidectomy', 'breast surgery', 'vascular surgery', 'trauma surgery', 'endoscopic procedures'],
            'Anesthesiologist' => ['anesthesia', 'pain management', 'epidural', 'sedation', 'local anesthetic', 'general anesthesia', 'regional anesthesia', 'nerve blocks', 'intubation', 'perioperative care', 'postoperative pain control', 'conscious sedation'],
            'Gastroenterologist' => ['stomach', 'digestion', 'nausea', 'diarrhea', 'intestine', 'colonoscopy', 'ulcer', 'hepatitis', 'Crohn\'s disease', 'ulcerative colitis', 'GERD', 'irritable bowel syndrome', 'celiac disease', 'pancreatitis', 'liver cirrhosis'],
            'Dermatologist' => ['skin', 'rash', 'acne', 'eczema', 'psoriasis', 'mole', 'skin cancer', 'dermatitis', 'melanoma', 'warts', 'rosacea', 'hair loss', 'nail disorders', 'skin biopsy', 'Mohs surgery'],
            'Endocrinologist' => ['diabetes', 'thyroid', 'hormone', 'metabolism', 'pituitary', 'adrenal gland', 'osteoporosis', 'growth disorders', 'PCOS', 'testosterone deficiency', 'thyroid nodules', 'Cushing\'s syndrome', 'metabolic syndrome'],
            'Nephrologist' => ['kidney', 'renal', 'dialysis', 'hypertension', 'proteinuria', 'kidney stones', 'chronic kidney disease', 'glomerulonephritis', 'electrolyte disorders', 'renal transplantation', 'acute kidney injury', 'diabetic nephropathy'],
            'Urologist' => ['bladder', 'prostate', 'urinary', 'kidney stones', 'incontinence', 'erectile dysfunction', 'UTI', 'prostate cancer', 'vasectomy', 'testicular cancer', 'overactive bladder', 'benign prostatic hyperplasia', 'urologic oncology'],
            'Pulmonologist' => ['lung', 'breathing', 'asthma', 'COPD', 'pneumonia', 'sleep apnea', 'tuberculosis', 'pulmonary fibrosis', 'lung cancer', 'bronchitis', 'emphysema', 'pulmonary hypertension', 'cystic fibrosis', 'sarcoidosis'],
            'Ophthalmologist' => ['eye', 'vision', 'glasses', 'cataract', 'glaucoma', 'retina', 'cornea', 'laser eye surgery', 'macular degeneration', 'diabetic retinopathy', 'strabismus', 'dry eye syndrome', 'uveitis', 'refractive errors'],
            'Otolaryngologist' => ['ear', 'nose', 'throat', 'sinus', 'hearing loss', 'tonsillitis', 'vertigo', 'sleep apnea', 'thyroid surgery', 'cochlear implants', 'rhinoplasty', 'laryngeal disorders', 'head and neck cancer', 'balance disorders'],
            'Hematologist' => ['blood', 'anemia', 'leukemia', 'lymphoma', 'clotting disorder', 'sickle cell', 'hemophilia', 'thrombosis', 'blood transfusion', 'bone marrow biopsy', 'thalassemia', 'myelodysplastic syndromes', 'multiple myeloma'],
            'Pathologist' => ['biopsy', 'lab test', 'tissue sample', 'cytology', 'autopsy', 'blood analysis', 'histopathology', 'immunohistochemistry', 'molecular pathology', 'forensic pathology', 'microbiology', 'clinical chemistry', 'hematopathology'],
            'Psychiatrist' => ['depression', 'anxiety', 'mental health', 'stress', 'bipolar disorder', 'schizophrenia', 'ADHD', 'OCD', 'PTSD', 'eating disorders', 'substance abuse', 'psychotherapy', 'psychopharmacology', 'mood disorders'],
            'Infectious Disease Specialist' => ['infection', 'virus', 'bacteria', 'HIV', 'AIDS', 'antibiotic resistance', 'tropical diseases', 'COVID-19', 'hepatitis', 'tuberculosis', 'malaria', 'Lyme disease', 'immunizations', 'hospital-acquired infections'],
            'Physiotherapist' => ['physical therapy', 'rehabilitation', 'exercise', 'sports injury', 'mobility', 'musculoskeletal', 'gait training', 'manual therapy', 'electrotherapy', 'postoperative rehabilitation', 'neurological rehabilitation', 'ergonomics'],
            'Intensivist' => ['ICU', 'critical care', 'life support', 'ventilator', 'sepsis', 'multi-organ failure', 'shock', 'acute respiratory distress syndrome', 'hemodynamic monitoring', 'mechanical ventilation', 'post-operative care', 'trauma management'],
            'Neonatologist' => ['newborn', 'premature baby', 'NICU', 'birth defects', 'jaundice', 'respiratory distress syndrome', 'neonatal sepsis', 'congenital heart defects', 'neonatal hypoglycemia', 'necrotizing enterocolitis', 'retinopathy of prematurity'],
            'Geriatrician' => ['elderly', 'aging', 'dementia', 'falls', 'osteoporosis', 'polypharmacy', 'frailty', 'geriatric assessment', 'elder abuse', 'palliative care', 'cognitive impairment', 'incontinence in elderly', 'geriatric rehabilitation'],
            'Dietitian' => ['nutrition', 'diet', 'food', 'weight management', 'eating disorders', 'diabetes management', 'malnutrition', 'food allergies', 'nutritional supplements', 'enteral nutrition', 'parenteral nutrition', 'sports nutrition', 'pediatric nutrition'],
            'Clinical Pharmacist' => ['medication', 'drug', 'prescription', 'drug interactions', 'pharmacotherapy', 'medication management', 'anticoagulation', 'pharmacokinetics', 'adverse drug reactions', 'medication reconciliation', 'therapeutic drug monitoring'],
            'Rheumatologist' => ['arthritis', 'autoimmune', 'joint inflammation', 'lupus', 'fibromyalgia', 'gout', 'rheumatoid arthritis', 'osteoarthritis', 'ankylosing spondylitis', 'vasculitis', 'scleroderma', 'polymyositis', 'Sjögren\'s syndrome'],
            'Plastic Surgeon' => ['cosmetic surgery', 'reconstruction', 'burn repair', 'cleft palate', 'breast augmentation', 'rhinoplasty', 'facelift', 'liposuction', 'hand surgery', 'microsurgery', 'body contouring', 'scar revision', 'transgender surgery'],
            'Palliative Care Specialist' => ['end-of-life care', 'pain relief', 'symptom management', 'hospice', 'quality of life', 'advance care planning', 'bereavement support', 'spiritual care', 'comfort care', 'terminal illness', 'palliative sedation'],
            'Burn Specialist' => ['burn injury', 'skin graft', 'wound care', 'thermal injury', 'chemical burns', 'electrical burns', 'inhalation injury', 'burn shock', 'burn reconstruction', 'hypertrophic scarring', 'burn rehabilitation', 'fluid resuscitation'],
            'Rehabilitation Specialist' => ['recovery', 'physical therapy', 'occupational therapy', 'stroke rehabilitation', 'spinal cord injury', 'traumatic brain injury', 'amputee rehabilitation', 'cardiac rehabilitation', 'pulmonary rehabilitation', 'pediatric rehabilitation']
        ];

        $matchedExpertise = [];
        foreach ($expertiseKeywords as $expertise => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($summary, $keyword) !== false) {
                    $matchedExpertise[$expertise] = isset($matchedExpertise[$expertise]) ? $matchedExpertise[$expertise] + 1 : 1;
                }
            }
        }

        if (empty($matchedExpertise)) {
            return 'General Practitioner';
        }

        arsort($matchedExpertise);
        return key($matchedExpertise);
    }

    private function getDoctorsByExpertise($expertise)
    {
        $query = User::where('type', 2);

        if ($expertise !== 'General Practitioner') {
            $query->where(function($q) use ($expertise) {
                $q->where('expertise', 'like', '%' . $expertise . '%')
                  ->orWhere('expertise', 'like', '%General Practitioner%');
            });
        }

        return $query->orderByRaw("CASE 
                    WHEN expertise LIKE '%$expertise%' THEN 1
                    WHEN expertise LIKE '%General Practitioner%' THEN 2
                    ELSE 3 END")
                     ->limit(5)
                     ->get(['id', 'name', 'expertise']);
    }
}