@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View KPI')
@section('pagecontent')
<div class="page">
  <div class="page-content"> 
	@if(session()->has('success'))
		<div class="alert alert-success text-center">
		  <p>{{ session()->get('success') }}</p>
		</div>
		<br />
    @endif    
    @if(session()->has('error'))
    <div class="alert alert-danger text-center">
       <p>{{ session()->get('error') }}</p>
    </div>
    <br />
    @endif    
    <!-- Panel Table Add Row -->
	<?php 
		$kpidata = $kpidata[0];
		//pr($kpidata); //die;  
	?>
    <div class="panel">
      <header class="panel-heading pull-right"><br/>
        <h1 class="panel-title text-center">{{ __('View KPI') }}</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('kpilist') }}">KPI List</a></li>
			<li class="breadcrumb-item">{{ __('View KPI') }}</li>
		</ol>
      </header>
      <div class="panel-body"> @if( userHasRight() )
        <div class="row">
          <form method="post" class="custom-form-wrap" name="mis_form" action="{{ route('kpiupdate', $kpidata['_id']) }}">
            @csrf
			<div class="form-wrap">
				<label>KPI Name:</label>
				<input type="text" readonly class="form-control" id="kpi_name" autocomplete="off" name="kpi_name" value="{{ $kpidata['kpi_name'] }}">
            </div>
            <div class="form-wrap"><label>FMS:</label>
            
						
						@foreach($fmslists as $fmslist)
							
							@if($fmslist['_id']==$kpidata['fms'])
							<input type="text" readonly class="form-control" id="kpi_name" autocomplete="off" name="kpi_name" value="{{$fmslist['fms_name']}}">
							@endif
							
						@endforeach
             
            </div>
            <div class="form-wrap">
            <label>DOER:</label>
               
			  <input type="text" readonly class="form-control" id="kpi_name" autocomplete="off" name="kpi_name" value="{{ getAgentDetails($kpidata['user']) }}">
            </div>
            <div class="form-wrap">
				<label>STEPS:</label>
				<div id="allsteps">
					@foreach($kpidata['step'] as $step)
						<?php 
							$stepData = getStepDataByStepId($step);
							//pr($stepData);
						?>
						<input type="radio" name="step[]" readonly checked value="{{ $step }}"> Step#{{ $stepData['step'] }} <br>
					@endforeach
				</div>
            </div>
           
          </form>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_validation_script')
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
<script type="text/javascript">
	
	$('#fms').on('change', function(){
		var fms_id = $(this).val();
		//alert(thisVal);
		if(fms_id!=''){
				/* $('#mis_from').val('');
				$('#mis_to').val('');
				$('.mis_date').show(); */
				var ajax_url = "<?php echo route('get_users_fms_access'); ?>/"+fms_id;
				$.ajax({
				method:'GET',
				url:ajax_url,
				data:{fms_id:fms_id},
				success:function(result){
						console.log(result);
						$('#user').html('');
						$('#user').html(result);
						},
				error:function(result)
						{
							return false;
						}
				});
		}else{
			$('#user').html('');
			/* $('#mis_from').val('');
			$('#mis_to').val('');
			$('.mis_date').hide(); */
		}

	})
	
	$('#user').on('change', function(){
		var user_id = $(this).val();
		var fms_id = $('#fms').val();
		//alert(thisVal);
		if(fms_id!='' && user_id!=''){
				/* $('#mis_from').val('');
				$('#mis_to').val('');
				$('.mis_date').show(); */
				var ajax_url = "<?php echo route('get_users_fms_steps'); ?>/"+fms_id+'/'+user_id;
				$.ajax({
				method:'GET',
				url:ajax_url,
				data:{fms_id:fms_id},
				success:function(result){
						//console.log(result);
						$('#allsteps').html('');
						$('#allsteps').html(result);
						},
				error:function(result)
						{
							return false;
						}
				});
		}else{
			$('#user').html('');
			/* $('#mis_from').val('');
			$('#mis_to').val('');
			$('.mis_date').hide(); */
		}

	})
</script> 
@endsection