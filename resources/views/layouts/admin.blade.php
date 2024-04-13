<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('admin.includes.head')
   @stack('css')
</head>
<body>
     @include('admin.includes.header')
     @yield('content')
     @include('admin.includes.footer')
     @stack('js')
</body>
</html>