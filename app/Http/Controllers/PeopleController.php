<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\PeopleViewModel;
use App\ViewModels\PeoplesViewModel;
use Illuminate\Support\Facades\Http;

class PeopleController extends Controller
{
    
   public function index($page = 1)
    {
        abort_if($page > 500, 204);

        $popularActors = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/popular?page='.$page)
            ->json()['results'];

        $viewModel = new PeoplesViewModel($popularActors, $page);

        return view('people.index', $viewModel);
    }

    public function show($id)
    {
        $actor = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id)
            ->json();

        $social = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id.'/external_ids')
            ->json();

        $credits = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id.'/combined_credits')
            ->json();

        $viewModel = new PeopleViewModel($actor, $social, $credits);

        return view('people.show', $viewModel);
    }

}
