@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Dropdown List')
@section('pagecontent')
<div class="page dropdown-box">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
          <header class="panel-heading pull-right">
            <h1 class="panel-title text-center">{{ __('Dropdown List') }}</h1>
          </header>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-15">
                  <a class="btn btn-primary" href="{{ route('createdropdown') }}">
                    <i class="icon md-plus" aria-hidden="true"></i> Create New Dropdown List
                  </a>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="exampleAddRow">
              <thead>
						<tr>
						  <th>Sr. No</th>
						  <th>Dropdown Name</th>
						  <th>Type Dropdown</th>
						  <th>Created at</th>
						  <th>Actions</th>
						</tr>
              </thead>
              <tbody>				
				@foreach($dropdownlists as $dropdownlist)
				<?php //echo "<pre>"; print_r($dropdownlist); die; ?>
				<tr class="gradeA">
					<td>{{ $no++ }}</td>
					<td>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#list_{{$dropdownlist->_id}}" title="Click to see list details">{{$dropdownlist->dropdown_name}}</a>
					
						<div class="modal fade modal-info show dropdown-popup" id="list_{{$dropdownlist->_id}}" aria-labelledby="exampleModalInfo" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title">{{$dropdownlist->dropdown_name}}</h4>
								  </div>
								  <div class="modal-body">
									{!! getDropdownListById($dropdownlist->_id) !!}
								  </div>
								  <!--<div class="modal-footer">
									<button type="button" class="btn btn-default btn-pure waves-effect waves-classic" data-dismiss="modal">Close</button>
								  </div>-->
								</div>
							</div>
						</div>
						
					</td>
					
					<td>{{$dropdownlist->input_type}}</td>
					<td>{{date('Y-m-d', strtotime($dropdownlist->created_at))}}</td>
					<td class="actions">
						
						<a href="javascript:void(0)" class="btn btn-success"
						  data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit" aria-hidden="true"></i> Edit</a>
						  
						<form action="javascript:void(0)" method="post">
								@csrf
								<input name="_method" type="hidden" value="DELETE">
								<input name="fms_id" type="hidden" value="">
								<button data-original-title="Delete" class="btn btn-danger waves-effect waves-classic remove-row" type="submit" onclick="return confirm('Are you sure to delete this item?');"><i class="icon md-delete" aria-hidden="true"></i> Delete</button>
						</form>
					</td>
				  
				</tr>
				@endforeach
                  
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Panel Table Add Row -->
      </div>
    </div>
@endsection