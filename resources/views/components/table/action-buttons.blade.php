@props([
    'showEdit' => true,
    'showDelete' => true,
    'editTitle' => 'Edit Data',
    'deleteTitle' => 'Hapus Data',
    'onEdit' => null,
    'onDelete' => null
])

@php
    $editHandler = $onEdit ?: ($attributes->get('on-edit') ?: $attributes->get('@click.edit'));
    $deleteHandler = $onDelete ?: ($attributes->get('on-delete') ?: $attributes->get('@click.delete'));
@endphp

<div class="flex items-center justify-end gap-1">
    @if($showEdit)
        <button type="button" 
                @if($editHandler) @click="{!! $editHandler !!}" @endif
                class="w-8 h-8 rounded-md text-textMuted-light hover:text-teal-light hover:bg-teal-light/10 transition-colors cursor-pointer inline-flex items-center justify-center" 
                title="{{ $editTitle }}">
            <i class="fa-solid fa-pen"></i>
        </button>
    @endif

    @if(isset($slot) && $slot->isNotEmpty())
        {{ $slot }}
    @endif

    @if($showDelete)
        <button type="button" 
                @if($deleteHandler) @click="{!! $deleteHandler !!}" @endif
                class="w-8 h-8 rounded-md text-textMuted-light hover:text-danger hover:bg-danger/10 transition-colors cursor-pointer inline-flex items-center justify-center" 
                title="{{ $deleteTitle }}">
            <i class="fa-solid fa-trash"></i>
        </button>
    @endif
</div>
