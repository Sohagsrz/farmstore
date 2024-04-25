@extends('layouts.default')

@section('title', 'Checkout')
@section('content')
<div class="container mt-5">
        <h1>Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- Order Summary -->
                <h2>Order Summary</h2>
                <div class="card">
                    <div class="card-body">
                        <!-- Display selected items from the cart -->
                        <ul class="list-group">
                            @foreach($cart as $item)
                            <li class="list-group-item">{{$item->name}} - ৳{{$item->price}} x {{$item->quantity}} = ৳{{$item->price * $item->quantity}}
                        </li>
                            @endforeach 
                        </ul>
                        <hr>
                        <!-- Display total price -->
                        <h5>Total: ৳{{
                        Cart::session(Auth::id())->getTotal()
                        }}
                    </h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Shipping Information Form -->
                <h2>Shipping Information</h2>
                <form action="{{route('checkout')}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" rows="3" name="address" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email"  name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone"  name="phone" required>
                    </div>
                    <!-- Payment Method (COD) -->
                    <div class="mb-3">
                        <label>Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="cod" checked>
                            <label class="form-check-label" for="cod">
                                Cash on Delivery (COD)
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
            </div>
        </div>
    </div>
 
@stop
@push('js')

@endpush

