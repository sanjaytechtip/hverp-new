@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit Staff')
@section('pagecontent')
<div class="page">
	<?php //pr($editArticle); die; ?>
	<div class="page-content container-fluid">
		<div class="row justify-content-center">
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div><br />
			@endif
			<div class="col-md-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('stafflist') }}">Staff List</a></li>
					<li class="breadcrumb-item"> Edit Staff</li>
				</ol>
				<div class="card">					
					<div class="card-body_rename card-body">
						<form name="staff_form" id="staff_form" method="POST" action="{{ route('editstaffdata', $editStaff['_id']) }}" id="emp_form" class="form-horizontal fv-form fv-form-bootstrap4">
							@csrf
							<input type="hidden" name="fms_id" value="">
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Name") }}<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									<input autocomplete="off" type="text" class="form-control date_with_time" name="name" value="{{ $editStaff['name'] }}" readonly>
									@if ($errors->has('name'))
										<p class="text-danger">{{ $errors->first('name') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Father's Name") }}<span class="required" style="color: #e3342f;">*</span></label>	
								<div class="col-md-9">
									<input autocomplete="off" type="text" class="form-control date_with_time" name="father_name" value="{{ $editStaff['father_name'] }}">
									@if ($errors->has('father_name'))
										<p class="text-danger">{{ $errors->first('father_name') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Mobile") }}<span class="required" style="color: #e3342f;">*</span></label>	
								<div class="col-md-9">
									<input autocomplete="off" type="number" class="form-control date_with_time" name="mobile" value="{{ $editStaff['mobile'] }}">
									@if ($errors->has('mobile'))
										<p class="text-danger">{{ $errors->first('mobile') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Email") }}</label>
								<div class="col-md-9">
									<input autocomplete="off" type="email" class="form-control date_with_time" name="email" value="{{ $editStaff['email'] }}">
									@if ($errors->has('email'))
										<p class="text-danger">{{ $errors->first('email') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Phone No") }}</label>	
								<div class="col-md-9">
									<input autocomplete="off" type="number" class="form-control date_with_time" name="phone_no" value="{{ $editStaff['phone_no'] }}">
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Correspond Address") }}<span class="required" style="color: #e3342f;">*</span></label>	
								<div class="col-md-9">
									<textarea class="form-control date_with_time" name="address"  autocomplete="off">{{ $editStaff['address'] }}</textarea>
									@if ($errors->has('address'))
										<p class="text-danger">{{ $errors->first('address') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Permanent Address") }}</label>
								<div class="col-md-9">
									<textarea class="form-control date_with_time" name="per_address"  autocomplete="off">{{ $editStaff['per_address'] }}</textarea>
									@if ($errors->has('per_address'))
										<p class="text-danger">{{ $errors->first('per_address') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("DOB") }}<span class="required" style="color: #e3342f;">*</span></label>	
								<div class="col-md-9">
									<input autocomplete="off" type="text" id="dob" class="form-control date_with_time" name="dob" value="{{ $editStaff['dob'] }}" data-plugin="datepicker" data-multidate="true">
									@if ($errors->has('dob'))
										<p class="text-danger">{{ $errors->first('dob') }}</p>
									@endif
								</div>
							</div>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Designation") }}</label>	
								<div class="col-md-9">
									<select class="form-control date_with_time" multiple="multiple" data-plugin="select2" name="designation[]" id="designation">
										@foreach(getOptionArray('designation') as $key=>$val)
                                            <option {{ in_array(str_replace(' ','_',strtolower($val)),$editStaff['designation']) ? 'selected' : '' }} value="{{ str_replace(' ','_',strtolower($val)) }}">{{ $val }}</option>
										@endforeach
									</select>
									@if ($errors->has('designation'))
										<p class="text-danger">{{ $errors->first('designation') }}</p>
									@endif
								</div>
							</div>
                            <?php $branch = $editStaff['branch'];?>
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Branch") }}</label>
								<div class="col-md-9">
									<select class="form-control date_with_time" data-plugin="select2" name="branch">
										@foreach(getOptionArray('branch') as $key=>$val )
											<option value="{{ $key }}" {{ $branch==$key ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
									@if ($errors->has('branch'))
										<p class="text-danger">{{ $errors->first('branch') }}</p>
									@endif
								</div>
							</div>  
							
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">{{ __("Login as User Role") }}</label>	
								<div class="col-md-9">
								<div class="checkbox-custom checkbox-inline checkbox-primary float-left">
							  <input class="form-check-input" type="checkbox" @if($editStaff['user_role_assigned']==1) checked @endif value="1" name="user_role_assigned" id="inputCheckbox">
							  <label for="inputCheckbox">&nbsp;</label>
							</div>
								</div>
							</div>
							
							<div class="form-group row form-material user-role" style=" @if($editStaff['user_role_assigned']==1) '' @else display:none; @endif ">
								<label class="col-md-3 form-control-label">{{ __("Role") }}</label>
								<div class="col-md-9">
							  <select class="form-control" id="user_role" name="user_role">
								<option value="" selected="selected">Please Select Role</option>
								@foreach(user_role() as $key =>$row)
								<option @if($editStaff['user_role']==$key) selected @endif value="{{ $key }}" >{{ $row }}</option>
								@endforeach						
							  </select>
							</div>
							</div>
							
							<div class="form-group row form-material">
								<label class="col-md-3 form-control-label">FMS Access<span class="required" style="color: #e3342f;">*</span></label>
								<div class="col-md-9">
									<select class="form-control" multiple="multiple" data-plugin="select2" id="user_role" name="access_fms_id[]" required="" placeholder="Assign FMS">
										
										@foreach(all_fms_list() as $fms)
										<?php 
											$currentId = in_array($fms['_id'], $editStaff['access_fms_id'])?'selected="selected"':'';
											if( $fms['_id']!='5d0cd734d0490449dd7eb122' &&  $fms['_id']!='5d2c0a0ad0490410d22e4412') {
										?>
										<option value="{{ $fms['_id']}}" {{ $currentId }}>{{ $fms['fms_name']}}</option>
										<?php } ?>
										@endforeach	
									
									</select>
								</div>
							</div>
							
							<div class="form-group row form-material user-password" style=" @if($editStaff['user_role_assigned']==1) '' @else display:none; @endif ">
								<label class="col-md-3 form-control-label">{{ __("Password") }}</label>
							  <div class="col-md-9">
							  <input type="text" class="form-control" id="org_password" value="{{ $editStaff['org_password'] }}" name="org_password" placeholder="Min length 6">
							</div>
							</div>
							
							<div class="form-group row form-material user-change-password" style=" @if($editStaff['user_role_assigned']==1) '' @else display:none; @endif ">
								<label class="col-md-3 form-control-label">{{ __("Change Password") }}</label>
							  <div class="col-md-9">
							  <input type="password" class="form-control" id="password" value="{{ $editStaff['org_password'] }}" name="password" placeholder="Min length 6">
							  <input type="hidden" name="hidden_passowrd" value="{{ $editStaff['org_password'] }}" />
							</div>
							</div>
							
							<div class="text-right">
								<label for="search">&nbsp;</label>
								<button type="submit" id="validateStaff" class="btn btn-primary waves-effect waves-classic">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>  
</div>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script>
    $(document).ready(function () {
    $("#dob").datepicker({
    autoclose: true,
    todayHighlight: true
    });
    });
    </script> 
	<script>
    $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
                //alert("Checkbox is checked.");
				$('.user-role').show();
				$('.user-password').show();
				$('.user-change-password').show();
				$('#user_role').attr("required","");
				$('#org_password').attr("required","");
				$('#password').attr("required","");
            }
            else if($(this).prop("checked") == false){
                $('.user-role').hide();
				$('.user-password').hide();
				$('.user-change-password').hide();
				$('#user_role').removeAttr("required","");
				$('#org_password').removeAttr("required","");
				$('#password').removeAttr("required","");
            }
        });
    });
</script>

@endsection