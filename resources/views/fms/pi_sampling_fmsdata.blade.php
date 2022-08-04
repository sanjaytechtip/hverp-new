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
				
				$unique_items = get_item_from_fms_data('sampling');
				$unique_s_person = get_sales_person_from_fms_data('sampling');
			?>

<div class="page no-border" id="fms_data">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
          <div class="panel-body">
			<div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
			<form method="get">
            <table class="table table-bordered table-hover table-striped tbale_header fixed_table" cellspacing="0" id="fmsdataTable">
				<thead>
						<tr class="tbale_header task_top_fix top-row">
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
										@endif <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										@endif <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										@endif <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										@endif <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										@endif <?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										<?php	echo nl2br($stepsdata['fms_what']).'</th>';
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
										<?php 
										if($stepsdata['step']==9){
											$dropdownArr = array("Blank", "Filled");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'" ><option value="">--Select--</option>';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && ($r ==$_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	

											$searchoption .='</select>';
										}
										?>
										
										<?php	echo nl2br($stepsdata['fms_what']).$searchoption.'</th>'; ?>
										
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
										<?php 
										if($stepsdata['step']==6){
											$dropdownArr = array("Blank","Courier Supplier", "Courier POS", "Courier Buyer", "Air Shpt", "Sea Shpt");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'[]" id="courier-cargo" multiple="multiple">';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && in_array($r, $_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}

											$searchoption .='</select>';
										}
										
										if($stepsdata['step']==8){
											$dropdownArr = array("Blank","Invoiced", "FOC");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'[]" id="invoiced-foc" multiple="multiple">';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && in_array($r, $_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}

											$searchoption .='</select>';
										}
										
										if($stepsdata['step']==10){
											$dropdownArr = array("Blank","Closed", "Cancelled", "Pending");
											$searchoption = '<br/><select name="'.$stepsdata['_id'].'[]" id="pi-order-status" multiple="multiple">';
										   
											foreach ($dropdownArr as $r){
												if(isset($_GET[$stepsdata['_id']]) && in_array($r, $_GET[$stepsdata['_id']])) {$sel = ' selected=""';}else{$sel = '';}
												
												$searchoption .='<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
											}	

											$searchoption .='</select>';
										}


										?>
										
										<?php echo nl2br($stepsdata['fms_what']).$searchoption.'</th>';
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
								
									<?php if('5eb254aa0f0e751788000099' == $taskdata['_id']){?>
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
										
											<?php if('5eb254aa0f0e751788000096' == $taskdata['_id']){?>
											<br/><input type="text" name="invoice_no" value="<?php if(isset($_GET['invoice_no'])){echo $_GET['invoice_no'];}?>" class=" form-control" id="autocomplete-dynamic" />
											<?php } ?>
											
											<?php if('5eb254aa0f0e751788000098' == $taskdata['_id']){?>
											<br/><input type="text" name="customer_data" value="<?php if(isset($_GET['customer_data'])){echo $_GET['customer_data'];}?>" class=" form-control" id="customer_data" />
											<?php } ?>
											
											<?php if('600e60258e744b009007db3a' == $taskdata['_id']){?>
											<br/><input type="text" name="brand_data" value="<?php if(isset($_GET['brand_data'])){echo $_GET['brand_data'];}?>" class=" form-control" id="brand_data" />
											<?php } ?>
											
											<?php if('5eb254aa0f0e751788000097' == $taskdata['_id']){?>
												<div class="between-date">
												<div class="date-child"><span>From:</span> <input type="text" name="pi_from" autocomplete="off" value="<?php if(isset($_GET['pi_from'])){echo $_GET['pi_from'];}?>" class="form-control date_with_dmy"></div>
												<div class="date-child"><span>To:</span> <input type="text" name="pi_to" autocomplete="off" value="<?php if(isset($_GET['pi_to'])){echo $_GET['pi_to'];}?>" class="form-control date_with_dmy"></div>
												</div>
												
											<?php } ?>
											
											<?php if('5eb254aa0f0e75178800009a' == $taskdata['_id']){?>
											<br/><input type="text" name="color" value="<?php if(isset($_GET['color'])){echo $_GET['color'];}?>" class=" form-control" id="color" />
											<?php } ?>
											
											<?php if('5eb254aa0f0e75178800009c' == $taskdata['_id']){?>
											<div class="article-box">
											<select id="sperson-select" multiple="multiple" name="s_person_data[]" >
												@foreach($unique_s_person as $skey=>$s_item)
													<option value="{{ $s_item['s_person_data']['s_value'] }}" <?php if(isset($_GET['s_person_data']) &&  in_array($s_item['s_person_data']['s_value'], $_GET['s_person_data'])) { echo ' selected';} ?>>{{ $s_item['s_person_data']['s_value'] }}</option>
												@endforeach
											</select>
											<?php 
												$s_person_hidden='';
												if(isset($_GET['s_person_data']) && !empty($_GET['s_person_data'])){
													$s_person_hidden=implode(',',$_GET['s_person_data']);
												} 
												?>
											<input type="hidden" name="" id="s_person_hidden" value="{{ $s_person_hidden }}">
											<?php /*?><input type="text" name="s_person_data" value="<?php if(isset($_GET['s_person_data'])){echo $_GET['s_person_data'];}?>" class=" form-control" id="s_person_data" /><?php */?>
											</div>
											<?php } ?>
											
											<?php if('5fa920ccc629341e9cf3400e' == $taskdata['_id']){?>
											<br/><input type="text" name="crm_data" value="<?php if(isset($_GET['crm_data'])){echo $_GET['crm_data'];}?>" class=" form-control" id="crm_data" />
											<?php } ?>
											
											<?php if('5fa920dcc629341e9cf3400f' == $taskdata['_id']){?>
											<br/><input type="text" name="merchant_data" value="<?php if(isset($_GET['merchant_data'])){echo $_GET['merchant_data'];}?>" class=" form-control" id="merchant_data" />
											<?php } ?>
											
											<?php if('5ee08ece580c60213c891ef2' == $taskdata['_id']){$dropdownArr = array("Confirmed", "Unconfirmed");
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
										<a href="{{ route('invoiceshow', $pi_id) }}{{ $partshipped_query }}" target="_blank" class="pi_po_number">{{ $fmsdata['invoice_no'] }}{{ $rev_p }}{{ $rev }}</a>
									@elseif($taskdata['field_type']=='pi_date')
										{{ changeDateToDmyHi($fmsdata['pi_date']) }}
									@elseif($taskdata['field_type']=='buyer_name')
										{{ $fmsdata['customer_data']['c_value'] }}
									@elseif($taskdata['field_type']=='brand_name')
										{{ $fmsdata['brand_data']['b_value'] }}
									@elseif($taskdata['field_type']=='sales_agent')									
										{{ $fmsdata['s_person_data']['s_value']}}
									@elseif($taskdata['field_type']=='item')
										{{ $fmsdata['item_data']['i_value'] }}
									@elseif($taskdata['field_type']=='etd')
										{{ date('d.m.y', strtotime($fmsdata['etd'])) }}
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
							if(array_key_exists('process', $stepsdata) && $stepsdata['process']=='dispatch_goods'){
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
							
							if(array_key_exists('planed',$fmsdata[$stepsdata['_id']]) && $stepsdata['_id']=='5eb2ac550f0e750c5c0020e8' && $s7_planned!='' && $fmsdata[$stepsdata['_id']]['planed']==''){
								$s7_planned = UpdateS7planned($fmsdata['_id'], '5eb2ac550f0e750c5c0020e8', $s7_planned);
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
												
												
													$pltime = dateTotime($planed_date);
													$actime = dateTotime($actual_date);
													
													if($pltime!=''  && $actime!='' && $actime==$pltime){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $actual_date>$planed_date){
														$planeAlert = 'background:'.BG_RED;
													}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
												
													if($o_status=='Cancelled'){
														$planeAlert = '';
													}
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
													
													$pltime = dateTotime($planed_date);
													$actime = dateTotime($actual_date);
													
													if($pltime!=''  && $actime!='' && $actime==$pltime){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $actual_date>$planed_date){
														$planeAlert = 'background:'.BG_RED;
													}elseif($planed_date!='' && $actual_date<$planed_date && $actual_date!=''){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $today_main>=$planed_date && $actual_date==''){
														$planeAlert = 'background:'.BG_YELLOW;
													}
												}elseif($stepsdata['process']=='inform_merchant' && $sampling_ready=='No'){
													
													$pltime = dateTotime($planed_date);
													$actime = dateTotime($actual_date);
													
													if($pltime!=''  && $actime!='' && $actime==$pltime){
														$planeAlert = 'background:'.BG_GREEN;
													}elseif($planed_date!='' && $actual_date>$planed_date){
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
													
													if($o_status=='Cancelled'){
														$planeAlert = '';
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
											@if($o_status!='Cancelled')
												{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }}
											@endif
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
										
										if($o_status=='Cancelled' && $stepsdata['_id']=='5eb2ac560f0e750c5c0020eb'){
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
										}elseif($o_status!='Cancelled'){
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
										<?php	}								
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
				<div class="from-filter">
					<input type="submit" value="Search" class="filter btn-info  waves-effect waves-classic">
					<a href="{{ route('fmsdata', $fms_id) }}" class="btn btn-info" role="button">Reset</a>
					<?php 
							$export='';
							if(isset($_GET) && !empty($_GET)){
								$export="&export=excel";
							}else{
								$export="?export=excel";
							}
							
							$full_url = url()->full().$export;
							$new_url = preg_replace('/&?page=[^&]*/', '', $full_url);
					?>
					<a href="<?php echo $new_url; ?>" target="_blank" class="btn btn-info" role="button">Export to Excel</a>
				</div>          
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
								$user_form_html.='<br/><button type="submit" name="user_permit" class="btn btn-primary block">Save</button>';
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

	<button data-toggle="modal" data-target="#user_staff_model" id="staff_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="user_staff_model" role="dialog">
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
					<form id="user_permit_form_staff" onsubmit="event.preventDefault(); stepUserPermitSubmit()">	
					WHAT: <textarea class="form-control" name="fms_what_new" id="fms_what_new_staff" rows="2" readonly></textarea>
					<hr/>
					HOW: <textarea class="form-control" name="fms_how_new" id="fms_how_new_staff" rows="2" readonly></textarea>
					<hr/>
					WHEN: <textarea class="form-control" name="fms_when_new" id="fms_when_new_staff" rows="2" readonly></textarea>
					<hr/>
					<div id="temlate_url_staff"></div>
					</div>			
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		  <!-- Modal content end -->
		</div>
	</div>
	<!-- for staff model end -->

<!-- Modal -->
<button data-toggle="modal" data-target="#comment_model" id="comment_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="comment_model" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Comment</h4>
			</div>
			<div class="modal-body">
				<div class="cmnt_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<a href="#" target="_blank" id="buyer_po_link" style="display:none;">Buyer PO Link <br/></a>
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveComment();" id="commentForm">
							@csrf
							<input type="hidden" name="data_id" id="cmnt_data_id" value="">
							<input type="hidden" name="step_id" id="cmnt_step_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="cmnt_id_with_row" value="" id="cmnt_id_with_row">
							<textarea class="form-control" placeholder="Comments" name="user_cmnt" id="user_cmnt"></textarea>
							<br>
							
							<button type="submit" class="btn btn-success" id="save_cmnt_btn" style="display:none;">Save</button>
						</form>
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
<!-- comment model end -->
<!-- Text-Box task Modal -->
<button data-toggle="modal" data-target="#textboxtask_model" id="textboxtask_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="textboxtask_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Enter Value</h4>
			</div>
			<div class="modal-body">
				<div class="text_task_update"></div>
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveTextboxTask();" id="textboxTaskForm">
							@csrf
							<input type="hidden" name="data_id" id="text_task_data_id" value="">
							<input type="hidden" name="task_id" id="text_task_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="task_id_with_row" value="" id="text_task_id_with_row">
							<input type="hidden" name="task_update_type" value="" id="text_task_id_update_type">
							<input type="text" class="form-control" placeholder="Enter Value" name="user_task_cmnt" id="user_text_task">
							<br>
							@if(userHasRight())
							<button type="submit" class="btn btn-success">Save</button>
							@endif
						</form>
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
<!-- textbox task model end -->
<!-- Change Date Time Model -->
	<button data-toggle="modal" data-target="#change_date_time_model" id="change_date_time_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="change_date_time_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Update Date And Time</h4>
			</div>
			<div class="modal-body">
				<div class="data_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POST" onsubmit="event.preventDefault(); update_date_and_time();" id="update_date_and_time_form">
							<input type="text" name="currentDateTime" id="currentDateTime" value="" class="form-control date_with_time" readonly>
							<br>
							<input type="hidden" name="actual_data_id" id="actual_data_id" value="">
							<input type="hidden" name="actual_step_id" id="actual_step_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row" value="" id="actual_id_with_row">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
	
	
	<!-- calendar model -->
	<button data-toggle="modal" data-target="#calendar_model" id="calendar_model_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="calendar_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Calendar</h4>
			</div>
			<div class="modal-body">
				<div class="data_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						
						<form action="" method="POST" onsubmit="event.preventDefault(); update_date_and_time_cal();" id="update_date_and_time_form_cal">
							<input type="text" name="currentDateTime_cal" id="currentDateTime_cal" value="" class="form-control date_with_dmy" readonly>
							<br>
							<input type="hidden" name="actual_data_id_cal" id="actual_data_id_cal" value="">
							<input type="hidden" name="actual_step_id_cal" id="actual_step_id_cal" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_cal" value="" id="actual_id_with_row_cal">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
						
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
	<!-- calendar model END -->
	
	<!-- Bulk QTY Actual model -->
	<button data-toggle="modal" data-target="#bulk_qty_model" id="bulk_qty_model_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="bulk_qty_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Actual QTY</h4>
			</div>
			<div class="modal-body">
				<div class="data_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						
						<form action="" method="POST" onsubmit="event.preventDefault(); updateBulkQty();" id="update_bulk_qty">
							<input type="hidden" name="planned_bulk" id="planned_bulk" value="" class="form-control">
							<input type="number" name="actual_bulk" id="actual_bulk" value="" class="form-control" step="any">
							<br>
							<input type="hidden" name="actual_data_id_bulk" id="actual_data_id_bulk" value="">
							<input type="hidden" name="actual_step_id_bulk" id="actual_step_id_bulk" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_bulk" value="" id="actual_id_with_row_bulk">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
						
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
	<!-- Bulk QTY Actual model END -->
	
	<!-- Pi - update model -->
	<button data-toggle="modal" data-target="#pi_model" id="pi_model_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="pi_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">PI Data</h4>
			</div>
			<div class="modal-body">
				<div class="data_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						
						<form action="" method="POST" onsubmit="event.preventDefault(); updatePiData();" id="update_pi_data">
							<input type="hidden" name="planned_pi" id="planned_pi" value="" class="form-control">
							<input type="hidden" name="actual_pi" id="actual_pi" value="" class="form-control">
							
							<input type="text" name="pi_number" id="pi_number" value="" class="form-control">
							<br>
							<input type="hidden" name="actual_data_id_pi" id="actual_data_id_pi" value="">
							<input type="hidden" name="actual_step_id_pi" id="actual_step_id_pi" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_pi" value="" id="actual_id_with_row_pi">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
						
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
	<!-- Pi - update model  END -->
	
	<!-- Invoice Details model -->
	<button data-toggle="modal" data-target="#invoice_model" id="invoice_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="invoice_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Update Invoice Details</h4>
			</div>
			<div class="modal-body">
				<div class="data_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						
						<form action="" method="POST" onsubmit="event.preventDefault(); update_invoice_details_sampling();" id="update_invoice_details">
							
							<div class="row">
								<div class="col-md-3">Inv Date:</div>
								<div class="col-md-9"><input type="text" name="inv_date" autocomplete="off" id="inv_date" required value="" class="form-control date_with_dmy"><br/></div>
								
							
							
								<div class="col-md-3">Inv Number:</div>
								<div class="col-md-9"><input type="text" name="inv_no" id="inv_no" value="" class="form-control"><br/></div>
							
							
							
								<div class="col-md-3">Inv Amount:</div>
								<div class="col-md-9"><input type="number" name="inv_amount" id="inv_amount" required value="" class="form-control" step="any"><br/></div>
							
							
							
								<div class="col-md-3">AWB No.:</div>
								<div class="col-md-9"><input type="text" name="awb_no" id="awb_no" required value="" class="form-control"><br/></div>
							
							
							
								<div class="col-md-3">Courier Company:</div>
								<div class="col-md-9">
									<select name="courier_company" class="form-control" id="courier_company" required>
										<option value="">--Select--</option>
										@foreach(getOptionArray('courier_company') as $key=>$courier_company)
											<option>{{ $courier_company }}</option>
										@endforeach
									</select><br/>
								</div>
							</div>
							
							<input type="hidden" name="actual_data_id_inv" id="actual_data_id_inv" value="">
							<input type="hidden" name="actual_step_id_inv" id="actual_step_id_inv" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_inv" value="" id="actual_id_with_row_inv">
							<input type="hidden" name="payment_term" value="" id="payment_term">
							@if($guest_login=='')
								<button type="submit" class="btn btn-success">Update</button>
							@endif
						</form>
						
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
<!-- Invoice Details model END -->
	
	<!-- update order-date model -->
	<button data-toggle="modal" data-target="#change_date_time_model_order" id="change_date_time_model_open_order" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="change_date_time_model_order" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Update Order Date <span id="actual_order_text"></span></h4>
			</div>
			<div class="modal-body">
				<div class="data_update_order"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POST" onsubmit="event.preventDefault(); update_order_date();" id="update_date_and_time_form_order">
							<input type="text" name="currentOrderDate" id="currentOrderDate" value="" class="form-control date_with_dmy" readonly>
							<br>
							<input type="hidden" name="actual_data_id_order" id="actual_data_id_order" value="">
							<input type="hidden" name="actual_task_id_order" id="actual_task_id_order" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_order" value="" id="actual_id_with_row_order">
							<input type="hidden" name="actual_order_no" value="" id="actual_order_no">
							<input type="hidden" name="o_item" value="" id="actual_item_no">
							<input type="hidden" name="order_qty" value="" id="actual_order_qty">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>	
	<!-- update order-date model end -->
	
	<!-- update plane-actula dropdown model -->
	<button data-toggle="modal" data-target="#change_dropdown" id="change_dropdown_model" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="change_dropdown" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Update Dropdown <span id="actual_order_text"></span></h4>
			</div>
			<div class="modal-body">
				<div class="text_task_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POST" onsubmit="event.preventDefault(); update_dropdown();" id="dropdown_form">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<div id="dropdown_select"></div><br>
							<button type="submit" class="btn btn-success">Update</button>
						</form>
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>	
	<!-- update order-date model end -->
	
		<!-- FUP calendar model -->
	<button data-toggle="modal" data-target="#calendar_fup_model" id="calendar_fup_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="calendar_fup_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">FUP DATE FOR COLLECTION </h4>
			</div>
			<div class="modal-body">
				<div class="data_update_fup"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						
						<form action="" method="POST" onsubmit="event.preventDefault(); update_date_and_time_cal_fup();" id="update_date_and_time_form_fup">
							<input type="text" name="currentDateTime_cal_fup" id="currentDateTime_cal_fup" value="" class="form-control date_with_dmy" readonly>
							<br>
							<input type="hidden" name="actual_data_id_cal_fup" id="actual_data_id_cal_fup" value="">
							<input type="hidden" name="actual_step_id_cal_fup" id="actual_step_id_cal_fup" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="actual_id_with_row_cal_fup" value="" id="actual_id_with_row_cal_fup">
							<button type="submit" class="btn btn-success">Save</button>
						</form>
						
					</div>
				</div>			
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  <!-- Modal content end -->
		</div>
	</div>
	<!-- FUP  calendar model END -->


@endsection

@section('custom_script')

<script>
$(document).ready(function(){
  $('body').addClass('footer-pagination');
  $("body").addClass("hasform-filter");
  /* for user login */
	var guest_login = '{{ $guest_login }}';
	if(guest_login!=''){
		$(".pi_po_number").click(function(e)
		{
			e.preventDefault();
		});

		$("#customer_data").prop("readonly", true);
		$("#brand_data").prop("readonly", true);
		
		$('.update_ac_date').remove();
		$('#color').remove();
		$('#pi_status').remove();
		$('.multiselect-native-select').remove();
		$("#crm_data").remove();
		$("#merchant_data").remove();
	}
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
	
	function dropdownModel(dataId, stpId, dropdownVal, planedDt, row_num, comment_dropdown,isCmntBox,pi_date)
	{
		var cmntBox ='';
		if(isCmntBox==0){
			cmntBox =' style="display:none;"';
		}
		
		$('.text_task_update').html('');
		$('#dropdown_select').html('');	
		var dropdown = dropdownVal.split(',');
		//console.log(dropdown);
		var dropdownSelect='';
		dropdownSelect += '<input type="hidden"name="data_id" value="'+dataId+'" />';	
		dropdownSelect += '<input type="hidden" name="step_id" value="'+stpId+'" />';	
		dropdownSelect += '<input type="hidden" name="row_num_step" value="'+row_num+'" />';	
		dropdownSelect += '<input type="hidden" name="planed_date" value="'+planedDt+'" />';	
		dropdownSelect += '<input type="hidden" name="pi_date" value="'+pi_date+'" />';	
		dropdownSelect += '<select class="form-control" name="dropdown">';
			for(var i=0; i<dropdown.length; i++){
				dropdownSelect += '<option>'+dropdown[i]+'</option>';
			}			
		dropdownSelect += '</select>';
		
		dropdownSelect += '<span '+cmntBox+'><br>Comment:<textarea class="form-control" name="comment_dropdown">'+comment_dropdown+'</textarea></span>';
			
		$('#dropdown_select').html(dropdownSelect);	
		$('#change_dropdown_model').trigger('click');		
	}
	function update_dropdown(){
		var formData = $('#dropdown_form').serialize();
		//console.log(formData);
		
		var ajax_url = "<?php echo route('ajax_save_dropdown_data_with_date_pisample'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
					
					var resData = JSON.parse(res);
					$('.text_task_update').html('');
					$('.text_task_update').html('<p class="text-success">Updated successfully.</p>');
					
					var stageHtml = '<span>'+resData['stage_text']+'</span>';
					$('.fms_stage_'+resData['row_num_step']).html(stageHtml);
					
					var dropdown = resData['dropdown'];
					var currentClicData = '';
					currentClicData = resData['date']+' | '+dropdown;
					$('#dropdown_'+resData['step_id']+'_'+resData['row_num_step']).html('');
					$('#dropdown_'+resData['step_id']+'_'+resData['row_num_step']).html(currentClicData);
					$('#dropdown_'+resData['step_id']+'_'+resData['row_num_step']).parent().parent().css({"background": ""+resData['resHtml'].bgcolor+""});
					
					
					var process = resData['process'];
					if(process=='sampling_ready'){
						var data_step_pl = 'data_step_pl_5eb25b870f0e750c5c0020d3_'+resData['row_num_step'];
						$('#'+data_step_pl).html(resData['pl_date']);
						
						var step_actual = 'step_actual_5eb25b870f0e750c5c0020d3_'+resData['row_num_step'];
						var actHtml = '<a href="javascript:void(0)" onclick="dropdownModelNew()">Select Option</a>';
						
						$('#'+step_actual).html(actHtml);
						
					}else if(process=='inform_merchant'){
						var data_step_pl = 'data_step_pl_5eb25b870f0e750c5c0020d4_'+resData['row_num_step'];
						$('#'+data_step_pl).html(resData['pl_date']);
						
						var step_actual = 'step_actual_5eb25b870f0e750c5c0020d4_'+resData['row_num_step'];
						var actHtml = '<a href="javascript:void(0)" onclick="dropdownModelNew()">Select Option</a>';
						
						$('#'+step_actual).html(actHtml);
						
					}else if(process=='inform_merchant_yes'){
						var data_step_pl = 'data_step_pl_5eb25b870f0e750c5c0020d5_'+resData['row_num_step'];
						$('#'+data_step_pl).html(resData['pl_date']);
						
						var step_actual = 'step_actual_5eb25b870f0e750c5c0020d5_'+resData['row_num_step'];
						var actHtml = '<a href="javascript:void(0)" onclick="dropdownModelNew()" class="btn btn-success">Time</a>';
						
						$('#'+step_actual).html(actHtml);
						
					}
					
					/* set planned date attach with this step planned_steps_ids */
					for(var i=0; i<resData['planned_steps_ids'].length; i++){
						//console.log(resData['planned_steps_ids'][i]); 
						if(process!='inform_merchant'){
							var data_step_pl = 'data_step_pl_'+resData['planned_steps_ids'][i]+'_'+resData['row_num_step'];
							$('#'+data_step_pl).html(resData['date']);	
						}
						
						
						//$('#step_'+resData['step_id']+'_'+resData['row_num_step']).show();
						//alert('step_'+resData['planned_steps_ids'][i]+'_'+resData['row_num_step']);
						
						
						
						var planned_steps_id = 'step_'+resData['planned_steps_ids'][i]+'_'+resData['row_num_step'];
						$('#'+planned_steps_id).show();
						$('#'+planned_steps_id).attr('rel', resData['resHtml'].date_main);						
					}
					
				}
		  });
		
	}
	
	function dropdownModelNew(){
		location.reload();
	}
	
	/* textbox model task */
	function textboxTaskModel(fms_data_id, task_id, cmnt_id_with_row, typeSave){
		$('.text_task_update').html('');
		$('#text_task_data_id').val('');
		$('#text_task_data_id').val(fms_data_id);
		$('#text_task_id').val('');
		$('#text_task_id').val(task_id);
		$('#text_task_id_with_row').val(cmnt_id_with_row);
		$('#text_task_id_update_type').val(typeSave);
		
		var comntText = $('.textbox_'+cmnt_id_with_row).text();
		$('#user_text_task').val(comntText);
		
		$('#textboxtask_model_open').trigger('click');
	}
	
	function saveTextboxTask(){
		var cmnt_id_with_row = $('#text_task_id_with_row').val();
		var user_cmnt = $('#user_text_task').val();
		var formData = $('#textboxTaskForm').serialize();
		var ajax_url = "<?php echo route('ajax_savetask_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res);
					var jsonData = JSON.parse(res);
					$('.text_task_update').html('');
					$('.text_task_update').html('<p class="text-success">'+jsonData.msg+'</p>');				
					$('.textbox_'+cmnt_id_with_row).text(user_cmnt);				
					$('#textboxlink_'+cmnt_id_with_row).removeClass('btn btn-success');	$('#textboxlink_'+cmnt_id_with_row).html('<strong>'+user_cmnt+'</strong>').removeClass('btn-lightgray').addClass('text-danger');
					console.log(jsonData);
				}
		  });
	
	}
	/* textbox Task model END*/
	
	/*END 27 july 2019 */
	
	// update_date_and_time
	function update_date_and_time(){
		var actual_id_with_row = $('#actual_id_with_row').val();
		var currentDateTime = $('#currentDateTime').val();
		var stpId = $('#actual_step_id').val();
		var row_num = $('#actual_id_with_row').val();
		var formData = $('#update_date_and_time_form').serialize();
		var ajax_url = "<?php echo route('ajax_updatedate_and_time'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).text('');
					$('#'+stpId+'_'+row_num).text(jsonData.current_date);
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">Date updated successfully.</p>');
					}
					
				}
			});
	}
	
	// update_date_and_time_form_order
	function update_order_date(){
		var actual_id_with_row = $('#actual_id_with_row_order').val();
		var currentOrderDate = $('#currentOrderDate').val();
		var taskId = $('#actual_task_id_order').val();
		var row_num = $('#actual_id_with_row_order').val();
		var formData = $('#update_date_and_time_form_order').serialize();

		formData = formData+'&order_type=stock_order&fms_id=<?php echo  $fms_id;?>';
		//console.log(formData);
		
		var ajax_url = "<?php echo route('ajax_update_order_date'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res);	return false;					
					if(res==1){
						$('.data_update_order').html('<p class="text-success">Order Date updated successfully.</p>');
						
						alert('Order date Updated successfully.');
						location.reload();
					}
				}
			});
	}
	
	


	function commentModel(fms_data_id, step_id, cmnt_id_with_row, cmntRight, buyer_po_link){
		debugger;
		$('.cmnt_update').html('');
		$('#cmnt_data_id').val('');
		$('#cmnt_data_id').val(fms_data_id);
		$('#cmnt_step_id').val('');
		$('#cmnt_step_id').val(step_id);
		$('#cmnt_id_with_row').val(cmnt_id_with_row);
		
	
		var comntText = $('.cmnt_'+cmnt_id_with_row).text();
		$('#user_cmnt').val(comntText);
		
		if(buyer_po_link!='' && comntText!=''){
			$('#buyer_po_link').show();
			$('#buyer_po_link').attr('href', comntText);
		}else{
			$('#buyer_po_link').hide();
			$('#buyer_po_link').attr('href', '#');
		}
		
		if(cmntRight==1){
			$('#save_cmnt_btn').show();
		}else{
			$('#save_cmnt_btn').hide();
		}
		
		
		$('#comment_model_open').trigger('click');
	}
	
	function saveComment(){
		var cmnt_id_with_row = $('#cmnt_id_with_row').val();
		var user_cmnt = $('#user_cmnt').val();
		var formData = $('#commentForm').serialize();
		var ajax_url = "<?php echo route('ajax_savecomment_in_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); cmntlink_
					var jsonData = JSON.parse(res);
					$('.cmnt_update').html('');
					$('.cmnt_update').html('<p class="text-success">'+jsonData.msg+'</p>');				
					$('.cmnt_'+cmnt_id_with_row).text(user_cmnt);				
					$('#cmntlink_'+cmnt_id_with_row).removeClass('btn btn-success');				
					$('#cmntlink_'+cmnt_id_with_row).html('<strong>Read Comment</strong>').removeClass('btn-lightgray').addClass('text-danger');				
					console.log(jsonData);
				}
		  });
	
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
	function OrderEmbroiderySave(thisVal,stepId, dataId, rowNum){
		//debugger;
		var currVal = thisVal;
		var dataId = dataId;
		var stepId = stepId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		
		if(currVal!=''){
		$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updateOrderEmbroidery'); ?>";
			$.ajax({
				type:'GET',
				url:ajax_url,
				data:{fms_id:fms_id,dataId:dataId,stepId:stepId,currVal:currVal},
				success:function(result){
					
						console.log(result);
						var res = JSON.parse(result);
						$('#loader').hide();
						
						$('.activate_timestamp'+'_'+rowNum+':first').show();
						if(res.stage_text_color!='' && res.stage_text!=''){
							$('.fms_stage_'+rowNum).html('');
							var stageHtml = '<span style="color:'+res.stage_text_color+'">'+res.stage_text+'</span>';
							$('.fms_stage_'+rowNum).html(stageHtml);
						}  
						return false;
						},
				error: function(result)
						{
							console.log(result);
							$('#loader').hide();
							return false;
						}
			});
		}
	}

	function saveFabricCmnt(thisVal,stepId, dataId, rowNum){
		var currVal = thisVal;
		var dataId = dataId;
		var stepId = stepId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		
		if(currVal!=''){
		$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updateFabricCmnt'); ?>";
			$.ajax({
				type:'GET',
				url:ajax_url,
				data:{fms_id:fms_id,dataId:dataId,stepId:stepId,currVal:currVal},
				success:function(result){
					
						console.log(result);
						var res = JSON.parse(result);
						$('#loader').hide();
						
						$('.activate_timestamp'+'_'+rowNum+':first').show();
						if(res.stage_text_color!='' && res.stage_text!=''){
							$('.fms_stage_'+rowNum).html('');
							var stageHtml = '<span style="color:'+res.stage_text_color+'">'+res.stage_text+'</span>';
							$('.fms_stage_'+rowNum).html(stageHtml);
						}
						return false;
						},
				error: function(result)
						{
							console.log(result);
							$('#loader').hide();
							return false;
						}
			});
		
		//$('#loader').hide();
		}
	}
	
	
	$('.update_ac_date').on('click', function(event) {
		//debugger;
		var stepIdMain = $(this).attr('id');
		var stepIdSplit = stepIdMain.split('_');
		var stepId = stepIdSplit[1];
		
		var row_num_step = stepIdSplit[2];

		var planed_date = $(this).attr('rel');
		
		var data_id = $(this).attr('data_id');
		
		var fms_id = '<?php echo $fms_id; ?>';
		
		var pm_check = $(this).attr('pm_check');
		var process = $(this).attr('process');
		var patch = $(this).attr('patch');
		
		var ajax_url = "<?php echo route('ajax_updateActualDate'); ?>";
		
		$.ajax({
			type:'GET',
			url:ajax_url,
			data:{data_id:data_id,fms_id:fms_id, step_id:stepId,row_num_step:row_num_step,planed_date:planed_date, pm_check:pm_check, process:process, patch:patch},
			success:function(result){
					//console.log(result); return false; 
					var res = JSON.parse(result);
					if(res.status==0){
						alert("Please complete all required Steps.");
						return false;
					}
					
					console.log(res);
					
					var step_id = res.step_id;
					var row_num_step = res.row_num_step;
				
					$('#step_'+step_id+'_'+row_num_step).after(res.resHtml['date']);
					$('#'+stepIdMain).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					
					$('#step_'+step_id+'_'+row_num_step).remove();
					
					if(stepId!=''){
						$('.activate_timestamp'+'_'+row_num_step+':first').show();
					}
					
					
					$('.fms_stage_'+row_num_step).html('');
					$('.fms_stage_'+row_num_step).html(res.stage_text);
					
					return false;
					},
			error: function(result)
					{
						//console.log(result);
						return false;
					}
		});
		
	});
	
	
