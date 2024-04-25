@extends('layouts.default')
@section('title',  "Settings")
@section('content')
 <!-- Header Section -->
<header class="bg-dark text-white py-5">
  <div class="container text-center">
    <h1>Settings</h1> 
  </div>
</header>
 
<div class="container mt-5">
        <h1>Settings</h1>
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
        <form action="{{route('farmer.settings')}}" method="POST" enctype="multipart/form-data" >
        @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{old('name', $user->name)}}" placeholder="Enter name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" disabled readonly value="{{old('email', $user->email)}}" placeholder="Enter email">
            </div> 
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="meta[address]" value="{{old('address', 
               get_field('address','user', $user->id, '') 
                )}}" placeholder="Enter address">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="meta[city]" value="{{old('city', 
                get_field('address','city', $user->id, '') 
                )}}" placeholder="Enter city">
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="meta[state]" value="{{old('state', 
                get_field('address','state', $user->id, '') 
                )}}" placeholder="Enter state">
            </div>
             
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="meta[description]" placeholder="Enter description">{{old('description', 
                get_field('description','user', $user->id, '') 
                )}}</textarea>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter confirm password">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="uploadImg">
                or Image url
                <input type="text" class="form-control" id="image" name="meta[profile_pic]" value="{{ 
                get_field('profile_pic','user', $user->id, '')
                 }}" placeholder="Enter image url">

            </div>


            <button type="submit" class="btn btn-primary">Update</button>
@stop
@push('js')

@endpush

