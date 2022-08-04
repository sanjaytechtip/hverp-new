@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add HSN')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">Create Hsn</h1>
        <ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('hsnlist') }}">Hsn List</a></li>
			<li class="breadcrumb-item"> Create Hsn</li>
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
						<form name="hsnform" id="hsnform" method="POST" action="{{ route('addhsndata') }}" id="emp_form" class="form-horizontal fv-form fv-form-bootstrap4">
							@csrf
							<input type="hidden" name="fms_id" value="">
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Hsn Code") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									<input autocomplete="off" type="text" class="form-control date_with_time" name="hsn_code" value="{{old('hsn_code')}}">
									@if ($errors->has('hsn_code'))
										<p class="text-danger">{{ $errors->first('hsn_code') }}</p>
									@endif
								</div>
							</div>	
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("GST Rate") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									<input autocomplete="off" type="number" class="form-control date_with_time" name="gstn" value="{{old('gstn')}}" step="0.01">
									@if ($errors->has('gstn'))
										<p class="text-danger">{{ $errors->first('gstn') }}</p>
									@endif
								</div>
							</div>	
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Description") }}</label>
								<div class="col-md-9">
									<textarea autocomplete="off" class="form-control date_with_time" name="desc">{{old('desc')}}</textarea>
									@if ($errors->has('desc'))
										<p class="text-danger">{{ $errors->first('desc') }}</p>
									@endif
								</div>
							</div>	
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Remark") }}</label>
								<div class="col-md-9">
									<textarea autocomplete="off" class="form-control date_with_time" name="remark">{{old('remark')}}</textarea>
								</div>
							</div>
							<div class="text-right">
								<label for="search">&nbsp;</label>
								<button type="submit" id="validateButton" class="btn btn-primary waves-effect waves-classic">Submit</button>
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
@endsection