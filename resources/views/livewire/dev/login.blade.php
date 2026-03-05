<div>
   <x-select icon="o-user" :options="$this->users" wire:model.live="selectedUser"/>
    <pre>
        @dump($selectedUser);
    </pre>

</div>
