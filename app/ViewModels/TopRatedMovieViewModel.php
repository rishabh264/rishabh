<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class TopRatedMovieViewModel extends ViewModel
{
    public $topRatedMovies;
    public $genres;
    public $page;

    public function __construct($topRatedMovies, $genres, $page)
    {
       	$this->genres = $genres;
		$this->page = $page;
        $this->topRatedMovies = $topRatedMovies;
    }

    
    public function topRatedMovies()
    {
        return $this->formatMovies($this->topRatedMovies);
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    private function formatMovies($movies)
    {
        return collect($movies)->map(function($movie) {
            $genresFormatted = collect($movie['genre_ids'])->mapWithKeys(function($value) {
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

            return collect($movie)->merge([
                'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'],
                'vote_average' => $movie['vote_average'] * 10 .'%',
                'release_date' => Carbon::parse($movie['release_date'])->format('M d, Y'),
                'genres' => $genresFormatted,
            ])->only([
                'poster_path', 'id', 'genre_ids', 'title', 'vote_average', 'overview', 'release_date', 'genres',
            ]);
        });
    }

    public function previous()
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    public function next()
    {
        return $this->page < 500 ? $this->page + 1 : null;
    }
}
