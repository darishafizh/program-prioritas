@props(['colspan' => 7, 'icon' => 'fa-inbox', 'message' => 'Belum ada data yang tersedia.'])

<tr data-empty-row>
    <td colspan="{{ $colspan }}" class="px-6 py-12 text-center text-textMuted-light dark:text-textMuted-dark">
        <div class="flex flex-col items-center justify-center gap-2">
            <i class="fa-solid {{ $icon }} text-3xl opacity-50 mb-2"></i>
            <p class="text-xs">{{ $message }}</p>
        </div>
    </td>
</tr>
