@props([
    'header',
    'name'
])

<div wire:click="sortBy('{{ $name }}', '{{ $header['sortDirection'] == 'asc' ? 'desc' : 'asc' }}')"
     class="cursor-pointer">
    {{$header['label']}} @if($header['sortColumnBy'] == $name)
        <x-icon :name="$header['sortDirection'] == 'asc' ? 'o-chevron-up' : 'o-chevron-down'"
                class="inline-block ml-1"/>
    @endif
</div>
