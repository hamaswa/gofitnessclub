@extends('layouts.app')

@section('title', 'Restaurent')

@section('content')
   
@endsection

@section('modal')
<div class="modal fade"  id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title"></h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <div class="table-responsive">
                <table class="table">
                   <thead id=myModalHead>
                     
                   </thead>
                   <tbody id="myModalBody">
                     
                   </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
 </div>
@endsection