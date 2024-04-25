<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('includes.head')
   @stack('css')
</head>
<body>

 
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="{{url('/')}}">{{env("APP_NAME")}}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>  

    
    <div class="collapse navbar-collapse" id="navbarNav">
    <form class="d-flex ms-auto" action="{{route('search')}}" method="GET">
        <input class="form-control me-2" type="search" name="q" placeholder="Search" value="{{request()->get('q','')}}" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="{{route('home')}}">Home</a>
        </li>
        @if (Auth::check())

        @if(Auth::user()->getRole() == 'farmer')
        <li class="nav-item">
          <a class="nav-link" href="{{route('farmer.addfromdashboard')}}">Add Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('farmer.dashboard')}}">Dashboard</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('orders')}}">Orders</a>
        </li>
        @endif

        <li class="nav-item">
          <a class="nav-link" href="{{route('cart')}}">Cart <span class="badge bg-primary">{{Cart::session(Auth::id())->
          getTotalQuantity()}}</span>
         </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('chats')}}">Messages <span class="badge bg-primary">2</span>
         </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('logout')}}">Logout</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('login')}}">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('register')}}">Register</a>
        </li>
        @endif
        
      </ul>
    </div>

     
  </div>
</nav>



<div class="container">
   <header class="row">
       @include('includes.header')
   </header>
   @foreach(['danger', 'warning', 'success', 'info'] as $msg)
       @if(session()->has($msg))
           <div class="alert alert-{{ $msg }}">
               {{ session()->get($msg) }}
           </div>
       @endif
    @endforeach

   <div id="main" class="row">
           @yield('content')
   </div>
   <footer class="row">
       @include('includes.footer')
   </footer>
</div>
@stack('js')
</body>
</html>