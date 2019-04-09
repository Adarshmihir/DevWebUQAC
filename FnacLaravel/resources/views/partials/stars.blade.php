<div class="review-star">
    @for($i=0; $i < 5; $i++)
        @if($rating < $i)
            <i class="mdi mdi-star-outline"></i>
        @elseif($rating < $i+0.5)
            <i class="mdi mdi-star-half"></i>
        @else
            <i class="mdi mdi-star"></i>
        @endif
    @endfor
</div>
