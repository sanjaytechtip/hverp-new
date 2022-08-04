@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'MIS Report')
@section('pagecontent')
<div class="page">
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
   
    <div class="panel">
      <header class="panel-heading pull-right"><br/>
        <h1 class="panel-title text-center">{{ __('MIS Report') }}</h1>
        <br/>
      </header>
      <div class="panel-body mis-wrap"> 
	  @if( userHasRight() )
        <div class="row">
			<?php 
					$user_id = '';
					$mis_period = '';
					$mis_from = '';
					$mis_to = '';
					$iscmsuser=0;
					
					if(!empty($post)){					
						//pr($post); die;					
						//$fms_id = $post['fms'];
						$user_id = $post['user'];
						$mis_period = $post['mis_period'];
						$mis_from = $post['mis_from'];
						$mis_to = $post['mis_to'];
						
						unset($post['_token']);
						unset($post['mis_period']);
						unset($post['fms']);
						$user_name = getAgentDetails($user_id);
						
						$iscmsuser = isCMSUser($user_id);
					}
					
					
			?>
          <form method="post" class="custom-form-wrap mis-form-wrap col-md-12" name="mis_form" action="{{ route('misscore') }}" onsubmit="return validateForm();">
            @csrf
            <div class="form-wrap">
            <label>DOER:</label>
				<?php 
					$allUsers = getAllUserList();
					//pr($allUser);
				?>
				<select class="form-control" name="user" id="user" data-plugin="select2" style="width:200px;">
					<option value="">--Select--</option>
					@foreach($allUsers as $alluser)
						<option value="{{ $alluser['_id'] }}" @if($alluser['_id']==$user_id) selected @endif>{{ $alluser['name'] }}</option>
					@endforeach
				</select>
            </div>
            <div class="form-wrap">
            <label>PERIOD:</label>
              <select class="form-control" name="mis_period" id="mis_period">
                <option value="">--Select--</option>
                <option value="last_week" @if($mis_period=='last_week') selected @endif>Last Week</option>
                <option value="till_date" @if($mis_period=='till_date') selected @endif>Till Date</option>
                <option value="custom_period" @if($mis_period=='custom_period') selected @endif>Custom Period</option>
              </select>
            </div>
            <?php  //echo '>>>>>>'.$mis_period.'===='; die;
					if($mis_period=='custom_period'){
						$custom_period = '';
					}else{
						$custom_period = "display:none;";
					} ?>
            <div class="form-wrap mis_date" style="{{ $custom_period }}">
            <label>From:</label>
              <input type="text" class="form-control date_with_dmy" id="mis_from" autocomplete="off" name="mis_from" value="{{ $mis_from }}">
            </div>
            <div class="form-wrap mis_date" style="{{ $custom_period }}">
            <label>To:</label>
              <input type="text" autocomplete="off"  id="mis_to" class="form-control date_with_dmy" name="mis_to" value="{{ $mis_to }}">
            </div>
            <div class="form-wrap form-wrap-submit"><label>&nbsp;</label>
              <button type="submit" class="btn btn-block btn-primary waves-effect waves-classic" name="submit">Submit</button>
            </div>
          </form>
		</div>
		@endif
		
		@if(!empty($post))
			<?php 
				//pr($user_kpi_fms); 
				foreach($user_kpi_fms as $kpi_fms){
					$fms_id = $kpi_fms['fms'];
					//echo $fms_id.'==';
					$fms_name =  getFmsNameById($fms_id);
					?>
					<div class="col-md-12"><u><h4>{{ $fms_name }}</h4></u></div>
					<?php 
					$kpi_datas = getallKpiByFmsAndUseId($fms_id, $user_id);
					
					
					foreach($kpi_datas as $kpi_data){
						
						$total_task =0;
					$plAcBoth  =0;
					$old_pending_cnt =0;
					$beforeOrOntimeAct = 0;
					$lateDone = 0;
					$actionNotTaken = 0;

					$task_not_done='';
					$task_done_late='';
					$delay_in_doing_task='';
					$delay_days=0;
						
						//pr($kpi_data); die;
						$o_id = (array) $kpi_data['_id'];
						$kp_id =  $o_id['oid'];
						$kpi_name = $kpi_data['kpi_name'];
					?> 
						<div class="row">
							<div class="col-md-12"><p><u>{{ $kpi_name }}</u></p></div>
						</div>
					<?php
						$step_arr =  $kpi_data['step'];
						$step_cnt = count($step_arr);
						//pr($step_arr); 
						$notDoneAct = 0;
						$beforeOrOntimeAct = 0;
						$lateDone = 0;
						$actionNotTaken = 0;
						$task_not_done='';
						$task_done_late='';
						$delay_in_doing_task='';
						$delay_days=0;
						$total_task = '';
						
						// start loop for steps inside KPI
						$step_id = $step_arr[0];
						if($step_cnt==1 && $step_id!="5e9946af0f0e75131c0037f5"){ /* skip for step 16*/
							//echo $step_id; die;
							$step_data = getStepDataByStepId($step_id);
							$step_no = ' (step#'.$step_data['step'].')';
							$step_name = $step_data['fms_what'].$step_no;
							// now we will get all data from fms collection
							$fulldata = getDataforMISScore($fms_id,$user_id, $step_id,$kp_id, $mis_period, $mis_from, $mis_to);
							$old_pending = $fulldata['old_pending'];
							$fdata = $fulldata['fms_data'];
							$total_task = count($fdata[$kp_id]);
							
							//echo $step_id; //die;
							// loop for fdata
							
							if(array_key_exists($kp_id,$old_pending)){
							$old_pending_cnt = $old_pending[$kp_id];
							}else{
								$old_pending_cnt = 0;
							}
			
							//pr($fulldata); die('==blade==for-one==');
						
							$k=0;
								foreach($fdata[$kp_id] as $dkey=>$data){
										//pr($data); die;
										//pr($data[$k][$step_id]);
										//die('===insode=='); 
											//pr($data[$k][$step_id]);
											$actual_dt = date('Y-m-d', strtotime($data[$step_id]['actual']));
											$planned_dt = date('Y-m-d', strtotime($data[$step_id]['planed']));
											
											if($data[$step_id]['planed']!='' && $data[$step_id]['actual']!=''){
												if( strtotime($planned_dt) >= strtotime($actual_dt) ){
													$beforeOrOntimeAct++;
												}elseif(strtotime($planned_dt) < strtotime($actual_dt) ){
													
													$diff_dt = dateDiff($planned_dt, $actual_dt);
													$delay_days =$delay_days+$diff_dt;
													
													//echo $diff_dt.'=='.$delay_days.'==<br>';
													$lateDone++;
													
												}
												$plAcBoth++;
											}else{
												$actionNotTaken++;
											}					
											if($data[$step_id]['actual']==''){
													$notDoneAct++;
											}
								}
								
								if($plAcBoth==0 && $total_task>0){
									$task_not_done = -100;
								}elseif($plAcBoth==0 && $total_task==0 && $old_pending_cnt>0){
									$task_not_done = -100;
								}else if($plAcBoth>0 && $total_task>0){
									
										if($mis_period=='till_date'){
											$old_pending_cnt1=0;
										}else{
											$old_pending_cnt1=$old_pending_cnt;
										}
										
									$task_not_done = round(((($plAcBoth/($total_task+$old_pending_cnt1))*100) - 100),2);
								}else{
									$task_not_done= 0;
								}
								
								if($plAcBoth>0 && $total_task>0){	
									$task_done_late= round((($lateDone/$plAcBoth)*100),2);
								}else{
									$task_done_late= 0;
								}
								
								
								if($plAcBoth>0 && $lateDone>0){
									$delay_in_doing_task = round((($delay_days/$lateDone)*100),2);
								}else{
									$delay_in_doing_task= 0;
								}
							/* echo 'TotalTask = '.$total_task.'===plAcBoth='.$plAcBoth.'===notDoneAct='.$notDoneAct.'===beforeOrOntimeAct='.$beforeOrOntimeAct.'===lateDone='.$lateDone.'===actionNotTaken='.$actionNotTaken;
							echo "<br/>";
							echo 'task_not_done = '.$task_not_done.'===task_done_late='.$task_done_late.'===delay_in_doing_task='.$delay_in_doing_task; */
							
								/* echo 'TotalTask = '.$total_task.'===plAcBoth='.$plAcBoth.'===notDoneAct='.$notDoneAct.'===beforeOrOntimeAct='.$beforeOrOntimeAct.'===lateDone='.$lateDone.'===actionNotTaken='.$actionNotTaken; die; */  
							
						}elseif($step_cnt>1){
								$multiDatas = array();
								$step_name_arr = array();
								$old_pending=0;
								foreach($step_arr as $step_ids){
									//echo $step_ids.'==';
									$step_id = $step_ids;
									$step_data = getStepDataByStepId($step_id);
									$step_no = ' (step#'.$step_data['step'].')';
									//$step_name_arr = $step_data['fms_what'];
									array_push($step_name_arr, $step_data['fms_what'].$step_no);
									// now we will get all data from fms collection
									$fdata = getDataforMISScore($fms_id,$user_id, $step_id,$kp_id, $mis_period, $mis_from, $mis_to);
									//pr($fdata); 
									//var_dump($fdata['old_pending'][$kp_id]); 
									$old_pending = $old_pending+$fdata['old_pending'][$kp_id];
									//die('==blade==for-multiple=='.$old_pending);
									//$old_pending = $old_pending+$fdata['old_pending'];
									$multiDatas[$step_id] = $fdata['fms_data'][$kp_id];
									//pr($multiDatas);die('hii');
									//$kk = array_merge($multipleData,$multiDatas);
								} 
								$old_pending_cnt = $old_pending;
								//pr($multiDatas);die('hii');
								$nkk = array();
								$ppp = array();
								$total_task=0;
								foreach($multiDatas as $kpi_step=>$fmskpiData){
									//pr($kpi_step);
									//pr($fmskpiData); //die;
									$total_task_temp = count($fmskpiData);
									foreach($fmskpiData as $data){
										//pr($kpi_step); die;
										$actual_dt = date('Y-m-d', strtotime($data[$kpi_step]['actual']));
										$planned_dt = date('Y-m-d', strtotime($data[$kpi_step]['planed']));

										if($data[$kpi_step]['planed']!='' && $data[$kpi_step]['actual']!=''){
											if( strtotime($planned_dt) >= strtotime($actual_dt) ){
												$beforeOrOntimeAct++;
											}elseif(strtotime($planned_dt) < strtotime($actual_dt) ){
												$diff_dt = dateDiff($planned_dt, $actual_dt);
												$delay_days =$delay_days+$diff_dt;
												//echo $diff_dt.'=='.$delay_days.'==<br>';
												$lateDone++;
											}
											$plAcBoth++;
										}else{
											$actionNotTaken++;
										}					
										if($data[$kpi_step]['actual']==''){
												$notDoneAct++;
										}
									}
									$total_task=$total_task+$total_task_temp;
									
								}
								
								/* if($plAcBoth>0 && $total_task>0){
									$task_not_done = round(((($plAcBoth/($total_task+$old_pending_cnt))*100) - 100),2);
								} 
								if($plAcBoth>0 && $total_task>0){	
									$task_done_late= round((($lateDone/$plAcBoth)*100),2);
								}
								if($plAcBoth>0 && $lateDone>0){
									$delay_in_doing_task = round((($delay_days/$lateDone)*100),2);
								} */
								
								
								if($plAcBoth==0 && $total_task>0){
									$task_not_done = -100;
								}elseif($plAcBoth==0 && $total_task==0 && $old_pending_cnt>0){
									$task_not_done = -100;
								}else if($plAcBoth>0 && $total_task>0){
										
										if($mis_period=='till_date'){
											$old_pending_cnt1=0;
										}else{
											$old_pending_cnt1=$old_pending_cnt;
										}
									
									$task_not_done = round(((($plAcBoth/($total_task+$old_pending_cnt1))*100) - 100),2);
								}else{
									$task_not_done= 0;
								}
								
								if($plAcBoth>0 && $total_task>0){	
									$task_done_late= -round((($lateDone/$plAcBoth)*100),2);
								}else{
									$task_done_late= 0;
								}
								
								
								if($plAcBoth>0 && $lateDone>0){
									$delay_in_doing_task = -round((($delay_days/$lateDone)*100),2);
								}else{
									$delay_in_doing_task= 0;
								}
								
								/* echo 'TotalTask = '.$total_task.'===plAcBoth='.$plAcBoth.'===notDoneAct='.$notDoneAct.'===beforeOrOntimeAct='.$beforeOrOntimeAct.'===lateDone='.$lateDone.'===actionNotTaken='.$actionNotTaken;
									die; */
								
								//pr($ppp);die;
								/* pr($multipleData); die;
								echo "multiple=="; */
								//pr($step_name_arr); die;
								$step_name = implode(', ',$step_name_arr);
						}
						
						// check if it is step#16 OC FMS
						if($step_id=="5e9946af0f0e75131c0037f5"){
							$step_data = getStepDataByStepId($step_id);
							$step_no = ' (step#'.$step_data['step'].')';
							$step_name = $step_data['fms_what'].$step_no;
							// now we will get all data from fms collection
							$fulldata = getDataforMISScoreOcFms($fms_id,$user_id, $step_id,$kp_id, $mis_period, $mis_from, $mis_to);
							$fdata = $fulldata['fms_data'];
							//pr($fdata); die('--in--blade--');
							
							$quantity_variation_is_more =0;
							$total_number_of_order = count($fdata[$kp_id]);
							
							$total_quantity_dispatched_mtr = 0;
							$total_quantity_dispatched_kg = 0;
							$total_quantity_dispatched_yd = 0;
							
							$total_quantity_planned_mtr = 0;
							$total_quantity_planned_kg = 0;
							$total_quantity_planned_yd = 0;
							
							$dispatch_quantity_is_not_updated = 0; /* total number of orders in which step#16 not updated */
							$no_of_orders_dispatched = 0;  /* total number of orders in which step#17 actual */
							
							foreach($fdata[$kp_id] as $dkey=>$data){
									/* echo $step_id; echo "<br>"; */
									//pr($data); die;
									$step17_id = '5e9946b00f0e75131c0037f6';
									
									if($data[$step_id]['pl_qty']!='' && $data[$step_id]['act_qty']!='' && $data[$step_id]['act_qty']>$data[$step_id]['pl_qty']){
										$quantity_variation_is_more++;
									}
									
									
									/* if step17 actual then order dispatch */
									if($data[$step17_id]['planed']!='' && $data[$step17_id]['actual']!=''){
										
										if($data['unit']=='Mtr'){
											$total_quantity_dispatched_mtr = $total_quantity_dispatched_mtr+ (int) $data[$step_id]['act_qty'];
										}else if($data['unit']=='Kg'){
											$total_quantity_dispatched_kg = $total_quantity_dispatched_kg+ (int) $data[$step_id]['act_qty'];
										}else if($data['unit']=='Yd'){
											$total_quantity_dispatched_yd = $total_quantity_dispatched_yd+ (int) $data[$step_id]['act_qty'];
										}
										
										$no_of_orders_dispatched++;
									}
									
									/* for Total Quantity Planned  */
									
										if($data['unit']=='Mtr'){
											$total_quantity_planned_mtr = $total_quantity_planned_mtr+ (int) $data['bulkqty'];
										}else if($data['unit']=='Kg'){
											$total_quantity_planned_kg = $total_quantity_planned_kg+ (int) $data['bulkqty'];
										}else if($data['unit']=='Yd'){
											$total_quantity_planned_yd = $total_quantity_planned_yd+ (int) $data['bulkqty'];
										}
										
									/* for 3 formula */
									if($data[$step_id]['act_qty']==''){
										$dispatch_quantity_is_not_updated++;
									}
									
									//pr($data[$k][$step_id]);
									//die('===insode=='); 
							}
							
							$total_task = count($fdata[$kp_id]);
							//echo $total_task; die;
						?>
							<?php 
								$last_w='';
								$curr_w='';
								if($mis_period=='last_week'){
									$w_data = getWeekData($fms_id, $user_id, $kp_id, $mis_period);
									//pr($w_data);	
									if(!empty($w_data['last_w'])){
										$last_w=$w_data['last_w'];
										$l_task_not_done=$last_w['task_not_done'];
										$l_task_done_late=$last_w['task_done_late'];
										$l_delay_in_doing_task=$last_w['delay_in_doing_task'];
									}else{
										$l_task_not_done='';
										$l_task_done_late='';
										$l_delay_in_doing_task='';
									}
									
									if(!empty($w_data['curr_w'])){
										$curr_w=$w_data['curr_w'];
										$c_task_not_done=$curr_w['task_not_done'];
										$c_task_done_late=$curr_w['task_done_late'];
										$c_delay_in_doing_task=$curr_w['delay_in_doing_task'];
									}else{
										$c_task_not_done='';
										$c_task_done_late='';
										$c_delay_in_doing_task='';
									}
									
								}
								
							?>
							<div class="row">
								<div class="col-md-3">No. of Orders in which quantity variation is more than PI = {{ $quantity_variation_is_more }} </div>
								<div class="col-md-3">No. of Orders as per Planned date = {{ $total_number_of_order }}</div>
								
								<div class="col-md-3">+/- Total Quantity Dispatched =<br/> {{ $total_quantity_dispatched_mtr }} Mtr,  {{ $total_quantity_dispatched_kg }} Kg, {{ $total_quantity_dispatched_yd }} Yd</div>
								
								<div class="col-md-3">Total Quantity Planned = <br/>
								{{ $total_quantity_planned_mtr }} Mtr, {{ $total_quantity_planned_kg }} Kg, {{ $total_quantity_planned_yd }} Yd
								</div>
								
								<div class="col-md-3">No. of orders in which Dispatch Quantity is not updated = {{ $dispatch_quantity_is_not_updated }}</div>
								<div class="col-md-3">No. of orders Dispatched as per Actual Date of Step 17 = {{ $no_of_orders_dispatched }}</div>

							</div>
					
							<div class="col-md-12">
								1. % of orders in which the quantity variation is more than buyer requirement = No. of Orders in which quantity variation is more than PI/No. of Orders as per Planned date = {{ $quantity_variation_is_more }}/{{ $total_number_of_order }}
							</div>
							
							<div class="col-md-12">
								2. Total Variation (Actual) = (+/- Total Quantity Dispatched)/Total Quantity Planned as per PI

								= {{ $total_quantity_dispatched_mtr }}/{{ $total_quantity_planned_mtr }} Mtr, 
									{{ $total_quantity_dispatched_kg }}/{{ $total_quantity_planned_kg }} Kg, 
									{{ $total_quantity_dispatched_yd }}/{{ $total_quantity_planned_yd }} Yd
							</div>
							
							<div class="col-md-12">
								3. No. of orders in which the dispatch quantity is not updated = {{ $quantity_variation_is_more }}/{{ $total_number_of_order }}
							</div>
							
							<table class="table table-bordered table-hover table-striped table-mis" cellspacing="0" id="exampleAddRow">
								<tbody>
									@if($mis_period!='last_week')
									<tr>
										<th colspan="4" class="text-center"><strong>{!! $step_name !!}</strong></th>
									</tr>
									@endif
									@if($mis_period=='last_week')
									<tr>
										<th colspan="2" class="text-center"><strong>{!! $step_name !!}</strong></th>
										<th class="text-center"><strong>Planned</strong></th>
										<th class="text-center"><strong>Actual</strong></th>
										<th class="text-center">
											<strong>Next Planned</strong> 
											<button class="btn btn-primary" id="set_score" onclick="setScore('{{ $c_task_not_done }}', '{{ $c_task_done_late }}', '{{ $c_delay_in_doing_task }}','{{$fms_id}}','{{ $user_id }}','{{ $mis_period }}','{{ $kp_id }}');">Set Score</button>
										</th>
									</tr>
									@endif
									<tr>
										<td>1</td>
										<td>% of orders in which the quantity variation is more than buyer requirement</td>
										@if($mis_period=='last_week')
										<td>{{ $l_task_not_done }}</td>
										@endif
										<td>
											@if($total_quantity_planned_mtr>0)
												{{ round(($quantity_variation_is_more / $total_number_of_order), 2) }}
											@endif
											<?php /*?>
											<a href="{{ route('misdetails', $fms_id) }}?<?php //echo $misUrlTask; ?>&task=task_not_done" target="_blank">Get Details</a>
											<?php */?>
										</td>
										@if($mis_period=='last_week')
										<td>{{ $c_task_not_done }}</td>
										@endif
									</tr>
									<tr>
										<td>2</td>
										<td>Total Variation (Actual)</td>
										@if($mis_period=='last_week')
										<td>{{ $l_task_done_late }}</td>
										@endif
										<td>
											@if($total_quantity_planned_mtr>0)
												{{ round(($total_quantity_dispatched_mtr / $total_quantity_planned_mtr),2) }} Mtr,
											@endif
											
											@if($total_quantity_planned_kg>0)
												{{ round(($total_quantity_dispatched_kg / $total_quantity_planned_kg),2) }} Kg, 
											@endif
											
											@if($total_quantity_planned_yd>0)
												{{ round(($total_quantity_dispatched_yd / $total_quantity_planned_yd),2) }} Yd
											@endif
											
											
										</td>
										@if($mis_period=='last_week')
										<td>{{ $c_task_done_late }}</td>
										@endif
									</tr>
									<tr>
										<td>3</td>
										<td>No. of orders in which the dispatch quantity is not updated</td>
										@if($mis_period=='last_week')
										<td>{{ $l_delay_in_doing_task }}</td>
										@endif
										<td>
											@if($total_number_of_order>0)
											{{ round(($quantity_variation_is_more / $total_number_of_order),2) }}
											@endif
										</td>
										@if($mis_period=='last_week')
										<td>{{ $c_delay_in_doing_task }}</td>
										@endif
									</tr>
								</tbody>
							</table>
							<?php 
								$queryUrl = array(
													'search'=>'s',
													's_type'=>$mis_period,
													'user_id'=>$user_id,
													'kpi_id'=>$kp_id,
													'step_id'=>"5e9946b00f0e75131c0037f6",
													'merchant_data'=>$user_name,
													'mis_from'=>$mis_from,
													'mis_to'=>$mis_to,
													'qty_variation'=>1,
												);
								$misUrl= http_build_query($queryUrl);
							?>
					<a href="{{ route('misdetails', $fms_id) }}?<?php echo $misUrl; ?>" target="_blank">Get Details</a>
					<?php }else{ 
								//die('===17 step==');
								$queryUrl_old = array(
														'search'=>'s',
														's_type'=>$mis_period,
														'user_id'=>$user_id,
														'kpi_id'=>$kp_id,
														'step_id'=>$step_id,
														'merchant_data'=>$user_name,
														'mis_from'=>$mis_from,
														'mis_to'=>$mis_to,
														'old_pending'=>1,
													);
								$old_pendingUrl= http_build_query($queryUrl_old);
							?>
					<div class="row">
						<div class="col-md-3">TOTAL PLANNED TASK = {{ $total_task }} </div>
						<div class="col-md-3">TOTAL ACTUAL TASK = {{ $plAcBoth }}</div>
						<div class="col-md-3">BEFORE OR ON TIME AC = {{ $beforeOrOntimeAct }}</div>
						<div class="col-md-3">LATE DONE = {{ $lateDone }}</div>
						<div class="col-md-3">ACTION NOT TAKEN = {{ $actionNotTaken }}</div>
						<div class="col-md-3"><a href="{{ route('misdetails', $fms_id) }}?<?php echo $old_pendingUrl; ?>" target="_blank">OLD PENDING = {{ $old_pending_cnt }}</a></div>
						<div class="col-md-3">No. OF DELAY DAYS = {{ $delay_days }}</div>
					</div>
					<div class="row">
					<div class="col-md-12">
						Task not done = ((PLACBOTH/TOTAL_TASK+OLD_PENDING)*100) - 100 = (({{ $plAcBoth }}/ ({{ $total_task }} @if($mis_period!='till_date') +{{ $old_pending_cnt }} @endif ))*100) - 100
					</div>
					<div class="col-md-12">
						Task done late = ((LATE DONE/PLACBOTH)*100) = (({{ $lateDone }}/{{ $plAcBoth }})*100
					</div>
					<div class="col-md-12">
						Delay in doing task = ((No. OF DELAY DAYS/LATEDONE)*100)=  ({{ $delay_days }}/{{ $lateDone }})*100 
					</div></div>
						<?php 
						$last_w='';
						$curr_w='';
						if($mis_period=='last_week'){
							$w_data = getWeekData($fms_id, $user_id, $kp_id, $mis_period);
							//pr($w_data);	
							if(!empty($w_data['last_w'])){
								$last_w=$w_data['last_w'];
								$l_task_not_done=$last_w['task_not_done'];
								$l_task_done_late=$last_w['task_done_late'];
								$l_delay_in_doing_task=$last_w['delay_in_doing_task'];
							}else{
								$l_task_not_done='';
								$l_task_done_late='';
								$l_delay_in_doing_task='';
							}
							
							if(!empty($w_data['curr_w'])){
								$curr_w=$w_data['curr_w'];
								$c_task_not_done=$curr_w['task_not_done'];
								$c_task_done_late=$curr_w['task_done_late'];
								$c_delay_in_doing_task=$curr_w['delay_in_doing_task'];
							}else{
								$c_task_not_done='';
								$c_task_done_late='';
								$c_delay_in_doing_task='';
							}
							
						}
						
					?>
			
					<?php 
					
						/* check if step_id = step#16 id then put step_id = step#17 */
						//echo $step_id.'==='; die;
 						$queryUrlTask = array(
												'search'=>'s',
												's_type'=>$mis_period,
												'user_id'=>$user_id,
												'kpi_id'=>$kp_id,
												'step_id'=>$step_id,
												'merchant_data'=>$user_name,
												'mis_from'=>$mis_from,
												'mis_to'=>$mis_to,
											); 
							//pr($queryUrlTask); die;
							$misUrlTask= http_build_query($queryUrlTask);
					?>
					<table class="table table-bordered table-hover table-striped table-mis" cellspacing="0" id="exampleAddRow">
						<tbody>
							@if($mis_period!='last_week')
							<tr>
								<th colspan="4" class="text-center"><strong>{!! $step_name !!}</strong></th>
							</tr>
							@endif
							@if($mis_period=='last_week')
							<tr>
								<th colspan="2" class="text-center"><strong>{!! $step_name !!}</strong></th>
								<th class="text-center"><strong>Planned</strong></th>
								<th class="text-center"><strong>Actual</strong></th>
								<th class="text-center">
									<strong>Next Planned</strong> 
									<button class="btn btn-primary" id="set_score" onclick="setScore('{{ $c_task_not_done }}', '{{ $c_task_done_late }}', '{{ $c_delay_in_doing_task }}','{{$fms_id}}','{{ $user_id }}','{{ $mis_period }}','{{ $kp_id }}');">Set Score</button>
								</th>
							</tr>
							@endif
							<tr>
								<td>1</td>
								<td>% Task not done</td>
								@if($mis_period=='last_week')
								<td>{{ $l_task_not_done }}</td>
								@endif
								<td>
									{{ $task_not_done }}%   
									<?php /*?>
									<a href="{{ route('misdetails', $fms_id) }}?<?php //echo $misUrlTask; ?>&task=task_not_done" target="_blank">Get Details</a>
									<?php */?>
								</td>
								@if($mis_period=='last_week')
								<td>{{ $c_task_not_done }}</td>
								@endif
							</tr>
							<tr>
								<td>2</td>
								<td>% Task done late</td>
								@if($mis_period=='last_week')
								<td>{{ $l_task_done_late }}</td>
								@endif
								<td>
									@if($task_done_late>0)
										-{{$task_done_late}}%
									@else 
										{{$task_done_late}}%
									@endif
									
									<?php /*?>
									<a href="{{ route('misdetails', $fms_id) }}?<?php //echo $misUrlTask; ?>&task=task_done_late" target="_blank">Get Details</a>
									<?php */?>
								</td>
								@if($mis_period=='last_week')
								<td>{{ $c_task_done_late }}</td>
								@endif
							</tr>
							<tr>
								<td>3</td>
								<td>% Delay in doing task</td>
								@if($mis_period=='last_week')
								<td>{{ $l_delay_in_doing_task }}</td>
								@endif
								<td>
									@if($delay_in_doing_task>0)
										-{{ $delay_in_doing_task }}%
									@else 
										{{ $delay_in_doing_task }}%
									@endif
									
								</td>
								@if($mis_period=='last_week')
								<td>{{ $c_delay_in_doing_task }}</td>
								@endif
							</tr>
						</tbody>
					</table>
					<?php 
						$queryUrl = array(
											'search'=>'s',
											's_type'=>$mis_period,
											'user_id'=>$user_id,
											'kpi_id'=>$kp_id,
											'step_id'=>$step_id,
											'merchant_data'=>$user_name,
											'mis_from'=>$mis_from,
											'mis_to'=>$mis_to,
										);
						//pr($queryUrl); die;
						$misUrl= http_build_query($queryUrl);
					?>
					<div class="row"><div class="col-md-12"><a href="{{ route('misdetails', $fms_id) }}?<?php echo $misUrl; ?>" target="_blank">Get Details</a></div></div>
					<?php } ?>
					
					<div class="col-md-12"><hr></div>
					<?php
					//die;
					}
				} // end kpi_fms loop
			?>
		@endif
    </div>
    
    <!-- End Panel Table Add Row --> 
    </div>
  </div>
</div>

<!-- Set score popup start -->
<button data-toggle="modal" data-target="#change_date_time_model" style="display:none;" id="score_popup"></button>
	<div class="modal fade modal-info user-list-box" id="change_date_time_model" role="dialog">
		<div class="modal-dialog modal-sm">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title">Next Planned Score</h4>
				</div>
				<div class="modal-body">
					<div class="data_update"></div>
					<div class="pop_form row">
						<div class="col-md-12">
							<form action="" method="POST" onsubmit="event.preventDefault(); update_kpi_target();" id="update_kpi_form">
								
								<input type="hidden" name="fms_id" id="p_fms_id" value="">
								<input type="hidden" name="user_id" id="p_user_id" value="">
								<input type="hidden" name="mis_period" id="p_mis_period" value="">
								<input type="hidden" name="kpi_id" id="kpi_id" value="">
								
								% Task not done: <input type="text" autocomplete="off" name="task_not_done" id="task_not_done" value="" class="form-control"><br>
								% Task done late: <input type="text" autocomplete="off" name="task_done_late" id="task_done_late" value="" class="form-control"><br>
								% Delay in doing task: <input type="text" autocomplete="off" name="delay_in_doing_task" id="delay_in_doing_task" value="" class="form-control"><br>
								
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
<!-- Set score popup end -->

@endsection



@section('custom_validation_script')
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
<script type="text/javascript">
	$('body').on('focus', ".date_with_dmy", function() {
		$('.date_with_dmy').datepicker({
			dateFormat: 'dd-mm-yy'
		});
	});
		
		
	function setScore(c_task_not_done, c_task_done_late, c_delay_in_doing_task,p_fms_id, p_user_id, p_mis_period, kpi_id){
		//alert('hii'); return false;
		$('#task_not_done').val(c_task_not_done);
		$('#task_done_late').val(c_task_done_late);
		$('#delay_in_doing_task').val(c_delay_in_doing_task);
		
		
		$('#p_fms_id').val(p_fms_id);
		$('#p_user_id').val(p_user_id);
		$('#p_mis_period').val(p_mis_period);
		$('#kpi_id').val(kpi_id);
		
		$('.data_update').html('');
		$('#score_popup').trigger('click');
	}
	
	$('#mis_period').on('change', function(){
		var thisVal = $(this).val();
		if(thisVal=='custom_period'){
			$('#mis_from').val('');
			$('#mis_to').val('');
			$('.mis_date').show();
		}else{
			$('#mis_from').val('');
			$('#mis_to').val('');
			$('.mis_date').hide();
		}
	})

	function validateForm(){
		var mis_period = $('#mis_period').val();
		if(mis_period==''){
			alert('Please select Period.'); return false;
		}else if(mis_period=='custom_period'){
			var mis_from = $('#mis_from').val();
			var mis_to = $('#mis_to').val();
			if(mis_from=='' || mis_to==''){
				alert('From and To date could not be blank.'); return false;
			}
		}
	}
	
	function update_kpi_target(){		
		var formData = $('#update_kpi_form').serialize();
		var ajax_url = "<?php echo route('ajax_update_kpi_target'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res);
					if(res=='1' || res=='2'){ 
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">KPI Target updated successfully.</p>');
					}
					// current_date
				}
			});
	} 
	
</script> 
@endsection