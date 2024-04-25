@extends('layouts.default')
@section('title', 'Connecting farmers with consumers')
@section('content')


<!-- Header Section -->
<header class="bg-dark text-white py-5">
  <div class="container text-center">
    <h1>Welcome to Farm to Consumer</h1>
    <p class="lead">Connecting farmers with consumers</p>
  </div>
</header>

<!-- Features Section -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-seedling fa-3x"></i>
            <h3>Locally Grown</h3>
            <p>Our products are locally grown and sourced from farmers in your area.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-truck fa-3x"></i>
            <h3>Direct Delivery</h3>
            <p>Products are delivered directly from the farm to your doorstep.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-shopping-basket fa-3x"></i>
            <h3>Shop Online</h3>
            <p>Order fresh produce online and have it delivered to your home.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
  <div class="container">
    <h2>Categories</h2>
    <p class="lead">Browse our categories to find the products you're looking for.</p>
    <div class="row">
      @foreach($categories as $category)
      <div class="col-md-2"> 
            <h5 class="card-title">{{ $category->name }}</h5>
            <a href="{{ route('category', $category) }}" class="btn btn-primary">View Products</a>
           
      </div>
      @endforeach
    </div>
  </div>
</section>
<!-- recent products -->
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
 
<section class="py-5 bg-light">
  <div class="container">
    <h2>Top Farmers</h2>
    <p class="lead">Meet some of the top farmers.</p>
    <div class="row">
      @foreach($farmers as $farmer)
      <div class="col-md-4">
        <div class="card text-center">
          <img src="{{get_field('profile_pic','user', $farmer->id, 'https://via.placeholder.com/400x300')}}" class="card-img-top rounded-circle" alt="Farmer Image">
          <div class="card-body">
            <h5 class="card-title">{{ $farmer->name }}</h5> 
            <a href="{{ route('farmerSingle', $farmer) }}" class="btn btn-primary">View Farm</a>
          </div>
        </div>
      </div>
      @endforeach
 
       
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
   
 
      <div class="col-md-6">
        <h2>Latest News</h2>
        <p class="lead">Stay up to date with the latest news and updates from our farm.</p>
      </div>
      </div>
    <div class="row">
      @foreach($news as $new)
      <div class="col-md-4">
        <div class="card">
          <img src="{{get_field('thumbnail','news', $new->id, 'https://via.placeholder.com/400x300')}}" class="card-img-top" alt="News Image">
          <div class="card-body">
            <h5 class="card-title
            ">{{ $new->post_title }}</h5> 
            <a href="{{ route('single', $new) }}" class="btn btn-primary">Read More</a>
            </div>
         </div>
      </div>
      @endforeach
       
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
        