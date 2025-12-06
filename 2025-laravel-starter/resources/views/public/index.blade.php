@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                @foreach($categories as $category)

                    <div style="width:300px; height:200px; background-color:lightblue;
                    text-align:center; vertical-align:middle; float:left; margin:50px;">
                        <h1>{{$category->name}}</h1>


                @endforeach
            </div>
        </div><!-- .col-md-12 -->
    </div>

@endsection

@section('scripts')
@endsection

@section('styles')
@endsection
