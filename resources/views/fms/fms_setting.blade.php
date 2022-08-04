@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'FMS Setting')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">FMS Setting</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active"><a href="{{route('fmslist')}}">FMS List</a></li>
          <li class="breadcrumb-item active">FMS Setting</li>
        </ol>
      </div>
	  <div class="page-content container-fluid">
				@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div>
					@endif
        <!-- Panel Full Example -->
        <div class="panel">
          <div class="panel-body">
			<?php 
				$fms_data = getFmsDetailsById($fms_id);
				//pr($fms_data);
			?>
            <form id="registration" name="registration" method="post" autocomplete="off" action="{{route('update_fms')}}" enctype="multipart/form-data" class="form-horizontal">
				@csrf
				<input type="hidden" name="fms_id" value="{{ $fms_id }}">
				<div class="form-group row form-material">
					<label class="col-md-3 form-control-label">FMS name<span class="required">*</span></label>
					<div class="col-md-9">
					  <input type="text" autocomplete="off" class="form-control" name="fms_name" value="{{ $fms_data['fms_name'] }}" placeholder="FMS Name"
						required="">
						@if ($errors->has('fms_name'))
							<span class="text-danger" role="alert">
								<strong>{{ $errors->first('fms_name') }}</strong>
							</span>
						@endif
					</div>
				</div>
				
				<div class="form-group row form-material">
					<label class="col-md-3 form-control-label">Room List<span class="required">*</span></label>
					<div class="col-md-9">
					  <select class="form-control" id="store_room_id" name="store_room_id" required="">
						<option value="">Please Select Room</option>
						@foreach(get_stock_room_list_api() as $key=>$val)
							<?php 
								if(isset($fms_data['store_room_id'])){
									$selectedVal = $val->id==$fms_data['store_room_id']?'selected':'';
								}else{
									$selectedVal ='';
								}
								
							?>
							<option value="{{ $val->id }}" {{ $selectedVal }}>{{ $val->name }}</option>
						@endforeach						
					  </select>
					</div>
				</div>
				 
				<div class="text-right">
					<button type="submit" class="btn btn-primary" id="validateButton1">Submit</button>
				</div>
            </form>
          </div>
        </div>
        <!-- End Panel Full Example -->
      </div>
    </div>
    <!-- End Page -->
	
@endsection

@section('custom_validation_script')

@endsection