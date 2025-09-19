@if ($paginator->hasPages())
<div class="navigation-list">
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
  	@if ($paginator->onFirstPage()) 
    <li class="page-item disabled">
      <a class="page-link" href="#" tabindex="-1">Previous</a>
    </li>
    @else
    <li class="page-item">
      <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1">Previous</a>
    </li>
    @endif
    
    @foreach ($elements as $element)
    @if(is_string($element))
    <li class="page-item disabled"><span>{{ $element }}</span></li>
    @endif
    @if(is_array($element))
        @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="page-item active">
              <span class="page-link">
                <span class="">{{ $page }}</span>
              </span>
            </li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach
    @endif    
    @endforeach
    
    @if ($paginator->hasMorePages()) 
    <li class="page-item">
      <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
    </li>
	@else
    <li class="page-item disabled">
      <a class="page-link" href="#">Next</a>
    </li>
    @endif
  </ul>
</nav>
</div>
@endif

<style>
	.page-item.active .page-link {
		z-index: 3;
		color: #fff;
		background-color: #3b5d50;
		border-color: #3b5d50;
	}
	.page-link {
    color: #3b5d50;
	}
	
</style>