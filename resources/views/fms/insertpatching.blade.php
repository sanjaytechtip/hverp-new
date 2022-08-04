@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Add Patching Details')

@section('pagecontent')
<div class="page creat-fms-box">
  <div class="page-content container-fluid">
    <h2>{{ __('Add Patching Details:') }} {{ getFmsNameById($fms_id) }}</h2>
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
            <form method="POST" action="{{ route('savepatching') }}" onsubmit="return insertPatchingValidate();">
              @csrf
			  <input type="hidden" name="fms_id" value="{{ $fms_id }}">
              <div id="formsteps" class="form-group col-lg-8 col-md-12">
                
                <div class="steps_fields">
                  <div class="form-group row row_number">
                    <div class="col-lg-4">
						<label>Patching Name</label>
                      <input type="text" class="form-control patching_name" name="patching[1][name]" value="">
                    </div>
					
                    <div class="col-lg-4">
						<label>Steps</label>
							<?php 
							$patch = str_replace('r_num','1',$patchingHtml);	
							
							echo $patch;
							?>
							
                    </div>
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
// Add fields patching[1][name]
function addField()
{
	debugger;
	var len_row_number = $( ".row_number" ).siblings();
	var row_id = len_row_number.length+1;
		row_id = row_id+1;
	var patching = '{!! $patchingHtml !!}';
	
	//var patching = ('{!! $patchingHtml !!}' + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
	
	var patchingMain = '';
		patchingMain = patching.replace(/r_num/g, row_id);
	
	var rowHtml  = 	'<div class="form-group row form-group-tab" id="curr_row_'+row_id+'">'+
									'<div class="col-lg-4">'+
										'<label>Patching Name</label><input type="text" class="form-control patching_name" name="patching['+row_id+'][name]" value="">'+
									'</div>'+
									'<div class="col-lg-4">'+patchingMain+
									'</div>'+
									'<div class="col-lg-4 row_class" id="r_'+row_id+'"></div>'+
									'<div class="remove_row"><a href="javascript:void(0)" id="remove_'+row_id+'" onclick="return deleteRow(this.id)"><i class="icon md-delete" aria-hidden="true"></i></a></div>'+
								'</div>'; 
/* 	var patchingHtml = '';
		patchingHtml='{!! $patchingHtml !!}';
		inputHtml+=patchingHtml.replace(/r_num/g,row_id);
	var rowHtml  = 	'<div class="form-group row form-group-tab" id="curr_row_'+row_id+'">'+
									'<div class="col-lg-4">'+
										'<label>Patching Name</label><input type="text" class="form-control task_name" name="row['+row_id+'][task_name]" value="">'+
									'</div>'+
									'<div class="col-lg-4">'+
									'<input type="hidden" name="row['+row_id+'][step]" value="'+row_id+'">'+
										'<label>Input Type</label><select class="form-control input_type" name="row['+row_id+'][input_type]"  id="typeid_'+row_id+'">'+
											'<option value=""> - - Select - - </option>'+
											'<option value="custom_fields">Custom Fields</option>'+
											'<option value="order_qty">Order Quantity</option>'+
											'<option value="custom_options">Custom Options</option>'+
											'<option value="builtin_options">Builtin Options</option>'+
										'</select>'+
									'</div>'+
									'<div class="col-lg-4 row_class" id="r_'+row_id+'"></div>'+
									'<div class="remove_row"><a href="javascript:void(0)" id="remove_'+row_id+'" onclick="return deleteRow(this.id)"><i class="icon md-delete" aria-hidden="true"></i></a></div>'+
								'</div>'; */
	
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



function insertPatchingValidate()
{
	 
	var patching_name = $('.patching_name');
	
	var isValid = true;
	
	patching_name.each(function () {
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