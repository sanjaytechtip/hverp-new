@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Company Details')
@section('pagecontent')
<div class="page formbuilder">
	<div class="page-header">
		<h1 class="page-title">Update Company Details</h1>
	</div>
	<div class="page-content container-fluid">
		<div class="row justify-content-center"> 
			@if (\Session::has('success'))					
			<div class="alert alert-success">	
				<p>{{ \Session::get('success') }}</p>	
			</div>		
			@endif		
			<div class="col-md-12">		
				<div class="card">	
					<div class="card-body_rename card-body">
						<h2 class="form-box form-box-heading">Update Company Details</h2>
						<div class="custom-form-box">
							<form id="registration" name="registration" method="post" autocomplete="off" action="{{route('addcompanydetailsdata')}}" enctype="multipart/form-data" class="custom-form">
								@csrf
								<div class="form-box rows  line_repeat_1">
									<div class="form-box col2">
										<label>Company Name<span class="required">*</span></label>
										<input type="text" class="form-control" name="company_name" value="{{$company_detail->company_name}}" required>	
									</div>
									<div class="form-box col2">
										<label>PAN No<span class="required">*</span></label>
										<input type="text" class="form-control" name="pan_no" value="{{$company_detail->pan_no}}" required>	
									</div>
									<div class="form-box col2">
										<label>GSTIN No<span class="required">*</span></label>
										<input type="text" class="form-control" name="gstin_no" value="{{$company_detail->gstin_no}}" required>	
									</div>
									<div class="form-box col2">
										<label>Address<span class="required">*</span></label>
										<textarea name="address" class="form-control address" id="" placeholder="Address" required="" aria-required="true">{{$company_detail->address}}</textarea>
									</div>
									<div class="form-box col2">
										<label>Bank Details<span class="required">*</span></label>
										<textarea name="bank_details" class="form-control bank_details" id="" placeholder="Bank Details" required="" aria-required="true">{{$company_detail->bank_details}}</textarea>	
									</div>
									<div class="form-box col2">	
										<button type="submit" class="btn btn-primary waves-effect waves-classic" id="validateButton1">Update</button>
									</div>	
								</div>
							</form>	
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
@endsection
@section('custom_validation_script')
@endsection