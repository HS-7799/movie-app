<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\category\CreateCategoryRequest;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Http\Resources\category\CategoryResource;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show','index']);
        // $this->middleware('can:manage_category');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request('page'))
        {
            return CategoryResource::collection(Category::latest()->paginate(10));
        }
        return CategoryResource::collection(Category::latest()->get());

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create([
            'name' => ucwords(request('name')),
            'slug' => Str::slug(request('name'))
        ]);

        return response(new CategoryResource($category),Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

        if(request()->wantsJson())
        {
            return new CategoryResource($category);

        }

        return $category->movies()->count();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => ucwords(request('name')),
            'slug' => Str::slug(request('name'))
        ]);

        return response(new CategoryResource($category),Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete_category');
        $category->delete();
        return response(null,Response::HTTP_OK);
    }
}
