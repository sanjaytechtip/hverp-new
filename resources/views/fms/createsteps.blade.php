@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'FMS Steps')
@section('pagecontent')
<style>
.small-text{
	font-size:10px;
}
</style>
<div class="page">
      <div class="page-content container-fluid">
		<h2>{{ __('Create Steps') }}</h2>
		<div class="row justify-content-center">
					@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
						<p><a href="{{route('createtask')}}?fms_id={{ \Session::get('fms_id') }}"> Click Here</a> for Create Task</p>
					  </div><br />
					@endif
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('storesteps') }}" id="storesteps_form" onsubmit="return storestepsformValidate();">
                        @csrf
						<input type="hidden" name="fms_id" value="{{ $fms_id }}">
						<div id="formsteps" class="form-group">
							<div class="form-group row">
							   <div class="col-md-1">Sr No</div>
							   <div class="col-md-2">What</div>
							   <div class="col-md-2">Who</div>
							   <div class="col-md-2">When</div>
							   <div class="col-md-2">Actions</div>
							   <div class="col-md-1">Stage</div>
							</div>
							
							<div class="steps_fields">
								
								<div class="form-group row" id="step_row_1">
								  <div class="col-md-1">Step#1</div>
									<div class="col-md-2">
										<input type="text" class="form-control fms_what" name="row[1][fms_what]" value="">
									</div>
								   
									<div class="col-md-2">
										<input type="text" class="form-control fms_who" name="row[1][fms_who]" value="">
									</div>
								   
									<div class="col-md-2">
										<select name="row[1][fms_when_type]" onchange="getWhenInput(this.id);" id="when_1" class="form-control fms_when_type">
											<option value="">--Select--</option>
											<option value="1">Link with Task</option>
											<option value="12">Link with Task with Dropdown</option>
											<option value="2">Custom Date</option>
											<option value="15">Calendar</option>
											<option value="3">Task Status</option>
											<option value="4">Notes</option>
											<option value="13">Dropdown Field</option>
											<option value="5">None</option>
											<option value="14">Blank Planned/Actual</option>
											<option value="7">Builtin Option</option>
											<option value="8">Custom Fields</option>
											<option value="9">Before ETD</option>
											<option value="10">After ETD</option>
											<option value="11">Date with Dropdown</option>
											<option value="16">QTY Planned/Actual with Variation</option>
											<option value="17">Invoice Details</option>
										<?php /* ?>
											upto 16 complete
										<?php */?>
										</select>
										 
									</div>
								   
									<div class="col-md-2">
										<div id="row_1"></div>
										
									</div>
									
									<div class="col-md-2">
										<input type="text" class="form-control fms_stage" name="row[1][fms_stage][stage_text]" value="" placeholder="Stage Text">
									</div>
								   
									<div class="col-md-1">
										<a href="javascript:void(0)" class="btn btn-success btn_add_1" id="1" onclick="addField(this.id)">+</a>
									</div>
								</div>
								   
							</div>
						</div>
						
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
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
function onchangeStep(id)
{
	var Newid = id.split('_');
	//alert(Newid[1]);
	$('#input_'+id).remove();
	$('#'+id).after('<span id="input_'+id+'"><br><input type="text" class="form-control fms_when_val" name="row['+Newid[1]+'][fms_when_val]" value=""></span>');
}

