@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit Brand')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">Edit Brand</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('brandlist') }}">Brand List</a></li>
			<li class="breadcrumb-item"> Edit Brand</li>
        </ol>
	</div>
	<?php //pr($editBrand); die; ?>
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
						<form method="POST" name="brandform" id="brandform" action="{{ route('editbranddata', $editBrand['_id']) }}" id="emp_form" class="form-horizontal fv-form fv-form-bootstrap4">
							@csrf
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Brand Name") }}</label>
								<div class="col-lg-9">
									<input autocomplete="off" type="text" class="form-control date_with_time" name="brand_name" value="{{ $editBrand['brand_name'] }}">
									@if ($errors->has('brand_name'))
										<p class="text-danger">{{ $errors->first('brand_name') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Agent/Sales Person") }}</label>
								<div class="col-md-9">
									{!! getUserByDesignation('sales_agent','sales_agent','---Select---',$editBrand['sales_agent']) !!}
									@if ($errors->has('sales_agent'))
										<p class="text-danger">{{ $errors->first('sales_agent') }}</p>
									@endif
								</div>
							</div>
							
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Merchant Name") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									{!! getUserByDesignation('merchant','merchant','---Select---',@$editBrand['merchant']) !!}
									@if ($errors->has('merchant'))
										<p class="text-danger">{{ $errors->first('merchant') }}</p>
									@endif
								</div>
							</div>
							
							<div class="form-group row  form-material">
								<label class="col-md-3 form-control-label">{{ __("Description") }}</label>
								<div class="col-md-9">
									<textarea class="form-control date_with_time" name="description" autocomplete="off" data-fv-field="description">{{ $editBrand['description'] }}</textarea>
								</div>
							</div>
							
							<div class="card-header">{{ __('Login Details') }}</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __('Login ID (Email ID)') }}</label>
								<div class="col-md-9">
									<input id="user_login_id" type="email" class="form-control" name="user_login_id" value="{{ @$editBrand['user_login_id'] }}">
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __('Login Password') }}</label>
								<div class="col-md-9">
									<input id="org_password" type="text" class="form-control" name="org_password" value="{{ @$editBrand['org_password'] }}">
								</div>
							</div>
					
					
							<div class="text-right">
								<button type="submit" id="validateArticle" class="btn btn-primary waves-effect waves-classic">Update</button>
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>
@endsection