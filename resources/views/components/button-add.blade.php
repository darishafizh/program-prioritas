@props([
    'label' => 'Tambah Data',
    'icon' => 'fa-plus',
    'href' => null
])

@if ($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'px-4 py-2 bg-teal-light text-white rounded-md text-xs font-medium hover:bg-teal-light/90 transition-all flex items-center justify-between gap-2 shadow-sm shrink-0 cursor-pointer whitespace-nowrap']) }}> 
    <span>{{ $label }}</span>
    <i class="fa-solid {{ $icon }}"></i> 
</a>
@else
<button type="button" {{ $attributes->merge(['class' => 'px-4 py-2 bg-teal-light text-white rounded-md text-xs font-medium hover:bg-teal-light/90 transition-all flex items-center justify-between gap-2 shadow-sm shrink-0 cursor-pointer whitespace-nowrap']) }}> 
    <span>{{ $label }}</span>
    <i class="fa-solid {{ $icon }}"></i> 
</button>
@endif
