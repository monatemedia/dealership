<?php
/** @var $paginator \Illuminate\Pagination\LengthAwarePaginator */
?>

{{-- Previous Page --}}
@if ($paginator->hasPages()) {{-- Display if there are more pages --}}
<nav class="pagination my-large">
    @if ($paginator->onFirstPage()) {{-- Display if the current page is the first page --}}
    <span class="pagination-item"> {{-- Disable button with span --}}
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
			stroke-width="1.5" stroke="currentColor" style="width: 18px">
			<path stroke-linecap="round" stroke-linejoin="round"
				d="M15.75 19.5 8.25 12l7.5-7.5" />
		</svg>
	</span>
    @else {{-- Display if the current page is not the first page --}}
	<a href="{{ $paginator->previousPageUrl() }}" class="pagination-item"> {{-- Enable button with a tag--}}
		<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
			stroke-width="1.5" stroke="currentColor" style="width: 18px">
			<path stroke-linecap="round" stroke-linejoin="round"
				d="M15.75 19.5 8.25 12l7.5-7.5" />
		</svg>
	</a>
    @endif

    {{-- Current Pages --}}
	@foreach ($elements as $element)
        @if (is_string($element)) {{-- Display if the element is a string --}}
	        <span class="pagination-item"> {{  $element }} </span> {{-- Display the string --}}
        @endif

        @if (is_array($element)) {{-- Display if the element is an array --}}
            @foreach($element as $page => $url)
                @if ($page == $paginator->currentPage()) {{-- Display if the page is the current page --}}
                    <span class="pagination-item active">{{ $page }}</span> {{-- Disable button with span --}}
                @else {{-- Enable if the page is not the current page --}}
                    <a href="{{ $url }}" class="pagination-item"> {{ $page }} </a> {{-- Enable button with a tag --}}
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page --}}
    @if ($paginator->hasMorePages()) {{-- Display if there are more pages --}}
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" style="width: 18px">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    @else {{-- Display if there are no more pages --}}
        <span class="pagination-item"> {{-- Disable button with span --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" style="width: 18px">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </span>
    @endif
</nav>
@endif
