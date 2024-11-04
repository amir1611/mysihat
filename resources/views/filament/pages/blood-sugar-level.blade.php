<x-filament-panels::page>

    <head>
        <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">




        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    </head>
    <div class="container px-4 mx-auto">

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <div class="p-4 bg-white rounded-md shadow">
                    <div class="flex items-center mb-4">
                        <i class="mr-2 text-violet-500 fas fa-robot"></i>
                        <h6 class="text-sm font-bold text-violet-500">Blood Sugar Input</h6>
                    </div>
                    <div class="mb-4 text-center">
                        <img src="{{ asset('build/assets/mysihatbot.png') }}" alt="Bot" class="w-20 mb-3">
                        <p class="text-gray-600">Hello {{ Auth::user()->name }}, I'll analyze your blood sugar level.
                        </p>
                    </div>

                    <form id="bloodSugarForm">
                        @csrf
                        <div class="mb-4">
                            <label for="bloodSugar" class="block text-sm font-medium text-gray-700">Blood Sugar Level
                                (mmol/L)</label>
                            <input type="number" class="w-full p-2 mt-1 border border-gray-300 rounded-md"
                                id="bloodSugar" name="blood_sugar" required step="0.1" min="0">
                        </div>
                        <button type="submit"
                            class="w-full py-2 font-medium text-white transition rounded-md bg-violet-500 hover:bg-violet-600">
                            <i class="mr-2 fas fa-calculator"></i>Analyze Blood Sugar
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="hidden p-4 bg-white rounded-md shadow" id="resultCard">
                    <div class="flex items-center mb-4">
                        <i class="mr-2 text-violet-500 fas fa-chart-line"></i>
                        <h6 class="text-sm font-bold text-violet-500">AI Blood Sugar Analysis</h6>
                    </div>
                    <div class="hidden text-center analysis-loading">
                        <div class="w-12 h-12 mb-3 border-t-4 rounded-full border-violet-500 loader animate-spin"></div>
                        <p class="text-gray-600">Analyzing your blood sugar level...</p>
                    </div>

                    <div class="analysis-results">
                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div class="text-center">
                                <div class="text-4xl font-bold" id="sugarValue">-</div>
                                <div class="text-xs font-bold uppercase text-violet-500">Blood Sugar Level</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold" id="statusValue">-</div>
                                <div class="text-xs font-bold uppercase text-violet-500">Status</div>
                            </div>
                        </div>

                        <div class="p-4 mb-4 rounded-md bg-gray-50">
                            <h6 class="flex items-center mb-3 font-bold text-violet-500">
                                <i class="mr-2 fas fa-brain"></i>AI Health Insights
                            </h6>
                            <div id="aiInsights" class="text-gray-700"></div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                            <div class="p-4 bg-white border-l-4 rounded-md shadow-sm border-violet-300">
                                <div class="text-xs font-bold uppercase text-violet-400">Recommended Range</div>
                                <div class="text-lg font-bold text-gray-800">4.0 - 7.8 mmol/L</div>
                            </div>
                            <div class="p-4 bg-white border-l-4 border-yellow-300 rounded-md shadow-sm">
                                <div class="text-xs font-bold text-yellow-400 uppercase">Health Risk Level</div>
                                <div class="text-lg font-bold text-gray-800" id="healthRiskValue">-</div>
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
        <ul class="pl-4 list-unstyled">
            ${getStatusInsights(data)}
        </ul>
    </li>`;

                // Add time-based recommendations
                insights += `
    <li class="mb-3">
        <h6 class="font-weight-bold text-info">
            <i class="mr-2 fas fa-clock"></i>Time-Based Recommendations
        </h6>
        <ul class="pl-4 list-unstyled">
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
            <li class="mb-2"><i class="mr-2 fas fa-exclamation-circle text-primary"></i>Your blood sugar is below the recommended range (< 4.0 mmol/L).</li>
            <li class="mb-2"><i class="mr-2 fas fa-apple-alt text-primary"></i>Consider consuming fast-acting carbohydrates:</li>
            <ul class="ml-4">
                <li>½ cup fruit juice</li>
                <li>1 tablespoon honey</li>
                <li>4-5 glucose tablets</li>
            </ul>
            <li class="mb-2"><i class="mr-2 fas fa-clock text-primary"></i>Monitor your levels every 15 minutes until normalized.</li>
            <li class="mb-2"><i class="mr-2 fas fa-exclamation-triangle text-warning"></i>Watch for symptoms:</li>
            <ul class="ml-4">
                <li>Shakiness</li>
                <li>Sweating</li>
                <li>Confusion</li>
            </ul>`,
                    normal: `
            <li class="mb-2"><i class="mr-2 fas fa-check text-success"></i>Your blood sugar is within the healthy range (4.0-7.8 mmol/L).</li>
            <li class="mb-2"><i class="mr-2 fas fa-heart text-primary"></i>Maintain healthy habits:</li>
            <ul class="ml-4">
                <li>Regular exercise</li>
                <li>Balanced meals</li>
                <li>Consistent monitoring</li>
            </ul>
            <li class="mb-2"><i class="mr-2 fas fa-chart-line text-primary"></i>Track patterns to maintain stability.</li>
            <li class="mb-2"><i class="mr-2 fas fa-calendar-check text-success"></i>Schedule regular check-ups.</li>`,
                    high: `
            <li class="mb-2"><i class="mr-2 fas fa-exclamation-triangle text-danger"></i>Your blood sugar is above the recommended range (> 7.8 mmol/L).</li>
            <li class="mb-2"><i class="mr-2 fas fa-walking text-primary"></i>Immediate actions:</li>
            <ul class="ml-4">
                <li>Take a 15-minute walk</li>
                <li>Drink water</li>
                <li>Monitor ketones if applicable</li>
            </ul>
            <li class="mb-2"><i class="mr-2 fas fa-user-md text-primary"></i>Contact healthcare provider if:</li>
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
        <li class="mb-2"><i class="mr-2 fas fa-sun text-warning"></i>Morning (Fasting):</li>
        <ul class="ml-4">
            <li>Test immediately upon waking</li>
            <li>Before any food or drink</li>
            <li>Target: 4.0-7.0 mmol/L</li>
        </ul>
        <li class="mb-2"><i class="mr-2 fas fa-utensils text-primary"></i>Meal-Related Testing:</li>
        <ul class="ml-4">
            <li>Before meals: 4.0-7.0 mmol/L</li>
            <li>2 hours after meals: 5.0-10.0 mmol/L</li>
            <li>Record food intake with readings</li>
        </ul>
        <li class="mb-2"><i class="mr-2 fas fa-moon text-info"></i>Evening:</li>
        <ul class="ml-4">
            <li>Before bed: 6.0-8.0 mmol/L</li>
            <li>Consider night testing if at risk</li>
            <li>Note any late snacks</li>
        </ul>`;
            }

            function getLifestyleInsights(data) {
                return `
        <li class="mb-2"><i class="mr-2 fas fa-dumbbell text-primary"></i>Exercise Tips:</li>
        <ul class="ml-4">
            <li>Test before and after exercise</li>
            <li>Carry fast-acting sugar during activity</li>
            <li>Stay hydrated</li>
        </ul>
        <li class="mb-2"><i class="mr-2 fas fa-apple-alt text-success"></i>Dietary Recommendations:</li>
        <ul class="ml-4">
            <li>Monitor carbohydrate intake</li>
            <li>Eat regular, balanced meals</li>
            <li>Include fiber-rich foods</li>
        </ul>
        <li class="mb-2"><i class="mr-2 fas fa-bed text-info"></i>Lifestyle Factors:</li>
        <ul class="ml-4">
            <li>Maintain regular sleep schedule</li>
            <li>Manage stress levels</li>
            <li>Keep testing supplies ready</li>
        </ul>`;
            }

            function getTrendAnalysis(data) {
                return `
        <li class="mb-2"><i class="mr-2 fas fa-chart-line text-primary"></i>Pattern Recognition:</li>
        <ul class="ml-4">
            <li>Track daily variations</li>
            <li>Note meal impacts</li>
            <li>Monitor exercise effects</li>
        </ul>
        <li class="mb-2"><i class="mr-2 fas fa-calendar-alt text-success"></i>Long-term Management:</li>
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
                <div class="text-white card-header bg-primary">
                    <h6 class="mb-0">
                        <i class="mr-2 fas fa-robot"></i>AI Analysis Results
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
</x-filament-panels::page>
