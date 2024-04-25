@extends('layouts.default')
@section('title', 'Edit Product')
@section('content')
 <!-- Header Section -->
<header class="bg-dark text-white py-5">
  <div class="container text-center">
    <h1>Edit Product</h1> 
  </div>
</header>
 
<div class="container mt-5">
        <h1>Edit Product</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('message'))
            <div class="alert alert-success">
                {{session('message')}}
            </div>
        @endif 
        <form action="{{route('farmer.editfromdashboard', $product)}}" method="POST" enctype="multipart/form-data" >
        @csrf
            <div class="mb-3">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="productName" name="post_title" value="{{$product->post_title}}" placeholder="Enter product name">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="post_content" rows="3" placeholder="Enter description">{{$product->post_content}}</textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter price"  value="{{
                get_field('price','products', $product->id, '0')
                }}">
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity"  value="{{
                get_field('quantity','products', $product->id, '0')
                }}">
            </div> 
            <div class="mb-3">
                <label for="min_order" class="form-label">Minimum Order</label>
                <input type="number" class="form-control" id="min_order" name="meta[min_order]" placeholder="Enter minimum order"  
                value="{{get_field('min_order','products', $product->id, '0')}}"> 
            </div>
         
            <div class="mb-3">
                <label for="unit" class="form-label">Unit</label>
                <input type="text" class="form-control" id="unit" name="meta[unit]" placeholder="Enter unit kg/pcs"
                value="{{get_field('unit','products', $product->id, '0')}}"> 
            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="meta[location]" placeholder="Enter location" 
                value="{{get_field('location','products', $product->id, '0')}}">  

            </div>
            <div class="mb-3">
                <label for="unit" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="meta[contact]" placeholder="Enter contact"  value="{{old('contact')}}">
            </div>
             

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="uploadImg" name="uploadImg" m>
                <br/>
                Or you can enter the image URL
                <input type="text" class="form-control" id="image" name="img" placeholder="Enter image URL"  
                value="{{get_field('thumbnail','products', $product->id, 'https://via.placeholder.com/400x300')}}">
                
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category">
                    <option selected>Choose...</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" 
                        @if(in_array($category->id, $selected_categories) ) selected @endif
                        >{{$category->name}}</option>
                    @endforeach
                </select>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
    
@stop
@push('js')

@endpush

