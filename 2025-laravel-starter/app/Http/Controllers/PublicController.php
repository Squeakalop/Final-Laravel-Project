<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        //get all categories from database
        $categories = \App\Models\CategoryModel::all()->sortBy('name');

        return view('public.index')->with('categories', $categories);
    }
}
