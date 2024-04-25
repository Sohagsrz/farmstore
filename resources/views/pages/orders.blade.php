@extends('layouts.default')

@section('title', 'Your Orders')
@section('content')
<div class="container mt-5">
    <h1>Your Orders</h1>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($orders->isEmpty())
                <tr>
                    <td colspan="4" class="text-center">You have not placed any orders yet</td>
                </tr>
                @endif
                @foreach($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>৳{{ 
                    get_field('total','orders', $order->id, 0)
                    
                    }}</td>
                    <td>{{$order->post_status}}</td>
                    <td>
                        <a href="{{route('orderDetails', $order->id)}}" type="button" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
 
@stop
@push('js')

@endpush

