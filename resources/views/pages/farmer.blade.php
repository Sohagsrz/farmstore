@extends('layouts.default')

@section('title', $farmer->name)

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-2">
             <div class="card text-center">
                <div class="card-body">
                  
                    <img src="{{get_field('profile_pic','user', $farmer->id, 'https://via.placeholder.com/400x300')}}" alt="{{$farmer->name}}" style="width: 100%;">
                    
                    <h3>{{
                    $farmer->name
                    }}</h3>
                      
                    <p>Address: {{  get_field('address','user', $farmer->id, '') }}
                    <p>{{
                    get_field('description','user', $farmer->id, '') 
                    
                    }}</p>
                </div>
</div>
        </div>
        <div class="col-md-10">
            <h2>Products</h2>
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

        </div>
    </div>
</div>
 
 
@stop
@push('js')

@endpush

