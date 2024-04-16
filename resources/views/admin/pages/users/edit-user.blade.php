@extends('layouts.admin')
@section('content')
 

 <div class="h-screen flex-grow-1 overflow-y-lg-auto">

<main class="py-6 bg-surface-secondary">
    <div class="container-fluid">

        <div class="card shadow border-0 mb-7">
            
            <div class="card-header add_cat">
                {{-- delete button right --}}
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0"> {{$name}}</h5>
                    </div>
                    <div class="col text-end">
                        <a href="{{route('admin.users.add')}}" class="btn btn-sm btn-primary float-right">Add New</a>
                    </div>
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
                <form action="{{route('admin.users.update', [
                    'id' => $user->id
                ])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Name:</label>
                        <input type="text" name="name" class="form-control" id="title" value="{{$user->name}}">
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" id="email" value="{{$user->email}}">
                    </div>
                    {{-- password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password: (Keep empty for not change)</label>
                        <input type="password" name="password" class="form-control" id="password">
                    </div>
                    {{-- role --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <select name="role" class="form-control" id="role" >
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                            <option value="{{$role->name}}" @if($user->role == $role->name) selected @endif>{{$role->name}}</option>
                            @endforeach 
                        </select>
                    </div>
                    

                
                       

                    <button type="submit" class="btn btn-sm bg-surface-secondary btn-neutral" style="width: 20%; " >Update <?php echo $name;?></button>
                </form>
            </div>
        </div>
    </div>
</main>
</div>

@stop
@push('css')
 

@endpush

@push('js')
 
 
 
@endpush