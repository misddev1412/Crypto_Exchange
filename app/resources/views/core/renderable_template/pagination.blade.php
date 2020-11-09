<div class="bg-primary py-3 px-3">
<div class="row">
    <div class="col-sm-6">
        @if ($paginationObject->hasPages() && $paginationObject->total() > $paginationObject->perPage())
<?php
    $leftRightPageLinks = $eachSide;
    if($leftRightPageLinks < 1){
        $leftRightPageLinks = 1;
    }

    $numberOfShowablePageLinks = ($leftRightPageLinks * 2) + 1;

    $minimumPageNumberToShowFirstLink = ($numberOfShowablePageLinks + 1)/2 + 1;
    $minimumPageNumberToShowLastLink = $paginationObject->lastPage() - ($numberOfShowablePageLinks + 1)/2;
    if($paginationObject->lastPage() < $numberOfShowablePageLinks){
        $pages = range(1,$paginationObject->lastPage());
    }
    elseif($paginationObject->lastPage()==$numberOfShowablePageLinks){
        $pages = range(1,$numberOfShowablePageLinks);
    }
    elseif(
        !($paginationObject->lastPage() > $numberOfShowablePageLinks && $paginationObject->currentPage() >= $minimumPageNumberToShowFirstLink) ||
        !($paginationObject->lastPage() > $numberOfShowablePageLinks && $paginationObject->currentPage() <= $minimumPageNumberToShowLastLink)
    ){
        $pages = range(($paginationObject->currentPage()-$numberOfShowablePageLinks), ($paginationObject->currentPage()+$numberOfShowablePageLinks));
        $pages = array_values(array_filter(
            $pages,
            function ($value) use($paginationObject) {
                return ($value >= 1 && $value <= $paginationObject->lastPage());
            }
        ));
        if(!($paginationObject->lastPage() > $numberOfShowablePageLinks && $paginationObject->currentPage() >= $minimumPageNumberToShowFirstLink)){
            $pages = array_slice($pages,0,($numberOfShowablePageLinks+1));
        }
        elseif(!($paginationObject->lastPage() > $numberOfShowablePageLinks && $paginationObject->currentPage() <= $minimumPageNumberToShowLastLink)){
            $pages = array_slice($pages,(count($pages)-$numberOfShowablePageLinks-1));
        }
    }
    else{
        $pages = range(($paginationObject->currentPage()-$leftRightPageLinks), ($paginationObject->currentPage()+$leftRightPageLinks));
        $pages = array_filter(
            $pages,
            function ($value) use($paginationObject) {
                return ($value >= 1 && $value <= $paginationObject->lastPage());
            }
        );
    }
?>
            <nav class="lf-pagination">
                <ul class="pagination pagination-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginationObject->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginationObject->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
                    @endif

                    @if(!in_array(1,$pages))
                        <li class="page-item hidden-xs"><a class="page-link" href="{{ $paginationObject->url(1) }}">..</a></li>
                    @endif
                    @foreach($pages as $i)
                        @if ($i == $paginationObject->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginationObject->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endforeach
                    @if(!in_array($paginationObject->lastPage(),$pages))
                        <li class="page-item hidden-xs"><a class="page-link" href="{{ $paginationObject->url($paginationObject->lastPage()) }}">..</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginationObject->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginationObject->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
    <div class="col-sm-6 text-white text-sm-right">
        <?php
        $pagcountst = ($paginationObject->currentPage() - 1) * $itemPerPage + 1;
        $pagcountnd = ($paginationObject->currentPage() - 1) * $itemPerPage + $paginationObject->count();
        $currentItem = '';
        if ($pagcountnd == 0 || $pagcountst > $pagcountnd) {
            $current = '0';
        } elseif ($pagcountst == $pagcountnd) {
            $current = $pagcountnd;
            $currentItem = __('no.') . ' ';
        } else {
            $current = $pagcountst . ' - ' . $pagcountnd;
        }
        ?>
        <span class="pagination-text">
            {{ view_html( __('showing: :currentItem <span>:current</span> of <span>:total</span> data',['currentItem'=>$currentItem, 'current'=>$current, 'total'=>$paginationObject->total()])) }}
        </span>
    </div>
</div>
</div>
