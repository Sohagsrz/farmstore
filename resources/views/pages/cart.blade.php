@extends('layouts.default')
@section('title', 'Cart')
@section('content')

<div class="container mt-5">
        <h1>Your Cart</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(Cart::isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">Your cart is empty</td>
                    </tr>
                    @else
                   
                  

                    @foreach($cart as $item)
                    <tr>
                        <td>
                        <a href="{{route('productSingle', $item->associatedModel)}}">
                        <img src="{{get_field('thumbnail','products', $item->associatedModel->id, 'https://via.placeholder.com/400x300')}}" alt="{{$item->name}}" style="width: 100px;">

                        {{$item->name}}
</a></td>
                        <td>৳{{$item->price}}</td>
                        <td>{{$item->quantity}}</td> 
                        <td> 
                            <form action="{{route('updateCart', $item->associatedModel)}}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{$item->quantity}}" min="{{
                                get_field('min_order','products', $item->associatedModel->id, 1)
                                }}" max="{{get_field('quantity','products', $item->associatedModel->id, 1)
                                }}">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                         
                        <td>
                            <a href="{{route('removeFromCart', $item->associatedModel)}}" type="button" class="btn btn-sm btn-danger">Remove</a>
                        </td>
                    </tr>

                    @endforeach 
                    
                    @foreach($cartConditions as $condition)
                    <tr>
                        <td colspan="4">{{$condition->getName()}}</td>
                        <td>+৳{{$condition->getCalculatedValue(Cart::getSubTotal())}}</td>
                    </tr>
                    @endforeach


                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td>৳{{Cart::getTotal()}}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div> 
        <div class="text-end">
            <a href="{{route('emptyCart')}}" class="btn btn-danger">Empty Cart</a>
        </div>
        <br>
        <div class="text-end">
            <a href="{{
            route("checkout")
            }}" class="btn btn-primary">Checkout</a>
        </div>
    </div>

@stop