@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="mt-6 flex items-center gap-1" aria-label="Paginación">
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link {{ $paginator->onFirstPage() ? 'pointer-events-none opacity-40' : '' }}" aria-label="Anterior">‹</a>

        @foreach ($paginator->links() as $link)
            @if ($link->url)
                <a href="{{ $link->url }}" class="pagination-link {{ $link->active ? 'pagination-link-active' : '' }}" @if ($link->label === $paginator->currentPage()) aria-current="page" @endif>{{ $link->label }}</a>
            @else
                <span class="pagination-link opacity-50">{{ $link->label }}</span>
            @endif
        @endforeach

        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link {{ $paginator->onLastPage() ? 'pointer-events-none opacity-40' : '' }}" aria-label="Siguiente">›</a>
    </nav>
@endif