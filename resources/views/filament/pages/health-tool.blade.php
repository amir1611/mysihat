<x-filament-panels::page>

    <head>
        <link rel="icon" type="image/svg+xml" href="{{ asset('build/assets/mysihat-icon.svg') }}">




        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    </head>

    <div class="container px-4 mx-auto">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="p-4 bg-white border-l-4 border-blue-500 rounded-lg shadow-md">
                <div class="mb-4 text-center">
                    <div class="inline-block p-4 text-white bg-blue-500 rounded-full">
                        <i class="text-3xl fas fa-weight"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-800">BMI Calculator</div>
                    <p class="mt-2 text-gray-600">Calculate your Body Mass Index (BMI) and get AI-powered health
                        insights.</p>
                    <a href="{{ $this->toBmiCalculator() }}"
                        class="inline-block px-4 py-2 mt-4 font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
                        <i class="mr-2 fas fa-calculator"></i>Try Me
                    </a>
                </div>
                <div class="p-2 mt-4 text-sm text-gray-600 bg-gray-100 rounded">
                    <i class="mr-1 fas fa-info-circle"></i>
                    Helps track and monitor your body composition
                </div>
            </div>

            <div class="p-4 bg-white border-l-4 border-green-500 rounded-lg shadow-md">
                <div class="mb-4 text-center">
                    <div class="inline-block p-4 text-white bg-green-500 rounded-full">
                        <i class="text-3xl fas fa-tint"></i>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-gray-800">Blood Sugar Calculator</div>
                    <p class="mt-2 text-gray-600">Monitor and analyze your blood sugar levels with AI-powered insights.
                    </p>
                    <a href="{{ $this->toBloodSugarCalculator() }}"
                        class="inline-block px-4 py-2 mt-4 font-semibold text-white bg-green-500 rounded hover:bg-green-600">
                        <i class="mr-2 fas fa-heartbeat"></i>Try Me
                    </a>
                </div>
                <div class="p-2 mt-4 text-sm text-gray-600 bg-gray-100 rounded">
                    <i class="mr-1 fas fa-info-circle"></i>
                    Track and manage your blood glucose levels
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-4 py-3 bg-gray-100 rounded-t">
                    <h6 class="text-lg font-semibold text-blue-500">
                        <i class="mr-2 fas fa-robot"></i>AI-Powered Health Tools
                    </h6>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <h5 class="font-semibold">Features:</h5>
                            <ul class="mt-2 space-y-1">
                                <li class="flex items-center">
                                    <i class="mr-2 text-green-500 fas fa-check-circle"></i>Real-time analysis
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-green-500 fas fa-check-circle"></i>Personalized recommendations
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-green-500 fas fa-check-circle"></i>Health tracking capabilities
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-green-500 fas fa-check-circle"></i>Easy-to-understand results
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-semibold">Benefits:</h5>
                            <ul class="mt-2 space-y-1">
                                <li class="flex items-center">
                                    <i class="mr-2 text-yellow-500 fas fa-star"></i>Monitor health metrics
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-yellow-500 fas fa-star"></i>Track progress over time
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-yellow-500 fas fa-star"></i>Get AI-powered insights
                                </li>
                                <li class="flex items-center">
                                    <i class="mr-2 text-yellow-500 fas fa-star"></i>Make informed health decisions
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-filament-panels::page>
