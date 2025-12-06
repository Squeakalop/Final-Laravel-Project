@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Company</div>
                    <div class="card-body">
                        <form method="POST" action="/categories/{{ $category->id }}">
                            @csrf
                            <input type="hidden" name="_method" value="PUT"/>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control" name="name" title="name"
                                           value="{{ old('category', $category->name) }}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/categories" class="btn btn-lg btn-danger w-100" style="margin-top:20px">Cancel</a>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Save Category"
                                    class="btn btn-lg btn-success w-100" style="margin-top:20px" />
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
