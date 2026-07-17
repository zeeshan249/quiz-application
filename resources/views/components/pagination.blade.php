<div class="dt-layout-row">
    <div class="dt-layout-cell dt-layout-start">
        <div class="dt-info" aria-live="polite" id="{{ $id ?? 'dt-table-info' }}" role="status">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </div>
    </div>
    <div class="dt-layout-cell dt-layout-end">
        <div class="dt-paging">
            <nav aria-label="pagination">
                <button class="dt-paging-button first" type="button" aria-label="First"
                    wire:click="gotoPage(1)" @disabled($paginator->onFirstPage())>«</button>
                <button class="dt-paging-button previous" type="button" aria-label="Previous"
                    wire:click="previousPage" @disabled($paginator->onFirstPage())>←</button>

                @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                    <button class="dt-paging-button @if ($page === $paginator->currentPage()) current @endif"
                        type="button"
                        @if ($page === $paginator->currentPage()) aria-current="page" @endif
                        wire:click="gotoPage({{ $page }})" @disabled($page === $paginator->currentPage())>
                        {{ $page }}
                    </button>
                @endfor

                <button class="dt-paging-button next" type="button" aria-label="Next"
                    wire:click="nextPage" @disabled(!$paginator->hasMorePages())>→</button>
                <button class="dt-paging-button last" type="button" aria-label="Last"
                    wire:click="gotoPage({{ $paginator->lastPage() }})" @disabled(!$paginator->hasMorePages())>»</button>
            </nav>
        </div>
    </div>
</div>
