
<div id="header-search">
    <input type="text" placeholder="Search movies ..." wire:model="query" wire:keydown.tab="reset" id="search-input">
    @if (!empty($query))
        <button wire:click="reset" id="search-input-button" class="btn" >x</button>
    @endif
    <span id="search-input-icon" >
        <i class='fas fa-search'></i>
    </span>
    <div wire:loading class="spinner-border text-primary" class="float-right" style="position: absolute;right:10px;left:10px;z-index:10" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (!empty($query))
        <ul class="list-group " id="header-search-results">
            @if ($moviesResults->count() !== 0)
                    @foreach ($moviesResults as $movie)
                        <a href="{{ Route('movies.show',$movie->id) }}">
                            <li class="list-group-item">
                                <img src="{{ $movie->getPosterLivewire() }}" width="45px"  alt="">
                                <span class="movie-title">{{ $movie->title }}</span>
                            </li>

                        </a>
                    @endforeach

                    @else
                        <li class="list-group-item">
                            No result for '{{ $query }}'
                        </li>
                    @endif
        </ul>


    @endif
</div>