// link_with_task
function getWhenInput(id)
{
	var selectVal='';
	selectVal =$('#'+id).val();
	var input_id = id.split('_');
	input_id = parseInt(input_id[1]);
	$("#row_"+input_id).html('');
	var inputField='';
    //alert(selectVal+'-'+input_id);
	if(input_id==1)
	{
		//  <option value="12">Link with Task with Dropdown</option>
		if(selectVal==1 ) //link_with_task 
		{
			var inputHtml2='';
			inputHtml2='{!! getDateFieldTaskByFmsId($fms_id) !!}';
			inputField=inputHtml2.replace(/r_num/g,input_id);
			inputField=inputField+'<div id="after_task_time_'+input_id+'"></div>';
			
		}else if(selectVal==12 )
		{
			var inputHtml2='';
			inputHtml2='{!! getDateFieldTaskByFmsId($fms_id) !!}';
			inputField=inputHtml2.replace(/r_num/g,input_id);
			inputField+='<div id="after_task_time_'+input_id+'"></div>';
			inputField= inputField+'<input type="text" name="row['+input_id+'][dropdown]" value="" class="form-control fms_when"><p class="small-text">Enter values with comma separated</p>';
			
		}else if(selectVal==2 )  //custom_date
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="custom_date" id="input_'+input_id+'" class="form-control" readonly>';
		}else if(selectVal==15 )  //calendar
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="calendar" id="input_'+input_id+'" class="form-control" readonly>';
		}else if(selectVal==4) //Notes
		{
			/* inputField = '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="notes">Notes</option></selcet>'; */
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="notes" id="input_'+input_id+'" class="form-control" readonly>';
			
		}else if(selectVal==3) //Inquiry Status
		{
			/* inputField = '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="notes">Notes</option></selcet>'; */
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="task_status" id="input_'+input_id+'" class="form-control" readonly>';
			
		}else if(selectVal==13) //dropdown field
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="" id="input_'+input_id+'" class="form-control"><p class="small-text">Enter values with comma separated</p>';
			
		}else if(selectVal==5) //none
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="none" id="input_'+input_id+'" class="form-control" readonly>';
		}else if(selectVal==14) // planned/Actual blank
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="pl_ac_none" id="input_'+input_id+'" class="form-control" readonly>';
		}else if(selectVal==7) //BUILTOPTION
		{
			var dropdownHtml = '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="">--Select--</option>';
				dropdownHtml += '{!! $builtOptionList !!}';
				dropdownHtml += '</select>';
			inputField = dropdownHtml;
		}else if(selectVal==8) //CUSTOM FIELDS
		{
			var dropdownHtml = '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="">--Select--</option>';
				dropdownHtml += '<option value="text_field">Text Field</option>';
				dropdownHtml += '<option value="stock_status">Stock Status</option>';
				dropdownHtml += '<option value="payment_approval">Payment Approval</option>';
				dropdownHtml += '<option value="order_status">Order Status</option>';
				dropdownHtml += '</select>';
			inputField = dropdownHtml;
		}else if(selectVal==9)
		{
			var inputHtml2='';
			inputHtml2='{!! getEtdDateFieldTaskByFmsId($fms_id) !!}';
			inputField=inputHtml2.replace(/r_num/g,input_id);
			inputField=inputField+'<div id="after_task_time_'+input_id+'"></div>';
		}else if(selectVal==10)
		{
			var inputHtml2='';
			inputHtml2='{!! getEtdDateFieldTaskByFmsId($fms_id) !!}';
			inputField=inputHtml2.replace(/r_num/g,input_id);
			inputField=inputField+'<div id="after_task_time_'+input_id+'"></div>';
		}else if(selectVal==16) //pl_ac_qty_variation 
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="pl_ac_qty_variation" id="input_'+input_id+'" class="form-control" readonly>';
		}else if(selectVal==17) // inv_details
		{
			inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="inv_details" id="input_'+input_id+'" class="form-control" readonly>';
		}
	}else{
		switch( parseInt(selectVal) )
		{
			case 1:
				var inputHtml3='', inputField3='';
				inputHtml3='{!! getDateFieldTaskByFmsId($fms_id) !!}';
				inputField3=inputHtml3.replace(/r_num/g,input_id);
				inputField = inputField3+'<div id="after_task_time_'+input_id+'"></div>';
				break;
			case 12:
				var inputHtml3='', inputField3='';
				inputHtml3='{!! getDateFieldTaskByFmsId($fms_id) !!}';
				inputField3=inputHtml3.replace(/r_num/g,input_id);
				inputField3 +='<div id="after_task_time_'+input_id+'"></div>';
				
				inputField= inputField3+'<input type="text" name="row['+input_id+'][dropdown]" value="" class="form-control fms_when"><p class="small-text">Enter values with comma separated</p>';
				
				break;
			case 2:
				inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="custom_date" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 15:
				inputField = '<input type="text" name="row['+input_id+'][fms_when]" value="calendar" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 3:
					inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="task_status" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 4:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="notes" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 13:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="" id="input_'+input_id+'" class="form-control fms_when"><p class="small-text">Enter values with comma separated</p>';
				break;
				
			case 5:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="none" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 14:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="pl_ac_none" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 7: //ORDER EMBROIDERY
				inputField += '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="">--Select--</option>';
				inputField += '{!! $builtOptionList !!}';
				inputField += '</select>';
				break;
			case 8: //CUSTOM FIELDS
				inputField += '<select name="row['+input_id+'][fms_when]" id="input_'+input_id+'" class="form-control" required><option value="">--Select--</option>';
				inputField += '<option value="text_field">Text Field</option>';
				inputField += '<option value="stock_status">Stock Status</option>';
				inputField += '<option value="payment_approval">Payment Approval</option>';
				inputField += '<option value="order_status">Order Status</option>';
				inputField += '</select>';
				break;
			case 9:
				var inputHtml4='', inputField4='';
				inputHtml4='{!! getEtdDateFieldTaskByFmsId($fms_id) !!}';
				inputField4=inputHtml4.replace(/r_num/g,input_id);
				inputField = inputField4+'<div id="after_task_time_'+input_id+'"></div>';
				break;
			case 10:
				var inputHtml4='', inputField4='';
				inputHtml4='{!! getEtdDateFieldTaskByFmsId($fms_id) !!}';
				inputField4=inputHtml4.replace(/r_num/g,input_id);
				inputField = inputField4+'<div id="after_task_time_'+input_id+'"></div>';
				break;
			case 11:
				
				inputField+='<select class="form-control fms_when" name="row['+input_id+'][fms_when]" id="input_'+input_id+'" onchange="onchangeStep(this.id)"> <option value=""> --Select-- </option>';
					for(var j=1; j<input_id; j++){
						inputField+='<option value="step'+j+'">After Step#'+j+'</option>';
					}
					inputField+='</select>';
					
				inputField+= '<input type="text" name="row['+input_id+'][dropdown]" value="" id="input_'+input_id+'" class="form-control fms_when"><p class="small-text">Enter values with comma separated</p>';
				break;
			case 16:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="pl_ac_qty_variation" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
			case 17:
				inputField+= '<input type="text" name="row['+input_id+'][fms_when]" value="inv_details" id="input_'+input_id+'" class="form-control fms_when" readonly>';
				break;
		}
	}
	
	$("#row_"+input_id).html(inputField);
}

