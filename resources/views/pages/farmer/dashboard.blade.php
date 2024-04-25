@extends('layouts.default')
@section('content')
 <!-- Header Section -->
<header class="bg-dark text-white py-5">
  <div class="container text-center">
    <h1>Dashboard</h1> 
  </div>
</header>

    <!-- Dashboard Main Content -->
    <div class="dashboard-main">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Navigation</h5>
                            <ul class="list-group">
                                <li class="list-group-item"><a href="{{route('farmer.dashboard')}}">Dashboard</a></li>
                                <li class="list-group-item"><a href="{{route('farmer.productslist')}}">Products</a></li>
                                <li class="list-group-item"><a href="{{route('farmer.orders')}}">Orders</a></li>
                                <li class="list-group-item"><a href="{{route('farmer.settings')}}">Settings</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content Area -->
                <div class="col-md-9">
                <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Total Products</h6>
                        <h2 class="card-text">{{
                        $total_products}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Total Orders</h6>
                        <h2 class="card-text">0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Customers</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Total Customers</h6>
                        <h2 class="card-text">{{ $total_products }}</h2>
                    </div>
                </div>
            </div>
        </div>

        
                    <h2>Products</h2>
                    <!-- Product Cards -->
                    <div class="row">
                        @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="card product-card">
                                <img src="{{
                                get_field('thumbnail','products', $product->id, 'https://via.placeholder.com/400x300')
                                }}" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title
                                    ">{{ $product->post_title }}</h5>
                                    <p class="card-text">Price: ৳{{ get_field('price','products', $product->id, '0') }}</p>
                                    <p class="card-text">Stock: {{ get_field('quantity','products', $product->id, '0') }}</p>
                                    <p class="card-text">Location: {{ get_field('location','products', $product->id, '0') }}</p>
                                    <a href="{{ route('farmer.editfromdashboard', $product) }}" class="btn btn-primary">Edit</a>
                                    <a href="{{ route('farmer.deletefromdashboard', $product) }}" class="btn btn-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                       
                        <!-- Add more product cards here -->
                    </div>
                    
                    <a href="{{ route('farmer.productslist') }}" class="btn btn-primary btn-block">ALL Product</a>




                    
                </div>
            </div>
        </div>
    </div>
@stop
@push('js')

@endpush

