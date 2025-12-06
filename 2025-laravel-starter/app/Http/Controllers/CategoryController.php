<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all categories from database
        $categories = \App\Models\CategoryModel::all()->sortBy('name');
//        dd($categories);

        return view('categories.index')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //check form submission for errors
        //insert into database or show error
        //dd($request->all());
        $rules = [
            'name' => 'required|max:50|unique:categories,name'
        ];
        $validator = $this->validate($request, $rules);

        $category = new \App\Models\CategoryModel;
        $category->name = $request->name;
        $category->save();

        //Flash a success message
        Session::flash('success', 'A new category has been created');
        //redirect to index
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = \App\Models\CategoryModel::find($id);

        if(!$category) {
            return response() ->json([
                'status' => false,
                'message' => 'Category not found',
            ],404);
        }
        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = \App\Models\CategoryModel::find($id);

        if(!$category) {
            return response() ->json([
                'status' => false,
                'message' => 'Category not found',
        ],404);
        }
        return view('categories.edit')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //dd($request);
        $category = \App\Models\CategoryModel::find($id);
        if(!$category) {
            return response() ->json([
                'status' => false,
                'message' => 'Category not found',
            ],404);
        }
        $rules = [
            'name' => 'required|max:50|unique:categories,name,'.$id
        ];
        $validator = $this->validate($request, $rules);

        $category->name = $request->name;
        $category->save();

        //Flash a success message
        Session::flash('success', 'The category has been updated');
        //redirect to index
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd('delete');
        $category = \App\Models\CategoryModel::find($id);
        if (!$category) {
            //dd("no company found");
            Session::flash('error', 'No category found');

        } else {
            $category->delete();
            Session::flash('success', 'Category deleted');
        }

        return redirect()->route('categories.index');

    }
}
