{{-- <div class="mb-3 overflow-hidden bg-white rounded-lg shadow-lg">

        <figure>
            <img class="object-cover object-center w-full h-64"
            src="https://assets.avant.org.au/cdf6134c-01d7-0292-26f5-2f5cf1db96f8/010e2adf-4bc5-49c7-9e75-29b021c42fae/Dr-James-Kemper_banner.jpg"
            alt="Shoes" />
        </figure>

        <div class="p-6">
            <h2 class="mb-2 text-2xl font-bold text-gray-800">{{$doctor->name}}</h2>
            <p class="mb-1 text-sm text-gray-600">
                <span class="font-semibold">Department:</span> Cardiology
            </p>
            <p class="mb-4 text-sm text-gray-600">
                <span class="font-semibold">Specializes in:</span> Heart Surgery
            </p>
            {{-- <p class="text-base text-gray-700">
                Dr. Jane Doe is a renowned cardiologist with over 15 years of experience in treating various heart conditions. She is known for her expertise in minimally invasive heart surgeries and her compassionate patient care.
            </p> --}}
{{-- <x-filament::button wire:model="doctor_id"
                title="Select"
                href="http://mysihat.test/patient/test/appointments/create?doctor={{ $doctor->id }}"
                tag="a"
                    class="w-full">
                        Select
        </x-filament::button>
    </div>  --}}


{{-- <x-filament::modal id="doctor-modal">
     <x-slot name="trigger">
        <x-filament::button>
            Open modal
        </x-filament::button>
    </x-slot>



    <x-slot name="heading">
        Modal heading
    </x-slot>

    <x-slot name="description">
        Modal description
    </x-slot>

    {{-- Modal content --}}
{{-- @foreach ($doctor as $doct)
      <div class="max-w-md overflow-hidden bg-white rounded-lg shadow-lg w-50">

        <figure>
            <img class="object-cover object-center w-full h-64"
            src="https://assets.avant.org.au/cdf6134c-01d7-0292-26f5-2f5cf1db96f8/010e2adf-4bc5-49c7-9e75-29b021c42fae/Dr-James-Kemper_banner.jpg"
            alt="Shoes" />
        </figure>

        <div class="p-6">
            <h2 class="mb-2 text-2xl font-bold text-gray-800">{{$doct->name}}</h2>
            <p class="mb-1 text-sm text-gray-600">
                <span class="font-semibold">Department:</span> Cardiology
            </p>
            <p class="mb-4 text-sm text-gray-600">
                <span class="font-semibold">Specializes in:</span> Heart Surgery
            </p>
            <p class="text-base text-gray-700">
                Dr. Jane Doe is a renowned cardiologist with over 15 years of experience in treating various heart conditions. She is known for her expertise in minimally invasive heart surgeries and her compassionate patient care.
            </p>
        <x-filament::button wire:model="doctor_id"
        title="Close"
        x-on:click="$dispatch('close-modal', { id: 'doctor-modal' })"
        class="m-4 w-85">
            Select
        </x-filament::button>  </div>
    @endforeach
</x-filament::modal> --}}



{{-- <x-filament::modal id="doctor-modal">
      <x-slot name="trigger">
        <x-filament::button>
            Open modal
        </x-filament::button>
    </x-slot>
    <x-slot name="heading">
        Select a Doctor
    </x-slot> --}}

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    @foreach ($doctors as $doctor)
        <div class="w-full max-w-md overflow-hidden bg-white rounded-lg shadow-lg">
            <figure>
                <img class="object-cover object-center w-full h-64"
                    src={{ asset('build/assets/Female-Doctor-Avatar.jpg') }} alt="Doctor Image" />
            </figure>
            <div class="p-6">
                <h2 class="mb-2 text-2xl font-bold text-gray-800">{{ $doctor->name }}</h2>
                <p class="mb-1 text-sm text-gray-600">
                    <span class="font-semibold">Department:</span> {{ $doctor->department ?? 'N/A' }}
                </p>
                <p class="mb-4 text-sm text-gray-600">
                    <span class="font-semibold">Specializes in:</span> {{ $doctor->specialization ?? 'N/A' }}
                </p>
                <x-filament::button
                    href="{{ auth()->user()->hasRole('patient') ? url('patient/appointments/create?doctor=' . $doctor->id) : url('management/appointments/create?doctor=' . $doctor->id) }}"
                    tag="a" title="Select" class="w-full mt-4">
                    Select
                </x-filament::button>
            </div>
        </div>
    @endforeach
</div>

{{--  <script> http://mysihat.test/patient/test/appointments/create?doctor={{ $doctor->id }}
    window.addEventListener('open-doctor-modal', event => {
        Livewire.emit('openDoctorModal');
    });
</script>
</x-filament::modal> --}}
