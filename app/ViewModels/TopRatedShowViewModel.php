<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;
use Carbon\Carbon;

class TopRatedShowViewModel extends ViewModel
{
   public $toprated;
   public $genres;
   public $page;

    public function __construct($toprated, $genres, $page)
    {
        $this->toprated = $toprated;
        $this->genres = $genres;
        $this->page = $page;
    }

    public function toprated()
    {
        return $this->formatTv($this->toprated);
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    private function formatTv($tv)
    {
        return collect($tv)->map(function($tvshow) {
            $genresFormatted = collect($tvshow['genre_ids'])->mapWithKeys(function($value) {
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

            return collect($tvshow)->merge([
                'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$tvshow['poster_path'],
                'vote_average' => $tvshow['vote_average'] * 10 .'%',
                'first_air_date' => Carbon::parse($tvshow['first_air_date'])->format('M d, Y'),
                'genres' => $genresFormatted,
            ])->only([
                'poster_path', 'id', 'genre_ids', 'name', 'vote_average', 'overview', 'first_air_date', 'genres',
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
