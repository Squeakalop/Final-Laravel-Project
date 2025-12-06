@extends('layouts.public')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Item</div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="item">Category ID: </label>
                                    {{ optional($item->category)->name }}({{$item->category_id}}) <br>
                                        <label for="item">Name: </label>
                                    {{ $item-> title }} <br>
                                    <label for="item">Description: </label>
                                    {{ $item-> description }} <br>
                                    <label for="item">Price: </label>
                                    {{ $item-> price }} <br>
                                    <label for="item">Quantity: </label>
                                    {{ $item-> quantity }} <br>
                                    <label for="item">SKU: </label>
                                    {{ $item-> sku }}<br>
                                    <label for="item">Picture: </label>
                                    {{ $item-> picture }} <br>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="/items" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
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
