<?php

namespace App\Http\Livewire;

use App\Movie;
use Livewire\Component;

class SearchMovies extends Component
{

    public $query;
    public $moviesResults;

    public function mount()
    {
        $this->reset();
    }

    public function reset(...$properties)
    {
        $this->query = '';
        $this->moviesResults = [];
    }

    public function updatedQuery()
    {
        $this->moviesResults = Movie::where('title','like','%'.$this->query.'%')->get();
    }

    public function render()
    {
        return view('livewire.search-movies');
    }
}
