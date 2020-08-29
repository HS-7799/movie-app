<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\actor\CreateActorRequest;
use App\Http\Requests\actor\UpdateActorRequest;

class ActorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['store','update','destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->wantsJson())
        {
            return Actor::latest()->paginate(10);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateActorRequest $request)
    {
        $actor = Actor::create([
            'name' => ucwords(request('name')),
            'slug' => Str::slug(request('name')),
            'biographie' => request('biographie')
        ]);

        if(request('photo'))
        {
            $this->uploadPhoto($request,$actor);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        return $actor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateActorRequest $request, Actor $actor)
    {
        $actor->update([
            'name' => ucwords(request('name')),
            'slug' => Str::slug(request('name')),
            'biographie' => request('biographie')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actor $actor)
    {
        $this->authorize('delete_actor');
        $actor->clearMediaCollection();
        return $actor->delete();
    }

    public function uploadPhoto(Request $request,Actor $actor)
    {
        $this->authorize('update_actor');
        request()->validate([
            'photo' => 'required|file|image'
        ]);
        $actor->clearMediaCollection();
        $actor->addMedia(request('photo'))->toMediaCollection();
        $url = $actor->getPhotoConverted();

        $actor->update([
            'photo' => $url
        ]);

        return $url;
    }

}
