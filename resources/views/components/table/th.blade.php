@props(['align' => 'left', 'width' => null])

<th {{ $attributes->merge(['class' => 'px-6 py-4 font-normal text-' . $align]) }} @if($width) style="width: {{ $width }}" @endif>
    {{ $slot }}
</th>
