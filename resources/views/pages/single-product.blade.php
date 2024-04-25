@extends('layouts.default')

@section('title', $product->post_title)
@section('content')
<div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="{{get_field('thumbnail','products', $product->id, 'https://via.placeholder.com/400x300')}}" alt="{{$product->post_title}}" style="width: 100%;">

            </div>
            <div class="col-md-6">
                <h1>{{$product->post_title}}</h1>  
                <p> Framer Information  </p>
                <p>Farmer: <a href="{{route('farmerSingle', $product->user()->first())}}">
                 {{
                $product->user()->first()->name
                }}</a></p>
                <p>Email: {{$product->user()->first()->email}}</p>
                <p>Address: {{ get_field('address','user',$product->user()->first()->id, '')}}</p>
                <hr/>
                 @if($product->categories()->first())
                <p>Category: <a href="{{route('category', $product->categories()->first())}}">
                    {{ $product->categories()->first()->name }}
                </a></p>
                @endif
            
                <p>{{$product->post_content}}</p>
                <h2>৳{{get_field('price','products', $product->id, 0)}}/ {{
                get_field('unit','products', $product->id, 'item')
                
                }}</h2>
                <form action="{{route('addToCart', $product)}}" method="POST">
                    @csrf
                    <input type="number" name="quantity"
                    value="{{
                    get_field('min_order','products', $product->id, 1)
                    }}"
                       min="{{
                    get_field('min_order','products', $product->id, 1)
                    }}" max="{{get_field('quantity','products', $product->id, 1)
                    }}">
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
            </div>
             
        </div>
    </div> 
    @if(count($related) > 0)
    <div class="container mt-5">
        <h2>Related </h2>
        <div class="row">
            @foreach($related as $product)
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
    @endif
 
@stop
@push('js')

@endpush

