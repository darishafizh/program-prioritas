<thead {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-900 text-textMuted-light dark:text-textMuted-dark text-[11px] uppercase font-normal border-b border-gray-100 dark:border-gray-800']) }}>
    <tr>
        {{ $slot }}
    </tr>
</thead>
