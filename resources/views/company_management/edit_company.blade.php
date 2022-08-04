@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit Group of Company')
@section('pagecontent')
<div class="page formbuilder">
	<div class="page-header">
		<h1 class="page-title">Edit Group of Company</h1>
		<ol class="breadcrumb">

          <li class="breadcrumb-item"> <a href="{{route('dashboard')}}">Home</a> </li>

          <li class="breadcrumb-item"><a href="{{route('companylist')}}">Group of Company Management</a></li>

        </ol>
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
						<form id="registration" name="registration" method="post" autocomplete="off" action="{{route('editcompanydata', $editElement['id'])}}" enctype="multipart/form-data" class="custom-form">
							@csrf	
							<div class="form-box rows  line_repeat_1">
								<div class="form-box col2">
									<label>Group Name<span class="required">*</span></label>
									<input type="text" autocomplete="off" class="form-control" name="group_name" value="{{ $editElement['group_name'] }}" required="">
								</div>
								<div class="form-box col2">
									<?php /*?><input type="text" autocomplete="off" class="form-control" name="company_name" value="{{ $editElement['company_name'] }}" required=""><?php */?>	
									
									<label>Add Company/Customer<span class="required">*</span></label>
									<?php //echo '<pre>';print_r($editElement['company_name']);echo'</pre>';?>
									<select class="form-control" multiple="multiple" data-plugin="select2" id="company_name" name="company_name[]" required="" placeholder="Assign FMS">
									<?php $categories = json_decode(getAllBuyer(),true);									$g_company_name = explode(',', $editElement['g_company_name']);
									foreach($categories as $cat=>$row){										if(in_array( $cat,$g_company_name)){
											$sel = ' selected="selected"';
										}else{																					$sel = '';
										}
									?>
									<option value="<?php echo $cat;?>" <?php echo $sel;?>><?php echo $row;?></option>
									<?php 	
									}
									?>
									</select>
					
									@if ($errors->has('company_name'))
									<span class="text-danger" role="alert">								
									<strong>{{ $errors->first('company_name') }}</strong>	
									</span>		
									@endif
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
@endsection
@section('custom_validation_script')

@endsection