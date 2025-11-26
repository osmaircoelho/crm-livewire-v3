<div class="bg-red-500 px-4 p-1 text-sm text-black cursor-pointer" wire:click="stop">
    <span wire:loading.remove wire:target="stop">
        {{ __("You're impersonating :name, click here to stop the impersonation.", ['name' => $user->name]) }}
    </span>

    <span wire:loading wire:target="stop" class="inline-flex items-center gap-1 whitespace-nowrap">
        <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
    </span>
</div>
