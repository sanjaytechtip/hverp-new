@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Create Group of Company')
@section('pagecontent')
<div class="page formbuilder">
	<div class="page-header">
		<h1 class="page-title">Add Group of Company</h1>
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
						<h2 class="form-box form-box-heading">Add Group of Company</h2>
						<div class="custom-form-box">
							<form id="registration" name="registration" method="post" autocomplete="off" action="{{route('addcompanydata')}}" enctype="multipart/form-data" class="custom-form">
								@csrf
								<div class="form-box rows  line_repeat_1">
									<div class="form-box col2">
										<label>Group Name<span class="required">*</span></label>
										<input type="text" class="form-control" name="group_name" value="" required>	
									</div>
									<div class="form-box col2">
										<label>Add Company/Customer<span class="required">*</span></label>
										<!--input type="text" class="form-control" id="inputCheckbox" name="company_name" value="" required> -->					<select class="form-control" multiple="multiple" data-plugin="select2" id="company_name" name="company_name[]" required="" placeholder="Assign FMS">					</select>					
									</div>
									<div class="form-box col2">	
										<button type="submit" class="btn btn-primary waves-effect waves-classic" id="validateButton1">Submit</button>
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
@section('custom_validation_script')<script>	$(function () { 		var custArr = '<?php print_r(getAllBuyer()); ?>';		custArr = JSON.parse(custArr);		var custData = $.map(custArr, function (value, key) { return { value: value, data: key }; });		var custHtml='';		for(var i=0; i<custData.length; i++){			custHtml+='<option value="'+custData[i].data+'">'+custData[i].value+'</option>';		}		$('#company_name').html(custHtml);	});</script>
@endsection