// Add fields
function addField(id)
{
	var newid='', stepOption='', stepNum=0;
	newid=parseInt(id)+1;
	
	if(newid>1)
	{
		stepOption+='<option value="4">By Step</option>';
	}
	
	$('.btn_add_'+id).addClass('disabled');
	
	$('.steps_fields').append('<div class="form-group row" id="step_row_'+newid+'"><div class="col-md-1">Step#'+newid+'</div><div class="col-md-2"><input type="text" class="form-control fms_what" name="row['+newid+'][fms_what]" value=""></div><div class="col-md-2"><input type="text" class="form-control fms_who" name="row['+newid+'][fms_who]" value=""></div><div class="col-md-2"><select require name="row['+newid+'][fms_when_type]" onchange="getWhenInput(this.id);" id="when_'+newid+'" class="form-control fms_when_type"><option value="">-- Select --</option><option value="1">Link with Taks</option><option value="12">Link with Taks with Dropdown</option><option value="15">Calendar</option><option value="3">Task Status</option><option value="2">Custom Date</option><option value="3">After Occurance of a Prticular Step</option><option value="4">Notes</option><option value="13">Dropdown Field</option><option value="5">None</option><option value="14">Blank Planned/Actual</option><option value="7">Builtin Option</option><option value="8">Custom Fields</option><option value="9">Before ETD</option><option value="10">After ETD</option><option value="11">Date with Dropdown</option><option value="16">QTY Planned/Actual with Variation</option><option value="17">Invoice Details</option></select></div><div class="col-md-2"><div id="row_'+newid+'"></div></div><div class="col-md-2"><input type="text" class="form-control fms_stage" name="row['+newid+'][fms_stage][stage_text]" value="" placeholder="Stage Text"></div><div class="col-md-1"><a href="javascript:void(0)" class="btn btn-success btn_add_'+newid+'" id="'+newid+'" onclick="addField(this.id)">+</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger" id="r_'+newid+'" onclick="removeField(this.id)">-</a></div></div>');
	
}


