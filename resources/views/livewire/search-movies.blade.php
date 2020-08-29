<div  class="col-5" >
    <input type="text" wire:model="query" wire:keydown.tab="reset"  class="form-control" placeholder="Search movies..." >
    <div wire:loading class="spinner-border text-primary" style="position: absolute;right:10px;left:10px;z-index:10" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (!empty($query))

            <ul class="list-group" style="position: absolute;right:10px;left:10px;z-index:10" >
                @if ($moviesResults->count() !== 0)
                            @foreach ($moviesResults as $movie)
                            <a href="{{ Route('movies.show',$movie->id) }}">
                                <li class="list-group-item">
                                    <img src="{{ $movie->getPosterLivewire() }}" width="50px"  class="img-fluid"   alt="">
                                    {{ $movie->title }}
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



