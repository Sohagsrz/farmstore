@extends('layouts.default')
@section('title', 'Search Results '. request()->input('q'))

@section('content')

 
<header class="bg-dark text-white py-5">
  <div class="container text-center"> 
    <p class="lead">Search results {{
    request()->input('q')
    
    }}</p>
  </div>
</header>
 
<section class="py-5">
  

<div class="container mt-5">
        <div class="row">
         @foreach($products as $product)
            <div class="col-md-4">
                <div class="card product-card">
                  <a href="{{ route('productSingle', $product) }}"><img src="{{get_field('thumbnail','products', $product->id, 'https://via.placeholder.com/400x300')}}" class="card-img-top" alt="Product Image"></a>
                   
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->post_title }}</h5> 
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>৳{{get_field('price','products', $product->id, '0')}}</h4>  
                            
                            <form action="{{route('addToCart' , $product)}}" method="POST">
                                @csrf
                                <input type="number" name="quantity" max="{{
                                get_field('quantity','products', $product->id, 1)
                                }}" min="{{
                                get_field('min_order','products', $product->id, 1)
                                }}" value="{{
                                get_field('min_order','products', $product->id, 1)
                                }}">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                            </form>
                               </div>
                    </div>
                  </div>
            </div>
            @endforeach

            
            <!-- Add more product cards here -->
        </div>
    </div>
 
</section> 
  



<style>
    .product-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card .card-img-top {
            border-radius: 15px 15px 0 0;
        }
        .product-card .card-body {
            padding: 20px;
        }
        .product-card .card-body h5 {
            font-size: 1.25rem;
        }
        .product-card .card-body p {
            font-size: 1rem;
        }
        .product-card .card-body h4 {
            font-size: 1.5rem;
        }
        .product-card .card-body button {
            border-radius: 5px;
        }
        .product-card .card-body button i {
            margin-right: 0.5em;
        }
        .product-card .card-body button:first-child {
            border-color: #f1f1f1;
            color: #f1f1f1;
        }
        .product-card .card-body button:first-child:hover {
            border-color: #007bff;
            color: #007bff;
        }
        .product-card .card-body button:last-child {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .product-card .card-body button:last-child:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
</style>
@stop
@push('js') 

@endpush
        