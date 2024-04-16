@extends('layouts.default')
@section('content')
<h1>
    Movies
</h1>
<pre>

    {{
        var_dump($post->toArray())
    }}
    <h1>
        Fields
    </h1>
    {{
        var_dump($post->custom_fields() ->toArray())
    }}
</pre>
@stop
@push('js')

@endpush

