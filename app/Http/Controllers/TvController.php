<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use App\ViewModels\AiringTodayTvShowViewModel;
use App\ViewModels\ONTvShowViewModel;
use App\ViewModels\TopRatedShowViewModel;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    public function index($page = 1)
    {
    	abort_if($page > 500, 204);

        $popularTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/popular?page='.$page)
            ->json()['results'];

       // $topRatedTv = Http::withToken(config('services.tmdb.token'))
          //  ->get('https://api.themoviedb.org/3/tv/top_rated')
            //->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new TvViewModel(
            $popularTv,
            $genres,
            $page
        );

        return view('tv.index', $viewModel);
    }

    public function show($id)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$id.'?append_to_response=credits,videos,images')
            ->json();

        $similertvshow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/'.$id.'/similar')
            ->json()['results'];
           

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];  


        $viewModel = new TvShowViewModel($tvshow, $similertvshow, $genres);

        return view('tv.show', $viewModel);
    }

    public function airingToday($page = 1)
    {
        abort_if($page > 500, 204);

        $airingToday = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/airing_today?page='.$page)
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new AiringTodayTvShowViewModel(
            $airingToday,
            $genres,
            $page
        );

        return view('tv.airing-today', $viewModel);
    }

    public function ontV($page = 1)
    {
        abort_if($page > 500, 204);

        $ontvshow = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/on_the_air?page='.$page)
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new ONTvShowViewModel(
            $ontvshow,
            $genres,
            $page
        );

        return view('tv.on-tv', $viewModel);
    }

    public function topRated($page = 1)
    {
        abort_if($page > 500, 204);

        $toprated = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/top_rated?page='.$page)
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

        $viewModel = new TopRatedShowViewModel(
            $toprated,
            $genres,
            $page
        );

        return view('tv.top-rated', $viewModel);
    }
}
