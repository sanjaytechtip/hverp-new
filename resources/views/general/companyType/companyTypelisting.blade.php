@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Dropdown List')
@section('pagecontent')
<div class="page dropdown-box">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
          <header class="panel-heading pull-right">
            <h1 class="panel-title text-center">{{ __('Company Type List') }}</h1>
          </header>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-15">
                  <a class="btn btn-primary" href="{{ route('companyTypecreate') }}">
                    <i class="icon md-plus" aria-hidden="true"></i> Create New Company Type List
                  </a>
                </div>
              </div>
            </div>
            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="exampleAddRow">
              <thead>
						<tr>
						  <th>Sr. No.</th>
						  <th>Company Type</th>
						  <th>Created at</th>
						  <th>Actions</th>
						</tr>
              </thead>
               <tbody>	
                <?php $sr=1; ?>			   
				@foreach($companyTypelists as $companyTypelist)
				<?php //echo "<pre>"; print_r($dropdownlist); die; ?>
				<tr class="gradeA">
			        <td>{{ $sr }}</td>
				   <td>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#list_{{ $companyTypelist['_id'] }}" title="Click to see list details">{{ $companyTypelist['company_name'] }}</a>
					
						<div class="modal fade modal-info show dropdown-popup" id="list_{{ $companyTypelist['_id'] }}" aria-labelledby="exampleModalInfo" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title">{{ $companyTypelist['company_name'] }}</h4>
								  </div>
								  <div class="modal-body">
									{!! getcompanyTypeListingById($companyTypelist['_id']) !!}
								  </div>
								<!--<div class="modal-footer">
									<button type="button" class="btn btn-default btn-pure waves-effect waves-classic" data-dismiss="modal">Close</button>
								  </div> -->
								</div>
							</div>
						</div>
						
					</td>
					
					
					<td>{{ DateFormate($companyTypelist['created_at']) }}</td>
					<td class="actions">
						<a href="{{ route('companyTypeedit', $companyTypelist['_id']) }}" class="btn btn-success" ><i class="icon md-edit" aria-hidden="true"></i> Edit</a>
						  
						<a onclick="return confirm('Are you sure you want to delete?');" href="{{ route('companyTypedelete', $companyTypelist['_id']) }}" class="btn btn-danger waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> Delete</a> 
					</td>
				</tr>
				<?php $sr++; ?>
				@endforeach
              </tbody>
            </table>
          </div>
        </div>
        <!-- End Panel Table Add Row -->
      </div>
    </div>
@endsection