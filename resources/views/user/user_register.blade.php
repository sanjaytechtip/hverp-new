@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Create User')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">User Management</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('userlist')}}">User Management</a></li>
          <li class="breadcrumb-item active">Add User</li>
        </ol>
      </div> 
	  <?php 
		/* $apidata = getuserapi();
		echo "<pre>";
		print_r($apidata['users']);  fms_user_right 
		*/
		//echo $userField = getCurUserField('fms_user_right');
	  ?>
	  <div class="page-content container-fluid">
				@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div>
					@endif
        <!-- Panel Full Example -->
        <div class="panel">
          <div class="panel-body">
			
            <form id="registration" name="registration" method="post" autocomplete="off" action="{{route('adduserpost')}}" enctype="multipart/form-data" class="form-horizontal">
			@csrf
			<div class="form-group row form-material">
                    <label class="col-md-3 form-control-label">Full name<span class="required">*</span></label>
                    <div class="col-md-9">
                      <input type="text" autocomplete="off" class="form-control" name="name" value="" placeholder="Enter Your Full Name"
                        required="">
						@if ($errors->has('full_name'))
							<span class="text-danger" role="alert">
								<strong>{{ $errors->first('full_name') }}</strong>
							</span>
						@endif
                    </div>
            </div>
			<div class="form-group row form-material">
                    <label class="col-md-3 form-control-label">Email<span class="required">*</span></label>
                    <div class="col-md-9">
                      <input type="email" class="form-control" autocomplete="off" name="email" placeholder="email@email.com"
                          required="">
						  @if ($errors->has('email'))
							<span class="text-danger" role="alert">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						  @endif
                    </div>
            </div>
			<div class="form-group row form-material">
                    <label class="col-md-3 form-control-label">Password<span class="required">*</span></label>
                    <div class="col-md-9">
                      <input type="password" class="form-control" name="password" placeholder="Min length 6"
                          required="">
                    </div>
            </div>				
			<div class="form-group row form-material">
                    <label class="col-md-3 form-control-label">Phone Number<span class="required">*</span></label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" name="phone" placeholder="Enter Your Phone"
                        required="" />
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