function deleteRow(rowNum, fmsDataId)
{
	var conf = confirm('Are you sure, you want delete this row?');
	if(conf)
	{
		$('.row_number_'+rowNum).remove();		
		$('#row_deleted').html('');
		
		var ajax_url = "<?php echo route('ajax_deleteFmsData'); ?>";
		$.ajax({
			method:'GET',
			url:ajax_url,
			data:{fmsDataId:fmsDataId},
			success:function(result){
					console.log(result);
					//$('#row_deleted').show();					
					
					$('#row_deleted').html('<p>Row has been deleted successfully.</p>');
					
					$('#row_deleted').fadeIn(1000);
					setTimeout(function() { 
					$('#row_deleted').fadeOut(500); 
					}, 1000);
					
					
					return false;
					},
			error: function(result)
					{
						console.log(result);
						return false;
					}
		});
		
	}else{
		$('#row_deleted').hide();
		return false;
	}
	
}

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
		var guest_login = '{{ $guest_login }}';
		if(guest_login!=''){
			return false;
		} 
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


/* function getOrderNumber(){
	var order_number = $('#order_number').val();
		if(order_number==''){
			alert('Order Number is required.');
			$('#order_number').focus();
			return false;
		}
	var fms_url = "<?php echo route('fmsdata', $fms['_id']) ?>";
	var newUrl = fms_url+'/0/'+order_number;
	window.location=newUrl;
} */


