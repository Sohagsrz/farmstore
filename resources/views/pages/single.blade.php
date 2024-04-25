@extends('layouts.default')

@section('title', $post->post_title)

@section('content')
<h1>
    Single All
</h1>
<pre>

    {{
        dd($post->toArray())
    }}
</pre>
@stop
@push('js')

@endpush

