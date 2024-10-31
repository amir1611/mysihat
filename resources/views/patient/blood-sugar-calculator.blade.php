@extends('layouts.patient.patient-layout')

@section('content')
    <style>
        #bloodSugarLevel::-webkit-inner-spin-button,
        #bloodSugarLevel::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #bloodSugarLevel {
            -moz-appearance: textfield;
            /* For Firefox */
        }
    </style>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-blood-drop mr-2"></i>
                Blood Sugar Analysis
            </h1>
        </div>

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <i class="fas fa-blood-drop mr-2 text-primary"></i>
                        <h6 class="m-0 font-weight-bold text-primary">Blood Sugar Input</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('build/assets/mysihatbot.png') }}" width="80" class="mb-3">
                            <p class="text-gray-600">Hello {{ Auth::user()->name }}, I'll analyze your blood sugar levels.
                            </p>
                        </div>

                        <form id="bloodSugarForm">
                            <div class="form-group">
                                <label for="bloodSugarLevel">Blood Sugar Level (mmol/L)</label>
                                <input type="number" class="form-control" id="bloodSugarLevel" name="bloodSugarLevel"
                                    required step="0.1" placeholder="Enter blood sugar level in mmol/L">
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
                            <h6 class="m-0 font-weight-bold text-primary">Blood Sugar Analysis Results</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="analysis-loading text-center" style="display: none;">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="sr-only">Analyzing...</span>
                            </div>
                            <p class="text-gray-600">Analyzing your blood sugar levels...</p>
                        </div>

                        <div class="analysis-results">
                            <div class="row mb-4">
                                <div class="col-md-12 text-center">
                                    <div class="h1 mb-0 font-weight-bold" id="sugarValue">-</div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Blood Sugar Level
                                        (mg/dL)</div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="font-weight-bold text-primary mb-3">
                                                <i class="fas fa-brain mr-2"></i>Health Insights
                                            </h6>
                                            <div id="healthInsights" class="text-gray-700"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Blood Sugar Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="analysis-results">
                            <table class="table table-bordered" id="bloodSugarTable">
                                <thead>
                                    <tr>
                                        <th id="dateHeader" style="cursor: pointer;" data-toggle="tooltip"
                                            title="Click to sort by date">Date</th>
                                        <th id="timeHeader">Time</th>
                                        <th id="levelHeader" style="cursor: pointer;" data-toggle="tooltip"
                                            title="Click to sort by blood sugar level">Blood Sugar Level (mmol/L)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bloodSugarTableBody">
                                    @foreach ($levels as $level)
                                        <tr>
                                            <td>{{ $level->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $level->created_at->format('H:i') }}</td>
                                            <td>{{ $level->level }}</td>
                                            <td>
                                                @if ($level->status === 'low')
                                                    <span class="text-danger">Low Blood Sugar</span>
                                                @elseif ($level->status === 'normal')
                                                    <span class="text-success">Normal Blood Sugar</span>
                                                @elseif ($level->status === 'high')
                                                    <span class="text-warning">High Blood Sugar</span>
                                                @else
                                                    <span class="text-muted">Unknown Status</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p class="text-muted" style="display: none;">Click on the column headers to sort the table by date or blood sugar level.</p>
                        </div>
                        <!-- Summary Section -->
                        <div class="mt-4">
                            <h5>Average Blood Sugar Level: {{ number_format($averageLevel, 2) }} mmol/L</h5>
                            <p>Status: {{ $averageStatus }}</p>
                            <p>
                                @if ($averageStatus === 'low')
                                    Consider consuming fast-acting carbohydrates and consult a healthcare provider if
                                    symptoms persist.
                                @elseif ($averageStatus === 'normal')
                                    Keep up the good work! Maintain a balanced diet and regular exercise.
                                @elseif ($averageStatus === 'high')
                                    It is advisable to consult a healthcare provider for further evaluation.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @push('scripts')
        <script>
            document.getElementById('bloodSugarForm').addEventListener('submit', function(e) {
                e.preventDefault();

                document.querySelector('.analysis-loading').style.display = 'block';
                document.querySelector('.analysis-results').style.display = 'none';
                document.getElementById('resultCard').style.display = 'block';

                const formData = {
                    bloodSugarLevel: document.getElementById('bloodSugarLevel').value
                };

                fetch('{{ route('bloodSugar.analyze') }}', {
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
                        document.getElementById('healthInsights').innerHTML = generateHealthInsights(data.status);

                        // Prepare graph data
                        const graphData = data.graphData; // Get graph data from the response
                        const ctx = document.getElementById('bloodSugarChart').getContext('2d');
                        const labels = Object.keys(graphData);
                        const values = Object.values(graphData);

                        // Check if there is data to display
                        if (labels.length > 0 && values.length > 0) {
                            const bloodSugarChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Blood Sugar Levels (mmol/L)',
                                        data: values,
                                        borderColor: 'rgba(78, 115, 223, 1)',
                                        backgroundColor: 'rgba(78, 115, 223, 0.2)',
                                        borderWidth: 2,
                                        fill: true,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Blood Sugar Level (mmol/L)'
                                            }
                                        }
                                    },
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Blood Sugar Levels Over the Last Month'
                                        }
                                    }
                                }
                            });
                        } else {
                            console.error('No data available for the chart.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });




            function analyzeBloodSugar($level) {
                if ($level < 4.0) return 'low'; // Below 4.0 mmol/L is low
                if ($level >= 4.0 && $level <= 7.8) return 'normal'; // 4.0 to 7.8 mmol/L is normal
                return 'high'; // Above 7.8 mmol/L is high
            }

            function generateHealthInsights(status) {
                let insights = '<ul class="list-unstyled">';
                if (status === 'low') {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-danger"><i class="fas fa-exclamation-circle mr-2"></i>Low Blood Sugar</h6>
            <p>Your blood sugar level is below normal. Consider consuming fast-acting carbohydrates.</p>
        </li>`;
                } else {
                    insights += `
        <li class="mb-3">
            <h6 class="font-weight-bold text-success"><i class="fas fa-check-circle mr-2"></i>Normal Blood Sugar</h6>
            <p>Your blood sugar level is within the normal range. Keep up the good work!</p>
        </li>`;
                }
                insights += '</ul>';
                return insights;
            }

            document.getElementById('dateHeader').addEventListener('click', function() {
                sortTable(0);
            });

            document.getElementById('timeHeader').addEventListener('click', function() {
                sortTable(1);
            });

            document.getElementById('levelHeader').addEventListener('click', function() {
                sortTable(2);
            });

            function sortTable(columnIndex) {
                const table = document.getElementById('bloodSugarTable');
                const tbody = table.getElementsByTagName('tbody')[0];
                const rows = Array.from(tbody.rows);
                const isAscending = tbody.dataset.sortOrder === 'asc';

                rows.sort((a, b) => {
                    const aText = a.cells[columnIndex].innerText;
                    const bText = b.cells[columnIndex].innerText;

                    if (columnIndex === 0 || columnIndex === 1) { // Date or Time
                        return isAscending ? new Date(bText) - new Date(aText) : new Date(aText) - new Date(bText);
                    } else { // Blood Sugar Level
                        return isAscending ? parseFloat(bText) - parseFloat(aText) : parseFloat(aText) - parseFloat(
                            bText);
                    }
                });

                // Clear the existing rows and append the sorted rows
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));

                // Toggle sort order
                tbody.dataset.sortOrder = isAscending ? 'desc' : 'asc';
            }

            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    @endpush
@endsection
