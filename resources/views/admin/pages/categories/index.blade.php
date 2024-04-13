@extends('layouts.admin')
@section('content')



<div class="h-screen flex-grow-1 overflow-y-lg-auto">
        
    <main class="py-6 bg-surface-secondary">
        <div class="container-fluid">
            <!-- Card stats -->
            <?php 
            if(isset($_SESSION['success'])){
                ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $_SESSION['success'];?>
                </div>
                <?php
                unset($_SESSION['success']);
            }
            ?>
            <div class="card shadow border-0 mb-7">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">All <?php echo $name;?></h5>
                        </div>
                        <div class="col text-end">
                            <a href="/dashboard/<?php echo $type;?>/add" class="btn btn-sm btn-primary">Add New</a>
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
                                    <?php 
                                    foreach ($categories as $category) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $category->name;?>
                                            </td>
                                            <td class="text-end">
                                                <a href="/dashboard/<?php echo $type;?>/edit?id=<?php echo $category->ID;?>" class
                                                ="btn btn-sm btn-neutral"><i class="bi bi-pencil"></i></a>
                                                <a href="/dashboard/<?php echo $type;?>/delete?id=<?php echo $category->ID;?>" onclick="return confirm('Are you sure you want to logout ?')" class="btn btn-sm btn-neutral"> <i class="bi bi-trash"></i></a>
                                            </td>
                                        </tr>

                                        <?php
                                    }
                                    ?>
                                    
                                </tbody>
                            </table>

                </div>
               

                <div class="card-footer border-0 py-5">
                    
                    <span class="text-muted text-sm"><?php 
                    echo 'Showing '.count($categories).' items out of '.$total_cats.' results found';
                    ?></span>
                    <?php 
                    if($total_pages>1){
                        ?>
                        <nav aria-label="Page navigation example">
                      <ul class="pagination justify-content-end ">
                            <li class="page-item <?php echo $page==1?'disabled':'';?>">
                                <a class="page-link" href="/dashboard/<?php echo $type;?>?page=<?php echo $page-1;?>" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <?php 
                            for($i=1;$i<=$total_pages;$i++){
                                ?>
                                <li class="page-item <?php echo $page==$i?'active':'';?>">
                                    <a class="page-link" href="/dashboard/<?php echo $type;?>?page=<?php echo $i;?>"><?php echo $i;?></a>
                                </li>
                                <?php
                            }
                            ?>
                            <li class="page-item <?php echo $page==$total_pages?'disabled':'';?>">
                                <a class="page-link" href="/dashboard/<?php echo $type;?>?page=<?php echo $page+1;?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                        <?php
                    }
                    ?>

                     
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