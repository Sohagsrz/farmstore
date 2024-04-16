@extends('layouts.default')
@section('content')
<h1>
    News
</h1>
<pre>

    {{
        dd($post->toArray())
    }}
</pre>
@stop
@push('js')

@endpush

