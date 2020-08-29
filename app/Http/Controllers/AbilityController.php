<?php

namespace App\Http\Controllers;

use App\Ability;
use App\Http\Resources\ability\AbilityResource;
use Illuminate\Http\Request;

class AbilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AbilityResource::collection(Ability::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create_role');
        request()->validate([
            'name' => ['required','unique:abilities']
        ]);

        $ability = Ability::create([
            'name' => request('name'),
            'label' => ucwords(str_replace('_',' ',request('name')))
        ]);

        return $ability;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ability  $ability
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ability $ability)
    {
        //
    }
}
