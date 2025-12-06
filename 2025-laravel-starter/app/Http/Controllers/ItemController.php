<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use Illuminate\Http\Request;
use Session;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = \App\Models\ItemModel::all()->sortBy('title');

        return view('items.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // minimal validation (no fancy checks)
        $rules = [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|max:100|unique:items,title',
            'description' => 'required|max:500',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'sku' => 'required|max:50|unique:items,sku',
            // require a file upload (no further checks)
            'picture' => 'required|file'
        ];

        $this->validate($request, $rules);

        $item = new \App\Models\ItemModel;
        $item->category_id = $request->category_id;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        // if a file was uploaded, move it to public/images using the original filename
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $originalName = $file->getClientOriginalName();
            $file->move(public_path('images'), $originalName);
            $item->picture = $originalName;
        }

        $item->save();

        Session::flash('success', 'A new item has been created');
        return redirect()->route('items.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = ItemModel::find($id);

        if(!$item) {
            return response() ->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }
        return view('items.show')->with('item', $item);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = \App\Models\ItemModel::find($id);

        if(!$item) {
            return response() ->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }

        return view('items.edit')->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = \App\Models\ItemModel::find($id);
        if(!$item) {
            return response() ->json([
                'status' => false,
                'message' => 'Item not found',
            ],404);
        }

        $rules = [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|max:100|unique:items,title,' . $id,
            'description' => 'required|max:500',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'sku' => 'required|max:50|unique:items,sku,' . $id,
            // file optional on update
            'picture' => 'nullable|file'

        ];
        $this->validate($request, $rules);

        $item->category_id = (int) $request->category_id;
        $item->title = $request->title;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->quantity = $request->quantity;
        $item->sku = $request->sku;

        // if a new file was uploaded, move it to public/images using the original filename and save it
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $originalName = $file->getClientOriginalName();
            $file->move(public_path('images'), $originalName);
            $item->picture = $originalName;
        }

        $item->save();

        //Flash a success message
        Session::flash('success', 'The item has been updated');
        //redirect to index
        return redirect()->route('items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd('delete');
        $item = \App\Models\ItemModel::find($id);
        if (!$item) {

            Session::flash('error', 'No item found');

        } else {
            $item->delete();
            Session::flash('success', 'Item deleted');
        }

        return redirect()->route('items.index');

    }

}
