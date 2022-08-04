@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'User Replace')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">User Replace</h1> 
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
						<form name="staff_form" id="staff_form" method="POST" action="{{ route('user_update') }}" id="emp_form" class="form-horizontal fv-form fv-form-bootstrap4">
							@csrf
							<div class="form-group row form-material">
								<label class="col-md-2 form-control-label">{{ __("Old Name") }}</label>
								<div class="col-md-4">
									<input autocomplete="off" type="text" class="form-control date_with_time" required name="old_name" value="">
									@if ($errors->has('old_name'))
										<p class="text-danger">{{ $errors->first('old_name') }}</p>
									@endif
								</div>
								<label class="col-md-2 form-control-label">{{ __("Old Email") }}</label>
								<div class="col-md-4">
									<input autocomplete="off" type="email" required class="form-control date_with_time" name="old_email" value="">
									@if ($errors->has('old_email'))
										<p class="text-danger">{{ $errors->first('old_email') }}</p>
									@endif
								</div>
							</div>
							
							<div class="form-group row form-material">
							<label class="col-md-2 form-control-label">{{ __("New Name") }}</label>
								<div class="col-md-4">
									<input autocomplete="off" type="text" required class="form-control date_with_time" name="new_name" value="">
									@if ($errors->has('new_name'))
										<p class="text-danger">{{ $errors->first('new_name') }}</p>
									@endif
								</div>
								<label class="col-md-2 form-control-label">{{ __("New Email") }}</label>
								<div class="col-md-4">
									<input autocomplete="off" type="email" required class="form-control date_with_time" name="new_email" value="">
									@if ($errors->has('new_email'))
										<p class="text-danger">{{ $errors->first('new_email') }}</p>
									@endif
								</div>
							</div>
							<div class="col-md-6 text-right">
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
				$('#user_role').attr("required","");
				$('#org_password').attr("required","");
            }
            else if($(this).prop("checked") == false){
                $('.user-role').hide();
				$('.user-password').hide();
				$('#user_role').removeAttr("required","");
				$('#org_password').removeAttr("required","");
            }
        });
    });
</script>
@endsection