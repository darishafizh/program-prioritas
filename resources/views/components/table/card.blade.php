@props([
    'title',
    'description' => null,
    'searchPlaceholder' => 'Cari data...',
    'searchName' => 'search',
    'searchValue' => '',
    'showPerPage' => true,
    'perPage' => 10,
    'perPageOptions' => [10, 25, 50, 100],
    'pagination' => null,
    'customTable' => false
])

@if(!$customTable)
<script>
if (typeof window.tableCardInit === 'undefined') {
    window.tableCardInit = true;
    document.addEventListener('alpine:init', () => {
        Alpine.data('tableCardManager', (config) => ({
            perPage: config.perPage || 10,
            currentPage: 1,
            searchQuery: config.searchQuery || '',
            totalRows: 0,

            get totalPages() {
                if (this.perPage === 'all' || !Number(this.perPage)) return 1;
                return Math.max(1, Math.ceil(this.totalRows / Number(this.perPage)));
            },

            init() {
                this.$nextTick(() => {
                    this.updateTableRows();
                });
                this.$watch('perPage', () => { this.currentPage = 1; this.updateTableRows(); });
                this.$watch('currentPage', () => { this.updateTableRows(); });
                this.$watch('searchQuery', () => { this.currentPage = 1; this.updateTableRows(); });
            },

            updateTableRows() {
                const tbody = this.$el.querySelector('tbody');
                if (!tbody) return;
                const allRows = Array.from(tbody.querySelectorAll('tr'));
                const dataRows = allRows.filter(r => !r.hasAttribute('data-empty-row'));
                
                if (dataRows.length === 0) return;

                const q = (this.searchQuery || '').toLowerCase().trim();
                const matchedRows = [];

                dataRows.forEach(row => {
                    const text = row.textContent || '';
                    if (!q || text.toLowerCase().includes(q)) {
                        matchedRows.push(row);
                    } else {
                        row.style.display = 'none';
                    }
                });

                this.totalRows = matchedRows.length;

                const limit = (this.perPage === 'all' || !Number(this.perPage)) ? matchedRows.length : Number(this.perPage);
                const start = (this.currentPage - 1) * limit;
                const end = start + limit;

                matchedRows.forEach((row, idx) => {
                    if (idx >= start && idx < end) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                const emptyRow = tbody.querySelector('tr[data-empty-row]');
                if (emptyRow) {
                    emptyRow.style.display = matchedRows.length === 0 ? '' : 'none';
                }
            }
        }));
    });
}
</script>
@endif

<div @if(!$customTable) x-data="tableCardManager({ perPage: {{ is_numeric($perPage) ? $perPage : "'$perPage'" }}, searchQuery: '{{ $searchValue }}' })" @endif class="flex flex-col gap-6 w-full">
    <!-- Row 1: Header (Left: Title + Subtitle, Right: Search + Add Button / Actions) -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div class="shrink-0">
            <h2 class="text-xl font-semibold tracking-tight text-textMain-light dark:text-textMain-dark">{{ $title }}</h2>
            @if($description)
                <p class="text-textMuted-light dark:text-textMuted-dark text-[11px] font-normal mt-1">{{ $description }}</p>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto justify-end">
            @if(isset($search))
                {{ $search }}
            @else
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" 
                           name="{{ $searchName }}" 
                           @if(!$customTable) x-model="searchQuery" @endif
                           value="{{ $searchValue }}"
                           placeholder="{{ $searchPlaceholder }}" 
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm outline-none focus:border-teal-light transition-colors text-textMain-light dark:text-textMain-dark">
                </div>
            @endif

            @if(isset($actions))
                {{ $actions }}
            @endif
        </div>
    </div>

    <!-- Divider -->
    <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>

    <!-- Table Container: Tampilan border tanpa card lagi -->
    <div class="overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-xl">
        @if($customTable)
            {{ $slot }}
        @else
            <table class="w-full text-left text-xs whitespace-nowrap">
                {{ $slot }}
            </table>
        @endif
    </div>

    <!-- Footer Row: Left (Show per page dropdown select Bahasa Indonesia), Right (Pagination) -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        @if($showPerPage)
            <div class="flex items-center gap-2 text-xs text-textMuted-light dark:text-textMuted-dark">
                <span>Tampilkan</span>
                <select @if(isset($onPerPageChange)) @change="{{ $onPerPageChange }}($event.target.value)" @else x-model="perPage" @change="if(typeof currentPage !== 'undefined') currentPage = 1" @endif class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none focus:border-teal-light text-textMain-light dark:text-textMain-dark font-medium cursor-pointer">
                    @foreach($perPageOptions as $option)
                        <option value="{{ $option }}" :selected="perPage == {{ is_numeric($option) ? $option : "'$option'" }}">{{ $option }}</option>
                    @endforeach
                </select>
                <span>data per halaman</span>
            </div>
        @else
            <div></div>
        @endif

        <div class="flex items-center gap-2">
            @if(isset($paginationSlot))
                {{ $paginationSlot }}
            @elseif($pagination && is_object($pagination) && method_exists($pagination, 'links'))
                {{ $pagination->links() }}
            @else
                <!-- Default Client-side / Visual Pagination Fallback -->
                <div class="flex items-center gap-1">
                    <button type="button" 
                            @click="if(typeof currentPage !== 'undefined' && currentPage > 1) currentPage--" 
                            :disabled="typeof currentPage === 'undefined' || currentPage === 1"
                            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer disabled:cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>
                    
                    <button type="button" 
                            class="w-8 h-8 rounded-lg font-medium text-xs flex items-center justify-center bg-teal-light text-white shadow-xs"
                            x-text="typeof currentPage !== 'undefined' ? currentPage : 1">
                        1
                    </button>

                    <button type="button" 
                            @click="if(typeof currentPage !== 'undefined' && (typeof totalPages !== 'function' || currentPage < totalPages())) currentPage++" 
                            :disabled="typeof currentPage === 'undefined' || (typeof totalPages === 'function' && currentPage >= totalPages())"
                            class="w-8 h-8 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-400 disabled:opacity-30 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer disabled:cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