$(document).ready(function() {
	$('#order_range').val('{{ @$search_by }}');
});


/* function searchChange(){
	var order_range = $('#order_range').val();
	$('#search_text').val('');
	if(order_range=='proforma_invoice_date'){
		$('#search_text').hide();
		$('#date_range').html('<input type="text" class="form-control date_with_dmy" name="search_text" autocomplete="off" placeholder="YYYY-MM-DD" required>');

	}else{
		$('#search_text').show();
		$('#date_range').html('');
	}
} */

function searchChange(){
	var order_range = $('#order_range').val();
	$('#search_text').val('');
	if(order_range=='proforma_invoice_date'){
		$('#search_text').hide();
		$('#date_range').html('<input type="text" class="form-control date_with_dmy" name="search_text" autocomplete="off" placeholder="YYYY-MM-DD" required>');
		$('.autocomplete-dynamic').prop('disabled', true);
		$('#item_id').hide();
		
	}else if(order_range=='item_number'){
			$('#search_text').hide();
			$('#search_text').prop('disabled', true);
			$('#date_range').html('');
			$('.autocomplete-dynamic').prop('disabled', false);
			$('#item_id').show();
	}else{
		$('#search_text').show();
		$('#search_text').prop('disabled', false);
		$('#date_range').html('');
		
		$('.autocomplete-dynamic').prop('disabled', true);
		$('#item_id').hide();
	}
}


