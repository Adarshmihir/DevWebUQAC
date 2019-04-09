<div class="container-fluid productslist">
    @forelse($games as $game)
        <a href="{{ route('videogame_one', ['id' => $game->jeu_id, 'slug' => $game->slug]) }}" class="col-xs-6 col-sm-6 col-md-4 col-lg-3 productslist_item">
            @if($game->photos->count() > 0)
                <div class="productlist_item_img" style="background-image: url({{ asset('img/'.$game->photos->first()->pho_url) }})"></div>
            @else
                <div class="productlist_item_img" style="background-image: url({{ asset('img/empty.png') }})"></div>
            @endif
            <h3 class="productlist_item_title">{{ $game->jeu_nom }}</h3>
            @include('partials.stars', ['rating' => $game->avis->avg('avi_note')])
        </a>
    @empty
        <div class="col-lg-12 center productslist_empty">
            Désolé, j'ai pas de résultats !
        </div>
    @endforelse
</div>
