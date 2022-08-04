<?php 
/* check guest user */

$userLoginData = Session::get('userLogin');
$pageTitle= '';
if(!empty($userLoginData)){
	if($userLoginData['login_type']=='buyer_login'){
		$guest_login = $userLoginData['company_name'];
	}else{
		$guest_login = $userLoginData['brand_name'];
	}
	$pageTitle= 'Buyer Sample Yardage Report';
}else{
	$guest_login='';
	$pageTitle= $fms['fms_name'];
}
//pr($userLoginData);
?>
@extends('layouts.fms_admin_layouts')

@section('pageTitle', $pageTitle)

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
				/* $data = getOptionArray('courier_company');
				pr($data); die; */
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
							<th colspan="<?php echo count($taskdatas);?>" class="text-center font-weight-bold task_left_fix_big purple-bg-top" style="left:-448px">
								<span class="fms-name">{{strtoupper($pageTitle)}}</span>
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
								$adminControl = '<a href="javascript:void(0)" class="user_permit user_permit_control" id="'.$s_id.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
								
								$staffControl = '<a href="javascript:void(0)" class="user_permit user_permit_staff" id="'.$s_id.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
							
							if (in_array(1, $userRightArr) || userHasRight()) {
								$input_type1='';
								
								
								if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date' && $stepsdata['fms_when_type']!=14 && $stepsdata['fms_when_type']!=16 && $stepsdata['fms_when_type']!=11))
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
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif
										<?php	echo strip_tags($stepsdata['fms_what']).'</th>';
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
											
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif
										<?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==6)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==15)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif <?php	echo strip_tags($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==17)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										
										@if($guest_login=='')
											{{ __('#') }}{!! $stepsdata['step'] !!}
										@endif
										
										
										<?php	echo strip_tags($stepsdata['fms_what']).'</th>'; ?>
										
									<?php }
									}else if($stepsdata['fms_when_type']==13)
									{
										$searchoption='';
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										@if($guest_login=='')
											{{ __('#') }}{{ $stepsdata['step'] }}
										@endif									
										
										<?php echo strip_tags($stepsdata['fms_what']).'</th>';
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
											@if($guest_login=='')
												{{ __('#') }}{{ $stepsdata['step'] }}
											@endif
											<?php echo strip_tags($stepsdata['fms_what']); ?>
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
									if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date') && $stepsdata['fms_when_type']!=14 && $stepsdata['fms_when_type']!=16 && $stepsdata['fms_when_type']!=11)
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
									<th class="bg-dark text-white">Planned</th>
									<?php $tbl_cell++; ?>
									<th class="bg-secondary text-white">Actual</th>									
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
						//pr($fmsdata);  die('oooo');
						$pi_id=$fmsdata['pi_id'];						
						$userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); 
						
						$lab_dip = $fmsdata['lab_dip'];
						$fob_approval=$fmsdata['fob_sample'];
						$bulk_qty=$fmsdata['bulkqty'];
						$ss_qty=$fmsdata['ssqty'];
						$fpt=$fmsdata['fpt_testing'];;
						$pi_etd = $fmsdata['etd'];
						?>
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
					
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
									$payment_term = $fmsdata['payment_term'];
									$pi_date_main = $fmsdata['pi_date'];									
									
									$rev='';
									$pi_v_data = getPiVersionsById($fmsdata['pi_id']);
									
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
										//$rev = 'R'.$pi_cnt;
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
									
									$crm_name='';
									if(array_key_exists('crm_data', $fmsdata) && !empty($fmsdata['crm_data'])){
										$crm_name=$fmsdata['crm_data']['c_value'];
									}
									
									$merchant_name='';
									if(array_key_exists('merchant_data', $fmsdata) && !empty($fmsdata['merchant_data'])){
										$merchant_name=$fmsdata['merchant_data']['m_value'];
									}
								?>								
								<td class="text-center{{ $left_fix }}">
									
									@if($taskdata['field_type']=='po_serial_number')
										<a href="{{ route('invoiceshow', $pi_id) }}{{ $partshipped_query }}" target="_blank">{{ $fmsdata['invoice_no'] }}{{ $rev_p }}{{ $rev }}</a>
									@elseif($taskdata['field_type']=='pi_date')
										{{ date('Y-m-d', strtotime($fmsdata['pi_date'])) }}
									@elseif($taskdata['field_type']=='buyer_name')
										{{ $fmsdata['customer_data']['c_value'] }}
									@elseif($taskdata['field_type']=='brand_name')
										{{ $fmsdata['brand_data']['b_value'] }}
									@elseif($taskdata['field_type']=='sales_agent')									
										{{ $fmsdata['s_person_data']['s_value']}}
									@elseif($taskdata['field_type']=='item')
										{{ $fmsdata['item_data']['i_value'] }}
									@elseif($taskdata['field_type']=='etd')
										{{ date('Y-m-d', strtotime($fmsdata['etd'])) }}
									@elseif($taskdata['field_type']=='bulkqty')
										{{ $fmsdata['bulkqty'] }}
									@elseif($taskdata['field_type']=='unit')
										{{ $fmsdata['unit'] }}
									@elseif($taskdata['field_type']=='colour')
										{{ $fmsdata['color'] }}
									@elseif($taskdata['field_type']=='pi_status_main')
										{{ $fmsdata['pi_status'] }}
									@elseif($taskdata['field_type']=='pi_crm')
										{{ $crm_name }}
									@elseif($taskdata['field_type']=='pi_merchant')
										{{ $merchant_name }}
									@elseif($taskdata['field_type']=='pi_buyer_po_no')
										{{ $fmsdata['buyer_po_number'] }}
									@elseif($taskdata['field_type']=='lab_dip')
										{{ $fmsdata['lab_dip'] }}
									@elseif($taskdata['field_type']=='fob_sample')
										{{ $fmsdata['fob_sample'] }}
									
									@elseif($taskdata['field_type']=='fpt_testing')
										{{ $fmsdata['fpt_testing'] }}
									
									@elseif($taskdata['field_type']=='pi_status')
										<div class="fms_stage_row{{ $row_num }}">
											@if(array_key_exists($taskdata['_id'], $fmsdata))
												{!! $fmsdata[$taskdata['_id']]['stage_text'] !!}
											@endif
										</div>
									@else
										{{ $taskdata['task_name'] }}
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
							$internal_appr='';
							$stock_status='';
							$inv_date_act='';
							$ac_date='';
							$stpId = '';
							
							$pi_actual_new ='';
							
							/* for pi sampling */
							$sampling_ready = '';
							$inform_merchant = '';
							$today_main = date('Y-m-d H:i:s');
							$o_status = $fmsdata['5eb2ac560f0e750c5c0020eb']['dropdownlist'];
							?>
							@foreach($stepsdatas as $stepsdata)
							<?php
							$patch='';
							$s10_dropdown='';
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='sampling_ready') {
								$sampling_ready = $fmsdata[$stepsdata['_id']]['dropdown'];
							}
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='inform_merchant') {
								$inform_merchant = $fmsdata[$stepsdata['_id']]['dropdown'];
								$inform_merchant_acl = $fmsdata[$stepsdata['_id']]['actual'];
							}
							
							/* for sampling Step7 Start */
							$s7_planned ='';
							$s1_act='';
							$s5_act='';
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='dispatch_goods') {
								$s10_dropdown = $fmsdata['5eb2ac560f0e750c5c0020eb']['dropdownlist'];
								$s1_dropdown = $fmsdata['5eb25b870f0e750c5c0020d2']['dropdown']; 
								$s1_act = $fmsdata['5eb25b870f0e750c5c0020d2']['actual']; 
								$s5_act = $fmsdata['5eb25b870f0e750c5c0020d6']['calendar'];
								$s1_act_plus_1day = $s1_act!=''?strtotime($s1_act."+1 day"):0;
								$main_etd = $pi_etd;
								
								if($s1_dropdown=='Yes'){
									
									if(strtotime($main_etd)>$s1_act_plus_1day){
										$s7_planned = $main_etd;
									}else{
										$s7_planned = $s1_act!=''?date('Y-m-d H:i:s', $s1_act_plus_1day):'';

									}
									
								}else{
									$s7_planned = $s5_act!=''?date('Y-m-d H:i:s', strtotime($s5_act."+1 day")):'';
								}	
							}
							/* for sampling Step7 End */
							
							
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
								
								
							
							
							
							if (strtotime($plane_date)>strtotime(0) && array_key_exists('planed',$fmsdata[$stepsdata['_id']]) && $do_collect!='do_collection' || $stepsdata['fms_when_type']==12 || $stepsdata['fms_when_type']==11 || $stepsdata['fms_when_type']==14)
							{
									$planeAlert='';
									if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='internal_appr'){
										$internal_appr = $fmsdata[$stepsdata['_id']]['dropdown'];
									}
								?>
								<td class="text-center" id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}">
								@if($o_status!='Cancelled')
									@if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='inform_merchant' && ($sampling_ready=='Yes' || $sampling_ready==''))
									
									@elseif(array_key_exists('process',$stepsdata) && $stepsdata['process']=='get_approval' && $inform_merchant=='')
									
									@elseif(array_key_exists('process',$stepsdata) && $stepsdata['process']=='dispatch_goods' && $s10_dropdown=='Cancelled')
										
									@elseif(array_key_exists('process',$stepsdata) && $stepsdata['process']=='dispatch_goods' && $s10_dropdown!='Cancelled')
										 {{ changeDateToDmyHi($s7_planned) }}
									@else
										{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
									@endif
								@endif
								</td>
								<?php
										$actual='';
										
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];
										
										$planed_date=$fmsdata[$stepsdata['_id']]['planed'];
										$bgStyle='';
										if($actual_date>$planed_date){
											$bgStyle = 'background:'.BG_RED;
										}elseif($actual_date<$planed_date && $actual_date!=''){
											$bgStyle = 'background:'.BG_GREEN;
										}
										
										if($stepsdata['fms_when_type']==11){
												if($stepsdata['process']=='get_approval' && $inform_merchant!=''){
													$planeAlert='';
													if($planed_date!='' && $actual_date>$planed_date){
														$planeAlert = 'background:'.BG_RED;
													}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
												
												}
												
												if($o_status=='Cancelled'){
														$planeAlert = '';
													}
										?>
											<td style="{{ $planeAlert }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}">
											@if($o_status!='Cancelled')
											<?php if($stepsdata['process']=='get_approval' && $inform_merchant==''){ ?>
												
											 <?php }else{ ?>
												@if($fmsdata[$stepsdata['_id']]['planed']!='')
													<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}',0, '{{ $pi_date_main }}')">
														
														@if($actual_date=='')
														<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
															Select Option
														</span>
														@else
															<span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}">
																{{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }}
															</span>
														@endif
													</a>
												@endif
											 <?php } ?>
											@endif
											</td>
										<?php }else if($stepsdata['fms_when_type']==12){ 
										
												$planeAlert='';
												if($stepsdata['process']=='sampling_ready'){
													if($planed_date!='' && $actual_date>$planed_date){
														$planeAlert = 'background:'.BG_RED;
													}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
												}elseif($stepsdata['process']=='inform_merchant' && $sampling_ready=='No'){
													if($planed_date!='' && $actual_date>$planed_date){
														$planeAlert = 'background:'.BG_RED;
													}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
												}
												
												if($o_status=='Cancelled'){
													$planeAlert = '';
												}
												
												?>
											<td style="{{ $planeAlert }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}">
												
											@if($o_status!='Cancelled')	
													
												 <?php if($stepsdata['process']=='inform_merchant' && ($sampling_ready=='Yes' || $sampling_ready=='')){ ?>
													
												 <?php }else{ 
													
													$cmntBx=0;
													if($stepsdata['process']=='sampling_ready'){
														$cmntBx=1;
													}
												 ?>
													@if($fmsdata[$stepsdata['_id']]['planed']!='')
														<a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}',{{ $cmntBx }}, '{{ $pi_date_main }}' )">
															
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
												 <?php } ?>
											@endif
												
											</td>
										
										<?php }else if($stepsdata['fms_when_type']==14){ 
												
											if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='dispatch_goods' && $s10_dropdown!='Cancelled'){
													$planeAlert='';
													if($s7_planned!='' && $actual_date>$s7_planned){
														$planeAlert = 'background:'.BG_RED;
													}elseif($s7_planned!='' && $actual_date<$s7_planned && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($s7_planned!='' && $today_main>=$s7_planned && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
											?>
											<td style="{{ $planeAlert }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}">
											@if($o_status!='Cancelled')
												@if($actual_date!='')
													{{ changeDateToDmyHi($actual_date) }}
												@elseif($s7_planned!='')
													@if(in_array(2, $userRightArr) || userHasRight())
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $s7_planned }}" data_id="{{$fmsdata['_id']}}" patch=""> <i class="icon md-time" aria-hidden="true"></i></a>
														@endif
												@endif
												
											@endif
											</td>
											<?php 
												}else{
													echo "<td></td>";
												}
											}elseif($stepsdata['fms_when_type']==17){ ?>
												<td style="{{ $bgStyle }}">
												
												</td>
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
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">';
												if($o_status!='Cancelled'){
												echo '<a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openPiUpdate('.$dtId.', '.$stpId.', '.$planed_date_pi.', '.$actual_date_pi.', '.$pi_number.', '.$row_num_stage.')">'.$dateHtml['date']." | ".$fmsdata[$stepsdata['_id']]['pi_number']."</a>";
												}
												echo "</td>";
											}else{
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">';
												if($o_status!='Cancelled'){
												echo '<a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a>";
												}
												echo "</td>";
											}
											
											
											
											}else{  
											echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">';
											if($o_status!='Cancelled'){
												echo $dateHtml['date'];
												}
											echo "</td>";
											} ?>
										<?php	
											
										}else{
												if($ac_and_dc_date_flag==''){
													$ac_and_dc_date = '';
													$ac_and_dc_date_flag=1;
												}else{
													$ac_and_dc_date = ' style="display:none"';
												}
													
												if($o_status=='Cancelled'){
													$planeAlert = '';
												}
												?>
												<td class="text-center" <?php echo $planeAlert; ?>>
												<?php
												if($o_status!='Cancelled'){
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
														
														?>	
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
															@else
																
															@endif
															
														@else
															<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
														@endif
													@endif
											<?php } ?>
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
											<td class="text-center" colspan="2"> 
											@if($o_status!='Cancelled')
											<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="">{{ date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}</a>
											@endif
											</td>
										
										<?php }else{
												?>
											<td class="text-center" colspan="2"> 
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }}
											</td>
										<?php } ?>
											
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										
										
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
											<td>
												@if($o_status!='Cancelled')
													{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}
												@endif
											</td>
											<td>
											@if($o_status!='Cancelled')
												@if(in_array(2, $userRightArr) || userHasRight())
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
												@endif
											@endif
											</td>
										
										<?php }?>
										
										<?php } ?>
									
									
									<?php 
									if($stepsdata['fms_when_type']==3 && array_key_exists("process",$stepsdata) && $stepsdata['process']=='order_sampling')
									{
										if($inform_merchant=='No' || $inform_merchant=='Alternate' || $inform_merchant==''){ ?>
											<td id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}"></td>
											<td id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}"></td>
										<?php }else{
											$plDt = date('Y-m-d H:i:s', strtotime($inform_merchant_acl . " +24 hours")); ?>
											<td id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}">
												@if($o_status!='Cancelled')
													{{ changeDateToDmyHi($plDt) }}
												@endif
											</td>
											
											<?php 
													$planeAlert='';	
													if($plDt!='' && $today_main>=$plDt){
														$planeAlert = 'background:'.BG_YELLOW;
													}
											?>
										
											<td id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}" style="{{ $planeAlert }}">
											@if($o_status!='Cancelled')
												@if(in_array(2, $userRightArr) || userHasRight())
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $plDt }}" data_id="{{$fmsdata['_id']}}" patch=""> <i class="icon md-time" aria-hidden="true"></i></a>
												@endif
											@endif
											</td>
										<?php 		
										}
									}
									?>
									
									<?php
									/***** for Fabric Comments ****/
									$custom_options='';
									$built_options='';
									
									/* start  chek stepid exit or not  */	
									/* dropdown with plane-actual */
									$dropdownVal='';
									
									if($stepsdata['fms_when_type']==13){
										
										if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='courier_cargo' && $sampling_ready!='No'){
											echo '<td class="text-center" colspan="2"></td>';
										}else{
										echo '<td class="text-center" colspan="2">';
										$custom_opt_Html='';
										$dataId = $fmsdata['_id'];
										$dataStepId = $stepsdata['_id'];
							
										$sId = "'".$dataStepId."'";
										$dtId = "'".$dataId."'"; 
										$row_num_stage = "'row".$row_num."'";
										$dropdownVal = explode(',',$stepsdata['fms_when']['dropdownlist']);
										$p_terms = $payment_term;
										$p_terms_arr = explode('_',$p_terms);
									
												$stock_status = $fmsdata[$stepsdata['_id']]['dropdownlist'];
												
												if($o_status=='Cancelled' && $stepsdata['_id']=='5eb2ac560f0e750c5c0020eb'){
												echo $fmsdata[$stepsdata['_id']]['dropdownlist'];
												}elseif($o_status!='Cancelled'){
													echo $fmsdata[$stepsdata['_id']]['dropdownlist'];
												}
												
										echo "</td>";
										}
										
									}
									
									// for calendar
									if($stepsdata['fms_when_type']==15 ){
										
										if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='inhouse_date' && $sampling_ready!='No'){
											echo '<td class="text-center" colspan="2"></td>';
										}else{
										echo '<td class="text-center" colspan="2">';
										if($o_status!='Cancelled'){
										$calendar_dt = date('d-m-Y', strtotime($fmsdata['etd']));
										?>	
										@if(($fmsdata[$stepsdata['_id']]['calendar'])!='') 
											<?php $new_edt = $fmsdata[$stepsdata['_id']]['calendar']; ?>
											<strong>{{  date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['calendar'])) }} | </strong> 
										@endif
										<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openCalendar('{{ $fmsdata['_id'] }}','{{ $calendar_dt }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" class="btn btn-success" title=""><i class="icon md-calendar" aria-hidden="true"></i></a>
										<?php	
										}
											echo '</td>';
										}
									}
									
									if($stepsdata['fms_when_type']==16){
										//pr($stepsdata);
										//pr($fmsdata[$stepsdata['_id']]['act_qty']);
										if($stepsdata['fms_when']['input_type']=='pl_ac_qty_variation'){
											echo '<td class="text-center">';	
												if($o_status!='Cancelled'){
													echo $bulk_qty;	
												}
											echo '</td>';
											?>
											<td class="text-center">
												@if($o_status!='Cancelled')
												<span>
													<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openBulkQtyUpdate('{{ $fmsdata['_id'] }}','{{ $bulk_qty }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')">
													<?php 
													if($fmsdata[$stepsdata['_id']]['act_qty']==''){ ?>
														Actual | Variation
													<?php }else{
														echo $fmsdata[$stepsdata['_id']]['act_qty'].' | ';
														$act_qty_bulk = $fmsdata[$stepsdata['_id']]['act_qty'];
														
														$variation_bulk = (($act_qty_bulk-$bulk_qty)/$bulk_qty)*100;
														
														echo '<strong>Variation: '.number_format($variation_bulk,2).' %</strong>';
													}
													?>
													</a>
												</span>
												@endif
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
											
											$awb_no='';
											$courier_company='';
											if(!array_key_exists('awb_no',$fmsdata[$stepsdata['_id']]) ||  $fmsdata[$stepsdata['_id']]['awb_no']==''){ 
												$awb_no='';
											}else{
												$awb_no= $fmsdata[$stepsdata['_id']]['awb_no'];
											}
											
											if(!array_key_exists('courier_company',$fmsdata[$stepsdata['_id']]) || $fmsdata[$stepsdata['_id']]['courier_company']==''){ 
												$courier_company='';
											}else{
												$courier_company= $fmsdata[$stepsdata['_id']]['courier_company'];
											}
											
											if($fmsdata[$stepsdata['_id']]['inv_date']!=''){
												$inv_date = date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['inv_date']));
											}else{
												$inv_date = date('d-m-Y');;
											}
											
											?>
											<td class="text-center" colspan="2">
											@if($o_status!='Cancelled')
												<span>
													<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}"  onclick="openInvoiceUpdate('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', 'row{{ $row_num }}', '{{ $inv_date }}', '{{ $fmsdata[$stepsdata['_id']]['inv_no'] }}', '{{ $fmsdata[$stepsdata['_id']]['inv_amount'] }}', '{{ $payment_term }}', '{{ $awb_no }}', '{{ $courier_company }}')">
													<?php 
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
														echo "Invoice Amount | ";
													}else{
														echo number_format($fmsdata[$stepsdata['_id']]['inv_amount'],2).' | ';
													}
													
													if(!array_key_exists('awb_no',$fmsdata[$stepsdata['_id']]) ||  $fmsdata[$stepsdata['_id']]['awb_no']==''){ 
														echo "AWB NO. | ";
													}else{
														echo $fmsdata[$stepsdata['_id']]['awb_no']." | ";
													}
													
													if(!array_key_exists('courier_company',$fmsdata[$stepsdata['_id']]) || $fmsdata[$stepsdata['_id']]['courier_company']==''){ 
														echo "Courier Company";
													}else{
														echo $fmsdata[$stepsdata['_id']]['courier_company'];
													}
													
													?>
													</a>
												</span>
											@endif
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
												$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')">';
												if($o_status!='Cancelled'){
												echo '<option value="">--Select--</option>';											
												foreach($patcherListArr as $key=>$val){
													if($key==$dropdown_id){
														$selc = " selected";
													}else{
														$selc="";
													}
													$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
												}
												
												$built__opt_Html.='</select>';
												}
												echo '</td>';
												if (in_array(3, $userRightArr) || userHasRight()){
													echo $built__opt_Html;
												}else{
													echo '<td class="text-center" colspan="2">';
														if($o_status!='Cancelled'){
															$dropdown_id = (int) $dropdown_id;
															if(array_key_exists($dropdown_id, $patcherListArr)){
																echo $patcherListArr[$dropdown_id]; 
															}														
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
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')">';
											if($o_status!='Cancelled'){
											echo '<option value="">--Select--</option>';
											
											foreach($embroidersArr as $key=>$val){
												if($key==$dropdown_id){
													$selc = " selected";
												}else{
													$selc="";
												}
												$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
											}
											
											$built__opt_Html.='</select>';
											}
											echo '</td>';
											if (in_array(3, $userRightArr) || userHasRight()){
												echo $built__opt_Html;
											}else{
												echo '<td class="text-center" colspan="2">';
													if($o_status!='Cancelled'){
														$dropdown_id = (int) $dropdown_id;
														if(array_key_exists($dropdown_id, $embroidersArr)){
															echo $embroidersArr[$dropdown_id]; 
														}
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
								?>
										<td class="text-center" colspan="2">
										@if($o_status!='Cancelled')
										<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
										<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
										<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
										<?php }else{ ?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray" po_link="{{ $buyer_po_link }}"><strong>Add Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
										<?php }?>
										@endif
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
         				filename: "pi-sampling-<?php echo date('d-M-Y');?>.xls",
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