function searchValidate(){

	var order_range = $('#order_range').val();
	var search_text = $('#search_text').val();
	
	if(order_range=='po_serial_number' && search_text.length<4){
		alert('Enter 4 digit PI NO.');
		return false;
	}

	if(order_range!=''){
		return true;
	}else{
		alert('At Least one search option required.');
		return false;
	}
}

	function saveDropdown(thisVal,stepId, dataId, rowNum){
		var guest_login = '{{ $guest_login }}';
		if(guest_login!=''){
			alert('You can not change.');
			return false;
		}
		var currVal = thisVal;
		var dataId = dataId;
		var stepId = stepId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		if(currVal!=''){
		$('#loader').show();
			var ajax_url = "<?php echo route('ajax_update_dropdown'); ?>";
			$.ajax({
					type:'GET',
					url:ajax_url,
					data:{fms_id:fms_id,dataId:dataId,stepId:stepId,currVal:currVal},
					success:function(result){					
							console.log(result); //return false;					
							
							$('#loader').hide();							
							/* var res = JSON.parse(result);
							$('.activate_timestamp'+'_'+rowNum+':first').show();
							if(res.stage_text_color!='' && res.stage_text!=''){
								$('.fms_stage_'+rowNum).html('');
								var stageHtml = '<span style="color:'+res.stage_text_color+'">'+res.stage_text+'</span>';
								$('.fms_stage_'+rowNum).html(stageHtml);
							} */							
							return false;
							},
					error: function(result)
							{
								console.log(result);
								$('#loader').hide();
								return false;
							}
			});
		}
	}
	
	
	function openCalendar(dtId, ac_date, stpId, row_num_stage){
		$('#currentDateTime_cal').val('');
		$('#currentDateTime_cal').val(ac_date);
		$('#actual_data_id_cal').val(dtId);
		$('#actual_step_id_cal').val(stpId);
		$('#actual_id_with_row_cal').val(row_num_stage);
		$('#calendar_model_model_open').trigger('click');
	}
	
	function update_date_and_time_cal(){
		var actual_id_with_row_cal = $('#actual_id_with_row_cal').val();
		var currentDateTime_cal = $('#currentDateTime_cal').val();
		var stpId = $('#actual_step_id_cal').val();
		var row_num = $('#actual_id_with_row_cal').val();
		var formData = $('#update_date_and_time_form_cal').serialize();
		var ajax_url = "<?php echo route('ajax_updatedate_and_time_cal'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).text('');
					$('#'+stpId+'_'+row_num).text(jsonData.current_date);
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">ETD Date updated successfully.</p>');
					}
					
				}
			});
	}
	
	/* for update bulk qty */	
	function openBulkQtyUpdate(dtId, actual_bulk, stpId, row_num_stage){
		$('.data_update').html('');
		$('#planned_bulk').val('');
		$('#planned_bulk').val(actual_bulk);
		
		$('#actual_bulk').val('');
		$('#actual_bulk').val(actual_bulk);
		
		$('#actual_data_id_bulk').val(dtId);
		$('#actual_step_id_bulk').val(stpId);
		$('#actual_id_with_row_bulk').val(row_num_stage);
		$('#bulk_qty_model_model_open').trigger('click');
	}
	
	function updateBulkQty(){
		var actual_id_with_row_bulk = $('#actual_id_with_row_bulk').val();
		var currentDateTime_cal = $('#currentDateTime_cal').val();
		var stpId = $('#actual_step_id_bulk').val();
		var row_num = $('#actual_id_with_row_bulk').val();
		var formData = $('#update_bulk_qty').serialize();
		var ajax_url = "<?php echo route('ajax_updatedate_bulk_qty'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).html('');
					//
					//var variation = (((jsonData.actual_bulk))-((jsonData.planned_bulk))/jsonData.planned_bulk);
					var variation = ((jsonData.actual_bulk-jsonData.planned_bulk)/jsonData.planned_bulk)*100;
					
					variation = Math.floor(variation*100)/100;
					
					$('#'+stpId+'_'+row_num).html('<strong>'+jsonData.actual_bulk+' | Variation:'+variation+' % </strong>');
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">Bulk quantity updated successfully.</p>');
					}
					
				}
			});
	}
	
	
	/* for update PI-number */	
	//function openPiUpdate(dtId, actual_pi, stpId, row_num_stage){
	function openPiUpdate(dtId, stpId, planned_pi, actual_pi,  pi_number,  row_num_stage){
		$('.data_update').html('');
		$('#planned_pi').val('');
		$('#planned_pi').val(planned_pi);
		
		$('#actual_pi').val('');
		$('#actual_pi').val(actual_pi);
		
		$('#pi_number').val('');		
		if(pi_number==''){
			pi_number = 'PI-POS#'+pi_number;
		}else{
			pi_number = pi_number;
		}
		
		$('#pi_number').val(pi_number);
		
		$('#actual_data_id_pi').val(dtId);
		$('#actual_step_id_pi').val(stpId);
		$('#actual_id_with_row_pi').val(row_num_stage);
		$('#pi_model_model_open').trigger('click');
	}
	
	function updatePiData(){
		var actual_id_with_row_pi = $('#actual_id_with_row_pi').val();
		var currentDateTime_cal = $('#currentDateTime_cal').val();
		var stpId = $('#actual_step_id_pi').val();
		var row_num = $('#actual_id_with_row_pi').val();
		var formData = $('#update_pi_data').serialize();
		var ajax_url = "<?php echo route('ajax_update_pi_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).html('');
					
					$('#'+stpId+'_'+row_num).html('<strong>'+jsonData.actual_pi+' | '+jsonData.pi_number+' </strong>');
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">PI updated successfully.</p>');
					}
					
				}
			});
	}
	
	/* for update invoice Details */	
	function openInvoiceUpdate(dtId, stpId, row_num_stage, inv_date, inv_no, inv_amount, payment_term, awb_no, courier_company){
		$('.data_update').html('');
		$('#inv_date').val('');
		$('#inv_date').val(inv_date);
		
		$('#inv_no').val('');
		$('#inv_no').val(inv_no);
		
		$('#inv_amount').val('');
		$('#inv_amount').val(inv_amount);
		
		$('#awb_no').val('');
		$('#awb_no').val(awb_no);
		
		$('#courier_company').val('');
		$('#courier_company').val(courier_company);
		
		
		$('#actual_data_id_inv').val(dtId);
		$('#actual_step_id_inv').val(stpId);
		$('#actual_id_with_row_inv').val(row_num_stage);
		$('#payment_term').val(payment_term);
		$('#invoice_model_open').trigger('click');
	}
	
	function update_invoice_details_sampling(){
		var actual_id_with_row_inv = $('#actual_id_with_row_inv').val();

		var stpId = $('#actual_step_id_inv').val();
		var row_num = $('#actual_id_with_row_inv').val();
		var formData = $('#update_invoice_details').serialize();
		var ajax_url = "<?php echo route('ajax_updatedate_inv_details_sampling'); ?>";
		$('#loader').show();
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).html('');
					//
					var invoice_details = jsonData.inv_date+' | '+jsonData.inv_no+' | '+jsonData.inv_amount+' | '+jsonData.awb_no+' | '+jsonData.courier_company;
					$('#loader').hide();
					$('#'+stpId+'_'+row_num).html(invoice_details);
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">Invoice Details updated successfully.</p>');
					}
					
				}
			});
	}
	
	

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
	
	function openFupCalendar(dtId, ac_date, stpId, row_num_stage){
		$('#currentDateTime_cal_fup').val('');
		$('#currentDateTime_cal_fup').val(ac_date);
		$('#actual_data_id_cal_fup').val(dtId);
		$('#actual_step_id_cal_fup').val(stpId);
		$('#actual_id_with_row_cal_fup').val(row_num_stage);
		$('#calendar_fup_model_open').trigger('click');
	}
	
	function update_date_and_time_cal_fup(){
		var actual_id_with_row_cal = $('#actual_id_with_row_cal_fup').val();
		var currentDateTime_cal = $('#currentDateTime_cal_fup').val();
		var stpId = $('#actual_step_id_cal_fup').val();
		var row_num = $('#actual_id_with_row_cal_fup').val();
		var formData = $('#update_date_and_time_form_fup').serialize();
		var ajax_url = "<?php echo route('ajax_updatedate_and_time_cal_fup'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					// current_date
					var jsonData = JSON.parse(res);
					$('#'+stpId+'_'+row_num).text('');
					$('#'+stpId+'_'+row_num).text(jsonData.current_date);
					if(jsonData.msg=='success'){
						$('.data_update_fup').html('');
						$('.data_update_fup').html('<p class="text-success">FUP updated successfully.</p>');
					}
					
				}
			});
	}

