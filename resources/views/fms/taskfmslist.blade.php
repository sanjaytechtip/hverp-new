@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Task FMS List')
@section('pagecontent')
<div class="page">
<div class="new-width-design new-new-width-design">
    <div class="new-design-inner">
<div class="page-header">
		<h1 class="page-title">{{ __('Task FMS Lists') }}</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="https://themesofwp.com/ppfms/admin">Home</a></li>
			<li class="breadcrumb-item">{{ __('Task FMS Lists') }}</li>
		</ol>
	</div>
  <div class="page-content"> @if (\Session::has('success'))
    <div class="alert alert-success text-center">
      <p>{{ \Session::get('success') }}</p>
    </div>
    <br />
    @endif    
    @if (\Session::has('error'))
    <div class="alert alert-danger text-center">
      <p>{{ \Session::get('error') }}</p>
    </div>
    <br />
    @endif 
    
    <!-- Panel Table Add Row -->
    
    <?php 

			//$store_room = get_stock_room_list_api();

			$store_room = array();

			$roomArr=array();

			foreach($store_room as $rkey=>$rval){

				$roomArr[$rval->id] = $rval->name;

			}

			

			$routeName = Route::currentRouteName();

			// userfmslist 

			$userQuery  = '';

			$userLoginData = array();

			if($routeName=='dashboard'){

				$userLoginData = Session::get('userLogin');

				$company_name = $userLoginData['company_name'];

				$userQuery  = '?search=s&'.http_build_query(['customer_data'=>$company_name]);

				//pr($userLoginData);

			}

			//echo $userQuery;

		?>
    <div class="panel">     
      <div class="panel-body">
        <table class="table table-bordered table-hover table-striped" cellspacing="0" id="exampleAddRow">
          <thead>
            <tr>
              <th class="bg-info" style="color:#fff !important;">Sr. No</th>
              <th class="bg-info" style="color:#fff !important;">Department Name</th>
              <th class="bg-info" style="color:#fff !important;">Actions</th>
            </tr>
          </thead>
          <tbody>
          @foreach($fmslists as $fmslist)
          <tr class="gradeA">
            <td>{{ $no++ }}</td>
            <td>{{$fmslist['department_name']}}</td>
            <?php

						$disableEdit = ''; 

						$disableClick = '';

						$disableTaskCreate = 'disabled';

						$chkTask='md-plus';

					?>
            @if(isTaskDone($fmslist['_id'])>0)
            <?php 

							$disableEdit = 'disabled'; 

							$disableClick = 'return false;';

							$disableTaskCreate = '';

							$chkTask='md-check';

						?>
            @endif
            <td class="actions"><a href="{{ route('fmscopy', $fmslist['_id']) }}" style="display:none;" class="btn btn-info"><i class="icon md-copy" aria-hidden="true"></i> Copy</a>
            <a href="{{ route('taskfmsdata', $fmslist['_id']) }}{{ $userQuery }}" class="btn btn-primary"> <i class="icon md-eye" aria-hidden="true"></i> View</a>
            <a href="{{ route('fms_setting', $fmslist['_id']) }}" style="display:none;" class="btn btn-info waves-effect waves-classic"> <i class="icon wb-settings" aria-hidden="true"></i>Setting</a> 
            <a href="{{ route('fmsedit', ['id'=>$fmslist['_id']]) }}" style="display:none;" class="btn btn-success {{ $disableEdit }} edit-row" data-toggle="tooltip" data-original-title="Edit" style="display:none;"><i class="icon md-edit" aria-hidden="true"></i> Edit</a>
            <a href="{{ route('createsteps', $fmslist['_id']) }}" style="display:none;" class="btn btn-dark"><i class="icon {{ $chkTask }}" aria-hidden="true"></i> Create Steps</a>
              <form action="{{ route('fmsdelete', $fmslist['_id']) }}" style="display:none;" method="post">
                @csrf
                <input name="_method" type="hidden" value="DELETE">
                <input name="fms_id" type="hidden" value="{{ $fmslist['_id'] }}">
                <button class="btn btn-danger waves-effect waves-classic remove-row" type="submit" onclick="return confirm('Are you sure to delete this item?');"><i class="icon md-delete" aria-hidden="true"></i> Delete</button>
              </form>
              </td>
          </tr>
          
          
          
          
          @endforeach
            </tbody>
          
        </table>
      </div>
    </div>
    
    <!-- End Panel Table Add Row --> 
    
  </div>
</div>
</div>
</div>
@endsection