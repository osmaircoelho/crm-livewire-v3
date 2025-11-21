<x-modal wire:model="modal"
         title="Deletion Confirmation"
         subtitle="You are restoring access for the user {{ $user?->name }}"
         separator>

    @error('confirmation')
    <x-alert icon="o-exclamation-triangle" class="alert-error mb-4" >
        {{ $message }}
    </x-alert>
    @enderror


    <x-input
        class="input-sm"
        label="Write `YODA` to confirm the restoration"
        wire:model="confirmation_confirmation"
        wire:keydown.enter="restore"
    />


    <x-slot:actions>
        <x-button label="Cancel" @click="$wire.modal = false" />
        <x-button label="Confirm" spinner class="btn-primary" wire:click="restore" />
    </x-slot:actions>

</x-modal>
