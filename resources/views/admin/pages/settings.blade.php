@extends('layouts.admin')
@section('content')

<style>
    .input-group {
      margin-bottom: 10px;
    }
  </style>
 <div class="h-screen flex-grow-1 overflow-y-lg-auto">

<main class="py-6 bg-surface-secondary">
    <div class="container-fluid">
       
        <div class="card shadow border-0 mb-7">
            
            <div class="card-header add_cat">
                <h5 class="mb-0">Settings</h5>
                 
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
                    route('admin.settingsProccess')
                }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                    
                <div class="mb-3">
                    <label for="sitename" class="form-label">Sitename</label>
                    <input type="text" name="sitename" value="{{get_option('sitename','')}}" class="form-control" id="siteTitle"  required>
                </div>
                
                    
                <div class="mb-3">
                    <label for="siteTitle" class="form-label">siteTitle</label>
                    <input type="text" name="siteTitle" value="{{get_option('siteTitle','')}}" class="form-control" id="sitename"  required>
                </div>

 
 
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" id="description"  required><?php echo htmlspecialchars(get_option('description',''));?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keywords" class="form-label">Keywords</label>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars(get_option('keywords',''));?>" class="form-control" id="keywords"  required>
                    </div>
                   
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo URL</label>
                        <input type="url" name="logo" class="form-control" id="logo"  value="{{get_option('logo','')}}" >
                    </div>
                    <div class="mb-3">
                        <label for="favicon" class="form-label">Favicon URL</label>
                        <input type="url" name="favicon" class="form-control" id="favicon"  value="{{get_option('favicon','')}}"  >
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Site Email</label>
                        <input type="emaurlil" name="email" value="<?php echo htmlspecialchars(get_option('email',''));?>" class="form-control" id="email"  required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="facebook" class="form-label">Facebook</label>
                        <input type="text" name="facebook" value="<?php echo htmlspecialchars(get_option('facebook',''));?>" class="form-control" id="facebook"  required>
                    </div>
                    <div class="mb-3">
                        <label for="telegram" class="form-label">Telegram</label>
                        <input type="text" name="telegram" value="<?php echo htmlspecialchars(get_option('telegram',''));?>" class="form-control" id="telegram"  required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telegram" class="form-label">Header Codes</label>
                        <textarea name="header_codes" class="form-control" id="header_codes"  required><?php echo htmlspecialchars(get_option('header_codes',''));?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="footer_codes" class="form-label">Footer Codes</label>
                        <textarea name="footer_codes" class="form-control" id="footer_codes"  required><?php echo htmlspecialchars(get_option('footer_codes',''));?></textarea>
                    </div>
                      
                  

                    <button type="submit" class="btn btn-sm bg-surface-secondary btn-neutral" style="width: 20%; " >Update</button>
                </form>
            </div>
        </div>
    </div>
</main>
</div>


@stop
@push('js')
 <link
  rel="stylesheet"
  href="https://unpkg.com/jodit@4.0.1/es2021/jodit.min.css"
/>
<script src="https://unpkg.com/jodit@4.0.1/es2021/jodit.min.js"></script>
          <script>
            
            var editor = Jodit.make('#content');

            jQuery(document).ready(function($) {
    $('select').select2();
}); 
          </script> 
@endpush