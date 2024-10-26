

{{-- <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm w-50">
   
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
            <p class="text-gray-700 text-base">
                Dr. Jane Doe is a renowned cardiologist with over 15 years of experience in treating various heart conditions. She is known for her expertise in minimally invasive heart surgeries and her compassionate patient care.
            </p>
        <x-filament::button wire:model="doctor_id" 
        title="Close"
        x-on:click="$dispatch('close-modal', { id: '34qQvxSWDQTW7lBz4fu2-form-component-action' })"
        class="w-85 m-4">
            Select
        </x-filament::button>  </div> --}}


<x-filament::modal id="doctor-modal">
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
    @foreach ($doctor as $doct)
      <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-sm w-50">
   
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
</x-filament::modal>

