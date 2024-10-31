@extends('layouts.patient.patient-layout')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-robot mr-2"></i>
                AI Health Analysis
            </h1>
           
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <i class="fas fa-robot mr-2 text-primary"></i>
                        <h6 class="m-0 font-weight-bold text-primary">Health Metrics Input</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('build/assets/mysihatbot.png') }}" width="80" class="mb-3">
                            <p class="text-gray-600">Hello {{ Auth::user()->name }}, I'll analyze your health metrics.</p>
                        </div>

                        <form id="bmiForm">
                            <div class="form-group">
                                <label for="height">Height (cm)</label>
                                <input type="number" class="form-control" id="height" name="height" required
                                    step="0.1">
                            </div>
                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" required
                                    step="0.1">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <input type="text" class="form-control" value="{{ ucfirst(Auth::user()->gender) }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="text" class="form-control" value="{{ $age }} years" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-calculator mr-2"></i>Analyze Health Metrics
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4" id="resultCard" style="display: none;">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line mr-2 text-primary"></i>
                            <h6 class="m-0 font-weight-bold text-primary">AI Health Analysis Results</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="analysis-loading text-center" style="display: none;">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="sr-only">Analyzing...</span>
                            </div>
                            <p class="text-gray-600">Analyzing your health metrics...</p>
                        </div>

                        <div class="analysis-results">
                            <div class="row mb-4">
                                <div class="col-md-6 text-center">
                                    <div class="h1 mb-0 font-weight-bold" id="bmiValue">-</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BMI Score</div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="h3 mb-0 font-weight-bold" id="categoryValue">-</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Health Category
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold text-primary mb-3">
                                                <i class="fas fa-brain mr-2"></i>AI Health Insights
                                            </h6>
                                            <div id="aiInsights" class="text-gray-700">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Ideal Weight Range</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="idealWeightValue">-
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-left-warning shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Health Risk Level</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="healthRiskValue">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="chart-container">
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
                <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-circle mr-2"></i>Underweight Status</h6>
                <ul class="list-unstyled pl-4">
                    <li class="mb-2"><i class="fas fa-arrow-circle-right text-primary mr-2"></i>Your BMI of ${data.bmi} indicates you're underweight. This may impact your overall health.</li>
                    <li class="mb-2"><i class="fas fa-utensils text-primary mr-2"></i>Consider increasing your daily caloric intake by 300-500 calories.</li>
                    <li class="mb-2"><i class="fas fa-apple-alt text-primary mr-2"></i>Focus on nutrient-dense foods rich in proteins, healthy fats, and complex carbohydrates.</li>
                    <li class="mb-2"><i class="fas fa-user-md text-primary mr-2"></i>Consultation with a nutritionist is recommended for a personalized meal plan.</li>
                </ul>
            </li>`;
                } else if (data.bmi < 25) {
                    insights += `
            <li class="mb-3">
                <h6 class="font-weight-bold text-success"><i class="fas fa-check-circle mr-2"></i>Healthy Weight Status</h6>
                <ul class="list-unstyled pl-4">
                    <li class="mb-2"><i class="fas fa-star text-primary mr-2"></i>Excellent! Your BMI of ${data.bmi} falls within the healthy range.</li>
                    <li class="mb-2"><i class="fas fa-heart text-primary mr-2"></i>Your current weight reduces risks of various health conditions.</li>
                    <li class="mb-2"><i class="fas fa-dumbbell text-primary mr-2"></i>Maintain this through regular exercise and balanced nutrition.</li>
                    <li class="mb-2"><i class="fas fa-clock text-primary mr-2"></i>Aim for 150 minutes of moderate exercise weekly to maintain your healthy status.</li>
                </ul>
            </li>`;
                } else if (data.bmi < 30) {
                    insights += `
            <li class="mb-3">
                <h6 class="font-weight-bold text-warning"><i class="fas fa-exclamation-circle mr-2"></i>Overweight Status</h6>
                <ul class="list-unstyled pl-4">
                    <li class="mb-2"><i class="fas fa-weight text-primary mr-2"></i>Your BMI of ${data.bmi} indicates you're overweight, which may increase health risks.</li>
                    <li class="mb-2"><i class="fas fa-walking text-primary mr-2"></i>Consider incorporating 30 minutes of daily physical activity.</li>
                    <li class="mb-2"><i class="fas fa-carrot text-primary mr-2"></i>Focus on portion control and increasing vegetable intake.</li>
                    <li class="mb-2"><i class="fas fa-chart-line text-primary mr-2"></i>Aim for a gradual weight loss of 0.5-1 kg per week.</li>
                </ul>
            </li>`;
                } else {
                    insights += `
            <li class="mb-3">
                <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Obesity Status</h6>
                <ul class="list-unstyled pl-4">
                    <li class="mb-2"><i class="fas fa-heartbeat text-primary mr-2"></i>Your BMI of ${data.bmi} indicates obesity, which increases risks of various health conditions.</li>
                    <li class="mb-2"><i class="fas fa-user-md text-primary mr-2"></i>Professional medical consultation is strongly recommended.</li>
                    <li class="mb-2"><i class="fas fa-notes-medical text-primary mr-2"></i>Consider getting a full health assessment.</li>
                    <li class="mb-2"><i class="fas fa-chart-bar text-primary mr-2"></i>Work with healthcare providers to set realistic weight loss goals.</li>
                </ul>
            </li>`;
                }

            
                insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-info"><i class="fas fa-calendar-alt mr-2"></i>Age-Specific Insights</h6>
            <ul class="list-unstyled pl-4">`;

                if (data.age < 30) {
                    insights +=
                        `
            <li class="mb-2"><i class="fas fa-running text-primary mr-2"></i>Your age group typically has higher metabolism - great time to establish healthy habits.</li>
            <li class="mb-2"><i class="fas fa-bone text-primary mr-2"></i>Focus on building strong bone density through weight-bearing exercises.</li>`;
                } else if (data.age < 50) {
                    insights +=
                        `
            <li class="mb-2"><i class="fas fa-balance-scale text-primary mr-2"></i>Metabolism naturally slows in your age range - adjust portion sizes accordingly.</li>
            <li class="mb-2"><i class="fas fa-heart-pulse text-primary mr-2"></i>Regular cardiovascular exercise becomes increasingly important.</li>`;
                } else {
                    insights +=
                        `
            <li class="mb-2"><i class="fas fa-dumbbell text-primary mr-2"></i>Maintaining muscle mass is crucial at your age - consider strength training.</li>
            <li class="mb-2"><i class="fas fa-bone text-primary mr-2"></i>Pay special attention to bone health through appropriate exercise and nutrition.</li>`;
                }
                insights += `</ul></li>`;

         
                insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-purple"><i class="fas fa-venus-mars mr-2"></i>Gender-Specific Considerations</h6>
            <ul class="list-unstyled pl-4">`;

                if (data.gender === 'male') {
                    insights +=
                        `
            <li class="mb-2"><i class="fas fa-mars text-primary mr-2"></i>Men typically have higher muscle mass, which may affect BMI readings.</li>
            <li class="mb-2"><i class="fas fa-heart text-primary mr-2"></i>Regular blood pressure monitoring is particularly important for men.</li>`;
                } else {
                    insights +=
                        `
            <li class="mb-2"><i class="fas fa-venus text-primary mr-2"></i>Women naturally carry more essential fat, which influences healthy BMI ranges.</li>
            <li class="mb-2"><i class="fas fa-bone text-primary mr-2"></i>Consider calcium intake for bone health, especially important for women.</li>`;
                }
                insights += `</ul></li>`;

           
                insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-danger"><i class="fas fa-file-medical mr-2"></i>Health Risk Assessment</h6>
            <ul class="list-unstyled pl-4">
                <li class="mb-2"><i class="fas fa-clipboard-list text-primary mr-2"></i>Current Health Risk Level: ${data.healthRisk}</li>
                <li class="mb-2"><i class="fas fa-weight text-primary mr-2"></i>Ideal Weight Range: ${data.idealWeightRange.min} - ${data.idealWeightRange.max} kg</li>
                <li class="mb-2"><i class="fas fa-arrows-alt-v text-primary mr-2"></i>Weight difference from ideal range: ${calculateWeightDifference(data.weight, data.idealWeightRange)} kg</li>
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
@endsection
