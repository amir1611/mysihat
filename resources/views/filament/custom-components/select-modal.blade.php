

{{-- <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-3">
   
        <figure>
            <img class="w-full h-64 object-cover object-center"
            src="https://assets.avant.org.au/cdf6134c-01d7-0292-26f5-2f5cf1db96f8/010e2adf-4bc5-49c7-9e75-29b021c42fae/Dr-James-Kemper_banner.jpg"
            alt="Shoes" />
        </figure>
        
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{$doctor->name}}</h2>
            <p class="text-sm text-gray-600 mb-1">
                <span class="font-semibold">Department:</span> Cardiology
            </p>
            <p class="text-sm text-gray-600 mb-4">
                <span class="font-semibold">Specializes in:</span> Heart Surgery
            </p>
            {{-- <p class="text-gray-700 text-base">
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
      <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-md w-50">
   
        <figure>
            <img class="w-full h-64 object-cover object-center"
            src="https://assets.avant.org.au/cdf6134c-01d7-0292-26f5-2f5cf1db96f8/010e2adf-4bc5-49c7-9e75-29b021c42fae/Dr-James-Kemper_banner.jpg"
            alt="Shoes" />
        </figure>
        
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{$doct->name}}</h2>
            <p class="text-sm text-gray-600 mb-1">
                <span class="font-semibold">Department:</span> Cardiology
            </p>
            <p class="text-sm text-gray-600 mb-4">
                <span class="font-semibold">Specializes in:</span> Heart Surgery
            </p>
            <p class="text-gray-700 text-base">
                Dr. Jane Doe is a renowned cardiologist with over 15 years of experience in treating various heart conditions. She is known for her expertise in minimally invasive heart surgeries and her compassionate patient care.
            </p>
        <x-filament::button wire:model="doctor_id" 
        title="Close"
        x-on:click="$dispatch('close-modal', { id: 'doctor-modal' })"
        class="w-85 m-4">
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
    </x-slot>--}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($doctors as $doctor)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-md w-full">
            <figure>
                <img class="w-full h-64 object-cover object-center" 
                     src= {{Storage::url($doctor->avatar_url)}} 
                     alt="Doctor Image" />
            </figure>
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $doctor->name }}</h2>
                <p class="text-sm text-gray-600 mb-1">
                    <span class="font-semibold">Department:</span> {{ $doctor->department ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600 mb-4">
                    <span class="font-semibold">Specializes in:</span> {{ $doctor->specialization ?? 'N/A' }}
                </p>
                <x-filament::button href="{{ url('patient/appointments/create?doctor='.$doctor->id) }}"
                                    tag="a"
                                    title="Select" 
                                    class="mt-4 w-full">
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

