@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create an item</div>
                    <div class="card-body">
                        <form method="POST" action="/items" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="item">Category</label>
                                    <input type="text" class="form-control" name="category_id" title="category_id" value="{{ old('category_id', null) }}">
                                    <label for="item">Title</label>
                                    <input type="text" class="form-control" name="title" title="title" value="{{ old('title', null) }}">
                                    <label for="item">Description</label>
                                    <input type="text" class="form-control" name="description" title="description" value="{{ old('description', null) }}">
                                    <label for="item">Price</label>
                                    <input type="text" class="form-control" name="price" title="price" value="{{ old('price', null) }}">
                                    <label for="item">Quantity</label>
                                    <input type="text" class="form-control" name="quantity" title="quantity" value="{{ old('quantity', null) }}">
                                    <label for="item">SKU</label>
                                    <input type="text" class="form-control" name="sku" title="sku" value="{{ old('sku', null) }}">
                                    <label for="item">Picture</label>
                                    <input type="file" class="form-control" name="picture" title="picture">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/items" class="btn btn-lg btn-danger w-100" style="margin-top:20px">Cancel</a>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Add Item"
                                           class="btn btn-lg btn-primary w-100" style="margin-top:20px" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- .col-md-12 -->
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection
