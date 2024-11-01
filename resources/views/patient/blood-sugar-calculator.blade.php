@extends('layouts.patient.patient-layout')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-heartbeat mr-2"></i>Blood Sugar Analysis
            </h1>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <i class="fas fa-robot mr-2 text-primary"></i>
                        <h6 class="m-0 font-weight-bold text-primary">Blood Sugar Input</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('build/assets/mysihatbot.png') }}" width="80" class="mb-3">
                            <p class="text-gray-600">Hello {{ Auth::user()->name }}, I'll analyze your blood sugar level.
                            </p>
                        </div>

                        <form id="bloodSugarForm">
                            @csrf
                            <div class="form-group">
                                <label for="bloodSugar">Blood Sugar Level (mmol/L)</label>
                                <input type="number" class="form-control" id="bloodSugar" name="blood_sugar" required
                                    step="0.1" min="0">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-calculator mr-2"></i>Analyze Blood Sugar
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
                            <h6 class="m-0 font-weight-bold text-primary">AI Blood Sugar Analysis</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="analysis-loading text-center" style="display: none;">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="sr-only">Analyzing...</span>
                            </div>
                            <p class="text-gray-600">Analyzing your blood sugar level...</p>
                        </div>

                        <div class="analysis-results">
                            <div class="row mb-4">
                                <div class="col-md-6 text-center">
                                    <div class="h1 mb-0 font-weight-bold" id="sugarValue">-</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Blood Sugar Level
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="h3 mb-0 font-weight-bold" id="statusValue">-</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Status</div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold text-primary mb-3">
                                                <i class="fas fa-brain mr-2"></i>AI Health Insights
                                            </h6>
                                            <div id="aiInsights" class="text-gray-700"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Recommended Range</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">4.0 - 7.8 mmol/L</div>
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
                                <canvas id="sugarChart"></canvas>
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
            let sugarChart = null;

            document.getElementById('bloodSugarForm').addEventListener('submit', function(e) {
                e.preventDefault();

                document.querySelector('.analysis-loading').style.display = 'block';
                document.querySelector('.analysis-results').style.display = 'none';
                document.getElementById('resultCard').style.display = 'block';

                const formData = {
                    blood_sugar: document.getElementById('bloodSugar').value
                };

                setTimeout(() => {
                    fetch('{{ route('blood-sugar.analyze') }}', {
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

                            document.getElementById('sugarValue').textContent = data.bloodSugarLevel;
                            document.getElementById('statusValue').textContent = capitalizeFirst(data
                                .status);
                            document.getElementById('healthRiskValue').textContent = data.healthRisk;

                            const insights = generateAIInsights(data);
                            document.getElementById('aiInsights').innerHTML = insights;

                            updateChart(data.bloodSugarLevel);
                        })
                        .catch(error => console.error('Error:', error));
                }, 1500);
            });

            function generateAIInsights(data) {
                let insights = '<ul class="list-unstyled">';

                // Add status-based insights
                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold ${getStatusColor(data.status)}">
            <i class="fas fa-${getStatusIcon(data.status)} mr-2"></i>${capitalizeFirst(data.status)} Blood Sugar
        </h6>
        <ul class="list-unstyled pl-4">
            ${getStatusInsights(data)}
        </ul>
    </li>`;

                // Add time-based recommendations
                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold text-info">
            <i class="fas fa-clock mr-2"></i>Time-Based Recommendations
        </h6>
        <ul class="list-unstyled pl-4">
            ${getTimeBasedInsights()}
        </ul>
    </li>`;

                insights += '</ul>';
                return insights;
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'low':
                        return 'text-warning';
                    case 'normal':
                        return 'text-success';
                    case 'high':
                        return 'text-danger';
                    default:
                        return 'text-primary';
                }
            }

            function getStatusIcon(status) {
                switch (status) {
                    case 'low':
                        return 'arrow-down';
                    case 'normal':
                        return 'check-circle';
                    case 'high':
                        return 'arrow-up';
                    default:
                        return 'info-circle';
                }
            }

            function getStatusInsights(data) {
                const baseInsights = {
                    low: `
            <li class="mb-2"><i class="fas fa-exclamation-circle text-primary mr-2"></i>Your blood sugar is below the recommended range (< 4.0 mmol/L).</li>
            <li class="mb-2"><i class="fas fa-apple-alt text-primary mr-2"></i>Consider consuming fast-acting carbohydrates:</li>
            <ul class="ml-4">
                <li>½ cup fruit juice</li>
                <li>1 tablespoon honey</li>
                <li>4-5 glucose tablets</li>
            </ul>
            <li class="mb-2"><i class="fas fa-clock text-primary mr-2"></i>Monitor your levels every 15 minutes until normalized.</li>
            <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Watch for symptoms:</li>
            <ul class="ml-4">
                <li>Shakiness</li>
                <li>Sweating</li>
                <li>Confusion</li>
            </ul>`,
                    normal: `
            <li class="mb-2"><i class="fas fa-check text-success mr-2"></i>Your blood sugar is within the healthy range (4.0-7.8 mmol/L).</li>
            <li class="mb-2"><i class="fas fa-heart text-primary mr-2"></i>Maintain healthy habits:</li>
            <ul class="ml-4">
                <li>Regular exercise</li>
                <li>Balanced meals</li>
                <li>Consistent monitoring</li>
            </ul>
            <li class="mb-2"><i class="fas fa-chart-line text-primary mr-2"></i>Track patterns to maintain stability.</li>
            <li class="mb-2"><i class="fas fa-calendar-check text-success mr-2"></i>Schedule regular check-ups.</li>`,
                    high: `
            <li class="mb-2"><i class="fas fa-exclamation-triangle text-danger mr-2"></i>Your blood sugar is above the recommended range (> 7.8 mmol/L).</li>
            <li class="mb-2"><i class="fas fa-walking text-primary mr-2"></i>Immediate actions:</li>
            <ul class="ml-4">
                <li>Take a 15-minute walk</li>
                <li>Drink water</li>
                <li>Monitor ketones if applicable</li>
            </ul>
            <li class="mb-2"><i class="fas fa-user-md text-primary mr-2"></i>Contact healthcare provider if:</li>
            <ul class="ml-4">
                <li>Levels remain high</li>
                <li>You feel unwell</li>
                <li>You have ketones</li>
            </ul>`
                };
                return baseInsights[data.status] || '';
            }

            function getTimeBasedInsights() {
                return `
        <li class="mb-2"><i class="fas fa-sun text-warning mr-2"></i>Morning (Fasting):</li>
        <ul class="ml-4">
            <li>Test immediately upon waking</li>
            <li>Before any food or drink</li>
            <li>Target: 4.0-7.0 mmol/L</li>
        </ul>
        <li class="mb-2"><i class="fas fa-utensils text-primary mr-2"></i>Meal-Related Testing:</li>
        <ul class="ml-4">
            <li>Before meals: 4.0-7.0 mmol/L</li>
            <li>2 hours after meals: 5.0-10.0 mmol/L</li>
            <li>Record food intake with readings</li>
        </ul>
        <li class="mb-2"><i class="fas fa-moon text-info mr-2"></i>Evening:</li>
        <ul class="ml-4">
            <li>Before bed: 6.0-8.0 mmol/L</li>
            <li>Consider night testing if at risk</li>
            <li>Note any late snacks</li>
        </ul>`;
            }

            function getLifestyleInsights(data) {
                return `
        <li class="mb-2"><i class="fas fa-dumbbell text-primary mr-2"></i>Exercise Tips:</li>
        <ul class="ml-4">
            <li>Test before and after exercise</li>
            <li>Carry fast-acting sugar during activity</li>
            <li>Stay hydrated</li>
        </ul>
        <li class="mb-2"><i class="fas fa-apple-alt text-success mr-2"></i>Dietary Recommendations:</li>
        <ul class="ml-4">
            <li>Monitor carbohydrate intake</li>
            <li>Eat regular, balanced meals</li>
            <li>Include fiber-rich foods</li>
        </ul>
        <li class="mb-2"><i class="fas fa-bed text-info mr-2"></i>Lifestyle Factors:</li>
        <ul class="ml-4">
            <li>Maintain regular sleep schedule</li>
            <li>Manage stress levels</li>
            <li>Keep testing supplies ready</li>
        </ul>`;
            }

            function getTrendAnalysis(data) {
                return `
        <li class="mb-2"><i class="fas fa-chart-line text-primary mr-2"></i>Pattern Recognition:</li>
        <ul class="ml-4">
            <li>Track daily variations</li>
            <li>Note meal impacts</li>
            <li>Monitor exercise effects</li>
        </ul>
        <li class="mb-2"><i class="fas fa-calendar-alt text-success mr-2"></i>Long-term Management:</li>
        <ul class="ml-4">
            <li>Regular A1C testing</li>
            <li>Adjust treatment as needed</li>
            <li>Document lifestyle changes</li>
        </ul>`;
            }

            function updateInsightsPanel(data) {
                const insightsContainer = document.getElementById('aiInsights');
                insightsContainer.innerHTML = `
        <div class="accordion" id="insightsAccordion">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-robot mr-2"></i>AI Analysis Results
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        ${getStatusInsights(data)}
                        <hr>
                        ${getTimeBasedInsights()}
                        <hr>
                        ${getLifestyleInsights(data)}
                        <hr>
                        ${getTrendAnalysis(data)}
                    </ul>
                </div>
            </div>
        </div>`;
            }

            function updateChart(sugarLevel) {
                const ctx = document.getElementById('sugarChart').getContext('2d');

                if (sugarChart) {
                    sugarChart.destroy();
                }

                const data = {
                    labels: ['Low', 'Normal', 'High'],
                    datasets: [{
                        label: 'Blood Sugar Ranges (mmol/L)',
                        data: [4.0, 7.8, 11.1],
                        backgroundColor: 'rgba(78, 115, 223, 0.2)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1,
                        fill: false
                    }, {
                        label: 'Your Level',
                        data: Array(3).fill(sugarLevel),
                        borderColor: 'red',
                        borderWidth: 2,
                        pointRadius: 0,
                        borderDash: [5, 5]
                    }]
                };

                sugarChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 15,
                                title: {
                                    display: true,
                                    text: 'Blood Sugar Level (mmol/L)'
                                }
                            }
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Blood Sugar Ranges Chart'
                            }
                        }
                    }
                });
            }

            function capitalizeFirst(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        </script>
    @endpush
@endsection
