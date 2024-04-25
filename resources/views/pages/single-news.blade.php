@extends('layouts.default')

@section('title', $post->post_title)
@section('content')
<div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card"> 

                    <img  src="{{get_field('thumbnail','news', $post->id, 'https://via.placeholder.com/400x300')}}" alt="{{$post->post_title}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h2 class="card-title"> 
                            {{
                            $post->post_title
                            }}
                        </h2>
                         
                        <p class="card-text">Published on
                        {{
                        date('d M Y', strtotime($post->created_at))
                        }}    
                        by  {{
                            $post->user()->first()->name
                            }}</p>
                        <p class="card-text"> 
                            {!!$post->post_content!!}

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@stop
@push('js')

@endpush

