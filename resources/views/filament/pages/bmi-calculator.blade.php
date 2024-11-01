<x-filament-panels::page>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MySihat</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">




        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <!-- Add this line in the head section -->
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">

    </head>
    <div class="container mx-auto">
        <div class="flex ">
            <div class="w-full mr-3 lg:w-5/12 xl:w-1/3">
                <div class="mb-4 bg-white rounded-lg shadow-lg">
                    <div class="flex items-center px-4 py-3 rounded-t-lg bg-violet-100">
                        <i class="mr-2 text-violet-600 fas fa-robot"></i>
                        <h6 class="font-semibold text-violet-600">Health Metrics Input</h6>
                    </div>
                    <div class="p-4">
                        <div class="mb-4 text-center">
                            <img src="{{ asset('build/assets/mysihatbot.png') }}" class="w-20 mx-auto mb-3">
                            <p class="text-gray-600">Hello {{ Auth::user()->name }}, I'll analyze your health metrics.
                            </p>
                        </div>
                        <form id="bmiForm">
                            <div class="mb-4">
                                <label for="height" class="block text-sm font-medium text-gray-700">Height
                                    (cm)</label>
                                <input type="number"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"
                                    id="height" name="height" required step="0.1">
                            </div>
                            <div class="mb-4">
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight
                                    (kg)</label>
                                <input type="number"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-violet-500 focus:border-violet-500"
                                    id="weight" name="weight" required step="0.1">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Gender</label>
                                <input type="text"
                                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm"
                                    value="{{ ucfirst(Auth::user()->gender) }}" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Age</label>
                                <input type="text"
                                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm"
                                    value="{{ $this->age }} years" readonly>
                            </div>
                            <button type="submit"
                                class="w-full py-2 text-white rounded-md shadow bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">
                                <i class="mr-2 fas fa-calculator"></i> Analyze Health Metrics
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-7/12 xl:w-2/3">
                <div id="resultCard" class="hidden mb-4 bg-white rounded-lg shadow-lg">
                    <div class="flex items-center px-4 py-3 rounded-t-lg bg-violet-100">
                        <i class="mr-2 text-violet-600 fas fa-chart-line"></i>
                        <h6 class="font-semibold text-violet-600">AI Health Analysis Results</h6>
                    </div>
                    <div class="p-4">
                        <div class="hidden text-center analysis-loading">
                            <div class="mb-3 text-violet-600 spinner-border"></div>
                            <p class="text-gray-600">Analyzing your health metrics...</p>
                        </div>
                        <div class="analysis-results">
                            <div class="flex flex-wrap mb-4">
                                <div class="w-1/2 text-center">
                                    <div class="mb-1 text-3xl font-bold" id="bmiValue">-</div>
                                    <div class="text-xs font-semibold uppercase text-violet-600">BMI Score</div>
                                </div>
                                <div class="w-1/2 text-center">
                                    <div class="mb-1 text-2xl font-bold" id="categoryValue">-</div>
                                    <div class="text-xs font-semibold uppercase text-violet-600">Health Category</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="p-4 bg-gray-100 rounded-lg">
                                    <h6 class="mb-3 font-semibold text-violet-600"><i class="mr-2 fas fa-brain"></i> AI
                                        Health Insights</h6>
                                    <div id="aiInsights" class="text-gray-700"></div>
                                </div>
                            </div>
                            <div class="flex flex-wrap mb-4">
                                <div class="w-full md:w-1/2">
                                    <div class="h-full p-4 border-l-4 rounded-lg shadow border-violet-300">
                                        <div class="mb-1 text-xs font-semibold uppercase text-violet-600">Ideal Weight
                                            Range</div>
                                        <div class="text-lg font-bold text-gray-800" id="idealWeightValue">-</div>
                                    </div>
                                </div>
                                <div class="w-full mt-4 md:w-1/2 md:mt-0">
                                    <div class="h-full p-4 border-l-4 border-yellow-300 rounded-lg shadow">
                                        <div class="mb-1 text-xs font-semibold text-yellow-600 uppercase">Health Risk
                                            Level</div>
                                        <div class="text-lg font-bold text-gray-800" id="healthRiskValue">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <canvas id="bmiChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let bmiChart = null;
            document.getElementById('bmiForm').addEventListener('submit', function(e) {
                e.preventDefault();

                document.querySelector('.analysis-loading').style.display = 'block';
                document.querySelector('.analysis-results').style.display = 'none';
                document.getElementById('resultCard').style.display = 'block';
                const formData = {
                    weight: document.getElementById('weight').value,
                    height: document.getElementById('height').value
                };
                setTimeout(() => {
                    fetch('{{ route('bmi.calculate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            document.querySelector('.analysis-loading').style.display = 'none';
                            document.querySelector('.analysis-results').style.display = 'block';
                            document.getElementById('bmiValue').textContent = data.bmi;
                            document.getElementById('categoryValue').textContent = data.category;
                            document.getElementById('healthRiskValue').textContent = data.healthRisk;
                            document.getElementById('idealWeightValue').textContent =
                                `${data.idealWeightRange.min} - ${data.idealWeightRange.max} kg`;

                            const insights = generateAIInsights(data);
                            document.getElementById('aiInsights').innerHTML = insights;
                            updateChart(data.bmi);
                        })
                        .catch(error => console.error('Error:', error));
                }, 1500);
            });

            function generateAIInsights(data) {
                let insights = '<ul class="list-unstyled">';

                if (data.bmi < 18.5) {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-danger"><i class="mr-2 fas fa-exclamation-circle"></i>Underweight Status</h6>
            <ul class="pl-4 list-unstyled">
                <li class="mb-2"><i class="mr-2 fas fa-arrow-circle-right text-primary"></i>Your BMI of ${data.bmi} indicates you're underweight. This may impact your overall health.</li>
                <li class="mb-2"><i class="mr-2 fas fa-utensils text-primary"></i>Consider increasing your daily caloric intake by 300-500 calories.</li>
                <li class="mb-2"><i class="mr-2 fas fa-apple-alt text-primary"></i>Focus on nutrient-dense foods rich in proteins, healthy fats, and complex carbohydrates.</li>
                <li class="mb-2"><i class="mr-2 fas fa-user-md text-primary"></i>Consultation with a nutritionist is recommended for a personalized meal plan.</li>
            </ul>
        </li>`;
                } else if (data.bmi < 25) {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-success"><i class="mr-2 fas fa-check-circle"></i>Healthy Weight Status</h6>
            <ul class="pl-4 list-unstyled">
                <li class="mb-2"><i class="mr-2 fas fa-star text-primary"></i>Excellent! Your BMI of ${data.bmi} falls within the healthy range.</li>
                <li class="mb-2"><i class="mr-2 fas fa-heart text-primary"></i>Your current weight reduces risks of various health conditions.</li>
                <li class="mb-2"><i class="mr-2 fas fa-dumbbell text-primary"></i>Maintain this through regular exercise and balanced nutrition.</li>
                <li class="mb-2"><i class="mr-2 fas fa-clock text-primary"></i>Aim for 150 minutes of moderate exercise weekly to maintain your healthy status.</li>
            </ul>
        </li>`;
                } else if (data.bmi < 30) {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-warning"><i class="mr-2 fas fa-exclamation-circle"></i>Overweight Status</h6>
            <ul class="pl-4 list-unstyled">
                <li class="mb-2"><i class="mr-2 fas fa-weight text-primary"></i>Your BMI of ${data.bmi} indicates you're overweight, which may increase health risks.</li>
                <li class="mb-2"><i class="mr-2 fas fa-walking text-primary"></i>Consider incorporating 30 minutes of daily physical activity.</li>
                <li class="mb-2"><i class="mr-2 fas fa-carrot text-primary"></i>Focus on portion control and increasing vegetable intake.</li>
                <li class="mb-2"><i class="mr-2 fas fa-chart-line text-primary"></i>Aim for a gradual weight loss of 0.5-1 kg per week.</li>
            </ul>
        </li>`;
                } else {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-danger"><i class="mr-2 fas fa-exclamation-triangle"></i>Obesity Status</h6>
            <ul class="pl-4 list-unstyled">
                <li class="mb-2"><i class="mr-2 fas fa-heartbeat text-primary"></i>Your BMI of ${data.bmi} indicates obesity, which increases risks of various health conditions.</li>
                <li class="mb-2"><i class="mr-2 fas fa-user-md text-primary"></i>Professional medical consultation is strongly recommended.</li>
                <li class="mb-2"><i class="mr-2 fas fa-notes-medical text-primary"></i>Consider getting a full health assessment.</li>
                <li class="mb-2"><i class="mr-2 fas fa-chart-bar text-primary"></i>Work with healthcare providers to set realistic weight loss goals.</li>
            </ul>
        </li>`;
                }

                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold text-info"><i class="mr-2 fas fa-calendar-alt"></i>Age-Specific Insights</h6>
        <ul class="pl-4 list-unstyled">`;
                if (data.age < 30) {
                    insights +=
                        `
        <li class="mb-2"><i class="mr-2 fas fa-running text-primary"></i>Your age group typically has higher metabolism - great time to establish healthy habits.</li>
        <li class="mb-2"><i class="mr-2 fas fa-bone text-primary"></i>Focus on building strong bone density through weight-bearing exercises.</li>`;
                } else if (data.age < 50) {
                    insights +=
                        `
        <li class="mb-2"><i class="mr-2 fas fa-balance-scale text-primary"></i>Metabolism naturally slows in your age range - adjust portion sizes accordingly.</li>
        <li class="mb-2"><i class="mr-2 fas fa-heart-pulse text-primary"></i>Regular cardiovascular exercise becomes increasingly important.</li>`;
                } else {
                    insights +=
                        `
        <li class="mb-2"><i class="mr-2 fas fa-dumbbell text-primary"></i>Maintaining muscle mass is crucial at your age - consider strength training.</li>
        <li class="mb-2"><i class="mr-2 fas fa-bone text-primary"></i>Pay special attention to bone health through appropriate exercise and nutrition.</li>`;
                }
                insights += `</ul></li>`;

                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold text-purple"><i class="mr-2 fas fa-venus-mars"></i>Gender-Specific Considerations</h6>
        <ul class="pl-4 list-unstyled">`;
                if (data.gender === 'male') {
                    insights +=
                        `
        <li class="mb-2"><i class="mr-2 fas fa-mars text-primary"></i>Men typically have higher muscle mass, which may affect BMI readings.</li>
        <li class="mb-2"><i class="mr-2 fas fa-heart text-primary"></i>Regular blood pressure monitoring is particularly important for men.</li>`;
                } else {
                    insights +=
                        `
        <li class="mb-2"><i class="mr-2 fas fa-venus text-primary"></i>Women naturally carry more essential fat, which influences healthy BMI ranges.</li>
        <li class="mb-2"><i class="mr-2 fas fa-bone text-primary"></i>Consider calcium intake for bone health, especially important for women.</li>`;
                }
                insights += `</ul></li>`;

                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold text-danger"><i class="mr-2 fas fa-file-medical"></i>Health Risk Assessment</h6>
        <ul class="pl-4 list-unstyled">
            <li class="mb-2"><i class="mr-2 fas fa-clipboard-list text-primary"></i>Current Health Risk Level: ${data.healthRisk}</li>
            <li class="mb-2"><i class="mr-2 fas fa-weight text-primary"></i>Ideal Weight Range: ${data.idealWeightRange.min} - ${data.idealWeightRange.max} kg</li>
            <li class="mb-2"><i class="mr-2 fas fa-arrows-alt-v text-primary"></i>Weight difference from ideal range: ${calculateWeightDifference(data.weight, data.idealWeightRange)} kg</li>
        </ul>
    </li>`;
                insights += '</ul>';
                return insights;
            }

            function calculateWeightDifference(currentWeight, idealRange) {
                if (currentWeight < idealRange.min) {
                    return `${(idealRange.min - currentWeight).toFixed(1)} kg below ideal range`;
                } else if (currentWeight > idealRange.max) {
                    return `${(currentWeight - idealRange.max).toFixed(1)} kg above ideal range`;
                }
                return "Within ideal range";
            }

            function updateChart(bmiValue) {
                const ctx = document.getElementById('bmiChart').getContext('2d');
                if (bmiChart) {
                    bmiChart.destroy();
                }
                const data = {
                    labels: ['Underweight', 'Normal', 'Overweight', 'Obese Class I', 'Obese Class II', 'Obese Class III'],
                    datasets: [{
                        label: 'BMI Range',
                        data: [18.5, 25, 30, 35, 40],
                        backgroundColor: 'rgba(78, 115, 223, 0.2)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Your BMI',
                        data: Array(6).fill(bmiValue),
                        borderColor: 'red',
                        borderWidth: 2,
                        pointRadius: 0,
                        borderDash: [5, 5]
                    }]
                };
                bmiChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 45,
                                title: {
                                    display: true,
                                    text: 'BMI Value'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'BMI Categories Chart'
                            }
                        }
                    }
                });
            }
        </script>
    @endpush

</x-filament-panels::page>
