@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View Staff')
@section('pagecontent')
<div class="page">
	<div class="page-content container-fluid">
		@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
		@endif
        <!-- Panel Full Example -->
		<?php //pr($viewStaff); die; ?>
        <div class="panel">
			<div class="panel-body">
				<form autocomplete="off" enctype="multipart/form-data" class="form-horizontal">
					@csrf
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Name:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewStaff['name'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Father's Name:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewStaff['father_name'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Mobile Number:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewStaff['mobile'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Email:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control" required="">{{ $viewStaff['email'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Phone No:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['phone_no'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Correspond Address:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['address'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Permanent Address:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['per_address'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">DOB:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['dob'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Designation:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ implode(',',$viewStaff['designation']) }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">FMS Access:</label>
						<div class="col-md-9">
									@foreach(all_fms_list() as $fms)
										<?php 
											echo in_array($fms['_id'], $viewStaff['access_fms_id'])?$fms['fms_name'].',':'';
										?>
									@endforeach
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Branch:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['branch'] }}</p>
						</div>
					</div>
					
					<div class="form-group row form-material">
						<label class="col-md-3 form-control-label">Created At:</label>
						<div class="col-md-9">
							<p autocomplete="off" class="form-control">{{ $viewStaff['created_at'] }}</p>
						</div>
					</div>
				</form>
			</div>
        </div><!-- End Panel Full Example -->
	</div>
</div><!-- End Page -->	
@endsection