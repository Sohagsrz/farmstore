@extends('layouts.default')

@section('title', 'Order - '. $order->id)

@section('content')
<div class="container mt-5">
    <h1>Order Details</h1>
     
    <div class="alert alert-info" role="alert">
        <strong>Status:</strong> {{$order->post_status}}
    </div>
    <div>

    Customer : {{get_field('name','orders', $order->id, '')}}
    </div>
    <div>
        Address: {{get_field('address','orders', $order->id, '')}}
    </div>
    <div>
        Phone: {{get_field('phone','orders', $order->id, '')}}
    </div>
    <div>
        Email: {{get_field('email','orders', $order->id, '')}}
    </div>
    <div>
        Order Date: {{$order->created_at}}
    </div>
    <div>
        Payment Method: {{get_field('paymentMethod','orders', $order->id, '')}}
    </div>
    <div>
        Delivery Charge: ৳100
    </div>
   
    
     

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <a href="{{route('single', App\Models\Srz_Cpt::find(
                        $item->associatedModel->id
                        )
                        
                        )}}">
                            <img src="{{get_field('thumbnail','products', $item->associatedModel->id, 'https://via.placeholder.com/400x300')}}" alt="{{$item->name}}" style="width: 100px;">
                            {{$item->name}}
                        </a>
                    </td>
                    <td>৳{{$item->price}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>৳{{$item->price * $item->quantity}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td>৳{{get_field('total','orders', $order->id, 0)}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
 
@stop
@push('js')

@endpush

