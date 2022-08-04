@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'MIS '.$fms['fms_name'])

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
	
	$unique_items = get_item_from_fms_data('bulk');
	
	
	/* if(isset($_GET) && array_key_exists('marchant', $_GET) && $_GET['marchant']!=''){
		$misUser = $_GET['marchant'];		
	}else{
		//$misUser = $currentUserId;	merchant_data
		$misUser = getUserIdByName($name);	
	}
	 */
	 $misUser='';
	 if(isset($_GET) && array_key_exists('marchant', $_GET) && $_GET['marchant']!=''){
		$misUser = getUserIdByName($_GET['merchant_data']);			
	}elseif(isset($_GET) && array_key_exists('merchant_data', $_GET) && $_GET['merchant_data']!=''){
		$misUser = getUserIdByName($_GET['merchant_data']);
	}
	$u_array = getCurrentUserMis($misUser);
	
	//$mis_sum_bulk = DB::table('mis_bulk')->sum($currentUserId);
	//pr($u_array); die('==blade==');
?>

<div class="page no-border" id="fms_data">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
          <div class="panel-body">
			<div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
			<form method="get">
			<input type="hidden" name="search" value="s">
            <table class="table table-bordered table-hover table-striped tbale_header fixed_table" cellspacing="0" id="fmsdataTable">
				<thead>
						<tr class="tbale_header task_top_fix top-row">
							<th colspan="<?php echo count($taskdatas);?>" class="text-center font-weight-bold task_left_fix_big purple-bg-top" style="left: -1448px;">
								<span class="fms-name">
									{{$fms['fms_name']}}
								</span>
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
								
								$fms_what_new='';
								$fms_how_new='';
								$fms_when_new='';
								$temlate_url='';
								if(array_key_exists('fms_what',$stepsdata)){
									$fms_what_new=str_replace('<br>', "\n", $stepsdata['fms_what']);
									$fms_what_new=str_replace('<br/>', "\n", $fms_what_new);
								}
								
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
								$adminControl = '<a href="javascript:void(0)" class="user_permit user_permit_control" id="'.$s_id.'" what="'.$fms_what_new.'" how="'.$fms_how_new.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
								
								$staffControl = '<a href="javascript:void(0)" class="user_permit user_permit_staff" id="'.$s_id.'" what="'.$fms_what_new.'" how="'.$fms_how_new.'" when="'.$fms_when_new.'" temlate_url="'.$temlate_url.'"> <i class="icon md-more" aria-hidden="true" ></i></a>';
							
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
										{{ __('#') }}{{ $stepsdata['step'] }} 
										<?php 
										$searchoption='';
										if($stepsdata['step']==7){
											$dropdownArr = array("Blank", "Filled");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'" ><option value="">--Select--</option>';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && ($r ==$_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}

											$searchoption .='</select>';
										}
										?>
										<?php	echo nl2br($stepsdata['fms_what']).$searchoption.'</th>';
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
										{{ __('#') }}{{ $stepsdata['step'] }} 
										<?php 
										$searchoption='';
										if($stepsdata['step']==19){
											$dropdownArr = array("Blank", "Filled");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'" ><option value="">--Select--</option>';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && ($r ==$_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	

											$searchoption .='</select>';
										}
										?>
										
										<?php	echo nl2br($stepsdata['fms_what']).$searchoption; ?>
										
										<?php 
										if($stepsdata['step']==25){ ?>
											<div class="between-date">
											<div class="date-child"><span>From:</span> <input type="text" name="fup_from" autocomplete="off" value="<?php if(isset($_GET['fup_from'])){echo $_GET['fup_from'];}?>" class="form-control date_with_dmy"></div>
											<div class="date-child"><span>To:</span> <input type="text" name="fup_to" autocomplete="off" value="<?php if(isset($_GET['fup_to'])){echo $_GET['fup_to'];}?>" class="form-control date_with_dmy"></div>
											</div>
										<?php } ?>
										
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
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo nl2br($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo nl2br($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo nl2br($stepsdata['fms_what']).'</th>';
									}else if($fms_when_type==15)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
											 {!! $adminControl !!}
											@else
												{!! $staffControl !!}
											@endif
										{{ __('#') }}{!! $stepsdata['step'] !!} <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										if($stepsdata['step']==20){
											$dropdownArr = array("Blank", "Filled");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'" ><option value="">--Select--</option>';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && ($r ==$_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	

											$searchoption .='</select>';
										}
										?>
										
										<?php	echo nl2br($stepsdata['fms_what']).$searchoption.'</th>';
									}
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
										{{ __('#') }}{{ $stepsdata['step'] }} 
										<?php 
										if($stepsdata['step']==4){
											$dropdownArr = array("Blank","Ready Stock", "In Transit", "Not Available");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'" ><option value="">--Select--</option>';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && ($r ==$_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	

											$searchoption .='</select>';
										}
										if($stepsdata['step']==15){
											$dropdownArr = array("Blank","Advance Received", "COD", "PDC","FUP");
											$searchoption = '<br/><select id="payment-approval-select" multiple="multiple" name="'.$stepsdata['_id'].'[]" >';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && in_array($r, $_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	
											$searchoption .='</select>';
										}
										
										
										if($stepsdata['step']==22){
											$dropdownArr = array("Blank","Closed", "Part Shipped", "Pending","Cancelled");
											$searchoption = '<br/><select id="order-staus-select" multiple="multiple" name="'.$stepsdata['_id'].'[]" >';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) &&  in_array($r, $_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	
											$searchoption .='</select>';
										}
										
										?>
										
										<?php	echo nl2br($stepsdata['fms_what']).$searchoption.'</th>';
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
											{!! nl2br($stepsdata['fms_what']) !!}
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
						<tr class="tbale_header text-center  task_top_fix">
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
								
								<?php if('5e79feefd049043d090fdbb8' == $taskdata['_id']){ ?>
								<div class="article-box">
								{{$taskdata['task_name']}}
								<br/>
								<select id="item-select" multiple="multiple" name="item_data[]" >
									
									@foreach($unique_items as $ikey=>$i_item)
										<option value="{{ $i_item['item_data']['i_value'] }}" <?php if(isset($_GET['item_data']) &&  in_array($i_item['item_data']['i_value'], $_GET['item_data'])) { echo ' selected';} ?>>{{ $i_item['item_data']['i_value'] }}</option>
									@endforeach
									
								</select>
								
								<?php 
								$item_hidden='';
								if(isset($_GET['item_data']) && !empty($_GET['item_data'])){
									$item_hidden=implode(',',$_GET['item_data']);
								} 
								?> 
								
								<input type="hidden" name="" id="item_hidden" value="{{ $item_hidden }}">
								</div>
								<?php }else{ ?>
											{{$taskdata['task_name']}}
											
											<?php if('5e79feefd049043d090fdbb4' == $taskdata['_id']){?>
												<div class="between-date">
												<div class="date-child"><span>From:</span> <input type="text" name="pi_from" autocomplete="off" value="<?php if(isset($_GET['pi_from'])){echo $_GET['pi_from'];}?>" class="form-control date_with_dmy"></div>
												<div class="date-child"><span>To:</span> <input type="text" name="pi_to" autocomplete="off" value="<?php if(isset($_GET['pi_to'])){echo $_GET['pi_to'];}?>" class="form-control date_with_dmy"></div>
												</div>
												
											<?php } ?>
											
											<?php if('5e79feefd049043d090fdbb3' == $taskdata['_id']){?>
											<br/><input type="text" name="invoice_no" value="<?php if(isset($_GET['invoice_no'])){echo $_GET['invoice_no'];}?>" class=" form-control" id="autocomplete-dynamic" />
											<?php } ?>
											
											<?php if('5e79feefd049043d090fdbb5' == $taskdata['_id']){?>
											<br/><input type="text" name="customer_data" value="<?php if(isset($_GET['customer_data'])){echo $_GET['customer_data'];}?>" class=" form-control" id="customer_data" />
											<?php } ?>
											
											
											<?php if('5e79feefd049043d090fdbb9' == $taskdata['_id']){?>
											<br/><input type="text" name="color" value="<?php if(isset($_GET['color'])){echo $_GET['color'];}?>" class=" form-control" id="color" />
											<?php } ?>
											
											<?php if('5e79feefd049043d090fdbbf' == $taskdata['_id']){?>
											<br/><input type="text" name="s_person_data" value="<?php if(isset($_GET['s_person_data'])){echo $_GET['s_person_data'];}?>" class=" form-control" id="s_person_data" />
											<?php } ?>
											
											<?php if('5fa8d0d6f271f11b0cbf698e' == $taskdata['_id']){?>
											<br/><input type="text" name="crm_data" value="<?php if(isset($_GET['crm_data'])){echo $_GET['crm_data'];}?>" class=" form-control" id="crm_data" />
											<?php } ?>
											
											<?php if('5fa8d10ef271f11b0cbf698f' == $taskdata['_id']){
														if( userHasRight() ){
															$m_input='';
														}else{
															$m_input=' readonly';
														}
												?>
												
											<br/><input type="text" name="merchant_data" {{ $m_input }} value="<?php if(isset($_GET['merchant_data'])){echo $_GET['merchant_data'];}?>" class=" form-control" id="merchant_data" />
											
											
											<?php } ?>
											
											<?php if('5ee0875b580c60213c891ef1' == $taskdata['_id']){ 
											$dropdownArr = array("Confirmed", "Unconfirmed");
											$searchoption = '<br/><select name="pi_status" ><option value="">--Select--</option>';

											foreach ($dropdownArr as $r){
												if(isset($_GET['pi_status']) && ($r ==$_GET['pi_status'])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	
											$searchoption .='</select>'; 
											echo $searchoption;
											}
								
									}
								?>
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
									<th class="bg-dark text-white">Planned</th>
									<?php $tbl_cell++; ?>
									<th class="bg-secondary text-white">Actual
									<?php 
									$searchval = '';
									if(isset($_GET[$stepsdata['_id']]) && !empty($_GET[$stepsdata['_id']])){
										$searchval = $_GET[$stepsdata['_id']];
									}?>
									<br/><select name="<?php echo $stepsdata['_id'];?>" ><option value="">--Select--</option><option value="Blank" <?php if($searchval=='Blank'){echo ' selected=""';}?>>Blank</option><option value="Filled" <?php if($searchval=='Filled'){echo ' selected=""';}?>>Filled</option></select>
									</th>
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
						
						$f_d = $fmsdata['_id'];
						$f_oid = (array) $f_d;						
						$f_did = $f_oid['oid'];
						

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
										<a href="{{ route('invoiceshow', $pi_id) }}{{ $partshipped_query }}" target="_blank"> {{ $fmsdata['invoice_no'] }}{{ $rev_p }}{{ $rev }}</a>
										
									@elseif($taskdata['field_type']=='pi_date')
										{{ changeDateToDmyHi($fmsdata['pi_date']) }}
									@elseif($taskdata['field_type']=='buyer_name')
										{{ $fmsdata['customer_data']['c_value'] }}
									@elseif($taskdata['field_type']=='sales_agent')									
										{{ $fmsdata['s_person_data']['s_value'] }}
									@elseif($taskdata['field_type']=='item')
										{{ $fmsdata['item_data']['i_value'] }}
									@elseif($taskdata['field_type']=='etd')
										{{ date('d.m.y', strtotime($fmsdata['etd'])) }}
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
							
							$pmChk = '';
							$process='';
							$patch='';
							if($stepsdata['step']==5){
								$o_cancelled=$fmsdata['5e8ef2c80f0e75228400661b']['dropdown'];
							}elseif($stepsdata['step']==22){
								$o_cancelled2=$fmsdata['5e9a9ecb0f0e750494006a35']['dropdownlist'];
								if($o_cancelled2=='' && $o_cancelled=='Cancelled'){
									$o_cancelled='Cancelled';
								}else{
									$o_cancelled=$o_cancelled2;
								}
							}
							
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
								<td class="text-center" id="data_step_pl_{{ $stepsdata['_id'] }}_row{{$row_num}}">
								@if(array_key_exists('process',$stepsdata) && $stepsdata['process']!='internal_appr' && $stepsdata['process']!='pass_order_to_bulk')
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
										
										if($planed_date!='' && $actual_date>$planed_date){
											$bgStyle = 'background:'.BG_RED;
										}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
											$bgStyle = 'background:'.BG_GREEN;
										}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
											$bgStyle = 'background:'.BG_YELLOW;
										}
										
										if($stepsdata['fms_when_type']==12){
										?>
											<td style="{{ $bgStyle }}" id="step_actual_{{ $stepsdata['_id'] }}_row{{$row_num}}">
											@if($fmsdata[$stepsdata['_id']]['planed']!='')
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
															<?php 
																/* if(array_key_exists( $f_did , $u_array)){
																	if(array_key_exists($stepsdata['_id'], $u_array[$f_did])){
																		echo " (". $u_array[$f_did][$stepsdata['_id']] .")";
																	}											
																} */
															?>
														</span>
													@endif
												</a>
											@endif
											
											</td>
										<?php }elseif($stepsdata['fms_when_type']==17){ ?>
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
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openPiUpdate('.$dtId.', '.$stpId.', '.$planed_date_pi.', '.$actual_date_pi.', '.$pi_number.', '.$row_num_stage.')">'.$dateHtml['date']." | ".$fmsdata[$stepsdata['_id']]['pi_number']."</a></td>";
											}else{
												echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a>";
												
												/* if(array_key_exists( $f_did , $u_array)){
													if(array_key_exists($stepsdata['_id'], $u_array[$f_did])){
														echo " (". $u_array[$f_did][$stepsdata['_id']] .")";
													}											
												} */
												
												echo "</td>";
											}
											
											}else{  
											echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">'.$dateHtml['date'];
											
											if(array_key_exists( $f_did , $u_array)){
												if(array_key_exists($stepsdata['_id'], $u_array[$f_did])){
													echo " (". $u_array[$f_did][$stepsdata['_id']] .")";
												}											
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
										$dropdownVal = explode(',',$stepsdata['fms_when']['dropdownlist']);
										$p_terms = $payment_term;
										$p_terms_arr = explode('_',$p_terms);
										if( array_key_exists('process', $stepsdata) && $stepsdata['process']=='stock_status'){
												$stock_status = $fmsdata[$stepsdata['_id']]['dropdownlist'];
												
												$custom_opt_Html.='<select data_id="drgdsfgd" id="row_'.$dataId.'" class="form-control" onchange="saveDropdown(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
												foreach($dropdownVal as $custom_opt_row){
												if($custom_opt_row==$fmsdata[$stepsdata['_id']]['dropdownlist']){
													$selc = " selected";
													}else{
														$selc="";
													}
													//$selc="";
													
													$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
												}										
												$custom_opt_Html.='</select>';
												echo $custom_opt_Html;
												
										}else if($p_terms_arr[0]==0 || strpos($p_terms, 'pdc') !== false){
											
											if($o_cancelled!='Cancelled'){
												$custom_opt_Html.='<select data_id="drgdsfgd" id="row_'.$dataId.'" class="form-control" onchange="saveDropdown(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
												
												$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
												//pr($currentDropDown);
												
												if($dataStepId=='5e9801ff0f0e750980005e4d'){
													$payment_approval=$fmsdata[$dataStepId]['dropdownlist'];
												}										
												foreach($dropdownVal as $custom_opt_row){
													if($custom_opt_row==$fmsdata[$dataStepId]['dropdownlist']){
														$selc = " selected";
													}else{
														$selc="";
													}
													//$selc="";
													
													$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
												}										
												$custom_opt_Html.='</select>';
												echo $custom_opt_Html;
											}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status' && $o_cancelled=='Cancelled'){
												$custom_opt_Html.='<select data_id="drgdsfgd" id="row_'.$dataId.'" class="form-control" onchange="saveDropdown(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
												
												$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
												//pr($currentDropDown);
												
												if($dataStepId=='5e9801ff0f0e750980005e4d'){
													$payment_approval=$fmsdata[$dataStepId]['dropdownlist'];
												}										
												foreach($dropdownVal as $custom_opt_row){
													if($custom_opt_row==$fmsdata[$dataStepId]['dropdownlist']){
														$selc = " selected";
													}else{
														$selc="";
													}
													//$selc="";
													
													$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
												}										
												$custom_opt_Html.='</select>';
												echo $custom_opt_Html;
											}
											
										}elseif(array_key_exists('process', $stepsdata) && $stepsdata['process']=='order_status'){
											$custom_opt_Html.='<select data_id="drgdsfgd" id="row_'.$dataId.'" class="form-control" onchange="saveDropdown(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
											
											$currentDropDown = $fmsdata[$dataStepId]['dropdownlist'];
											//pr($currentDropDown);					
											foreach($dropdownVal as $custom_opt_row){
												if($custom_opt_row==$fmsdata[$dataStepId]['dropdownlist']){
													$selc = " selected";
												}else{
													$selc="";
												}
													$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
												}										
											$custom_opt_Html.='</select>';
											echo $custom_opt_Html;
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
												
												echo changeDateToDmyHi($dt_pi_plane);
												
												echo '</td>';
												$bgStyle_po='';
												if($today_main>=$dt_pi_plane){
													$bgStyle_po = 'background:'.BG_YELLOW;
												}
										
												?>
												<td class="text-center" style="{{ $bgStyle_po }}">
													<span>
														<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openPiUpdate('{{ $fmsdata['_id'] }}',  '{{ $stepsdata['_id'] }}', '{{ $dt_pi_plane }}','', '', 'row{{ $row_num }}')">PO No.</a>
													</span>
												
												</td>
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
														<a href="javascript:void(0)" <?php echo $colle_style; ?> class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$collection_pl}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
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
															
															echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a>";
															
															/* if(array_key_exists( $f_did , $u_array)){
																if(array_key_exists($stepsdata['_id'], $u_array[$f_did])){
																	echo " (". $u_array[$f_did][$stepsdata['_id']] .")";
																}											
															} */
												
															echo "</td>";
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
												<strong>{{  date('d.m.y', strtotime($fmsdata[$stepsdata['_id']]['calendar'])) }} | </strong> 
											@endif
											
											<?php if($o_cancelled!='Cancelled'){ ?>
											<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}" onclick="openCalendar('{{ $fmsdata['_id'] }}','{{ $calendar_dt }}', '{{ $stepsdata['_id'] }}', 'row{{ $row_num }}')" class="btn btn-success" title="Current ETD: {{ $calendar_dt }}"><i class="icon md-calendar" aria-hidden="true"></i></a>
											<?php } ?>
											
										@else
											<strong>{{  $calendar_main_dt }}</strong>
										@endif
										
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
																	$variation_bulk=0;
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
									}
									
									if($stepsdata['fms_when_type']==17){
										//pr($stepsdata);
										//pr($fmsdata[$stepsdata['_id']]['act_qty']);
										if($stepsdata['fms_when']['input_type']=='inv_details'){
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
											<td class="text-center" colspan="2">
												<?php if($o_cancelled!='Cancelled'){ ?>
													<span>
														<a href="javascript:void(0)" id="{{ $stepsdata['_id'] }}_row{{ $row_num }}"  onclick="openInvoiceUpdate('{{ $fmsdata['_id'] }}','{{ $stepsdata['_id'] }}', 'row{{ $row_num }}', '{{ $inv_date_new }}', '{{ $inv_no_new }}', '{{ $fmsdata[$stepsdata['_id']]['inv_amount'] }}', '{{ $payment_term }}')">
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
														</a>
													</span>
												<?php } ?>
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
										<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong> <span style="color:#068e06;">Read Comment</span></strong></a>
										<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
										<?php }else{ ?>
											
											<?php if($o_cancelled!='Cancelled'){ ?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}', '{{ $buyer_po_link }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray" po_link="{{ $buyer_po_link }}"><strong>Add Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
											<?php } ?>
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
			<?php /*?>
			<div class="from-filter">
					<input type="submit" value="Search" class="filter btn-info  waves-effect waves-classic">
					<a href="{{ route('misdata', $fms_id) }}" class="btn btn-info" role="button">Reset</a>
			</div>  
			<?php */?>
			</form>
          </div>
        </div>
        <!-- End Panel Table Add Row -->
		<?php 
			$filter_para = request()->except('page');
		?>
		<div class="nav-div"> {{ $all_data->appends($filter_para)->links() }} </div>
      </div>
    </div>
	
@if( userHasRight() )
<!-- model start -->
<!-- Modal -->
<button data-toggle="modal" data-target="#user_auth_model" id="model_open" style="display:none;"></button>
  <div class="modal fade modal-info user-list-box" id="user_auth_model" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title">Step Details</h4>
		</div>
        <div class="modal-body">
			<div id="user_form">
			<form id="user_permit_form" onsubmit="event.preventDefault(); stepUserPermitSubmit()">	
			WHAT: <textarea class="form-control" name="fms_what_new" id="fms_what_new" rows="2"></textarea>
			<hr/>
			HOW: <textarea class="form-control" name="fms_how_new" id="fms_how_new" rows="2"></textarea>
			<hr/>
			WHEN: <textarea class="form-control" name="fms_when_new" id="fms_when_new" rows="2"></textarea>
			<hr/>
			WHO:
			<ul>
					<li class="list-inline-item">
                      <div class="checkbox-custom checkbox-success">
                        <input type="checkbox" name="user_select" value="all" class="user_list">
                        <label>Select All</label>
                      </div>
                    </li>
					<li class="list-inline-item">
                      <div class="checkbox-custom checkbox-success">
                        <input type="checkbox" name="user_select" value="none_all" class="user_list">
                        <label>Unselect All</label>
                      </div>
                    </li>
			</ul>
			<hr>
			
			<?php 
					$user_form_html = '<ul style="list-style:none;">
												<input type="hidden" name="_token" value="'.csrf_token().'">
												<input type="hidden" name="step_id" value="" id="user_step_id">';
												foreach(getUserList($fms_id) as $user)
												{
												$user_form_html.='<li>
																	<div class="checkbox-custom checkbox-success">
																		<input type="checkbox" name="user_id[]" value="'.$user['_id'].'" class="user_id_list" id="user_'.$user['_id'].'" onclick="user_id_list(this.value)">
																		<label>'.$user['name'].'</label>
																	</div>
																	<ul style="list-style:none; display:inline;">';
																	$user_form_html.='<li class="list-inline-item">
																			<div class="checkbox-custom checkbox-success">
																				<input type="checkbox" name="'.$user['_id'].'[]" value="1" class="user_id_list '.$user['_id'].'" rel="'.$user['_id'].'" onclick="permission_grant(this.id, this.value)" id="authV_'.$user['_id'].'">
																				<label>View</label>
																			</div>
																		</li>
																		
																		<li class="list-inline-item">
																			<div class="checkbox-custom checkbox-success">
																				<input type="checkbox" name="'.$user['_id'].'[]" value="2" class="user_id_list '.$user['_id'].'"  rel="'.$user['_id'].'" onclick="permission_grant(this.id)" id="authTS_'.$user['_id'].'">
																				<label>Timestamp</label>
																			</div>
																		</li>
																		
																		<li class="list-inline-item">
																			<div class="checkbox-custom checkbox-success">
																				<input type="checkbox" name="'.$user['_id'].'[]" value="3" class="user_id_list '.$user['_id'].'"  rel="'.$user['_id'].'" onclick="permission_grant(this.id)" id="authE_'.$user['_id'].'">
																				<label>Edit</label>
																			</div>
																		</li>
																	</ul>
																</li>';
												}
								$user_form_html.='<br/>Template URL: <input type="text" name="temlate_url" id="temlate_url" style="width:100%;"><br/>';
								//$user_form_html.='<br/><button type="submit" name="user_permit" class="btn btn-primary block">Save</button>';
								$user_form_html.='<div id="user_update"></div>';
					$user_form_html.='</ul>';
			$user_form_html.='</form>';
			echo $user_form_html;
			?>
			</div>
			
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- Modal content end -->
    </div>
  </div>
<!-- model end -->
@endif


@endsection

@section('custom_script')
<script>
$(document).ready(function(){
  $('body').addClass('footer-pagination');
  $("body").addClass("hasform-filter");
});
</script>

<script>
	$('#check_completed').on('click', function(){
		var chk_cond = $('#check_completed').prop('checked');
		if(chk_cond){
			window.location.href='<?php echo route("completed_order", $fms_id);?>?status=yes&c_url={{url()->current()}}';
		}else{
			window.location.href='<?php echo route("completed_order", $fms_id);?>?status=no&c_url={{url()->current()}}';
		}
	}) 

	
	function openDeteTimeModel(dtId, ac_date, stpId, row_num_stage){
		$('#currentDateTime').val('');
		$('#currentDateTime').val(ac_date);
		$('#actual_data_id').val(dtId);
		$('#actual_step_id').val(stpId);
		$('#actual_id_with_row').val(row_num_stage);
		$('#change_date_time_model_open').trigger('click');
	}
	
	function openDeteTimeModelOrder(dtId, ac_date, taskId, row_num){
		
		var chkAnyEntry = $('#enyentry_'+row_num).val();
		if(chkAnyEntry>0){
			alert("You can not update date for this order, step entry already done.");
			return false;
		}
		
		$('#actual_order_no').val('');
		$('#actual_order_text').html('');
		var order_no = Number($('#order_'+row_num).text());
		$('#currentOrderDate').val('');
		$('#currentOrderDate').val(ac_date);
		$('#actual_data_id_order').val(dtId);
		$('#actual_task_id_order').val(taskId);
		$('#actual_id_with_row_order').val(row_num); 
		$('#actual_order_no').val(order_no); 
		$('#actual_order_text').html(''); 
		$('#actual_order_text').html('('+order_no+')'); 
		$('.data_update_order').html('');
		var item_no = $('#order_item_'+order_no).val();
		$('#actual_item_no').val(item_no);
		
		var order_qty = $('#order_qty_'+order_no).val();
		$('#actual_order_qty').val(order_qty);
		
		$('#change_date_time_model_open_order').trigger('click');
	}	
</script>

<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-sliderAccess.js"></script>

<script>
	$('body').on('focus', ".date_with_time", function() {
		$('.date_with_time').datetimepicker({
			dateFormat: 'dd-mm-yy',
			timeFormat: 'HH:mm',
		});
	});
	
	$('body').on('focus', ".date_with_dmy", function() {
		$('.date_with_dmy').datepicker({
			dateFormat: 'dd-mm-yy'
		});
	});
</script>
<script>
function stepUserPermitSubmit()
{
	
	var formData = $('#user_permit_form').serialize();
	var ajax_url = "<?php echo route('ajax_stepUserPermitSubmit'); ?>";
	$.ajax({
			method:'POST',
			url:ajax_url,
			data:formData,
			success:function(result){
					$('#user_update').html('');
					if(result=='success')
					{
						$('#user_update').html('<p class="alert text-success text-center">Data added successfully.</p>');
					}else if(result=='failed')
					{
						$('#user_update').html('<p class="alert text-danger text-center">Please try again!</p>');
					}
					return false;
					},
			error:function(result)
					{
						console.log(result);
						return false;
					}
		});
}

$('document').ready(function() {
  $('.user_permit_control').click(function() {
	  //debugger;
	  var step_id = $(this).attr('id');
	  
	  var fms_what_new = $(this).attr('what');
	  var fms_how_new = $(this).attr('how');
	  var fms_when_new = $(this).attr('when');
	  var temlate_url = $(this).attr('temlate_url');
	  
	   $('#fms_what_new').val(fms_what_new);
	   $('#fms_how_new').val(fms_how_new);
	    $('#fms_when_new').val(fms_when_new);
	    $('#temlate_url').val(temlate_url);
	  
	  $('#user_step_id').val('');
	  $('#user_step_id').val(step_id);
	  $('#user_update').html('');
	var ajax_url = "<?php echo route('ajax_stepUserPermitModel'); ?>";
	$.ajax({
		method:'GET',
		url:ajax_url,
		data:{step_id:step_id, fms_id:'{{$fms_id}}'},
		success:function(result){
				var jsonData = result;
				//var userId = jsonData.length;
				//console.log(jsonData);
				var arKey = Object.keys(jsonData);
				
				$('.user_id_list').prop('checked', false);
				
				for(var i=0; i<arKey.length; i++)
				{
					for(var j=0; j<(jsonData[arKey[i]]).length; j++)
					{
						//console.log('userId='+arKey[i]+'=='+jsonData[arKey[i]][j])
						if(jsonData[arKey[i]][j]==1)
						{
							$('#user_'+arKey[i]).prop('checked', true);
							$('#authV_'+arKey[i]).prop('checked', true);
						}
						if(jsonData[arKey[i]][j]==2)
						{
							$('#user_'+arKey[i]).prop('checked', true);
							$('#authTS_'+arKey[i]).prop('checked', true);
						}
						if(jsonData[arKey[i]][j]==3)
						{
							$('#user_'+arKey[i]).prop('checked', true);
							$('#authE_'+arKey[i]).prop('checked', true);
						}
							
					}
				} 
				return false;
				},
		error:function(result)
				{
					//console.log(result);
					return false;
				}
	});
	
	$('#model_open').trigger('click');
	
	
  });
  
  $(".user_list").on('click', function() {
		var boxVal = $(this).val();
		var $box = $(this);
		if ($box.is(":checked")) {
			var group = "input:checkbox[name='user_select']";
			$(group).prop("checked", false);
			$box.prop("checked", true);
		}
	  
		if(boxVal=='none_all')
		{
			$('.user_id_list').prop('checked', false);
		}else if(boxVal=='all')
		{
			$('.user_id_list').prop('checked', true);
		}
  
});
});


function permission_grant(userId)
{
	//debugger;
	var userId = userId.split('_');
	var chkName = userId[1];
	//alert(userId)
	var isChk_length = $('[name="'+chkName+'[]"]:checked').length;
	var isChecked = $(this).prop('checked');
	if(isChk_length==0)
	{
		$('#user_'+chkName).prop('checked', false);
	}else{
		$('#user_'+chkName).prop('checked', true);
	}
}

function user_id_list(thisVal)
{
	var thisVal = thisVal;
	var isChk = $('#user_'+thisVal).prop('checked');
	if(isChk)
	{
		$('.'+thisVal).prop('checked', true);
	}else{
		$('.'+thisVal).prop('checked', false);
	}
}	
</script>
<script>
$(document).ready(function() {
$("table.fixed_table tr .task_left_fix").each(function() {
 var x = $(this).offset();
 /*alert("Top position: " + x.top + " Left position: " + x.left);
   console.log(x.left + "px" + " " + x.top + "px");*/
 //$(this).css({ "left": x.left - 865 + "px"});
 $(this).css({ "left": x.left - 65 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});

$("table.fixed_table tr.task_top_fix th").each(function() {
 var y = $(this).offset();
 /*alert("Top position: " + x.top + " Left position: " + x.left);
   console.log(x.left + "px" + " " + x.top + "px");*/
 //$(this).css({ "top": y.top - 123 + "px"});
 $(this).css({ "top": y.top - 0 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});
});



	$('.user_permit_staff').click(function() {
		//debugger;
		var step_id = $(this).attr('id');
		var fms_what_new = $(this).attr('what');
		var fms_how_new = $(this).attr('how');
		var fms_when_new = $(this).attr('when');
		var temlate_url = $(this).attr('temlate_url');

		$('#fms_what_new_staff').val(fms_what_new);
		$('#fms_how_new_staff').val(fms_how_new);
		$('#fms_when_new_staff').val(fms_when_new);
		//$('#temlate_url_staff').val(temlate_url);	
		
		if(temlate_url!=''){
			$('#temlate_url_staff').html('Temlate URL: <a href="'+temlate_url+'" target="_blank">Click to open Template</a>');	
		}else{
			$('#temlate_url_staff').html('');
		}
		
			
		
		$('#staff_model_open').trigger('click');
	});
	
</script>


<script type="text/javascript" src="{{ URL::asset('public/multiselect/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#order-staus-select').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		
		$('#payment-approval-select').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		
		$('#item-select').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			
			onChange: function(option, checked) {
				var item_hidden = $('#item_hidden').val();
				var arr = '';
				var item_hidden_arr=[];
				if(item_hidden.length>0){
					item_hidden_arr = item_hidden.split(',');
				}
				if (checked === true) {					
					item_hidden_arr.push(option.val());
					$('#item_hidden').val(item_hidden_arr.toString());
				}else{
					var arr = item_hidden_arr;
					arr = arr.filter(function(item) {
						return item !== option.val()
					})
					$('#item_hidden').val(arr.toString());					
				}
				
				//console.log(item_hidden_arr);
				//console.log(arr);
				
            }
			
			
		});
	});
</script>


@endsection

@section('custom_validation_script')
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(function() {
		 jQuery('#item-select,#payment-approval-select,#order-staus-select').closest('th').addClass('multiselect-dropdown');
		
		jQuery(".autocomplete-dynamic").autocomplete({
			minLength: 2,
			source: "{{ route('getmatcharticle') }}",
			select: function( event, ui ) {
					event.preventDefault();
					//console.log(ui);					
					//console.log(ui.item.id);
					//console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
					jQuery('#hidden_item_id').val(ui.item.id);
					jQuery('.autocomplete-dynamic').val(ui.item.value);	
			}
		});
		
		
		/* for ato pi item */
			jQuery("#autocomplete-dynamic").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=invoice_no&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#autocomplete-dynamic').val(ui.item.value);	
					}
				});
		/* for ato pi item */
		
		/* for ato customer_data */
			jQuery("#customer_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=customer_data&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#customer_data').val(ui.item.value);	
					}
				});
		/* for ato customer_data */
		
		/* for ato item_data */
			jQuery("#item_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=item_data&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#item_data').val(ui.item.value);	
					}
				});
		/* for ato item_data */
		
		/* for ato color */
		
			jQuery("#color").on('keyup click', function(){
				
				/* check item hidden  field*/
				var item_hidden = jQuery('#item_hidden').val();
				//console.log(item_hidden);
				var item_hidden2 = item_hidden.replace(/#/g, "---");
				//console.log(item_hidden2);
				jQuery("#color").autocomplete({
					minLength: 2,
					source: "{{ route('searchdatafromfms') }}?searchby=color&fms=bulk&items="+item_hidden2,
					select: function( event, ui ) {
					event.preventDefault();
					//console.log(ui);
					//console.log('---');
						jQuery('#color').val(ui.item.value);	
						}
					});
				
			})
		/* for ato color */
		
		/* for ato s_person_data */
			jQuery("#s_person_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=s_person_data&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#s_person_data').val(ui.item.value);	
					}
				});
		/* for ato s_person_data */
		
		/* for ato crm_data */
			jQuery("#crm_data").autocomplete({ 
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=crm_data&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#crm_data').val(ui.item.value);	
					}
				});
		/* for ato crm_data */
		
		/* for ato merchant_data */
			jQuery("#merchant_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=merchant_data&fms=bulk",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#merchant_data').val(ui.item.value);
					}
				});
		/* for ato merchant_data */

	});
</script>
<script>
$.noConflict();
</script>
@endsection