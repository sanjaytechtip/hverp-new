@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Create FMS')

@section('pagecontent')
<div class="page creat-fms-box">
  <div class="page-content container-fluid">
    <h2>{{ __('Create FMS') }}</h2>
    <div class="row justify-content-center"> 
		@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
		<br />
		@endif
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('storefms') }}" onsubmit="return storefmsValidate();">
              @csrf
			<div id="formsteps" class="form-group col-lg-8 col-md-12">
                <div class="form-group row">

					<div class=" col-lg-4">
						<label>FMS Name</label>
						<input type="text" class="form-control" name="fms_name" value="{{ old('fms_name') }}" id="fms_name">
						@if($errors->has('fms_name'))
							<p class="text-danger">{{ $errors->first('fms_name') }}</p>
						@endif
					</div>
				  <div class=" col-lg-4">
                  <label>FMS Type</label>
					<select name="fms_type" class="form-control">
						<option value="">--Select--</option>
						@foreach(getFmsType() as $key=>$val)
							<option value="{{ $key }}">{{ $val }}</option>
						@endforeach
					</select>
                  </div>
                </div>
                <div class="form-group row fms-label-wrap">
                  <div class="col-lg-4">Task Name</div>
                  <div class="col-lg-4">Input Type</div>
                </div>
                <div class="steps_fields fms-fields-wrap">
                  <div class="form-group row row_number">
                    <div class="col-lg-4">
                    <label>Task Name</label>
                      <input type="text" class="form-control task_name" name="row[1][task_name]" value="">
                    </div>
                    <div class="col-lg-4">
                    <label>Input Type</label>
                      <input type="hidden" name="row[1][step]" value="1">
                      <select class="form-control input_type" onchange="checkField(this.value, this.id)" name="row[1][input_type]" id="typeid_1">
                        <option value=""> - - Select - - </option>
                        <option value="task_fields">Task Fields</option>
                        <option value="custom_options">Custom Options</option>
                      </select>
                    </div>
                    <div class="col-lg-4" id="r_1"></div>
                  </div>
                </div>
                <!-- Step field END  --> 
                
              </div>
              <div class="form-group row creat-fms-btn">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-success waves-effect waves-classic"> {{ __('Create') }} </button>
                  <a  class="btn btn-primary waves-effect waves-classic float-right" onclick="addField()" href="javascript:void(0)"> <i class="icon md-plus" aria-hidden="true"></i> Add New Row </a> </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

function appendCustomfield(row_id, fieldVal)
{
	 if(fieldVal=='task_fields')
	{ 
		return '<select class="form-control custom_type" name="row['+row_id+'][field_type]"><option value=""> - - Select - - </option><option value="ticket_number">Ticket Number</option><option value="department_name">Department</option><option value="customer_name">Customer</option><option value="subject_name">Subject</option><option value="date_time">Date & Time</option><option value="category_name">Category</option><option value="user_name">User</option></select>';
		
		 
	}
}

function checkField(fieldVal, id)
{
	var len_row_number = $( ".row_number" ).siblings()
	var row_id = len_row_number.length+1;
	var fieldVal = fieldVal;
	
	var r_id = id.split('_');
		r_id = r_id[1];
		//alert(r_id+'-'+fieldVal);
	var HtmlCont = appendCustomfield(r_id, fieldVal);
	var fieldVal = $('#r_'+r_id).html(HtmlCont);
}

// Add fields
function addField()
{
	var len_row_number = $( ".row_number" ).siblings();
	var row_id = len_row_number.length+1;
		row_id = row_id+1;
	var rowHtml  = 	'<div class="form-group row form-group-tab" id="curr_row_'+row_id+'">'+
									'<div class="col-lg-4">'+
										'<label>Task Name</label><input type="text" class="form-control task_name" name="row['+row_id+'][task_name]" value="">'+
									'</div>'+
									'<div class="col-lg-4">'+
									'<input type="hidden" name="row['+row_id+'][step]" value="'+row_id+'">'+
										'<label>Input Type</label><select class="form-control input_type" name="row['+row_id+'][input_type]" onchange="checkField(this.value, this.id)" id="typeid_'+row_id+'">'+
											'<option value=""> - - Select - - </option>'+
											'<option value="task_fields">Task Fields</option>'+
											'<option value="custom_options">Custom Options</option>'+
										'</select>'+
									'</div>'+
									'<div class="col-lg-4 row_class" id="r_'+row_id+'"></div>'+
									'<div class="remove_row"><a href="javascript:void(0)" id="remove_'+row_id+'" onclick="return deleteRow(this.id)"><i class="icon md-delete" aria-hidden="true"></i></a></div>'+
								'</div>';
	
	$('.steps_fields').append(rowHtml);
}

function deleteRow(thisId)
{
	//debugger;
	var id = thisId.split('_');
		id = id[1];
	var check = confirm('Are you sure to delete this row?');
	if(check)
	{
		$('#curr_row_'+id).remove();
	}
}

// Remove fields
function removeField(id)
{
	var newid='';
	newid = id.split('_');
	$('#step_row_'+newid[1]).remove();
	$('.btn_add_'+(newid[1]-1)).removeClass('disabled');
}

function storefmsValidate()
{
	 
	var fms_name = $('#fms_name');
	var task_name = $('.task_name');
	var input_type = $('.input_type');
	var custom_type = $('.custom_type');
	
	var isValid = true;
	if(fms_name.val()=='')
	{
		fms_name.addClass('border-danger');
		isValid = false;
	}else{
			fms_name.removeClass('border-danger');
		}
	
	
	task_name.each(function () {
			if($(this).val()=='')
			{
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
		}
    });
	
	input_type.each(function () {
			if($(this).val()=='')
			{
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
			}
    });
	
	custom_type.each(function () {
			if($(this).val()=='')
			{
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
			}
    });
	
	if(isValid){
		return true;
	}else{
		return false;
	}
	
}
</script> 
@endsection