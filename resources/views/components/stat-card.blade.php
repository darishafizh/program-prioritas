@props([
    'title' => '',
    'icon' => '',
    'iconColor' => 'text-teal-light dark:text-teal-400',
    'iconBg' => 'bg-teal-light/10 dark:bg-teal-light/20',
    'blobBg' => null,
    'value' => '',
    'unit' => '',
    'description' => '',
])

@php
    $blobColor = $blobBg ?? $iconBg;
@endphp

<div {{ $attributes->merge(['class' => 'bg-bgSurface-light dark:bg-bgSurface-dark border border-gray-100 dark:border-gray-800 rounded-3xl p-6 relative overflow-hidden group hover:border-teal-light/40 dark:hover:border-teal-light/40 hover:shadow-lg hover:shadow-teal-light/5 transition-all duration-300 flex flex-col justify-between']) }}>
    @if($blobColor)
        <div class="absolute top-0 right-0 w-32 h-32 {{ $blobColor }} rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110 pointer-events-none"></div>
    @endif

    <div class="relative z-10 flex flex-col justify-between h-full">
        <!-- Baris Atas: Judul & Icon -->
        <div class="flex items-start justify-between gap-4 mb-3">
            <h3 class="text-xs font-semibold text-textMuted-light dark:text-textMuted-dark uppercase tracking-wider leading-snug pt-0.5">
                {{ $title }}
            </h3>
            @if($icon)
                <div class="w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center {{ $iconColor }} text-sm shrink-0 shadow-sm transition-transform group-hover:scale-105">
                    <i class="{{ $icon }}"></i>
                </div>
            @endif
        </div>

        <!-- Baris Kedua: Nilai beserta satuan & Keterangan singkat -->
        <div>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-extrabold text-textMain-light dark:text-textMain-dark tracking-tight">{{ $value }}</span>
                @if($unit)
                    <span class="text-sm font-semibold text-textMuted-light dark:text-textMuted-dark">{{ $unit }}</span>
                @endif
            </div>
            @if($description)
                <div class="text-xs font-medium text-textMuted-light dark:text-textMuted-dark mt-1 leading-relaxed">
                    {!! $description !!}
                </div>
            @endif
            @if($slot->isNotEmpty())
                <div class="mt-2.5">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</div>
