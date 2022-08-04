@extends('layouts.fms_admin_layouts')

@section('pageTitle', $fms['fms_name'])

@section('pagecontent')
<link rel="stylesheet" href="{{ URL::asset('public/multiselect/bootstrap-multiselect.css') }}" type="text/css"/>
<style type="text/css">
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
.date_with_dmy, .date_with_time{ background-color: #fffbfb !important; opacity: 1;}
#ui-datepicker-div {  z-index: 9999999 !important;}
</style>

<?php
	/* Get the user who has access of this fms */
	$userScoreArrMain = getUserIdAccessOfThisFms($fms['_id']);
	
	$fms_id = $fms['_id'];
	$currentUserId = Auth::user()->id;
		
?>

<?php 
	$last_pi='';
	$pi_cnt='';
	if(array_key_exists('last_pi', $_GET) && $_GET['last_pi']!=''){
		$last_pi = $_GET['last_pi'];
		$pi_cnt = $_GET['pi_cnt'];
	}
	$pi_id='';
	
?>

<div class="page no-border" id="fms_data">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
			<div class="row top-filter-row has-xls-btn-box">
				<div class="col-md-2 logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
				<div class="xls-btn-box float-right">
                        <button class="exportToExcelTrigger btn btn-primary waves-effect waves-classic">Export to XLS</button>
                </div>
				
			</div> 
			
        <div class="panel-body">
            <table class="table-bordered table-hover table-striped tbale_header fixed_table table2excel" cellspacing="0" id="fmsdataTable">
				<thead>
						<tr class="tbale_header top-row">
							<th colspan="<?php echo count($taskdatas);?>" class="text-center font-weight-bold task_left_fix purple-bg-top">
								<span class="fms-name">{{$fms['fms_name']}}</span>
							</th>
							<?php							
								$cnt = count($stepsdatas); 
								$stepArr = array();
								//pr($stepsdatas); die;
								$step_no_cnt = 0;
								?>
							@foreach($stepsdatas as $stepsdata)							
							<?php 
								$userRightArr = array();
								if(!empty($stepsdata['user_right'])){
									$step_user_right = $stepsdata['user_right'];
									if(array_key_exists($currentUserId,$step_user_right)){
										$userRightArr = $stepsdata['user_right'][$currentUserId];
									}
								}
								
								$fms_how_new='';
								$fms_when_new='';
								$temlate_url='';
								if(array_key_exists('fms_how_new',$stepsdata)){
									$fms_how_new=$stepsdata['fms_how_new'];
								}
								
								if(array_key_exists('fms_when_new',$stepsdata)){
									$fms_when_new=$stepsdata['fms_when_new'];
								}
								if(array_key_exists('temlate_url',$stepsdata)){
									$temlate_url=$stepsdata['temlate_url'];
								}
								
								$s_id = $stepsdata['_id'];
								$adminControl = '<a href="javascript:void(0)" class="user_permit user_permit_control" id="'.$s_id.'" how="'.$fms_how_new.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
								
								$staffControl = '<a href="javascript:void(0)" class="user_permit user_permit_staff" id="'.$s_id.'" how="'.$fms_how_new.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
							
							if (in_array(1, $userRightArr) || userHasRight()) {
								$input_type1='';
								
								
								if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date' && $stepsdata['fms_when_type']!=14 && $stepsdata['fms_when_type']!=16))
								{
									$input_type1 = $stepsdata['fms_when']['input_type'];
									if($input_type1=='notes')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==6)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==15)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{!! $stepsdata['step'] !!} <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==17)
									{
										
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{!! $stepsdata['step'] !!}
										<?php 
										echo strip_tags($stepsdata['fms_what']);
										 ?>
										</th>
										<?php
									}
									}else if($stepsdata['fms_when_type']==13)
									{
										
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} 
										<?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else{
									$step_no_cnt++;
								?>
									<th class="text-center font-weight-bold step-title black-bg" colspan="2"> 
										<div class="auth-icon">
											
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
											{{ __('#') }}{{ $stepsdata['step'] }}
											<?php  echo strip_tags($stepsdata['fms_what']); ?>
										</div>
									</th>
									<?php 
										
									}
									
								?>
							
							<?php }?>
							@endforeach							
							<?php 
								if( userHasRight()){
									$userAccessCnt=count($userScoreArrMain);
								}else{
									$userAccessCnt=1;
								}
							?>							
							<?php /* ?>
							<th class="text-center font-weight-bold" colspan="<?php echo $userAccessCnt;?>">
								 MIS Report 
							</th>
							<?php */ ?>
							
							<?php /*?>
							@if( userHasRight())
							<th width="12%"></th>
							@endif	
							<?php */?>
						</tr>
						<tr class="tbale_header text-center ">
							<?php 
								$taskArr = array();
								$tbl_cell=0;
								$no=0;
								$cnt = count($taskdatas);
								
								//pr($taskdatas);  die;
							?>
							<?php /* ?><th class="bg-info text-white task_left_fix purple-bg">Sr. No</th><?php */ ?>
							@foreach($taskdatas as $taskdata)
								<?php 
									$taskArr[$no] = $taskdata['_id'];								
									$tbl_cell++;
									$no++;
									
									if($taskdata['task_name']!='Stage'){
										$purple=' purple-bg';
									}else{
										$purple='';
									}
									
									$left_fix='';
									if(array_key_exists('left_fix', $taskdata) && $taskdata['left_fix']==1){
										$left_fix=' task_left_fix';
									}
								?>
								<th class="bg-info text-white{{ $left_fix }}{{ $purple }}">
								{{$taskdata['task_name']}}
								</th>							
							@endforeach
							
							@foreach($stepsdatas as $stepsdata)
								<?php 
									$userRightArr = array();
									if(!empty($stepsdata['user_right'])){
										$step_user_right = $stepsdata['user_right'];
										if(array_key_exists($currentUserId,$step_user_right)){
											$userRightArr = $stepsdata['user_right'][$currentUserId];
										}
									}
									
									if (in_array(1, $userRightArr) || userHasRight()) {
									$input_type='';
									if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date') && $stepsdata['fms_when_type']!=14 && $stepsdata['fms_when_type']!=16)
									{
										
									}elseif($stepsdata['fms_when_type']==13){
										
									}else{										
									$tbl_cell++; 
									$userRightArr = array();
									if(!empty($stepsdata['user_right'])){
										$step_user_right = $stepsdata['user_right'];
										if(array_key_exists($currentUserId,$step_user_right)){
											$userRightArr = $stepsdata['user_right'][$currentUserId];
										}
									}
									
									
									?>
										<?php if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='rev_delivery_date'){ ?>
										<th class="bg-dark text-white">Actual</th>
										<?php $tbl_cell++; ?>
										<th class="bg-secondary text-white">Revised ETD</th>
										<?php }else{ ?> 
											<th class="bg-dark text-white">Planned</th>
										<?php $tbl_cell++; ?>
										<th class="bg-secondary text-white">Actual</th>
										<?php }?>
									
									<?php 
										}
									}
								?>
							@endforeach
							
							<?php 
							//$currentUserId
							/*
							if(!userHasRight()){
								echo '<th>'.$userScoreArrMain[$currentUserId]['full_name'].'</th>';
							}else{
								foreach($userScoreArrMain as $theKey=>$theVal){ ?>
									<th><?php echo $theVal['full_name'];?></th>
								<?php }
							}
							/*
							?>
							
							<?php /* ?>
							@if( userHasRight())
							<th width="12%">Action</th>
							@endif
							<?php */ ?>
						</tr>
						
						
				</thead>
			  
				<tbody id="fmsTable">
						<?php 
						//pr($fmsdatas); die;
							$row_num=0;
							//$pgNum = ($fmsdatas_pg->currentPage()-1)*100+1;
							$pgNum = ($fmsdatas_pg['current_page']-1)*100+1;
							
						?>
						@foreach($fmsdatas as $fmsdata)
						<?php
						$lab_dip='';
						$fob_approval='';
						$bulk_qty='';
						$ss_qty='';
						$fpt='';
						$shipment='';
						$transit_time='';

						$pi_id=$fmsdata['po_id'];
						
						$supplier_name = $fmsdata['supplier_data']['s_value'];
						
						$pacth='';
						$userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); 
						
						// transit_time

						$lab_dip = $fmsdata['lab_dip'];
						$fob_approval=$fmsdata['fob_sample'];
						$bulk_qty=$fmsdata['order_qty'];
						$ss_qty=$fmsdata['ss_qty'];
						
						$shipment=$fmsdata['mode_of_transport'];
						$transit_time=$fmsdata['transit_time'];
						
						?>
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
						<?php /* ?><td class="text-center task_left_fix sr_no">{{ $pgNum }}</td><?php */?>
							<!---Task data-->
							<?php 
								$pgNum++;								
								$no=0; $row_num++;
								$cnt = count($taskdatas);
								$k=1;
								$order_no ='';
								$payment_term ='';
								//pr($taskdatas); die();
							?>
							@foreach($taskdatas as $taskdata)
								<?php
									//pr($taskdatas); die;
									
									$payment_term = $fmsdata['payment_terms'];
									
									$rev='';
									
									$pi_v_data = getPuInvVersionsById($fmsdata['po_id']);
									
									/* if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
										$pi_v_data = $pi_v_data['pi_version'];
										$pi_cnt = count($pi_v_data);
										$rev_cnt = $pi_cnt-1;
							
										if($rev_cnt==0){
											$rev='';
										}else{
											$rev = '-R'.($rev_cnt);
										}
									} */
									
									$partshipped_query='';
									$rev_p='';
									if(array_key_exists('partshipped_no', $fmsdata)){
										$rev_p = '-P'.$fmsdata['partshipped_no'];
										$partshipped_query='?partshipped='.$fmsdata['partshipped_no'];
										$rev = '';
										if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
											$pi_v_data_res = $pi_v_data['pi_version'];
											$pi_cnt = count($pi_v_data_res);									
											$rev_cnt = $pi_cnt-1;							
											if($rev_cnt==0){
												$rev='';
											}else{
												$rev = '-R'.($rev_cnt);
											}
										}
									}elseif(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){ 
										$pi_v_data_res = $pi_v_data['pi_version'];
										$pi_cnt = count($pi_v_data_res);
										$rev_cnt = $pi_cnt-1;							
										if($rev_cnt==0){
											$rev='';
										}else{
											$rev = '-R'.($rev_cnt);
										}
									}
									
									$left_fix='';
									if(array_key_exists('left_fix', $taskdata) && $taskdata['left_fix']==1){
										$left_fix=' task_left_fix';
									}
									
									$factory_code='';
									if(array_key_exists('factory_code', $fmsdata)){
										$factory_code=$fmsdata['factory_code'];
									}
								?>								
								<td class="text-center{{ $left_fix }}">									 
									@if($taskdata['field_type']=='timestamp')
										{{ date('Y-m-d', strtotime($fmsdata['po_date'])) }}
									@elseif($taskdata['field_type']=='po_no')
										<a href="{{ route('purchaseview', $pi_id) }}{{ $partshipped_query }}" target="_blank">{{$fmsdata['invoice_no']}}{{ $rev_p }}{{ $rev }}</a>
										
									@elseif($taskdata['field_type']=='po_date')
										{{ date('Y-m-d', strtotime($fmsdata['po_date'])) }}
									@elseif($taskdata['field_type']=='supplier_name')
										{{ $supplier_name }}
									@elseif($taskdata['field_type']=='article_no')
										{{ $fmsdata['item_data']['i_value'] }}
									@elseif($taskdata['field_type']=='color')
										{{ $fmsdata['color'] }}
									@elseif($taskdata['field_type']=='factory_code')
										{{ $factory_code }}
									@elseif($taskdata['field_type']=='order_qty')
										{{ $fmsdata['order_qty'] }}
									@elseif($taskdata['field_type']=='ss_qty')
										{{ $fmsdata['ss_qty'] }}
									@elseif($taskdata['field_type']=='unit')
										{{ $fmsdata['unit'] }}
									@elseif($taskdata['field_type']=='price')
										{{ $fmsdata['unit_price'] }}
									@elseif($taskdata['field_type']=='etd')
										{{ date('Y-m-d', strtotime($fmsdata['etd'])) }}
									@elseif($taskdata['field_type']=='mode_of_transport')
										{{ $fmsdata['mode_of_transport'] }}
									@elseif($taskdata['field_type']=='po_type')
										{{ $fmsdata['po_type'] }}
									@elseif($taskdata['field_type']=='sic_no')
									{{ $fmsdata['item_data']['i_value'] }}/{{ $fmsdata['color'] }}/{{$fmsdata['invoice_no']}}{{ $rev }}
									@elseif($taskdata['field_type']=='po_status')
										<div class="fms_stage_row{{ $row_num }}">
											@if(array_key_exists($taskdata['_id'], $fmsdata))
												{!! $fmsdata[$taskdata['_id']]['stage_text'] !!}
											@endif
										</div>
									@else
										<?php /*?>{{ $taskdata['task_name'] }} <?php */?>
									@endif
									
									
								</td>
							@endforeach
							<!---Stap data-->
							<?php
							$ac_and_dc_date = ''; 
							$ac_and_dc_date_flag = ''; 
							$kk=1;
							$acDatePrev=array();
							$delaypercentShow=0;
							//$userScoreArr = getUserIdAccessOfThisFms($fms['_id']);							
							$enyEntry=0;
							$currentDropDown = '';
							$pi_approval_dropdown='';
							$pi_actual='';
							$new_edt='';
							$payment_approval='';
							$stock_status='';
							$inv_date_act='';
							$ac_date='';
							$stpId = '';
							
							$pi_actual_new ='';
							$pi_approval_dropdown_new='';
							
							$order_status='';
							$rev_delivery_date='';
							$part_shipped_planned='';
							$final_vessel_sailing='';
							$import_customs_act='';
							$unload_goods_act='';
							$random_quality_act='';
							$quality_check_drop_down='';
							$quality_check_drop_down_act='';
							$security_deposit_dropdown='';
							$order_status_val='';
							$process_val ='';
							
							$random_quality_check_pl='';
							$random_quality_check_pl_main='';
							$today_main = date('Y-m-d H:i:s');
							?>
							@foreach($stepsdatas as $stepsdata)
							<?php
							// for step 9 dropdown value
							$order_status_val = $fmsdata['5ee7671f0f0e750a80007414']['dropdownlist'];
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']!='') {
								$process_val =$stepsdata['process'];
							}
							
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='final_vessel_sailing'){
							$final_vessel_sailing = $fmsdata[$stepsdata['_id']]['actual'];
							}
							
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status' && !empty($dropdownVal)){
								$order_status = $fmsdata[$dataStepId]['dropdownlist'];
							}
							
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='import_customs'){
								$import_customs_act = $fmsdata[$stepsdata['_id']]['actual'];
							}
							
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='unload_goods'){
									$unload_goods_act = $fmsdata[$stepsdata['_id']]['actual'];
							}
							
							$patch='';
							
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='pi_approval') {
								$pi_actual_new = $fmsdata[$stepsdata['_id']]['actual'];
								$pi_approval_dropdown_new = $fmsdata[$stepsdata['_id']]['dropdown'];
							}
							
							if(array_key_exists($stepsdata['_id'], $fmsdata)) {
								
								$userRightArr = array();
								if(!empty($stepsdata['user_right'])){
									$step_user_right = $stepsdata['user_right'];
									if(array_key_exists($currentUserId,$step_user_right)){
										$userRightArr = $stepsdata['user_right'][$currentUserId];
									}
								}
								
								/* ********************* 14 june 19 ******************** */
								if(array_key_exists('actual', $fmsdata[$stepsdata['_id']])){
									$acDatePrev[$kk] = $fmsdata[$stepsdata['_id']]['actual'];
								}else{
									$acDatePrev[$kk]=''; 
								}
								
								/* for none */
								if(array_key_exists('input_type', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['input_type']=='none'){
									$acDatePrev[$kk] = $fmsdata[$stepsdata['_id']]['none_date'];
								}else{
									$acDatePrev[$kk]=''; 
								}
								
								if (in_array(1, $userRightArr) || userHasRight()) { ?>
								<?php 
								$plane_date='';
								if(array_key_exists('planed',$fmsdata[$stepsdata['_id']])){
										$plane_date = $fmsdata[$stepsdata['_id']]['planed'];
								}
								
								$do_collect = '';
								if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='do_collection'){
									$do_collect = $stepsdata['process'];
								}
								
							if (strtotime($plane_date)>strtotime(0) && array_key_exists('planed',$fmsdata[$stepsdata['_id']]) && $do_collect!='do_collection' || $stepsdata['fms_when_type']==12)
							{
									$planeAlert='';
									
									$test_report_approval='';
									if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='test_report_approval'){
										$test_report_approval = $fmsdata[$stepsdata['_id']]['dropdown'];
									}
									
									$test_report = $fmsdata['test_report'];
									$inspection_report = $fmsdata['inspection_report']; 
									
									$fob_sample = $fmsdata['fob_sample'];
									
									if($unload_goods_act!=''){
										$random_quality_check_pl = date('d.m.y', strtotime($unload_goods_act."+7 days"));
										$random_quality_check_pl_main = date('Y-m-d', strtotime($unload_goods_act."+7 days"));
									}else{
										$random_quality_check_pl = '';
									}
									
								?>
								<td class="text-center" id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}">
								
								
									@if(array_key_exists('process',$stepsdata))
										@if($stepsdata['process']=='get_po_confirmation' && $order_status_val=='Cancelled')
											
										@elseif($stepsdata['process']=='lab_dip' && $lab_dip=='Pending')
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
										@elseif($stepsdata['process']=='ss_qty' && $ss_qty>0)
										{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
										@elseif($stepsdata['process']=='fpt' && $fpt=='Required')
										{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
										@elseif($stepsdata['process']=='resolve_query')
										{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
										@elseif($stepsdata['process']=='test_report_approval' && ($test_report!='Yes' ||  $order_status_val=='Cancelled'))
										
										@elseif($stepsdata['process']=='fob_sample_approval' && ($fob_sample!='Yes' || $order_status_val=='Cancelled'))
										
										@elseif($stepsdata['process']=='inspection_report' && ($inspection_report!='Yes' || $order_status_val=='Cancelled'))
										
										@elseif($stepsdata['process']=='random_quality' && $order_status_val=='Cancelled')
										
										@elseif($stepsdata['process']=='random_quality' && $order_status_val!='Cancelled')
											{{ $random_quality_check_pl }}
										@elseif($stepsdata['process']=='get_lab_dip_appr' && $lab_dip=='Approved')
										
										@else
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
										@endif
										
									@else
										{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
									@endif								
									<?php 
											$delaypercent_main = '';
											/* $delaypercent_main = getTimeDelay($fmsdata,$stepsdata, $stepsdata['_id']);
											$delaypercent = $delaypercent_main>0?'-'.abs($delaypercent_main):$delaypercent_main;										
											$delaypercentShow = $delaypercentShow+$delaypercent;										
											foreach($stepsdata['user_right'] as $uKey=>$uVal){
												if(in_array(2, $uVal)){												
													if($delaypercent_main>0 || $fmsdata[$stepsdata['_id']]['actual']!=''){
														$userScoreArr[$uKey]['score']= $userScoreArr[$uKey]['score']+$delaypercent_main;
														$userScoreArr[$uKey]['count']=$userScoreArr[$uKey]['count']+1;
													}else{
														$userScoreArr[$uKey]['score']= abs($userScoreArr[$uKey]['score']+$delaypercent_main);
														$userScoreArr[$uKey]['count']=$userScoreArr[$uKey]['count']+0;
													}	
												}
											} */
									?>
									
								</td>
								<?php 
										$actual='';										
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];										
										$planed_date=$fmsdata[$stepsdata['_id']]['planed'];
										$bgStyle='';
										
										if($inspection_report=='No' && $stepsdata['process']=='inspection_report'){
											$planed_date='';
										}
										
										if($stepsdata['process']=='random_quality'){
											$planed_date=$random_quality_check_pl_main;
										}
										
										if($planed_date!='' && $actual_date>$planed_date){
											$bgStyle = 'background:'.BG_RED;
										}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
											$bgStyle = 'background:'.BG_GREEN;
										}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
											$bgStyle = 'background:'.BG_YELLOW;
										}

										

										
										if($stepsdata['fms_when_type']==12){
											// if($stepsdata['process']=='test_report_approval' && $test_report!='Yes')
												
										?>
											<td style="{{ $bgStyle }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}">
												@if($fmsdata[$stepsdata['_id']]['planed']!='' && $stepsdata['process']=='test_report_approval' && ($test_report!='Yes' || $order_status_val=='Cancelled'))
													
												@elseif($stepsdata['process']=='test_report_approval' && $order_status_val!='Cancelled')
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<?php 
															$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 
															$pi_actual = $actual_date;
															?>
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
													
												@elseif($fmsdata[$stepsdata['_id']]['planed']!='' && $stepsdata['process']=='inspection_report' && ($inspection_report!='Yes' || $order_status_val=='Cancelled'))
												
												@elseif($stepsdata['process']=='inspection_report' && $order_status_val!='Cancelled')
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<?php 
															$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 
															$pi_actual = $actual_date;
															?>
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
												
												@elseif($stepsdata['process']=='fob_sample_approval' && ($fob_sample!='Yes' || $order_status_val=='Cancelled'))
												
												@elseif($stepsdata['process']=='fob_sample_approval' && $order_status_val!='Cancelled')
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<?php 
															$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 
															$pi_actual = $actual_date;
															?>
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
												
												@elseif(($stepsdata['process']=='random_quality' && $order_status_val=='Cancelled') || $random_quality_check_pl=='')
												
												@elseif($stepsdata['process']=='random_quality' && $order_status_val!='Cancelled' && $random_quality_check_pl!='')
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $random_quality_check_pl_main }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<?php 
															$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 
															$pi_actual = $actual_date;
															
															 $quality_check_drop_down = $fmsdata[$stepsdata['_id']]['dropdown'];
															 $quality_check_drop_down_act =$actual_date;
															?>
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
												@else
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<?php 
															$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 
															$pi_actual = $actual_date;
															?>
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
												@endif											
											</td>
										<?php }elseif($stepsdata['fms_when_type']==17){ ?>
												<td style="{{ $bgStyle }}">
												
												</td>
										<?php }elseif($stepsdata['fms_when_type']==1 && $stepsdata['process']!='get_po_confirmation' && $fmsdata[$stepsdata['_id']]['actual']==''){ 
										
											if($stepsdata['process']=='get_lab_dip_appr' && $lab_dip=='Approved'){
													echo "<td></td>";
												}else{
										
											$dateHtml =  dateCompare($fmsdata[$stepsdata['_id']]['planed'], '');
											?>
													<td style="background:<?php echo $dateHtml['bgcolor'];?>;">
														<?php //pr($dateHtml);?>
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													</td>
												<?php }?>
										<?php }else if($actual_date!=''){
											$enyEntry=1;
											$dateHtml =  dateCompare($planed_date, $actual_date);
											?>
											
											<?php if (in_array(3, $userRightArr) || userHasRight()) {
											$ac_date = "'".date('d-m-Y H:i', strtotime($actual_date))."'";
											
											$stpId = '';
											$stpId = $stepsdata['_id'];
											$dataId = $fmsdata['_id'];

											//$custom_opt_Html='';
											$stpId = "'".$stpId."'";
											$dtId = "'".$dataId."'";
											$row_num_stage = "'row".$row_num."'";
											
											$planed_date_pi = "'".$planed_date."'";
											$actual_date_pi = "'".$actual_date."'";
											
											
											
											
											if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='issue_po'){
												$pi_number = "'".$fmsdata[$stepsdata['_id']]['pi_number']."'";
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openPiUpdate('.$dtId.', '.$stpId.', '.$planed_date_pi.', '.$actual_date_pi.', '.$pi_number.', '.$row_num_stage.')">'.$dateHtml['date']." | ".$fmsdata[$stepsdata['_id']]['pi_number']."</a></td>";
											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='final_vessel_sailing'){
												$final_vessel_sailing = $actual_date;
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
										
											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='import_customs'){
												$import_customs_act = $actual_date;
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
												
											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='unload_goods'){
												$unload_goods_act = $actual_date;
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
												
											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='random_quality'){
												$random_quality_act = $actual_date;
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
												
											}else{
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
											}
											
											
											
											}else{  
											echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">'.$dateHtml['date']."</td>";
											} ?>
										<?php	
											
										}else{
												if($ac_and_dc_date_flag==''){
													$ac_and_dc_date = '';
													$ac_and_dc_date_flag=1;
												}else{
													$ac_and_dc_date = ' style="display:none"';
												}												
												?>
												
												<?php
												
												$pmChk = '';
												if(array_key_exists('pm_check', $stepsdata)){
													$pmChk =$order_no;
												}
											
												$process = '';	
												if(array_key_exists('process', $stepsdata)){
													$process = $stepsdata['process'];
													$pmChk =$order_no;
													
												}
												if(array_key_exists('none_date', $stepsdata)){
													if($fmsdata[$stepsdata['_id']]['none_date']!=''){
															$ac_and_dc_date_flag='';
													}										
												}	

												// 22 Apr 2020 
												$ac_and_dc_date='';
												
												$dateHtml =  dateCompare($fmsdata[$stepsdata['_id']]['planed'], '');
												
												?>
											<td class="text-center" <?php echo $planeAlert; ?> style="background:<?php echo $dateHtml['bgcolor'];?>;">
											<?php //pr($dateHtml); ?>
											@if(in_array(2, $userRightArr) || userHasRight())
												@if(array_key_exists('process',$stepsdata))
													@if($stepsdata['process']=='lab_dip' && $lab_dip=='Required')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='fob_approval' && $fob_approval=='Buyer')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='ss_qty' && $ss_qty>0)
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='fpt' && $fpt=='Required')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='get_po_confirmation' && $order_status_val!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>														
													@else
														
													@endif
													
												@else
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
												@endif
											@endif
												</td>
												<?php
										}
									 
								}else{ 
										/* none field */
										$fms_when_type_none='';
										$input_type_none='';
										
										if(array_key_exists('input_type', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['input_type']=='none'){
										
										$input_type_none=$fmsdata[$stepsdata['_id']]['input_type'];
										
										if($ac_and_dc_date_flag==''){
												$ac_and_dc_date = '';
												$ac_and_dc_date_flag=1;
										}else{
											$ac_and_dc_date = ' style="display:none"';
										} 
										
										
										// 22 Apr 2020
										$ac_and_dc_date='';
										
										if($fmsdata[$stepsdata['_id']]['none_date']!=''){
											$ac_and_dc_date_flag='';

											if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='fup_collection'){ ?> 
											<td class="text-center" colspan="2"> <a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="">{{ date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}</a></td>
										
										<?php }else{
												?>
											<td class="text-center" colspan="2"> 
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }}
											</td>
										<?php } ?>
											
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										
										@if(in_array(2, $userRightArr) || userHasRight())
											
										<?php if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='eta_adjusted'){ ?>
											<?php 
												if($final_vessel_sailing!=''){
													$transit_time_num=explode(' ', $transit_time);													
													$transit_time_dt = $transit_time_num[0];													
													$eta_adjusted_dt = date('Y-m-d', strtotime($final_vessel_sailing.'+'.$transit_time_dt.' days'));
													echo changeDateToDmyHi($eta_adjusted_dt);													
												}												
											?>
										<?php }else{ ?>
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" patch="" > <i class="icon md-time" aria-hidden="true"></i></a> 
										<?php }?>
											
										
										
										@endif
										
										</td>
											<?php }?>
											
										<?php } ?>
										
										<?php 
										/* custom_date field */										
										if(array_key_exists('input_type', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['input_type']=='custom_date'){
										
										$input_type_none=$fmsdata[$stepsdata['_id']]['input_type'];
										
										if($ac_and_dc_date_flag==''){
												$ac_and_dc_date = '';
												$ac_and_dc_date_flag=1;
										}else{
											$ac_and_dc_date = ' style="display:none"';
										}
										
										$tt = '';
										if($kk>1){
											if($acDatePrev[$kk-1]!=''){
											$ac_and_dc_date = '';
											$tt = $acDatePrev[$kk-1];
											}
										} 
										
										if(empty($tt)){
											$ac_and_dc_date = ' style="display:none"';
										}
										
										if($kk==1){
											$ac_and_dc_date = '';
										}
														
										// 22 Apr 2020
										$ac_and_dc_date='';
										
										if(array_key_exists('delivery_date', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['delivery_date']!=''){
										?>
											<td>{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}</td>
											<td>
												@if(in_array(2, $userRightArr) || userHasRight())
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
												@endif
											</td>
										
										<?php }?>
										
										<?php } ?>
									
									<?php
									/***** for Fabric Comments ****/
									$custom_options='';
									$built_options='';
									
									/* start  chek stepid exit or not  */	
									/* dropdown with plane-actual */
									$dropdownVal='';
									
									if($stepsdata['fms_when_type']==13){
										echo '<td class="text-center" colspan="2">';
										
										$custom_opt_Html='';
										$dataId = $fmsdata['_id'];
										$dataStepId = $stepsdata['_id'];
							
										$sId = "'".$dataStepId."'";
										$dtId = "'".$dataId."'"; 
										$row_num_stage = "'row".$row_num."'";
										
										/* if(array_key_exists('dropdownlist', $stepsdata['fms_when'])){
											$dropdownVal = explode(',',$stepsdata['fms_when']['dropdownlist']);
										} */
										$dropdownVal = explode(',',$stepsdata['fms_when']['dropdownlist']);
										$p_terms = $payment_term;
										$p_terms_arr = explode('_',$p_terms);
										if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status' && !empty($dropdownVal)){
											
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
											$order_status = $currentDropDown;
											
											if($currentDropDown=='Part Shipped'){
												$last_update_on =  $fmsdata[$dataStepId]['last_update_on'];
												$part_shipped_planned =  date('Y-m-d H:i:s', strtotime($last_update_on.'+1 days'));
											}
											
											echo $currentDropDown;
										}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='quality_check' && !empty($dropdownVal)){
											
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
											
											
											echo $currentDropDown;
										}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='security_deposit' && !empty($dropdownVal)){
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
											$security_deposit_dropdown = $currentDropDown;											
											echo $currentDropDown;
										}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status_on_delivery'){
											
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
												if($fmsdata[$dataStepId]['dropdownlist']!=''){
													$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
												}else{
													$today = date('Y-m-d');													
													if($final_vessel_sailing=='' && $unload_goods_act==''){													
														$currentDropDown = 'Under Production';
													}elseif($final_vessel_sailing!='' && $unload_goods_act==''){													
														$currentDropDown = 'In Transit';
													}elseif($unload_goods_act!=''){
														$currentDropDown = 'In House';
													}
												}
												echo $currentDropDown;											
										}else{
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
											echo $currentDropDown;
										}
										
										echo "</td>";
										
									}
									
									// for pl_ac_none value blank
									if($stepsdata['fms_when_type']==14){
										//$stock_status
											/* if(array_key_exists('depend_on_dropdown', $stepsdata) && ($currentDropDown=='Not Available' || $currentDropDown=='not available') && ($pi_approval_dropdown_new=='Approved' || $pi_approval_dropdown_new=='approved')){ */
											
											if(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='rev_delivery_date')){		
												
												if($fmsdata[$stepsdata['_id']]['actual_rev']!=''){
													$rev_delivery_date = $fmsdata[$stepsdata['_id']]['actual_rev'];
												echo '<td class="text-center" id="pl_'.$stepsdata['_id'].'_row'.$row_num.'">';
												//echo changeDateToDmyHi($fmsdata['etd']);
												echo changeDateToDmyHi($fmsdata[$stepsdata['_id']]['actual_rev']);
												
												echo '</td>';
												?>
												<td class="text-center">
												{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['rev_rev']) }} | 
													<a href="javascript:void(0)" title="ETD: {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['rev_rev']) }}" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['rev_rev'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="" class="btn btn-success"><i class="icon md-calendar" aria-hidden="true"></i></a>
												</td>
												<?php }else{ ?>
														<td id="pl_{{ $stepsdata['_id'] }}_row{{$row_num}}"></td>
														<td class="text-center">
															<a href="javascript:void(0)" title="ETD: {{ changeDateToDmyHi($fmsdata['etd']) }}" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata['etd'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="" class="btn btn-success"><i class="icon md-calendar" aria-hidden="true"></i></a>
														</td>
												<?php  }?>
												
											<?php }elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='final_vessel_sailing')){ 
											
												echo '<td class="text-center">';
												echo changeDateToDmyHi($fmsdata['etd']);
												echo '</td>';
												$dateHtml =  dateCompare($fmsdata['etd'], '');
												?>
											
												<td class="text-center" style="background:<?php echo $dateHtml['bgcolor'];?>;">
													@if(in_array(2, $userRightArr) || userHasRight())
													<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata['etd'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="" class="btn btn-success"><i class="icon md-calendar" aria-hidden="true"></i></a>
													@endif
												</td>
											
												<?php 
												$currentDropDown = '';
												$pi_approval_dropdown='';
												$pi_actual='';
												
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='delivery_extention')){
												
												if($rev_delivery_date !== '' && $order_status_val!='Cancelled'){
													
													$collection_pl='';
													$collection_pl=$fmsdata[$stepsdata['_id']]['planed']; 
													
													$rev_delivery_date = date('Y-m-d',strtotime($rev_delivery_date." +1 days"));
													$dateHtml =  dateCompare($rev_delivery_date, '');
													
													echo '<td class="text-center">';														
														echo changeDateToDmyHi($rev_delivery_date);	
													echo '</td>';
													$pmChk='';
													$process='';
													?>
													
														<?php if($fmsdata[$stepsdata['_id']]['actual']=='') { ?>
														<td class="text-center" style="background:<?php echo $dateHtml['bgcolor'];?>;">
														<a href="javascript:void(0)"  class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$rev_delivery_date}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
														</td>
														<?php }else{ 
															$dateHtml =  dateCompare($collection_pl, $fmsdata[$stepsdata['_id']]['actual']);
															
															$ac_date = "'".date('d-m-Y H:i', strtotime($fmsdata[$stepsdata['_id']]['actual']))."'";
											
															$stpId = '';
															$stpId = $stepsdata['_id'];
															$dataId = $fmsdata['_id'];

															//$custom_opt_Html='';
															$stpId = "'".$stpId."'";
															$dtId = "'".$dataId."'";
															$row_num_stage = "'row".$row_num."'";
															
															echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
														?>
															
														<?php } ?>
													
													
													<?php
												}else{
													echo '<td class="text-center"></td><td class="text-center"></td>';
												}												
												$currentDropDown = '';
												$pi_approval_dropdown='';
												$pi_actual='';
												
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='issue_part_shipped')){
													
													if($part_shipped_planned!=''){
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.changeDateToDmyHi($part_shipped_planned).'</td>';
													
													$dateHtml =  dateCompare($part_shipped_planned, '');
													
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $part_shipped_planned }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';
													}
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='ss_to_be_received')){
													$ss_to_be_received_pl = date('Y-m-d H:i:s', strtotime($fmsdata['etd']."+7 days"));
													
													$dateHtml =  dateCompare($ss_to_be_received_pl, '');
													
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'" >'.changeDateToDmyHi($ss_to_be_received_pl).'</td>';
													
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $ss_to_be_received_pl }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='import_customs')){
													
													if($order_status!='Cancelled' && $order_status!='' && $final_vessel_sailing!=''){
														/* check shipment */
														//$final_vessel_sailing 
														$transit_time_num=explode(' ', $transit_time); 
														
														$s13Pl='';
														if($shipment=='Sea'){
															$s13Pl_temp = ($transit_time_num[0]-2);
															$s13Pl = date('Y-m-d H:i:s', strtotime($final_vessel_sailing."+ $s13Pl_temp days"));
														}elseif($shipment=='Air'){
															$s13Pl_temp = ($transit_time_num[0]-1);
															$s13Pl = date('Y-m-d H:i:s', strtotime($final_vessel_sailing."+ $s13Pl_temp days"));
														}
														//echo $s13Pl.'===';
														
														//pr($transit_time_num); die;
														$dateHtml =  dateCompare($s13Pl, '');
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.changeDateToDmyHi($s13Pl).'</td>';
													
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													@if(in_array(2, $userRightArr) || userHasRight())
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $s13Pl }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													@endif
													</td>
													
													<?php
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='unload_goods')){
													if($order_status!='Cancelled' && $import_customs_act!=''){
														//import_customs_act
														$dateHtml='';
														if($import_customs_act!=''){
															$s14Pl_main = date('Y-m-d H:i:s', strtotime($import_customs_act."+ 1 days"));
															$dateHtml =  dateCompare($s14Pl_main, '');
															$s14Pl = changeDateToDmyHi($s14Pl_main);
														}else{
															$s14Pl = '';
														}
														
														
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.$s14Pl.'</td>';
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $s14Pl_main }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='random_quality')){
													// step 15
													if($order_status!='Cancelled' && $unload_goods_act!=''){
														//import_customs_act
														if($unload_goods_act!=''){
															$s15Pl = date('Y-m-d H:i:s', strtotime($unload_goods_act."+7 days"));
															$s15Pl_main = date('Y-m-d H:i:s', strtotime($unload_goods_act."+7 days"));
															$dateHtml =  dateCompare($s15Pl_main, '');
															$s15Pl = changeDateToDmyHi($s15Pl);
														}else{
															$s15Pl = '';
														}
														
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.$s15Pl.'</td>';
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $s15Pl_main }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='if_the_quality_is_fail')){
													// step 17
													if($order_status!='Cancelled' && $quality_check_drop_down_act!='' && $quality_check_drop_down=='Fail'){
														//import_customs_act
														if($quality_check_drop_down_act!=''){
															$s17Pl = date('Y-m-d H:i:s', strtotime($quality_check_drop_down_act."+1 days"));
															$s17Pl_main = date('Y-m-d H:i:s', strtotime($quality_check_drop_down_act."+1 days"));
															$dateHtml =  dateCompare($s17Pl, '');
															$s17Pl = changeDateToDmyHi($s17Pl);
														}else{
															$s17Pl = '';
														}
														
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.$s17Pl.'</td>';
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $s17Pl_main }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='book_import' || $stepsdata['process']=='import_summary' || $stepsdata['process']=='submit_the_bill')){
												
												// step = 18, 19, 20
												
													if($order_status!='Cancelled' && $unload_goods_act!=''){
														//import_customs_act
														if($unload_goods_act!=''){
															
															$dayInc = 0;
															if($stepsdata['process']=='book_import'){
																$dayInc=8;
															}else{
																$dayInc=15;
															}
															
															$planned_dt = date('Y-m-d H:i:s', strtotime($unload_goods_act."+$dayInc days"));
															$planned_dt_main = date('Y-m-d H:i:s', strtotime($unload_goods_act."+$dayInc days"));
															$dateHtml =  dateCompare($planned_dt, '');
															
															$planned_dt = changeDateToDmyHi($planned_dt);
															
															
															
														}else{
															$planned_dt = '';
														}
														
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.$planned_dt.'</td>';
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $planned_dt_main }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
													
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='get_security_deposit')){
													if($order_status!='Cancelled' && $import_customs_act!='' && $security_deposit_dropdown=='Yes'){
														//import_customs_act
														if($import_customs_act!=''){
															$s22Pl = date('Y-m-d H:i:s', strtotime($import_customs_act."+20 days"));
															$s22Pl_main = date('Y-m-d H:i:s', strtotime($import_customs_act."+20 days"));
															
															$dateHtml =  dateCompare($s22Pl_main, '');
															
															$s22Pl = changeDateToDmyHi($s22Pl);
														}else{
															$s22Pl = '';
														}
														
														
													echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'">'.$s22Pl.'</td>';
													echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'" style="background:'.$dateHtml['bgcolor'].';">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="{{ $s22Pl_main }}" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
													<?php echo '</td>';
													}else{														
														echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
														echo '<td></td>';													
													}
													
													
											}else{ 
												echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
												
												echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'">'; ?>
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
												<?php echo '</td>';
												
											
											}
									}
									
									// for calendar
									if($stepsdata['fms_when_type']==15 ){
										echo '<td class="text-center" colspan="2">';										
										$calendar_dt = date('d-m-Y', strtotime($fmsdata['etd']));
										
										?>	
										@if(($fmsdata[$stepsdata['_id']]['calendar'])!='') 
											<?php $new_edt = $fmsdata[$stepsdata['_id']]['calendar']; ?>
											<strong>{{  date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['calendar'])) }} | </strong> 
										@endif
										<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openCalendar('{{ $fmsdata['_id'] }}','{{ $calendar_dt }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" class="btn btn-success" title="Current ETD: {{ $calendar_dt }}"><i class="icon md-calendar" aria-hidden="true"></i></a>
									<?php	echo '</td>';
									}
									
									if($stepsdata['fms_when_type']==16){
										//pr($stepsdata);
										//pr($fmsdata[$stepsdata['_id']]['act_qty']);
										if($stepsdata['fms_when']['input_type']=='pl_ac_qty_variation'){
											echo '<td class="text-center">';											
												echo number_format($bulk_qty,2);											
											echo '</td>';
											
											if($fmsdata[$stepsdata['_id']]['act_qty']!=''){
												$bulk_qty_a=$fmsdata[$stepsdata['_id']]['act_qty'];
											}else{
												$bulk_qty_a=$bulk_qty;
											}
											?>
											<td class="text-center">
												
												<span>
													<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openBulkQtyUpdate('{{ $fmsdata['_id'] }}','{{ $bulk_qty_a }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')">
													<?php 
													if($fmsdata[$stepsdata['_id']]['act_qty']==''){ ?>
														Actual | Variation
													<?php }else{
														echo $fmsdata[$stepsdata['_id']]['act_qty'].' | ';
														$act_qty_bulk = $fmsdata[$stepsdata['_id']]['act_qty'];
														
														if($bulk_qty>0){
															$variation_bulk = (($act_qty_bulk-$bulk_qty)/$bulk_qty)*100;
														}else{
															$variation_bulk = 0;
														}
														
														echo '<strong>Variation: '.number_format($variation_bulk,2).' %</strong>';
													}
													?>
													</a>
												</span>
											</td>
											<?php
											
										}
									}
									
									if($stepsdata['fms_when_type']==17){
										//pr($stepsdata);
										//pr($fmsdata[$stepsdata['_id']]['act_qty']);
										if($stepsdata['fms_when']['input_type']=='inv_details'){
											/* echo '<td class="text-center">';											
												echo changeDateToDmyHi($fmsdata[$stepsdata['_id']]['inv_date']);											
											echo '</td>'; */
											$inv_date_new='';
											if($fmsdata[$stepsdata['_id']]['inv_date']==''){
												$inv_date_new = date('d-m-Y');
											}else{
												$inv_date_new = date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['inv_date']));
											}
											
											$inv_no_new = '';
											if($fmsdata[$stepsdata['_id']]['inv_no']==''){
												//$inv_no_new = 'POLLP/'.date('Y').'-'.date('y', strtotime('+1 year')) .'/';
												$posVal='';
												$pos = array_values(getOptionArray('financial_year'));
												if(!empty($pos)){
													$posVal=$pos[0];
												}
												//$inv_no_new = 'POLLP/'.$posVal.'/';												
												$inv_no_new = '';												
											}else{
												$inv_no_new = $fmsdata[$stepsdata['_id']]['inv_no'];
											}
											
											?>
											<td class="text-center" colspan="2">
												<span>
													<?php /*?><a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}"  onclick="openInvoiceUpdate('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', 'row{{ $row_num }}', '{{ $inv_no_new }}')"> <?php */?>
													<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}"  onclick="openInvoiceUpdate('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', 'row{{ $row_num }}', '{{ $inv_date_new }}', '{{ $inv_no_new }}', '{{ $fmsdata[$stepsdata['_id']]['inv_amount'] }}', '{{ $payment_term }}')">
													<?php
													
													/* if($fmsdata[$stepsdata['_id']]['inv_no']==''){ 
														echo "Invoice No.";
													}else{
														echo $fmsdata[$stepsdata['_id']]['inv_no'];
													} */
													
													$inv_date = $fmsdata[$stepsdata['_id']]['inv_date'];
													if($inv_date==''){ 
														echo "Invoice Date | ";
													}else{
														echo date('d.m.y', strtotime($inv_date))." | ";
													}
													
													if($fmsdata[$stepsdata['_id']]['inv_no']==''){ 
														echo "Invoice No. | ";
													}else{
														echo $fmsdata[$stepsdata['_id']]['inv_no']." | ";
													}
													
													
													if($fmsdata[$stepsdata['_id']]['inv_amount']==''){ 
														echo "Invoice Amount";
													}else{
														echo number_format($fmsdata[$stepsdata['_id']]['inv_amount'],2);
													}
													
													?>
													</a>
												</span>
											</td>
											<?php
											
										}
									}
										
									if(array_key_exists('built_options', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['built_options']==7){
										
											$dataId = $fmsdata['_id'];
											$dataStepId = $stepsdata['_id'];
											$custom_opt_Html='';
											$sId = "'".$dataStepId."'";
											$dtId = "'".$dataId."'";
											$row_num_stage = "'row".$row_num."'";
										if($fmsdata[$stepsdata['_id']]['built_options_id']=='patchers_name'){
											
											if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
												$dropdown_id = $fmsdata[$stepsdata['_id']]['dropdown_id'];
											}else{
												$dropdown_id='';
											}
											
												$built__opt_Html='';
												$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';											
												foreach($patcherListArr as $key=>$val){
													if($key==$dropdown_id){
														$selc = " selected";
													}else{
														$selc="";
													}
													$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
												}
												
												$built__opt_Html.='</select></td>';
												if (in_array(3, $userRightArr) || userHasRight()){
													echo $built__opt_Html;
												}else{
													echo '<td class="text-center" colspan="2">';
														$dropdown_id = (int) $dropdown_id;
														if(array_key_exists($dropdown_id, $patcherListArr)){
															echo $patcherListArr[$dropdown_id]; 
														}														
													echo '</td>';
												}
											
											
										}else{
											if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
												$dropdown_id = $fmsdata[$stepsdata['_id']]['dropdown_id'];
											}else{
												$dropdown_id='';
											}
											
											
											$built__opt_Html='';
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
											
											foreach($embroidersArr as $key=>$val){
												if($key==$dropdown_id){
													$selc = " selected";
												}else{
													$selc="";
												}
												$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
											}
											
											$built__opt_Html.='</select></td>';
											if (in_array(3, $userRightArr) || userHasRight()){
												echo $built__opt_Html;
											}else{
												echo '<td class="text-center" colspan="2">';
													$dropdown_id = (int) $dropdown_id;
													if(array_key_exists($dropdown_id, $embroidersArr)){
														echo $embroidersArr[$dropdown_id]; 
													}
													
												echo '</td>';
											}
										}
									}
									?> 
								<?php if($stepsdata['fms_when']['fms_when_type']==4 && $stepsdata['fms_when']['input_type']=='notes'){
									
									$saveCmnt='';
									if(in_array(3, $userRightArr) || userHasRight()){
										$saveCmnt=1;
										}else{
											$saveCmnt='';
										}
										
										$buyer_po_link='';
										if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='buyer_po_link'){
											$buyer_po_link = 1;
										}
								?>
										<td class="text-center" colspan="2">
										<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
										<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
										<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
										<?php }else{ ?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray" po_link="{{ $buyer_po_link }}"><strong>Add Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
										<?php }?>
										</td>
										<?php 
										}								
									}
								}
								$kk++;								
							}else{ ?>
								<td class="text-center" colspan="2">
									No Data
								</td>
							<?php } ?>
							@endforeach
							
							<?php
							//pr($userScoreArr); die; 
							/*
							if(!userHasRight()){
								
								if($userScoreArr[$currentUserId]['score']>0){
								echo '<td>-'.round(($userScoreArr[$currentUserId]['score']/$userScoreArr[$currentUserId]['count']),2).'%';
								echo '</td>';
								}else{
									echo '<td></td>';
								}
							}else{
								//pr($userScoreArr);
								foreach($userScoreArr as $theKey=>$theVal){
									//pr($theVal);
									if($theVal['score']>0){
										$theCnt = $theVal['count']>0?$theVal['count']:1;
										$aveRage = round(($theVal['score']/$theCnt),2);
										
										echo '<td>-'.$aveRage.'%';
										//echo '=score=='.$theVal['score'].'=theCnt=='.$theCnt;
										echo '</td>';
										
									}else{
										echo "<td></td>";
									}
								}
							}
							*/
							?>
							
							<?php /*?>
							@if( userHasRight())
							<td class="text-center">
								<input type="hidden" name="any_entry" value="{{ $enyEntry }}" id="enyentry_row{{$row_num}}">
								<a href="javascript:void(0)" class="btn btn-danger" onclick="deleteRow({{$row_num}}, '{{ $fmsdata['_id']}}')">DELETE</a>
								
							</td>
							@endif
							<?php */?>
							
						</tr>
						@endforeach
						<?php 
						
							$taskArr = implode(',', $taskArr);							
							$stepArr = implode(',', $stepArr);							
							$fms_id = $fms['_id'];
							
						?>
						
						
				</tbody>
            </table>	
			<button class="exportToExcel" style="display:none;">Export to XLS</button>			
          </div>
        </div>
        <!-- End Panel Table Add Row -->
		<?php 
				$filter_para = request()->except('page');
			?>
		<div class="nav-div"> {{ $all_data->appends($filter_para)->links() }} </div>
      </div>
    </div>
@endsection


@section('custom_script')
<script type="text/javascript" src="{{ URL::asset('public/html-table-to-excel/jquery.table2excel.js') }}"></script>
    <script>
         $(function() {
			$(".exportToExcelTrigger").click(function(e){
				$(".exportToExcel").trigger('click');
			});
			 
         	$(".exportToExcel").click(function(e){
         		var table = $(this).prev('.table2excel');
         		if(table && table.length){
         			$(table).table2excel({
         				exclude: ".noExl",
         				name: "Po Import",
         				filename: "po-import-<?php echo date('d-M-Y');?>.xls",
         				fileext: ".xls",
         				exclude_img: true,
         				exclude_links: true,
         				exclude_inputs: true,
         				preserveColors: true
         			});
         		}
         	});
         	
         });
		 
$(document).ready(function() {
	$("body").addClass("footer-pagination");    
});
		 
    </script>
@endsection