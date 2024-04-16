@extends('layouts.admin')
@section('content')



<div class="h-screen flex-grow-1 overflow-y-lg-auto">
        
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <!-- Card stats -->
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
            
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">All <?php echo $name;?></h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{route('admin.categories.add') }}" class="btn btn-sm btn-primary">Add New</a>
                        </div>
                    </div> 
                     
                </div>
                
                <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Edit / Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            {{$category->name}}
                                        </td> 
                                        <td>
 
                                            <a href="{{route('category',$category)}}"
                                                 class="btn btn-sm btn-neutral">
                                                <i class="bi bi-eye"></i></a>
                                            
                                            <a href="{{route('admin.categories.edit',['id'=>$category->id])}}" class
                                            ="btn btn-sm btn-neutral"><i class="bi bi-pencil"></i></a>
                                            <a href="{{route('admin.categories.delete',['id'=>$category->id])}}" onclick="return confirm('Are you sure you want to delete ?')" class="btn btn-sm btn-neutral"> <i class="bi bi-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                      
                                    
                                </tbody>
                            </table>

                </div>
               

                
                <div class="card-footer border-0 py-5">

                    <span class="text-muted text-sm">
                        {{ $categories->firstItem()}}-{{ $categories->lastItem() }} of {{ $categories->total() }} results
                     </span>
                     @if($categories->total()>0)
                        <span class="text-muted text-sm">page {{$categories->currentPage()}} of {{$categories->lastPage()}}</span>
                     @endif
                     @if ($categories->total() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item {{ $categories->currentPage() == 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $categories->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                @for ($i = 1; $i <= $categories->lastPage(); $i++)
                                    <li class="page-item {{ $categories->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $categories->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $categories->currentPage() == $categories->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $categories->nextPageUrl() }}">Next</a>
                                </li>
                            </ul>
                        </nav>
                    @endif 
 
                </div>
            </div>
        </div>
    </main>
</div>


@stop
@push('js')
 <style>
    /* hello */
 </style>
@endpush