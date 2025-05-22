@if ($paginator->hasPages())
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-12">
                <div class="box">
                    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <a class="pagination-previous" disabled>Anterior</a>
                        @else
                            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-previous">Anterior</a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-next">Siguiente</a>
                        @else
                            <a class="pagination-next" disabled>Siguiente</a>
                        @endif

                        <ul class="pagination-list">
                            {{-- Pagination Elements --}}
                            @foreach ($elements as $element)
                                {{-- "Three Dots" Separator --}}
                                @if (is_string($element))
                                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                                @endif

                                {{-- Array Of Links --}}
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $paginator->currentPage())
                                            <li><a class="pagination-link is-current" aria-label="Página {{ $page }}" aria-current="page">{{ $page }}</a></li>
                                        @else
                                            <li><a href="{{ $url }}" class="pagination-link" aria-label="Ir a la página {{ $page }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endif
