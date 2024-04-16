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
                            <h5 class="mb-0">All {{$name}}</h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{route('admin.news.add')}}" class="btn btn-sm btn-primary">Add New</a>
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
                                    @if(count($news)==0)
                                    <tr>
                                        <td colspan="5" class="text-center">No News Found</td>
                                    </tr>
                                    @endif
                                    @foreach($news as $newss)
                                    <tr> 
                                        <td>
                                            {{$newss->post_title}}
                                        </td>
                                        <td>
                                            {{date('d M Y',strtotime($newss->created_at))}}
                                        </td>
                                        <td>
                                            {{$newss->post_status}}
                                        </td>
                                         
                                        <td>
                                            {{intval(get_field('views','news', $newss->id, 0))}}
                                        </td>
                                        <td class="text-end">
                                        <a href="/{{$newss->post_slug}}" class="btn btn-sm btn-neutral" target="_blank"><i class="bi bi-eye"></i></a>
                                            <a href="{{route('admin.news.edit',['id'=>$newss->id])}}" class="btn btn-sm btn-neutral"><i class="bi bi-pencil"></i></a>
                                            <a href="{{route('admin.news.delete',['id'=>$newss->id])}}" onclick="return confirm('Are you sure you want to delete ?')" class="btn btn-sm btn-neutral"> <i class="bi bi-trash"></i></a>
                                             
                                        </td>
                                    </tr>
                                    @endforeach
                              

                                    
                                </tbody>
                            </table>

                </div>
               

                <div class="card-footer border-0 py-5">
                    {{-- pagination --}}


                    
                    <span class="text-muted text-sm">
                        {{ $news->firstItem()}}-{{ $news->lastItem() }} of {{ $news->total() }} results
                     </span>
                     @if($news->total()>0)
                        <span class="text-muted text-sm">page {{$news->currentPage()}} of {{$news->lastPage()}}</span>
                     @endif
                     @if ($news->total() > 1)
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item {{ $news->currentPage() == 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $news->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                @for ($i = 1; $i <= $news->lastPage(); $i++)
                                    <li class="page-item {{ $news->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $news->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ $news->currentPage() == $news->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $news->nextPageUrl() }}">Next</a>
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