// Remove fields
function removeField(id)
{
	var newid='';
	newid = id.split('_');
	$('#step_row_'+newid[1]).remove();
	$('.btn_add_'+(newid[1]-1)).removeClass('disabled');
}

function taskDateDropdown(thisVal, thisId)
{
	var newid='';
	newid = thisId.split('_');
	input_id = parseInt(newid[1]);
	
	var whenVal = $('#when_'+input_id).val();
	//alert(whenVal)
	if(whenVal==9)
	{
		var inputHtml = '<br><input type="number" name="row['+input_id+'][before_etd_time]" value="" class="form-control after_task_time" placeholder="Enter time in hour only">';
		
		$('#after_task_time_'+input_id).html('');		
		$('#after_task_time_'+input_id).html(inputHtml);
	}else if(whenVal==10)
	{
		var inputHtml = '<br><input type="number" name="row['+input_id+'][after_etd_time]" value="" class="form-control after_task_time" placeholder="Enter time in hour only">';
		
		$('#after_task_time_'+input_id).html('');		
		$('#after_task_time_'+input_id).html(inputHtml);
	}else{
		var inputHtml = '<br><input type="number" name="row['+input_id+'][after_task_time]" value="" class="form-control after_task_time" placeholder="Enter time in hour only">';
		$('#after_task_time_'+input_id).html('');		
		$('#after_task_time_'+input_id).html(inputHtml);
	}
}


function storestepsformValidate(){
	var fms_what = $('.fms_what');	
	var fms_who = $('.fms_who');
	var fms_when_type = $('.fms_when_type');
	var fms_when = $('.fms_when');
	var fms_when_val = $('.fms_when_val');
	var fms_stage = $('.fms_stage');	
	
	var after_task_time = $('.after_task_time');	
	var task_id_link = $('.task_id_link');
	
	var isValid = true;
	
	fms_what.each(function () {
			if($(this).val()=='')
			{				
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
			}
    });
	
	fms_who.each(function () {
		if($(this).val()=='')
		{
			$(this).addClass('border-danger');
			isValid = false;
		}else{
			$(this).removeClass('border-danger');
		}
    });
	
	fms_when_type.each(function () {
		if($(this).val()=='')
		{
			$(this).addClass('border-danger');
			isValid = false;
		}else{
			$(this).removeClass('border-danger');
		}
    });
	fms_when.each(function () {
		if($(this).val()=='')
		{
		
			$(this).addClass('border-danger');
			isValid = false;
		}else{
			$(this).removeClass('border-danger');
		}
    });
	
	fms_when_val.each(function () {
		if($(this).val()=='')
		{
			$(this).addClass('border-danger');
			isValid = false;
		}else{
			$(this).removeClass('border-danger');
		}
    });
	
	fms_stage.each(function () {
		if($(this).val()=='')
		{
			$(this).addClass('border-danger');
			isValid = false;
		}else{
			$(this).removeClass('border-danger');
		}
    });
	
	if(after_task_time.length>0){
		after_task_time.each(function () {
			if($(this).val()=='')
			{
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
			}
		});
	}
	
	if(task_id_link.length>0){
		task_id_link.each(function () {
			if($(this).val()=='')
			{
				$(this).addClass('border-danger');
				isValid = false;
			}else{
				$(this).removeClass('border-danger');
			}
		});
	}
	
	
	if(isValid){
		return true;
	}else{
		return false;
	}
}

</script>
@endsection