</script>

<script type="text/javascript" src="{{ URL::asset('public/multiselect/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#courier-cargo').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		$('#invoiced-foc').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		$('#pi-order-status').multiselect({
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
		
		$('#sperson-select').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			
			onChange: function(option, checked) {
				var s_person_hidden = $('#s_person_hidden').val();
				var arr = '';
				var item_hidden_arr=[];
				if(s_person_hidden.length>0){
					item_hidden_arr = s_person_hidden.split(',');
				}
				if (checked === true) {					
					item_hidden_arr.push(option.val());
					$('#s_person_hidden').val(item_hidden_arr.toString());
				}else{
					var arr = item_hidden_arr;
					arr = arr.filter(function(item) {
						return item !== option.val()
					})
					$('#s_person_hidden').val(arr.toString());					
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
		 jQuery('#item-select,#sperson-select,#courier-cargo,#invoiced-foc, #pi-order-status').closest('th').addClass('multiselect-dropdown');
		
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
				source: "{{ route('searchdatafromfms') }}?searchby=invoice_no&fms=sampling",
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
				source: "{{ route('searchdatafromfms') }}?searchby=customer_data&fms=sampling",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#customer_data').val(ui.item.value);	
					}
				});
		/* for ato customer_data */
		
		/* for ato brand_data */
			jQuery("#brand_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=brand_data&fms=sampling",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#brand_data').val(ui.item.value);	
					}
				});
		/* for ato brand_data */
		
		/* for ato item_data */
			jQuery("#item_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=item_data&fms=sampling",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#item_data').val(ui.item.value);	
					}
				});
		/* for ato item_data */
		
		/* for ato color */
			jQuery("#color").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=color&fms=sampling",
				select: function( event, ui ) {
				event.preventDefault();
				//console.log(ui);
					jQuery('#color').val(ui.item.value);	
					}
				});
		/* for ato color */
		
		/* for ato s_person_data */
			jQuery("#s_person_data").autocomplete({
				minLength: 2,
				source: "{{ route('searchdatafromfms') }}?searchby=s_person_data&fms=sampling",
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
				source: "{{ route('searchdatafromfms') }}?searchby=crm_data&fms=sampling",
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
				source: "{{ route('searchdatafromfms') }}?searchby=merchant_data&fms=sampling",
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