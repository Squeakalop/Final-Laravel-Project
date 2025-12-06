@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a Category</div>
                    <div class="card-body">
                        <form method="POST" action="/categories">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="category">Category</label>
                                    <input type="text" class="form-control" name="name" title="name" value="{{ old('name', null) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/categories" class="btn btn-lg btn-danger w-100" style="margin-top:20px">Cancel</a>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" value="Add Category"
                                           class="btn btn-lg btn-primary w-100" style="margin-top:20px" />
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div><!-- .col-md-8 -->
        </div>
    </div>
@endsection

@section('styles')
@endsection

@section('scripts')
@endsection
