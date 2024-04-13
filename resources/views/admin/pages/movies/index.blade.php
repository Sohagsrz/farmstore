@extends('layouts.admin')
@section('content')


<div class="h-screen flex-grow-1 overflow-y-lg-auto">
        
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <!-- Card stats -->
            <?php 
            // laravel sucess or error allert
            if(Session::has('success')){
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="bi bi-check-circle"></i></span>
                    <span class="alert-text
                    "><?php echo Session::get('success');?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            if(Session::has('error')){
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-icon
                    "><i class="bi bi-x-circle"></i></span>
                    <span class="alert-text
                    "><?php echo Session::get('error');?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            
            ?>
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">All {{$name}}</h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{route('admin.movies.add')}}" class="btn btn-sm btn-primary">Add New</a>
                        </div>
                    </div> 
                     
                </div>
                
                <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Added</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Views</th>
                                        <th scope="col">Edit / Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movies as $movie)
                                    <tr> 
                                        <td>
                                            {{$movie->post_title}}
                                        </td>
                                        <td>
                                            {{date('d M Y',strtotime($movie->created_at))}}
                                        </td>
                                        <td>
                                            {{$movie->post_status}}
                                        </td>
                                         
                                        <td>
                                            {{intval(get_field('views','movies', $movie->id, 0))}}
                                        </td>
                                        <td class="text-end">
                                        <a href="/{{$movie->post_slug}}" class="btn btn-sm btn-neutral" target="_blank"><i class="bi bi-eye"></i></a>
                                            <a href="{{route('admin.movies.edit',['id'=>$movie->id])}}" class="btn btn-sm btn-neutral"><i class="bi bi-pencil"></i></a>
                                            <a href="{{route('admin.movies.delete',['id'=>$movie->id])}}" onclick="return confirm('Are you sure you want to logout ?')" class="btn btn-sm btn-neutral"> <i class="bi bi-trash"></i></a>
                                             
                                        </td>
                                    </tr>
                                    @endforeach
                              

                                    
                                </tbody>
                            </table>

                </div>
               

                <div class="card-footer border-0 py-5">
                    {{-- pagination --}}


                    
                    <span class="text-muted text-sm">
                        {{ $movies->firstItem()}}-{{ $movies->lastItem() }} of {{ $movies->total() }} results
                     </span>
                     @if($movies->total()>0)
                        <span class="text-muted text-sm">page {{$movies->currentPage()}} of {{$movies->lastPage()}}</span>
                     @endif
                     @if ($movies->total() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item {{ $movies->currentPage() == 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $movies->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                @for ($i = 1; $i <= $movies->lastPage(); $i++)
                                    <li class="page-item {{ $movies->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $movies->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $movies->currentPage() == $movies->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $movies->nextPageUrl() }}">Next</a>
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