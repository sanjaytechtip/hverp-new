@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'FMF Edit')

@section('pagecontent')
<div class="page">
      <div class="page-content container-fluid">
		<h2>{{ __('Create FMS') }}</h2>
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
                    <form method="POST" action="">
                        @csrf
                        <div class="form-group row">
                            <label for="fms_name" class="col-md-4 col-form-label text-md-right">{{ __('FMS Name') }}</label>

                            <div class="col-md-6">
                                <input id="fms_name" type="text" class="form-control{{ $errors->has('fms_name') ? ' is-invalid' : '' }}" name="fms_name" value="{{ $fmsdata->fms_name}}" required>

                                @if ($errors->has('fms_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('fms_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						<div id="formsteps" class="form-group">
							<div class="form-group row">
							   <div class="col-md-2">Sr No</div>
							   <div class="col-md-2">What</div>
							   <div class="col-md-2">How</div>
							   <div class="col-md-2">Who</div>
							   <div class="col-md-2">When</div>
							</div>
							
							<div class="steps_fields">
								
								<?php $sr=1;?>
								@foreach($steps_rows as $steps_row)
								<div class="form-group row" id="step_row_1">
								  <div class="col-md-2">Step#{{ $sr }}</div>
								   <div class="col-md-2">
										<input type="text" class="form-control" name="row[{{ $sr }}][fms_what]" value="{{$steps_row->fms_what}}">
								   </div>
								   
								   <div class="col-md-2">
									<input type="text" class="form-control" name="row[{{ $sr }}][fms_how]" value="{{$steps_row->fms_how}}">
								   </div>
								   
								   <div class="col-md-2">
										<select class="form-control" name="row[{{ $sr }}][fms_who]">
											<option value="">--Select--</option>
											<option value="shoyeb">Shoyeb</option>
											<option value="hemant">Hemant</option>
										</select>
								   </div>
								   
								   <div class="col-md-2">
										<input type="date" class="form-control datepicker" name="row[{{ $sr }}][fms_when]" value="{{$steps_row->fms_when}}" autocomplete="off" id="date_{{ $sr }}">
								   </div>
								   
								   <div class="col-md-2">
										<a href="javascript:void(0)" class="btn btn-success btn_add_{{ $sr }}" id="{{ $sr }}" onclick="addField(this.id)">+</a>
								   </div>
								</div>
								<?php $sr++;?>
								@endforeach
								
								
								
								
								   
							</div>
						</div>
						
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
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
// show steps
function showFmsSteps(){
		$('#formsteps').show();
}

// Add fields
function addField(id)
{
	var newid='';
	newid=parseInt(id)+1;
	$('.btn_add_'+id).addClass('disabled');

	$('.steps_fields').append('<div class="form-group row" id="step_row_'+newid+'"><div class="col-md-2">Step#'+newid+'</div><div class="col-md-2"><input type="text" class="form-control" name="row['+newid+'][fms_what]" value=""></div><div class="col-md-2"><input type="text" class="form-control" name="row['+newid+'][fms_how]" value=""></div><div class="col-md-2"><select class="form-control" name="row['+newid+'][fms_who]"><option value="">--Select--</option><option value="shoyeb">Shoyeb</option><option value="hemant">Hemant</option></select></div><div class="col-md-2"><input type="date" class="form-control datepicker" id="date_'+newid+'" name="row['+newid+'][fms_when]" value=""></div><div class="col-md-2"><a href="javascript:void(0)" class="btn btn-success btn_add_'+newid+'" id="'+newid+'" onclick="addField(this.id)">+</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="btn btn-danger" id="r_'+newid+'" onclick="removeField(this.id)">-</a></div></div>');
}

// Remove fields
function removeField(id)
{
	var newid='';
	newid = id.split('_');
	$('#step_row_'+newid[1]).remove();
	$('.btn_add_'+(newid[1]-1)).removeClass('disabled');
}

/* $(document).ready(function(){
$('body').on('focus',".datepicker", function(){
        $(this).datepicker({dateFormat:'d-m-yy'});
    }); 
}) */
</script>

@endsection