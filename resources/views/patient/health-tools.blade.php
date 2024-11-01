@extends('layouts.patient.patient-layout')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-heartbeat mr-2"></i>Health Tools
        </h1>
    </div>


    <div class="row">
   
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary text-white p-4 d-inline-block">
                            <i class="fas fa-weight fa-3x"></i>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">BMI Calculator</div>
                            <p class="mt-2 mb-3 text-center">Calculate your Body Mass Index (BMI) and get AI-powered health insights.</p>
                            <div class="text-center">
                                <a href="{{ route('patient.bmi') }}" class="btn btn-primary">
                                    <i class="fas fa-calculator mr-2"></i>Try Me
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Helps track and monitor your body composition
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-success text-white p-4 d-inline-block">
                            <i class="fas fa-tint fa-3x"></i>
                        </div>
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-0 font-weight-bold text-gray-800 text-center">Blood Sugar Calculator</div>
                            <p class="mt-2 mb-3 text-center">Monitor and analyze your blood sugar levels with AI-powered insights.</p>
                            <div class="text-center">
                                <a href="{{ route('patient.blood-sugar') }}" class="btn btn-success">
                                    <i class="fas fa-heartbeat mr-2"></i>Try Me
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        Track and manage your blood glucose levels
                    </small>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-robot mr-2"></i>AI-Powered Health Tools
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Features:</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success mr-2"></i>Real-time analysis</li>
                                <li><i class="fas fa-check-circle text-success mr-2"></i>Personalized recommendations</li>
                                <li><i class="fas fa-check-circle text-success mr-2"></i>Health tracking capabilities</li>
                                <li><i class="fas fa-check-circle text-success mr-2"></i>Easy-to-understand results</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Benefits:</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-star text-warning mr-2"></i>Monitor health metrics</li>
                                <li><i class="fas fa-star text-warning mr-2"></i>Track progress over time</li>
                                <li><i class="fas fa-star text-warning mr-2"></i>Get AI-powered insights</li>
                                <li><i class="fas fa-star text-warning mr-2"></i>Make informed health decisions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
