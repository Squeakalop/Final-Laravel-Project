@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Category</div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="category">Category: </label>
                                    {{ $category-> name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/categories" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                                </div>
                            </div>
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
