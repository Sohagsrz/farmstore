@extends('layouts.default')
@section('content')
   i am the single page
   {{$movie->post_title}}
   {{$movie->post_content}} 
@stop
@push('js')

@endpush