@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
      <span class="px-3 py-1 text-gray-400">« Anterior</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-full hover:bg-gray-100">« Anterior</a>
    @endif

    {{-- Pagination Elements --}}
    <ul class="flex items-center space-x-2">
      @foreach ($elements as $element)
        {{-- “Three Dots” Separator --}}
        @if (is_string($element))
          <li class="px-3 py-1 text-gray-500">{{ $element }}</li>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <li>
                <span class="px-3 py-1 bg-blue-600 text-white rounded-full">{{ $page }}</span>
              </li>
            @else
              <li>
                <a href="{{ $url }}" class="px-3 py-1 bg-white border border-gray-300 rounded-full hover:bg-gray-100">
                  {{ $page }}
                </a>
              </li>
            @endif
          @endforeach
        @endif
      @endforeach
    </ul>

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-300 rounded-full hover:bg-gray-100">Siguiente »</a>
    @else
      <span class="px-3 py-1 text-gray-400">Siguiente »</span>
    @endif
  </nav>
@endif
