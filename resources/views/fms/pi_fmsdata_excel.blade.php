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
	$pageTitle= 'Buyer Bulk Report';
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
.search-input{margin-top: 14px; width:150px}
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
			<?php /*?>
			<div class="col-md-2 logo-box">
				<header class="panel-heading">
					<div class="panel-title float-left"> @include('../layouts/logo_client') </div>
				</header>
			</div>
			<?php */?>
        <div class="xls-btn-box float-right">
          <button class="exportToExcelTrigger btn btn-primary waves-effect waves-classic">Export to XLS</button>
        </div>
      </div>
      <div class="panel-body">
        <div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
        <table class="table-bordered table-hover table-striped tbale_header fixed_table table2excel" cellspacing="0" id="fmsdataTable">
          <thead>
            <tr class="tbale_header  top-row">
				<th colspan="<?php echo count($taskdatas);?>" class="text-center font-weight-bold task_left_fix_big purple-bg-top"> 
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
              <?php	echo strip_tags($stepsdata['fms_what']); ?>
                </th>
              <?php 

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
				@endif
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

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
				@endif
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

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
				@endif
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

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
					 {{ __('#') }}{!! $stepsdata['step'] !!}
				@endif
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

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
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

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
              
              
				@if($guest_login=='')
					 {{ __('#') }}{{ $stepsdata['step'] }}
				@endif
              <?php	echo strip_tags($stepsdata['fms_what']).'</th>';

									}else{

									$step_no_cnt++;

								?>
              <th class="text-center font-weight-bold step-title black-bg" colspan="2"> <div class="auth-icon"> @if( userHasRight() )
                  
                  {!! $adminControl !!}
                  
                  @else
                  
                  {!! $staffControl !!}
                  
                  @endif
                  
					@if($guest_login=='')
						{{ __('#') }}{{ $stepsdata['step'] }}
					@endif
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
            <tr class="tbale_header text-center">
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
              <th class="bg-info text-white{{ $left_fix }}{{ $purple }}">{{$taskdata['task_name']}}</th>
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

						

						$pacth='';

						$userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); 

						

						$lab_dip = $fmsdata['lab_dip'];	



						$fob_approval=$fmsdata['fob_sample'];

						

						$bulk_qty=$fmsdata['bulkqty'];

						$ss_qty=$fmsdata['ssqty'];

						

						$fpt=$fmsdata['fpt_testing'];

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

									$payment_term = $fmsdata['payment_term'];

									

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
								<td class="text-center{{ $left_fix }}"> @if($taskdata['field_type']=='po_serial_number') <a href="{{ route('invoiceshow', $pi_id) }}{{ $partshipped_query }}" target="_blank"> {{ $fmsdata['invoice_no'] }}{{ $rev_p }}{{ $rev }}</a> @elseif($taskdata['field_type']=='pi_date')
								  {{ date('Y-m-d', strtotime($fmsdata['pi_date'])) }}
								  @elseif($taskdata['field_type']=='buyer_name')
								  {{ $fmsdata['customer_data']['c_value'] }}
								  @elseif($taskdata['field_type']=='brand_name')
										{{ $fmsdata['brand_data']['b_value'] }}
								  @elseif($taskdata['field_type']=='sales_agent')
								  {{ $fmsdata['s_person_data']['s_value'] }}
								  @elseif($taskdata['field_type']=='item')
								  {{ $fmsdata['item_data']['i_value'] }}
								  @elseif($taskdata['field_type']=='etd')
								  {{ date('Y-m-d', strtotime($fmsdata['etd'])) }}
								  @elseif($taskdata['field_type']=='bulkqty')
								  {{ $bulk_qty }}
								  @elseif($taskdata['field_type']=='ssqty')
								  {{ $ss_qty }}
								  @elseif($taskdata['field_type']=='unit')
								  {{ $fmsdata['unit'] }}
								  @elseif($taskdata['field_type']=='unit_price')
								  {{ $fmsdata['unit_price'] }}
								  @elseif($taskdata['field_type']=='colour')
								  {{ $fmsdata['color'] }}
								  @elseif($taskdata['field_type']=='pi_status_main')
								  {{ $fmsdata['pi_status'] }}
								 @elseif($taskdata['field_type']=='pi_crm')
									{{ $crm_name }}
								 @elseif($taskdata['field_type']=='pi_merchant')
									{{ $merchant_name }}
								@elseif($taskdata['field_type']=='buyer_po_number')
										{{ $fmsdata['buyer_po_number'] }}
								@elseif($taskdata['field_type']=='lab_dip')
										{{ $fmsdata['lab_dip'] }}
								@elseif($taskdata['field_type']=='pi_lab_dip_approval_date')
									{{ changeDateToDmyHi($fmsdata['5e9801ff0f0e750980005e48']['actual']) }}
								@elseif($taskdata['field_type']=='fob_sample')
									{{ $fmsdata['fob_sample'] }}
								@elseif($taskdata['field_type']=='pi_fob_approval_date')
									{{ changeDateToDmyHi($fmsdata['5e9801ff0f0e750980005e49']['actual']) }}
								@elseif($taskdata['field_type']=='pi_ss_submission_date')
									{{ changeDateToDmyHi($fmsdata['5e9801ff0f0e750980005e4a']['actual']) }}
								@elseif($taskdata['field_type']=='fpt_testing')
									{{ $fmsdata['fpt_testing'] }}	
								@elseif($taskdata['field_type']=='pi_fpt_testing_approval_date')
									{{ changeDateToDmyHi($fmsdata['5e9801ff0f0e750980005e4b']['actual']) }}
								@elseif($taskdata['field_type']=='pi_status')
									<div class="fms_stage_row{{ $row_num }}"> 
										@if(array_key_exists($taskdata['_id'], $fmsdata))
											{!! $fmsdata[$taskdata['_id']]['stage_text'] !!}
										@endif 
									</div>
								@else
									{{ $taskdata['task_name'] }}
								@endif </td>
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
							$resolve_query ='';
							$pi_approval='';
							$stock_status='';
							$inv_date_act='';
							$ac_date='';
							$stpId = '';
							$pi_actual_new ='';
							$pi_approval_dropdown_new='';
							$order_status='';
							$pass_order = 0;

							$o_cancelled='';
							$today_main = date('Y-m-d H:i:s');
							?>
            @foreach($stepsdatas as $stepsdata)
            <?php

							/* step-5
							5e8ef2c80f0e75228400661b.dropdown
							22 step 
							5e9a9ecb0f0e750494006a35.dropdownlist */
							
							$o_cancelled='';
							if($fmsdata['5e8ef2c80f0e75228400661b']['dropdown']=="Cancelled" || $fmsdata['5e9a9ecb0f0e750494006a35']['dropdownlist']=="Cancelled"){
								$o_cancelled='Cancelled';
							}
							/* if($stepsdata['step']==5){
								$o_cancelled=$fmsdata['5e8ef2c80f0e75228400661b']['dropdown'];
							}elseif($stepsdata['step']==22){
								$o_cancelled2=$fmsdata['5e9a9ecb0f0e750494006a35']['dropdownlist'];
								if($o_cancelled2=='' && $o_cancelled=='Cancelled'){
									$o_cancelled='Cancelled';
								}else{
									$o_cancelled=$o_cancelled2;
								}
							} */

							$patch='';						
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='pi_approval') {
										$pi_actual_new = $fmsdata[$stepsdata['_id']]['actual'];
										$pi_approval_dropdown_new = $fmsdata[$stepsdata['_id']]['dropdown'];
									}

							

							if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='internal_appr'){
										$internal_appr = $fmsdata[$stepsdata['_id']]['dropdown'];
							}

								
							if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='resolve_query'){
								$resolve_query = $fmsdata[$stepsdata['_id']]['dropdown'];
							}
							
							if(array_key_exists('process',$stepsdata) && $stepsdata['process']=='pi_approval'){
								$pi_approval = $fmsdata[$stepsdata['_id']]['dropdown'];
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

								?>
            <td class="text-center" id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}"> @if(array_key_exists('process',$stepsdata) && $stepsdata['process']!='internal_appr' && $stepsdata['process']!='pass_order_to_bulk')
              
              @if($stepsdata['process']=='lab_dip' && $lab_dip=='Approved')
              
              
              
              @elseif($stepsdata['process']=='lab_dip' && $lab_dip=='Required' && $o_cancelled=='Cancelled')
              
              
              
              @elseif($stepsdata['process']=='fob_approval' && $fob_approval=='Self')
              
              
              
              @elseif($stepsdata['process']=='fob_approval' && $fob_approval=='Buyer' && $o_cancelled=='Cancelled')
              
              
              
              @elseif($stepsdata['process']=='ss_qty' && ($ss_qty==0 || $ss_qty==''))
              
              
              
              @elseif($stepsdata['process']=='ss_qty' && ($ss_qty>0 || $ss_qty!='') && $o_cancelled=='Cancelled')
              
              
              
              @elseif($stepsdata['process']=='fpt' && $fpt=='Not Required')
              
              
              
              @elseif($stepsdata['process']=='fpt' && $fpt=='Required' && $o_cancelled=='Cancelled')
              
              
              
              @elseif($stepsdata['process']=='provide_delivery_date' && $o_cancelled=='Cancelled')
              
              
              
              @elseif($stepsdata['process']=='resolve_query')
              
              {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
              
              @else
              
              {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
              
              @endif
              
              @elseif(array_key_exists('process',$stepsdata) && $stepsdata['process']=='pass_order_to_bulk')
				  @if($o_cancelled!='Cancelled')
						  @if($fmsdata[$stepsdata['_id']]['actual']!='')
						  
						  {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
						  
						  @elseif(($internal_appr=='Approved' || $resolve_query=='Approved') && $pi_approval=='Approved')
						  
						  {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
						  <?php $pass_order=1; ?>
						  @endif
              
					@endif
              @elseif($o_cancelled=='Cancelled')
			  
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

										

								?></td>
            <?php

										$actual='';
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];
										$planed_date=$fmsdata[$stepsdata['_id']]['planed'];

										$bgStyle='';
										
										/* if($actual_date>$planed_date){
											$bgStyle = 'background:'.BG_RED;
										}elseif($actual_date<$planed_date && $actual_date!=''){
											$bgStyle = 'background:'.BG_GREEN;
										} */
										
										if($planed_date!='' && $actual_date>$planed_date){
											$bgStyle = 'background:'.BG_RED;
										}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
											$bgStyle = 'background:'.BG_GREEN;
										}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
											$bgStyle = 'background:'.BG_YELLOW;
										}

										if($stepsdata['fms_when_type']==12){ 
										//if(array_key_exists('comment_dropdown', ))
										// $fmsdata[$stepsdata['_id']]['comment_dropdown']

										?>
            <td style="{{ $bgStyle }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}"> @if($fmsdata[$stepsdata['_id']]['planed']!='') <a href="javascript:void(0)" onclick="dropdownModel('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', '{{ $stepsdata['fms_when']['dropdown'] }}', '{{ $planed_date }}', 'row{{$row_num}}', '{{ $fmsdata[$stepsdata['_id']]['comment_dropdown'] }}','{{ $fmsdata[$stepsdata['_id']]['dropdown'] }}')"> @if($actual_date=='') <span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}"> Select Option </span> @else
              <?php 

														$pi_approval_dropdown = $fmsdata[$stepsdata['_id']]['dropdown']; 

														$pi_actual = $actual_date;

														?>
              <span id="dropdown_{{ $stepsdata['_id'] }}_row{{$row_num}}"> {{ changeDateToDmyHi($actual_date) }} | {{ $fmsdata[$stepsdata['_id']]['dropdown'] }} </span> @endif </a> @endif </td>
            <?php }elseif($stepsdata['fms_when_type']==17){ ?>
            <td style="{{ $bgStyle }}"></td>
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

											}else{
													
													if($o_cancelled!='Cancelled'){
													echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
													}else{
														echo "<td></td>";
													}

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
												
												@if(array_key_exists('process',$stepsdata))
													@if($stepsdata['process']=='lab_dip' && $lab_dip=='Required' && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@elseif($stepsdata['process']=='fob_approval' && $fob_approval=='Buyer' && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@elseif($stepsdata['process']=='ss_qty' && $ss_qty>0 && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@elseif($stepsdata['process']=='fpt' && $fpt=='Required' && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@elseif($stepsdata['process']=='pass_order_to_bulk' && $pass_order==1 && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@elseif($stepsdata['process']=='provide_delivery_date' && $o_cancelled!='Cancelled')
														<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
														?>
													@else
														<?php 
															$planeAlert='';
														?>
													@endif
													
												@else
													<?php 
															if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['planed']!=''){
																if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
																	$planeAlert = 'background:'.BG_YELLOW;
																}
															}
															
															if($o_cancelled=='Cancelled'){
																$planeAlert = '';
															}
														?>
												@endif
												
												
												<td class="text-center" style="{{ $planeAlert }}">
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
												?>
											@if(in_array(2, $userRightArr) || userHasRight())
												@if(array_key_exists('process',$stepsdata))
													@if($stepsdata['process']=='lab_dip' && $lab_dip=='Required' && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='fob_approval' && $fob_approval=='Buyer' && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='ss_qty' && $ss_qty>0 && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@elseif($stepsdata['process']=='fpt' && $fpt=='Required' && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													
													@elseif($stepsdata['process']=='pass_order_to_bulk' && $pass_order==1 && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
														
													@elseif($stepsdata['process']=='provide_delivery_date' && $o_cancelled!='Cancelled')
														<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
													@else
														
													@endif
													
												@elseif($o_cancelled!='Cancelled')
													<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php //echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
												@else
													
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
            <td class="text-center" colspan="2"><a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="">{{ date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['none_date'])) }}</a></td>
            <?php }else{

												?>
            <td class="text-center" colspan="2"> {{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }} </td>
            <?php } ?>
            <?php }else{ ?>
            <td class="text-center" colspan="2"> @if(in_array(2, $userRightArr) || userHasRight())
              <?php if($payment_approval=='COD' && $stepsdata['_id']=='5e9946b00f0e75131c0037f8'){ ?>
              <a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
              <?php }elseif($stepsdata['_id']!='5e9946b00f0e75131c0037f8' ){ ?>
              <?php if(array_key_exists('process', $stepsdata) &&  $stepsdata['process']=='resolve_query') { 

													if($internal_appr=='Rejected'){ ?>
              <a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
              <?php }

											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='fup_collection'){ ?>
              <?php if($o_cancelled!='Cancelled'){ ?>
              <a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openFupCalendar('{{ $fmsdata['_id'] }}','{{ date('d-m-Y') }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" title="">Set FUP</a>
              <?php } ?>
              <?php }else{ ?>
              <a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
              <?php } }?>
              @endif </td>
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
            <td> @if(in_array(2, $userRightArr) || userHasRight()) <a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" patch="" <?php //echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a> @endif </td>
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

										$dropdownVal = explode(',',$stepsdata['fms_when']['dropdownlist']);

										$p_terms = $payment_term;

										$p_terms_arr = explode('_',$p_terms);

										if( array_key_exists('process', $stepsdata) && $stepsdata['process']=='stock_status'){											

												$stock_status = $fmsdata[$stepsdata['_id']]['dropdownlist'];												

												echo $stock_status;												

										}else if($p_terms_arr[0]==0 || strpos($p_terms, 'pdc') !== false){

											

											if($o_cancelled!='Cancelled'){

												

												$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];

												if($dataStepId=='5e9801ff0f0e750980005e4d'){

													$payment_approval=$fmsdata[$dataStepId]['dropdownlist'];

												}

												

												echo $fmsdata[$dataStepId]['dropdownlist'];

												

											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status' && $o_cancelled=='Cancelled'){

												

												$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];

												if($dataStepId=='5e9801ff0f0e750980005e4d'){

													$payment_approval=$fmsdata[$dataStepId]['dropdownlist'];

												}

												

												echo $fmsdata[$dataStepId]['dropdownlist'];

											}

											

										}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status'){											

											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];											

											echo $fmsdata[$dataStepId]['dropdownlist'];

										}else{

											echo "";

										}

										echo "</td>";

										

									}

									

									

									

									// for pl_ac_none value blank

									if($stepsdata['fms_when_type']==14){

										//$stock_status

											

											if(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='issue_po') && $pi_approval_dropdown_new=='Approved' && $stock_status=='Not Available' ){
												echo '<td class="text-center">';
												$dt_pi_plane = date('Y-m-d H:i',strtotime('+1 days',strtotime($pi_actual_new)));
												$dt_pi_plane = checkSunday($dt_pi_plane);
												echo changeDateToDmyHi($dt_pi_plane);
												echo '</td>';
												
												$bgStyle_po='';
												if($today_main>=$dt_pi_plane){
													$bgStyle_po = 'background:'.BG_YELLOW;
												}
												
												?>
											<td class="text-center" style="{{ $bgStyle_po }}"><span> <a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openPiUpdate('{{ $fmsdata['_id'] }}',  '{{ $stepsdata['_id'] }}', '{{ $dt_pi_plane }}','', '', 'row{{ $row_num }}')">PO No.</a> </span></td>
											<?php 

												$currentDropDown = '';
												$pi_approval_dropdown='';
												$pi_actual='';
											}elseif(array_key_exists('process', $stepsdata) && ($stepsdata['process']=='do_collection')){
												$p_terms = $payment_term;
												$p_terms_arr = explode('_',$p_terms);
												if(strpos($p_terms, 'pdc') !== false && $o_cancelled!='Cancelled'){
													$collection_pl='';
													$collection_pl=$fmsdata[$stepsdata['_id']]['planed']; 
													echo '<td class="text-center">';
														echo changeDateToDmyHi($collection_pl);	
													echo '</td>';
													$colle_style='';
													if($collection_pl==''){
														$colle_style =' style="display:none;"';
													}
													
													$planeAlert_p='';
													if(array_key_exists('planed', $fmsdata[$stepsdata['_id']]) && $collection_pl!=''){
														if($today_main>=$fmsdata[$stepsdata['_id']]['planed']){
															$planeAlert_p = 'background:'.BG_YELLOW;
														}
													}
													?>
														<?php if($fmsdata[$stepsdata['_id']]['actual']=='') { ?>
														<td class="text-center" style="{{ $planeAlert_p }}">
															<a href="javascript:void(0)" <?php echo $colle_style; ?> class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$collection_pl}}" data_id="{{$fmsdata['_id']}}" pm_check="" patch=""  process=""> <i class="icon md-time" aria-hidden="true"></i></a>
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
															
															if($o_cancelled!='Cancelled'){
															echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
															}else{
																echo "<td></td>";
															}

														?>
													<?php } ?>
													<?php

												}else{

													echo '<td class="text-center"></td><td class="text-center"></td>';

												}												

												$currentDropDown = '';
												$pi_approval_dropdown='';
												$pi_actual='';
											}else{
												echo '<td class="text-center" id="data_step_pl_'.$stepsdata['_id'].'_row'. $row_num.'"></td>';
												echo '<td class="text-center" id="step_actual_'.$stepsdata['_id'].'_row'. $row_num.'">';
												//check for step8 entry --calendar - new etd 
												if($new_edt!='' && $stepsdata['_id']=='5e9801ff0f0e750980005e47'){
												?>
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{ $row_num }} btn btn-success" id="step_{{ $stepsdata['_id'] }}_row{{ $row_num }}" rel="" data_id="{{ $fmsdata['_id'] }}" pm_check="" patch="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
												<?php }elseif($stepsdata['_id']!='5e9801ff0f0e750980005e47'){ ?>
												<?php	echo '</td>';

												}
											}
									}
									// for calendar
									if($stepsdata['fms_when_type']==15 ){
										echo '<td class="text-center" colspan="2">';										
										$calendar_dt = date('d-m-Y', strtotime($fmsdata['etd']));
										$calendar_main = $fmsdata[$stepsdata['_id']]['calendar'];
										$calendar_main_dt='';
										if($calendar_main!=''){
											$calendar_main_dt = date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['calendar']));
										}
										?>
										@if(in_array(2, $userRightArr) || in_array(3, $userRightArr) || userHasRight())
										
										@if(($fmsdata[$stepsdata['_id']]['calendar'])!='')
										<?php $new_edt = $fmsdata[$stepsdata['_id']]['calendar']; ?>
										<strong>{{  date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['calendar'])) }} | </strong> @endif
										<?php if($o_cancelled!='Cancelled'){ ?>
										<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openCalendar('{{ $fmsdata['_id'] }}','{{ $calendar_dt }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" class="btn btn-success" title="Current ETD: {{ $calendar_dt }}"><i class="icon md-calendar" aria-hidden="true"></i></a>
										<?php } ?>
										@else <strong>{{  $calendar_main_dt }}</strong> @endif
										<?php	echo '</td>';
									}
									if($stepsdata['fms_when_type']==16){
										//pr($stepsdata);
										//pr($fmsdata[$stepsdata['_id']]['act_qty']);
										if($stepsdata['fms_when']['input_type']=='pl_ac_qty_variation'){
											if($o_cancelled=='Cancelled'){ ?>
											<td class="text-center"></td>
											<td class="text-center"></td>
											<?php }else{
													echo '<td class="text-center">';											
														echo $bulk_qty;											
													echo '</td>';
													if($fmsdata[$stepsdata['_id']]['act_qty']!=''){
														$bulk_qty_a=$fmsdata[$stepsdata['_id']]['act_qty'];
													}else{
														$bulk_qty_a=$bulk_qty;
													}
													?>
												<td class="text-center"><span> <a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openBulkQtyUpdate('{{ $fmsdata['_id'] }}','{{ $bulk_qty_a }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')">
												  <?php 
													if($fmsdata[$stepsdata['_id']]['act_qty']==''){ ?>
													Actual | Variation
												  <?php }else{
																echo $fmsdata[$stepsdata['_id']]['act_qty'].' | ';
																$act_qty_bulk = $fmsdata[$stepsdata['_id']]['act_qty'];
																if($bulk_qty>0){
																	$variation_bulk = (($act_qty_bulk-$bulk_qty)/$bulk_qty)*100;
																}else{
																	$variation_bulk=0;
																}
																echo '<strong>Variation: '.number_format($variation_bulk,2).' %</strong>';
															}
															?>
												  </a> </span></td>
												<?php
											}
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
												$posVal='';
												$pos = array_values(getOptionArray('financial_year'));
												if(!empty($pos)){
													$posVal=$pos[0];
												}
												$inv_no_new = 'POLLP/'.$posVal.'/';;
											}else{
												$inv_no_new = $fmsdata[$stepsdata['_id']]['inv_no'];
											}
											?>
											<td class="text-center" colspan="2"><?php if($o_cancelled!='Cancelled'){ ?>
											  <span> <a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}"  onclick="openInvoiceUpdate('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', 'row{{ $row_num }}', '{{ $inv_date_new }}', '{{ $inv_no_new }}', '{{ $fmsdata[$stepsdata['_id']]['inv_amount'] }}', '{{ $payment_term }}')">
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
															echo "Invoice Amount";
														}else{
															echo number_format($fmsdata[$stepsdata['_id']]['inv_amount'],2);
														}
														?>
											  </a> </span>
											  <?php } ?></td>
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
            <td class="text-center" colspan="2"><?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
              {{$fmsdata[$stepsdata['_id']]['comment']}}
              <?php }else{ ?>
              <?php if($o_cancelled!='Cancelled'){ ?>
              <?php } ?>
              <?php }?></td>
            <?php 

										}								

									}

								}

								$kk++;								

							}else{ ?>
            <td class="text-center" colspan="2"> No Data </td>
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

         				filename: "pi-bulk-<?php echo date('d-M-Y');?>.xls",

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