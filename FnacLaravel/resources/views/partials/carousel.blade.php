@php($_id = md5(rand(0, 10)))

<div class="carousel">
    @forelse($photos as $photo)
        <img src="{{ asset("img/{$photo->url}") }}" alt="">
    @empty
        <img src="{{ asset("img/empty.png") }}" alt="">
    @endforelse
</div>
