@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Brand')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">Create Brand</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('brandlist') }}">Brand List</a></li>
			<li class="breadcrumb-item"> Create Brand</li>
        </ol> 
	</div>
	<div class="page-content container-fluid">
		<div class="row justify-content-center">
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div><br />
			@endif
			
			<div class="col-md-12">
				<div class="card">					
					<div class="card-body_rename card-body">
						<form name="brandform" id="brandform" method="POST" action="{{ route('addbranddata') }}" id="emp_form" class="form-horizontal fv-form fv-form-bootstrap4">
							@csrf
							<input type="hidden" name="fms_id" value="">
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Brand Name") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									<input autocomplete="off" type="text" class="form-control date_with_time" value="{{old('brand_name')}}" name="brand_name" value="">
									@if ($errors->has('brand_name'))
										<p class="text-danger">{{ $errors->first('brand_name') }}</p>
									@endif
								</div>
							</div>
							<?php /*<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Merchant Name") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									{!! getUserByDesignation('Marchant','marchant','---Select---',old('marchant')) !!}
									@if ($errors->has('marchant'))
										<p class="text-danger">{{ $errors->first('marchant') }}</p>
									@endif
								</div>
							</div>*/?>
							
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Agent/Sales Person") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									{!! getUserByDesignation('sales_agent','sales_agent','---Select---',old('sales_agent')) !!}
									@if ($errors->has('sales_agent'))
										<p class="text-danger">{{ $errors->first('sales_agent') }}</p>
									@endif
								</div>
							</div>
							
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Merchant Name") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									{!! getUserByDesignation('merchant','merchant','---Select---','') !!}
									@if ($errors->has('merchant'))
										<p class="text-danger">{{ $errors->first('merchant') }}</p>
									@endif
								</div>
							</div>
							
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Description") }}</label>
								<div class="col-md-9">
									<textarea class="form-control date_with_time" name="description" autocomplete="off" data-fv-field="description"></textarea>
								</div>
							</div>
							
							<div class="card-header">{{ __('Login Details') }}</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __('Login ID (Email ID)') }}</label>
								<div class="col-md-9">
									<input id="user_login_id" type="email" class="form-control" name="user_login_id" value="">
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __('Login Password') }}</label>
								<div class="col-md-9">
									<input id="org_password" type="text" class="form-control" name="org_password" value="">
								</div>
							</div>
							
							<div class="text-right">
								<label for="search">&nbsp;</label>
								<button type="submit" id="validateBrand" class="btn btn-primary waves-effect waves-classic">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>

@endsection
@section('custom_validation_script')
<script>

 </script> 
@endsection