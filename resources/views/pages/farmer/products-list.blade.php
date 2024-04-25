@extends('layouts.default')
@section('title',  "Products")
@section('content')
 <!-- Header Section -->
<header class="bg-dark text-white py-5">
  <div class="container text-center">
    <h1>All Products</h1> 
  </div>
</header>

<div class="container mt-5">
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3">
            <div class="card">
                <img src="{{get_field('thumbnail','products', $product->id, 'https://via.placeholder.com/400x300')}}" alt="{{$product->post_title}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{$product->post_title}}</h5>
                    <p class="card-text">৳{{get_field('price','products', $product->id, 0)}}</p>
                                    <a href="{{ route('farmer.editfromdashboard', $product) }}" class="btn btn-primary">Edit</a>
                                    <a href="{{ route('farmer.deletefromdashboard', $product) }}" class="btn btn-danger">Delete</a>
                    <a href="{{route('productSingle', $product)}}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

 
@stop
@push('js')

@endpush

