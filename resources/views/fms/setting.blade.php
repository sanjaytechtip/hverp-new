@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Setting Page')

@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="page creat-fms-box">
      <div class="page-content container-fluid">
	  <h2>{{ __('Create Custom Dropdown List') }}</h2>
    <div class="row justify-content-center">
		
		@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div><br />
		@endif
        <div class="col-md-12">
            <div class="card">					
                <div class="card-body">
                    <form method="POST" action="{{ route('storedropdown') }}">
                        @csrf
						<input type="hidden" name="user_id" value="{{ getCurUserField() }}">
						<div id="formsteps" class="form-group col-lg-8 col-md-12">
								<div class="form-group row">
									<div class=" col-lg-8 col-md-12">
                                    <label for="dropdown_name">{{ __('Dropdown Name') }}</label>
										<input id="dropdown_name" type="text" class="form-control{{ $errors->has('dropdown_name') ? ' is-invalid' : '' }}" name="dropdown_name" value="{{ old('dropdown_name') }}" required>
										
										@if($errors->has('dropdown_name'))
											<span class="invalid-feedback" role="alert">
												<strong>{{ $errors->first('dropdown_name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group row">
									<div class=" col-lg-8 col-md-12">
                                    <label>Input Type</label>
										<select name="input_type" class="form-control" id="select_input">
											<option value="">--Select--</option>
											<option value="dropdown_list">Dropdown List</option>
										</select>
									</div>
								</div>
								
							
                            
                            
                 <div class="form-group row fms-label-wrap">
                  <div class="col-lg-4">Label Name</div>
                  <div class="col-lg-4">Label Id</div>
                </div>
                            
							
							
							<div class="steps_fields  fms-fields-wrap">
								<div class="form-group row" id="step_row_1">
								   <div class="col-lg-4">
                                   <label>Label Name</label>
									<input type="text" id="label_1" class="form-control" name="row[1][label_name]" value="" onblur="labelSlug(this.id)">
								   </div>
								   <div class="col-lg-4">
                                   <label>Label Id</label>
										<input type="text" id="label_1_id" class="form-control" name="row[1][label_id]" value="">
								   </div>
								   <div class="col-lg-4"><a href="javascript:void(0)" class="btn btn-success btn_add_1" id="1" onclick="addField(this.id)">+</a></div>
								</div>
							</div>
						</div>
						
                        <div class="form-group rowcreat-fms-btn">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
						
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  
</div>

								
<script>
// Add fields
function addField(id)
{
	var newid='';
	newid=parseInt(id)+1;
	$('.btn_add_'+id).addClass('disabled');
	
	$('.steps_fields').append('<div class="form-group row" id="step_row_'+newid+'"><div class="col-lg-4"><label>Label Name</label><input type="text" id="label_'+newid+'" class="form-control" name="row['+newid+'][label_name]" value="" onBlur="labelSlug(this.id)"></div><div class="col-lg-4"><label>Label Id</label><input type="text" id="label_'+newid+'_id" class="form-control" name="row['+newid+'][label_id]" value=""></div><div class="col-lg-4"><a href="javascript:void(0)" class="btn btn-success btn_add_'+newid+'" id="'+newid+'" onClick="addField(this.id)">+</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger" id="r_'+newid+'" onClick="removeField(this.id)">-</a></div></div>');
}

// Remove fields
function removeField(id)
{
	var newid='';
	newid = id.split('_');
	$('#step_row_'+newid[1]).remove();
	$('.btn_add_'+(newid[1]-1)).removeClass('disabled');
}

$(document).ready(function(){
	$('#select_input').on('change', function(){
		//alert($(this).val());
		var curr_val = $(this).val();
		if(curr_val=='custom_dropdown')
		{
			$('#custom_dropdown').show();
			$('#inbuild_dropdown').hide();
		}else if(curr_val=='inbuild_dropdown')
		{
			$('#inbuild_dropdown').show();
			$('#custom_dropdown').hide();
		}
	});
	
})

// to make label slug auto
function labelSlug(id)
{
	var inputVal = $('#'+id).val();
		inputVal = inputVal.toLowerCase();
		
	$('#'+id+'_id').val(inputVal.replace(' ', '_'));
}

</script>

@endsection