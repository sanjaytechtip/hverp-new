@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Settings')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">Settings</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active">Setting</li>
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

		<div class="col-lg-6">
            <!-- Panel Basic -->
            <div class="panel">
              <div class="panel-heading">
                <h3 class="panel-title">Office Schedule</h3>
              </div>
              <div class="panel-body">
                 <form id="registration" method="post" autocomplete="off" action="{{route('weeklyoff')}}" enctype="multipart/form-data">
					@csrf
                  <div class="form-group form-material row">
                    <label class="col-md-3 form-control-label">Office Time</label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="inputTime" data-plugin="formatter" name="office_time[]" value="<?php echo $weekoff['office_time'][0]; ?>" data-pattern="[[99]]: [[99]]">
					   <input type="text" class="form-control" id="inputTime" data-plugin="formatter" name="office_time[]" value="<?php echo $weekoff['office_time'][1]; ?>" data-pattern="[[99]]: [[99]]">
                   </div>								
                  </div>
				  
				  <div class="form-group form-material row">
                    <label class="col-md-3 form-control-label">Tea Break 1</label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="inputTime" name="tea_break1[]" value="<?php echo $weekoff['tea_break1'][0]; ?>" data-plugin="formatter" data-pattern="[[99]]: [[99]]">
					   <input type="text" class="form-control" id="inputTime" name="tea_break1[]" value="<?php echo $weekoff['tea_break1'][1]; ?>" data-plugin="formatter" data-pattern="[[99]]: [[99]]">
                   </div>								
                  </div>
				  
				  <div class="form-group form-material row">
                    <label class="col-md-3 form-control-label">Tea Break 2</label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="inputTime" name="tea_break2[]" value="<?php echo $weekoff['tea_break2'][0]; ?>"  data-plugin="formatter" data-pattern="[[99]]: [[99]]">
					   <input type="text" class="form-control" id="inputTime" name="tea_break2[]" value="<?php echo $weekoff['tea_break2'][1]; ?>"  data-plugin="formatter" data-pattern="[[99]]: [[99]]">
                   </div>								
                  </div>
				  
				  <div class="form-group form-material row">
                    <label class="col-md-3 form-control-label">Lunch</label>
                    <div class="col-md-9">
                      <input type="text" class="form-control" id="inputTime" name="lunch[]" value="<?php echo $weekoff['lunch'][0]; ?>"  data-plugin="formatter" data-pattern="[[99]]: [[99]]">
					   <input type="text" class="form-control" id="inputTime" name="lunch[]" value="<?php echo $weekoff['lunch'][1]; ?>"  data-plugin="formatter" data-pattern="[[99]]: [[99]]">
                   </div>								
                  </div>
				
				  <div class="form-group form-material row">
                    <label class="col-md-3 form-control-label">Holiday</label>
                    <div class="col-md-9">
                      @foreach(weekDays() as $days)
                        <div class="checkbox-custom checkbox-primary">
                          <input type="checkbox" id="{{$days}}" name="weekdays[]" value="{{$days}}" <?php if(in_array($days,$weekoff['weekoff'])){ echo 'checked';}?> >
                          <label for="{{$days}}">{{$days}}</label>
                        </div>
					   @endforeach	
                    </div>
                  </div>	
					
                  <div class="form-group form-material row">
                    <div class="col-md-9 offset-md-3">
					
                      <button type="submit" class="btn-primary1 btn waves-effect1 waves-classic1" id="validateButton1">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- End Panel Basic -->
          </div>
		  
		  
        </div>
        <!-- End Panel Full Example -->
      </div>
    </div>
    <!-- End Page -->
@endsection
<?php /*
@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Settings')
@section('pagecontent')
<div class="page">
	<div class="page-header">
        <h1 class="page-title">Settings</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active">Weekly Off</li>
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
		  <div class="panel-heading">
            <h3 class="panel-title">Weekly Off
              <span class="panel-desc">Please Select Week day Off.</span>
            </h3>
          </div>
          <div class="panel-body">

            <form id="registration" method="post" autocomplete="off" action="{{route('weeklyoff')}}" enctype="multipart/form-data">
			@csrf
             <div class="row row-lg">
                <div class="col-xl-9 form-horizontal">
              	
				   <div class="form-group row form-material">
                       <div class="col-xl-12 col-md-9">
					   <div class="d-flex flex-column">
					   @foreach(weekDays() as $days)
                        <div class="checkbox-custom checkbox-primary">
                          <input type="checkbox" id="{{$days}}" name="weekdays[]" value="{{$days}}" <?php if(in_array($days,$weekoff)){ echo 'checked';}?> >
                          <label for="{{$days}}">{{$days}}</label>
                        </div>
					   @endforeach	
                      </div>
                    </div>
					
					<div class="form-group form-material col-xl-36 text-left padding-top-m">
						<button type="submit" class="btn btn-primary" id="validateButton1">Save</button>
					</div>
                  </div>				
				</div>
             </div>
            </form>
          </div>
        </div>
        <!-- End Panel Full Example -->
      </div>
    </div>
    <!-- End Page -->
@endsection */?>

