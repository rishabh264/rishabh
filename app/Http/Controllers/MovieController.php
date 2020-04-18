<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\ViewModels\PopularMovieViewModel;
use App\ViewModels\MoviesViewModel;
use App\ViewModels\MovieViewModel;
use App\ViewModels\NowPlayingMovieViewModel;
use App\ViewModels\UpcomingMovieViewModel;
use App\ViewModels\TopRatedMovieViewModel;

class MovieController extends Controller
{
   //Index pages
   public function index(){

   	 $popularMovies = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular')->json()['results'];
   	
   $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

   $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];

    $popularTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/popular')
            ->json()['results'];

    $genresTVArray = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'];

    $genrestv = collect($genresTVArray)->mapWithKeys(function ($genre) {
    return [$genre['id'] => $genre['name']];
	});

    $onairtv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/on_the_air')
            ->json()['results'];
 		

   	$viewModel = new MoviesViewModel(
            $popularMovies,
            $nowPlayingMovies,
            $genres,
            $popularTv,
            $genrestv,
            $onairtv

        );

      return view('index', $viewModel);        


   }

   //details of movies
   public function show($id){

   	$data['movie'] = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images')
            ->json();

   $data['similerMovies'] =  Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'/similar')
            ->json()['results'];

    $data['reviews'] =  Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'/reviews')
            ->json()['results'];


    $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

    $data['genres'] = collect($genresArray)->mapWithKeys(function ($genre) {
    return [$genre['id'] => $genre['name']];
	  });

   	return view('movies.show', $data);
   }

   //Movies list
  public function popularMovies($page = 1){

    abort_if($page > 500, 204);

    $popularMovies = Http::withToken(config('services.tmdb.token'))->get('https://api.themoviedb.org/3/movie/popular?page='.$page)->json()['results'];

    $genres = Http::withToken(config('services.tmdb.token'))
              ->get('https://api.themoviedb.org/3/genre/movie/list')
              ->json()['genres'];

          
    $viewModel = new PopularMovieViewModel($popularMovies,  $genres, $page);

    return view('movies.list', $viewModel);
   
  }

  public function nowplaying($page = 1){
  
  abort_if($page > 5, 204);

  $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing?page='.$page)
            ->json()['results'];

    $genres = Http::withToken(config('services.tmdb.token'))
              ->get('https://api.themoviedb.org/3/genre/movie/list')
              ->json()['genres'];

    $viewModel = new NowPlayingMovieViewModel($nowPlayingMovies, $genres, $page);

    return view('movies.now-playing', $viewModel);
  }

  public function upcoming($page = 1){

  abort_if($page > 30, 204);
  
    $upcomingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/upcoming?page='.$page)
            ->json()['results'];

    $genres = Http::withToken(config('services.tmdb.token'))
              ->get('https://api.themoviedb.org/3/genre/movie/list')
              ->json()['genres'];       

    $viewModel = new UpcomingMovieViewModel($upcomingMovies, $genres, $page);

    return view('movies.upcoming', $viewModel);
            
  }


  public function topRated($page = 1){

    abort_if($page > 500, 204);
  
    $topRatedMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/top_rated?page='.$page)
            ->json()['results'];

    $genres = Http::withToken(config('services.tmdb.token'))
              ->get('https://api.themoviedb.org/3/genre/movie/list')
              ->json()['genres'];

    $viewModel = new TopRatedMovieViewModel($topRatedMovies, $genres, $page);

    return view('movies.top-rated', $viewModel);
  }



}
