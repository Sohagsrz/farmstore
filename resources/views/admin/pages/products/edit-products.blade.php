@extends('layouts.admin')
@section('content')

 <div class="h-screen flex-grow-1 overflow-y-lg-auto">

<main class="py-6 bg-surface-secondary">
    <div class="container-fluid">

        <div class="card shadow border-0 mb-7">
            
            <div class="card-header add_cat">
                <h5 class="mb-0">Edit  {{$newss->post_title}}</h5>
                 
            </div>
            </div>
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="bi bi-check-circle"></i></span>
                <span class="alert-text
                ">{{Session::get('success')}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="bi bi-x-circle"></i></span>
                <span class="alert-text
                ">{{$error}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endforeach
            @endif



        <div class="card shadow border-0 mb-7 add-container">
            <div class="card-body">
                <form action="{{
                    route('admin.news.update',  [ 'id' => $newss->id])
                }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" name="post_title" class="form-control" id="title" 
                        value="{{$newss->post_title}}" required> 
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <textarea class="form-control" name="post_content" id="content"  rows="4" required>{{$newss->post_content}}</textarea>
                    </div> 
                    <div class="mb-3">
                        <label for="uploadImg" class="form-label">Upload Image:</label>
                        <input type="file" name="uploadImg" class="form-control" id="uploadImg" >
                        <br/>  
                        <label for="img" class="form-label">Or Input Image Url:</label>
                         <input type="url" name="img" class="form-control" id="img" 
                         value="{{get_field('thumbnail','news', $newss->id, '')}}"
                         >
                    </div>
 
                     
 

                    <button type="submit" class="btn btn-sm bg-surface-secondary btn-neutral">Update  {{ $name}}</button>
                </form>
            </div>
        </div>
    </div>
</main>
</div>


@stop
@push('css')
<style>
    .input-group {
      margin-bottom: 10px;
    }
  </style>
<link
  rel="stylesheet"
  href="https://unpkg.com/jodit@4.0.1/es2021/jodit.min.css"
/>

@endpush

@push('js')
<script src="https://unpkg.com/jodit@4.0.1/es2021/jodit.min.js"></script>
          <script>
            
            var editor = Jodit.make('#content');

            jQuery(document).ready(function($) {
    $('select').select2();
});
function addInput() {
      var inputContainer = document.getElementById("input-container");
      var newInput = document.createElement("div");
      newInput.classList.add("input-group");
      newInput.innerHTML = `
        <input type="url" name="watch_urls[]" class="form-control">
        <button type="button" class="btn btn-danger" onclick="removeInput(this)">Delete</button>
      `;
      inputContainer.appendChild(newInput);
    }
    function addDlinput() {
      var inputContainer = document.getElementById("input-container-dl");
      var newInput = document.createElement("div");
      newInput.classList.add("input-group");
      newInput.innerHTML = `
        <input type="url" name="dl_urls[]" class="form-control">
        <button type="button" class="btn btn-danger" onclick="removeInput(this)">Delete</button>
      `;
      inputContainer.appendChild(newInput);
    }


    function removeInput(btn) {
      btn.parentNode.remove();
    }
          </script>  
@endpush