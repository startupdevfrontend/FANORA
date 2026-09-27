@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="mt-6 flex items-center gap-1" aria-label="Paginação">
        @foreach ($paginator->links() as $link)
            @php
                $isNav = ! ctype_digit((string) $link->label);
                $isCurrent = $link->active;
            @endphp
            @if ($link->url)
                <a
                    href="{{ $link->url }}"
                    class="pagination-link {{ $isCurrent ? 'pagination-link-active' : '' }}"
                    @if ($isCurrent) aria-current="page" @endif
                    aria-label="{{ $isNav ? strip_tags($link->label) : 'Página '.$link->label }}"
                >{{ $link->label }}</a>
            @else
                <span class="pagination-link pointer-events-none opacity-50">{{ $link->label }}</span>
            @endif
        @endforeach
    </nav>
@endif