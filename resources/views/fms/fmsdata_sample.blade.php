@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'Sample FMS Data')

@section('pagecontent')
<style type="text/css">
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
</style>
<div class="page no-border" id="fms_data"> 
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
			<div class="row">
				<div class="col-md-2 logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
				
				<div class="col-md-4 search-form-wrap">
					<select class="form-control float-left" onchange="location = '{{route('fmsdata', $fms['_id'])}}/'+this.options[this.selectedIndex].value;" id="order_range" style="margin-top: 14px; width:150px;">
						<option value=""> --Select Month--</option>
						{!! fms_order_month_dropdown_new1($fms['_id'], $order_range) !!}
					</select>
				</div>
				
			</div>
			
			
          <div class="panel-body">
			<div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
			
			<?php 
			
			$pm_user_id = getCurUserField('pm_user');
			
			/* $u = get_task_id_by_fms_id('5d0b1c4e0f0e75030c0067c2');
			pr($u); 
			$stepsIds = get_step_id_by_fms_id('5d0b1c4e0f0e75030c0067c2');
			pr($stepsIds); tailorLists */ 
			
			$tailorLists = get_tailor_list_api();
			$tailorListArr = array();
			foreach($tailorLists as $tailorList){
				//$tailorListArr[$tailorList->id] = $tailorList->f_name;
				array_push($tailorListArr, $tailorList->id);
			}
			
			//$embroiders = get_embroider_list_api();
			$embroiders = get_h_embroider_list_view_api();
			$embroidersArr_h = array();
			foreach($embroiders as $embroider){
				$embroidersArr_h[$embroider->id] = $embroider->f_name;
			}
			
			$embroiders = get_m_embroider_list_view_api();
			$embroidersArr_m = array();
			foreach($embroiders as $embroider){
				$embroidersArr_m[$embroider->id] = $embroider->f_name;
			}
			
			
			$fms_id = $fms['_id'];
			$itemsArrAll = get_item_list_api();
			$itemsArr = $itemsArrAll['itemIdCodeArr']; // do not remove
			//pr($itemsArr); die;
			
			$currentUserId = Auth::user()->id;
			//echo $currentUserId.'============';
			
			/* sample designer */
			$sample_designer_arr = getuserlistbyrole(5,1);
			?>
			
            <table class="table table-bordered table-hover table-striped tbale_header fixed_table" cellspacing="0" id="fmsdataTable">
				<thead>
						<tr class="tbale_header task_top_fix top-row">
							<th colspan="<?php echo count($taskdatas)+1;?>" class="text-center font-weight-bold task_left_fix purple-bg-top" style="border-right:5px solid green;"><span class="fms-name">{{$fms['fms_name']}}</span></th>
							
							<?php 
								$cnt = count($stepsdatas); 
								$stepArr = array();
								//pr($stepsdatas); die;
								$step_no_cnt = 0;
								?>
							@foreach($stepsdatas as $stepsdata)
							<?php 
								$step_no_cnt++;
								if($cnt==$no)
								{
									$styleBorder='style="border-right:5px solid green;"';
								}else{
									$styleBorder='';
								}
								
							?>

							<?php 
								$userRightArr = array();
								if(!empty($stepsdata['user_right'])){
									$step_user_right = $stepsdata['user_right'];
									if(array_key_exists($currentUserId,$step_user_right)){
										$userRightArr = $stepsdata['user_right'][$currentUserId];
									}
								}
								
								
							?>
							
							<?php if (in_array(1, $userRightArr) || userHasRight()) { ?>
							
							<?php 
								$input_type1='';
								
								if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date'))
								{
									$input_type1 = $stepsdata['fms_when']['input_type'];
									if($input_type1=='notes')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($input_type1=='fabricator')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==6)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										<?php /* ?> {{ __('#') }}{{ $step_no_cnt }} <?php */ ?>
										<?php	echo $stepsdata['fms_what'].'</th>';
										$step_no_cnt--;
										
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} 
										<?php	echo $stepsdata['fms_what'].'</th>';
									}
									
								}else{
									
									
									/* $black_bg='';
									if($stepsdata['fms_what']=='Fabric is ready'){
										$black_bg = " black-bg";
									} */
								?>
									<th class="text-center font-weight-bold step-title black-bg" colspan="2"> 
										<div class="auth-icon">
											
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
											{{ __('#') }}{{ $step_no_cnt }}
											{{ $stepsdata['fms_what'] }}
										</div>
									</th>
									<?php 
										
									} 
								?>
							
							<?php }?>
							@endforeach
							
							
							@if( userHasRight())
							<th width="12%"></th>
							@endif
							
						</tr>
						
						
						<tr class="tbale_header text-center  task_top_fix">
							<?php 
								$taskArr = array();
								$tbl_cell=0;
								$no=0;
								$cnt = count($taskdatas);
							?>
							<th class="bg-info text-white task_left_fix purple-bg">Sr. No</th>
							@foreach($taskdatas as $taskdata)
							
							<?php 
								$taskArr[$no] = $taskdata['_id'];
								
								$tbl_cell++;
								$no++;
								if($no==$cnt)
								{
									$borderStyle = 'style="border-right:5px solid green;"';
								}else{
									$borderStyle='';
								}
								
								if($taskdata['task_name']!='Stage'){
									$purple=' purple-bg';
								}else{
									$purple='';
								}
							?>
							<th <?php echo $borderStyle; ?> class="bg-info text-white task_left_fix{{ $purple }}">{{$taskdata['task_name']}}</th>
							
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
							?>
							
							<?php if (in_array(1, $userRightArr) || userHasRight()) { ?>
							<?php 
								$input_type='';
								if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date'))
								{
									/* $input_type = $stepsdata['fms_when']['input_type'];
									if($input_type=='notes')
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">Notes</th>';
									}else if($input_type=='fabricator')
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">Fabricator Name</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">'.$stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==6)
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">Fabric Comments</th>';
									}else if($fms_when_type==7)
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">'.$stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==8)
									{
										$tbl_cell++;
										echo '<th class="bg-dark text-white light-gray" colspan="2">Patching</th>';
									} */
									
									
								}else{
									
								$tbl_cell++; 
								?>
								<?php 
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
							
							@if( userHasRight())
							<th width="12%">Action</th>
							@endif
							
						</tr>
						
						
				</thead>
			  
				<tbody id="fmsTable">
						<?php 
						//die();
							$row_num=0;
							$time_active='';
						?>
						@foreach($fmsdatas as $fmsdata)
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
							<td class="text-center task_left_fix">{{ $row_num+1 }}</td>
							<!---Task data-->
							<?php 
								
								if(!array_key_exists('pm_assign', $fmsdata) || userHasRight()){
									$time_active='';
								}elseif($fmsdata['pm_assign']==$pm_user_id || userHasRight()){
									$time_active='';
								}else{
									$time_active='style="display:none;"';
								}
								
								
								/********************** for patching data ***********************/
								$isPatching = getPatchingByfmsId($fms_id);
								$patching = '';
								if(!empty($isPatching)){
									//$patching='Yes patching';
									$pdata = $isPatching;
									$patching = '<select class="form-control" name="row[r_num][task]['.$taskdata['_id'].']" required="" onchange="patchingSteps(this.id)" id="p_r_num_'.$fms_id.'"><option value="">--Select--</option>';
									foreach($pdata as $pkey=>$pval) {
										$patching .= '<option value="'.$pkey.'">'.$pval['p_name'].'</option>';
									}
									$patching .= '</select>';
									//echo $patching."<pre>"; print_r($pdata); die;
									
								}
								/********************** for patching data ***********************/							
								
								//echo $patching."<pre>"; print_r($pdata); die;
								
								$no=0; $row_num++;
								$cnt = count($taskdatas);
								$k=1;
								$order_no ='';
							?>
							@foreach($taskdatas as $taskdata)
								<?php
								
								//echo "<pre>";
								//print_r($taskdata); echo "</pre>";
								
									$no++;
									if($no==$cnt)
									{
										$borderStyle = 'style="border-right:5px solid green;"';
									}else{
										$borderStyle='';
									}
									
									if($taskdata['custom_type']=='orders')
									{
										$order_no = $fmsdata[$taskdata['_id']];
									}
									
								
								if($taskdata['custom_type']=='date')
								{
								?>
								
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									<?php 
										if(!array_key_exists($taskdata['_id'],$fmsdata)){
											echo "No data";
										}else{
											echo changeDateToDmy($fmsdata[$taskdata['_id']]);
										}
									?>
								
								</td>
								
								<?php								
								}else if($taskdata['custom_type']=='orders')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									{{$fmsdata[$taskdata['_id']]}}
								</td>
								<?php }else if($taskdata['custom_type']=='delivery_date')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									<?php 
										if(!array_key_exists($taskdata['_id'],$fmsdata)){
											echo "No data";
										}else{
											echo changeDateToDmy($fmsdata[$taskdata['_id']]);
										}
										//{{ changeDateToDmy($fmsdata[$taskdata['_id']]) }}
									?>
									
								</td>
								<?php } else if($taskdata['custom_type']=='stage')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									<div class="fms_stage_row{{ $row_num }}">
										<?php //pr($fmsdata[$taskdata['_id']]);?>
										@if(is_array($fmsdata[$taskdata['_id']]) && array_key_exists('stage_text', $fmsdata[$taskdata['_id']]))
											
										<?php 
												$datediff='';
												$out_date='';
												if($fmsdata[$taskdata['_id']]['stage_text']=="Complete" || $fmsdata[$taskdata['_id']]['stage_text']=="Item Complete & Packed"){
													$comp_icon_cls = 'comp_icon';
													$stage_text = '';
												}else{
													$comp_icon_cls ='';
													$stage_text = $fmsdata[$taskdata['_id']]['stage_text'];
													
													$last_update ='';
													if(array_key_exists('last_update', $fmsdata[$taskdata['_id']])){
														$last_update = $fmsdata[$taskdata['_id']]['last_update'];
														$datediff = dateDiffInDays($last_update, date('Y-m-d H:i:s', time()));
														if($datediff>5){
															$out_date = "out-date";
														}
													}
													
												}
											?>
											
										<span class="{{ $comp_icon_cls }}" style="color:{{$fmsdata[$taskdata['_id']]['stage_text_color']}}">
											{{ $stage_text }}
										</span>
										
										@endif
									</div>									
								</td>
								<?php }else if($taskdata['custom_type']=='patching')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									@if(empty($patching))
										<a href="{{ route('insertpatching', $fms_id) }}">Enter Patching Details</a>
									@else
									{!! $patching !!}
									@endif
									
								</td>
								<?php }else{
								
								if($k==3){ ?> 
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}</td>
								<?php }else{
								?>
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									<?php 
									if($taskdata['custom_type']=='fabricators'){
									/*?> {{$fmsdata[$taskdata['_id']]}} {!! $fabricatorHtml !!}
										
										<a href="{{route('fabricator', [$fmsdata['fb_id'], $fms['_id']] )}}">
										<span id="row{{$row_num}}_{{ $taskdata['_id'] }}">{{$fmsdata[$taskdata['_id']]}}</span>
										</a>
									<?php */?>
									
										<?php if(!empty($fmsdata[$taskdata['_id']])){ ?>
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');">
											<span id="row{{$row_num}}_{{ $taskdata['_id'] }}">{{$fmsdata[$taskdata['_id']]}}</span>
										</a>
										<?php }else{ ?>
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');">Click</a>
										<?php }
									}else if($taskdata['input_type']=='builtin_options' && $taskdata['custom_type']=='items'){?> 
										{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
									<?php }else if($taskdata['input_type']=='builtin_options' && $taskdata['custom_type']=='sample_designer'){ 
											
												$dataId = $fmsdata['_id'];
												$dataTaskId = $taskdata['_id'];
												//$tId = "'".$dataTaskId."'";
												$tId = "'pm_assign'";
												$dtId = "'".$dataId."'";
												$row_num_stage = "'row".$row_num."'";
												
												$pm_id='';
												$designer_name='';
												if(array_key_exists('pm_assign', $fmsdata)){
													$pm_id=$fmsdata['pm_assign'];
													$designer_name=$sample_designer_arr[$pm_id];
												}
												
												if(userHasRight()){
													$selectedVal='';
													$designer_drop='<select style="width:80px;" name="pm_assign" onchange="clicentOrderSave(this.value, '.$tId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
													foreach($sample_designer_arr as $id=>$name){
														$selectedVal = $id==$pm_id?'selected':'';												
														$designer_drop.='<option value="'.$id.'" '.$selectedVal.'>'.$name.'</option>';
													}											
													$designer_drop.='</select>';
													echo $designer_drop;
												}else{
													echo $designer_name;
												}
												
											
									}else { 
											?>
											{{$fmsdata[$taskdata['_id']]}}
											<?php } 
									
									?>
										
									</td>
								<?php
									}
									
								} 
								$k++;
								?>
							@endforeach
							<?php //$itemsArr[$fmsdata[$taskdata['_id']]]; ?>
							<!---Stap data-->
							<?php 
							$ac_and_dc_date = ''; 
							$ac_and_dc_date_flag = ''; 
							
							////////////////////////////////////////////////////////////////////////////////////
							$kk=1;
							$acDatePrev=array();
							$pmChk2='';
							//echo $order_no; pmChk
							?>
							@foreach($stepsdatas as $stepsdata)

							<?php
							//echo '===='.$order_no.'---';
							if(array_key_exists($stepsdata['_id'], $fmsdata)){
									
							
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
								/* ********************* 14 june 19 ******************** */
								
								
								if (in_array(1, $userRightArr) || userHasRight()) { ?>
								<?php 
								$plane_date='';
								if(array_key_exists('planed',$fmsdata[$stepsdata['_id']])){
										$plane_date = $fmsdata[$stepsdata['_id']]['planed'];
								}
								
								if (strtotime($plane_date)>strtotime(0) || $plane_date=='none')
								{ 
									$endTime = checkStepEndtime($fmsdata[$stepsdata['_id']]['planed']);
									$planeAlert='';
									if($endTime<5 && empty($fmsdata[$stepsdata['_id']]['actual'])){
										$planeAlert = 'style="background:#f6f041;"';
									}else{
										$planeAlert = '';
									}
									
								?>
								<td class="text-center" <?php echo $planeAlert; ?>>{{changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed'])}}</td>
								
									<?php
										$actual='';
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];
										
										$planed_date=$fmsdata[$stepsdata['_id']]['planed'];
										
										if($actual_date!=''){
											$dateHtml =  dateCompare($planed_date, $actual_date);
											?>
											
											<?php if (in_array(3, $userRightArr) || userHasRight()) {
											$ac_date = "'".date('d-m-Y H:i', strtotime($actual_date))."'";
											
											/* echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" onclick="openDeteTimeModel('.$ac_date.')">'.$dateHtml['date']."</a></td>"; */
											
											echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'">'.$dateHtml['date']."</td>";
											
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
											
											$process= '';
											$pmChk_no ='';
											if(array_key_exists('process', $stepsdata)){
												$process= $stepsdata['process'];
												$pmChk_no =$order_no;
											}
											
										?>
										<td class="text-center">
											<?php									
												if($kk==3){
													$ac_and_dc_date = '';
												}									
												//echo $kk.'===';
											?>
											
											@if(in_array(2, $userRightArr) || userHasRight())
											<a href="javascript:void(0)" {!! $time_active !!} class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk_no }}" store_room_id="sample" process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
											@endif
										</td>
										<?php
										}									 
										?>
								
								<?php }else{?>
									@if(array_key_exists('fabricator_id', $fmsdata[$stepsdata['_id']]))
										<td class="text-center" colspan="2"><a href="{{route('fabricator', [$fmsdata[$stepsdata['_id']]['fabricator_id'], $fms['_id']] )}}">{{$fmsdata[$stepsdata['_id']]['planed']}}
										</a></td>
									@endif
									
									<?php 
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
										///////////////////
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
										
										
										 // order_no
										if(array_key_exists('pm_check', $stepsdata)){
											$pmChk =$order_no;
											$pmChk2 =$order_no;
										}else{
											$pmChk = '';
										}
										
										if($fmsdata[$stepsdata['_id']]['none_date']!=''){ ?>
											<td class="text-center" colspan="2"> 
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }}
											</td>
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										@if(in_array(2, $userRightArr) || userHasRight())
										<a href="javascript:void(0)" {!! $time_active !!} class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" store_room_id="sample" data_id="{{$fmsdata['_id']}}" <?php //echo $ac_and_dc_date; ?> pm_check="{{ $pmChk }}" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
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
										///////////////////
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
										
										 
										if(array_key_exists('delivery_date', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['delivery_date']!=''){
										?>
											<td>{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['delivery_date']) }}</td>
											<td>
												@if(in_array(2, $userRightArr) || userHasRight())
												
												<a href="javascript:void(0)" {!! $time_active !!} class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['delivery_date']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk2 }}" store_room_id="main"> <i class="icon md-time" aria-hidden="true"></i></a>
									
												
												<a href="javascript:void(0);" onclick="customStorekRoom('stp_{{$stepsdata['_id']}}_row{{$row_num}}', '{{$fmsdata[$stepsdata['_id']]['delivery_date']}}', '{{$fmsdata['_id']}}', '{{ $pmChk2 }}');" id="room_{{ $pmChk2 }}" class="store_room_row{{$row_num}}">Custom</a>
												

												@endif
											</td>
										
										<?php }?>
										
										<?php } ?>
									
									<?php
									/***** for Fabric Comments ****/
									$custom_options='';
									$built_options='';
									
									if(array_key_exists('fms_when_type', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['fms_when_type']==6){
										$custom_options=$fmsdata[$stepsdata['_id']]['fms_when_type'];
										$custom_options_id=$fmsdata[$stepsdata['_id']]['input_type'];
										
										if(array_key_exists('fabric_comments', $fmsdata[$stepsdata['_id']])){
											$fabric_comments=$fmsdata[$stepsdata['_id']]['fabric_comments'];
										}else{
											$fabric_comments='';
										}
										
										
										$custom_opt_rows = getBuiltOptionlistById($custom_options_id);
										//pr($custom_opt_rows);
										$dataId = $fmsdata['_id'];
										$dataStepId = $stepsdata['_id'];
										
										$custom_opt_Html='';
										$sId = "'".$dataStepId."'";
										$dtId = "'".$dataId."'";
										$row_num_stage = "'row".$row_num."'";
										$custom_opt_Html.='<td class="text-center" colspan="2"><select name="custom_opt_row" data_id="drgdsfgd" id="row_'.$dataId.'" class="form-control" onchange="saveFabricCmnt(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
										foreach($custom_opt_rows as $custom_opt_row){
											if($custom_opt_row==$fabric_comments){
												$selc = " selected";
											}else{
												$selc="";
											}
											
											
											$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
										}
										echo $custom_opt_Html.='</select></td>';
										
									}
										
									if(array_key_exists('built_options', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['built_options']==7){
										
											$dataId = $fmsdata['_id'];
											$dataStepId = $stepsdata['_id'];
											
											$custom_opt_Html='';
											$sId = "'".$dataStepId."'";
											$dtId = "'".$dataId."'";
											$row_num_stage = "'row".$row_num."'";
										
										if($fmsdata[$stepsdata['_id']]['built_options_id']=='tailor_name'){
											
											$built__opt_Html='';
											$order_embroidery_id='';
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
											//dropdown_id
												$dropdown_id='';
												if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
													$dropdown_id=1;
												}
												foreach($tailorLists as $tailorList){
													if($dropdown_id==1){
														$selected_tailor = $fmsdata[$stepsdata['_id']]['dropdown_id']==$tailorList->id?' selected':'';
													}else{
														$selected_tailor='';
													}
													
													//$selected_fab = '';
													$built__opt_Html.='<option value='.$tailorList->id.' '.$selected_tailor.'>'.$tailorList->f_name.'</option>';
												}
											
											echo $built__opt_Html.='</select></td>';
											
										}elseif($fmsdata[$stepsdata['_id']]['built_options_id']=='h_embroider_name'){
											$built__opt_Html='';
											$order_embroidery_id='';
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">N/A</option>';
											
											$dropdown_id='';
											if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
												$dropdown_id=1;
											}
											foreach($embroidersArr_h as $key=>$val){
												if($dropdown_id==1){
													if($key==$fmsdata[$stepsdata['_id']]['dropdown_id']){
														$selc = " selected";
													}else{
														$selc="";
													}													
												}else{
													$selc="";
												}
												$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
											}
											
											echo $built__opt_Html.='</select></td>';
										}elseif($fmsdata[$stepsdata['_id']]['built_options_id']=='m_embroider_name'){
											$built__opt_Html='';
											$order_embroidery_id='';
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">N/A</option>';
											
											$dropdown_id='';
											if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
												$dropdown_id=1;
											}
											foreach($embroidersArr_m as $key=>$val){
												if($dropdown_id==1){
													if($key==$fmsdata[$stepsdata['_id']]['dropdown_id']){
														$selc = " selected";
													}else{
														$selc="";
													}													
												}else{
													$selc="";
												}
												$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
											}
											
											echo $built__opt_Html.='</select></td>';
										}else{
											echo "";
										}
										
									}
									
									/* $prdata = $stepsdata;
									pr($prdata); */
									
									?> 
									
								<?php if($stepsdata['fms_when_type']==2 && $stepsdata['process']=='move_to_sho'){ ?>
										<td class="text-center">
											<?php 
												$ac_date = date('d-m-Y H:i');
												//row{{$row_num}}
											?>
											<span id="set_date_{{ $stepsdata['_id'] }}_row<?php echo $row_num;?>">
												<button onclick="openDeteTimeModel('<?php echo $fmsdata['_id'];?>', '<?php echo $ac_date;?>', '<?php echo $stepsdata['_id'];?>', 'row<?php echo $row_num;?>')">Date</button>
											</span>
											
											
										</td>
										<td class="text-center">
											<?php 
												if($fmsdata[$stepsdata['_id']]['actual']==''){
													$hideAct = 'display:none;';
												}else{
													$hideAct = '';
												}
											?>
											<a href="javascript:void(0)" {!! $time_active !!} class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['actual']}}" data_id="{{$fmsdata['_id']}}" pm_check="" store_room_id="" style="{{ $hideAct }}"> <i class="icon md-time" aria-hidden="true"></i></a>
											
										</td>
								<?php } ?>
								
									
								<?php } ?>
								
								
								<?php } $kk++; ?>
								
								<?php }else{
									echo "<td colspan='2'>No data</td>";
								} ?>
								
							@endforeach
							<!-- end of steps inside data -->
							
							@if( userHasRight())
							<td class="text-center">
								<a href="javascript:void(0)" class="btn btn-danger" onclick="deleteRow({{$row_num}}, '{{ $fmsdata['_id']}}')">DELETE</a>
							</td>
							@endif
						</tr>
						<?php //break; ?>
						@endforeach
						<?php 
						
							$taskArr = implode(',', $taskArr);							
							$stepArr = implode(',', $stepArr);							
							$fms_id = $fms['_id'];
							
						?>
						
						
				</tbody>
            </table>
			<!-- pagination start -->
			@if($fmsdatas_pg['total']>100)
			<?php 
				$noActive='';
				$current_pgnum='';
				$page_url = url()->full();
					$pg_url = explode('=',$page_url);
					if(count($pg_url)>1){
						$current_pgnum = $pg_url[1];
					}else{
						$current_pgnum='';
					}
					$noActive=1;
			?>
			
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-6">
					<nav>
						<ul class="pagination">
							<?php
							for($pg=1; $pg<=$fmsdatas_pg['last_page']; $pg++){
								if(!isset($_GET['page'])){
									$active = $pg==1?' active':'';
								}else{
									$active = $pg==$current_pgnum?' active':'';
								}
								
							?>	
							<li class="page-item<?php echo $active;?>">
								<a class="page-link" href="{{ $fmsdatas_pg['path'] }}?page={{ $pg }}">{{ $pg }}</a>
							</li>
							<?php } ?>
						</ul>
					</nav>
				</div>
			</div>
			@endif
			<!-- pagination END -->
			
				@if( userHasRight() )
				<div id="Fms_data_form_table" style="display:none;" class="add-table-data">
			
					<table class=" table-bordered table-hover add-table-heading " cellspacing="0">
						<thead>
								
								<tr class="tbale_header">
								
									@foreach($taskdatas as $taskdata)
									<th class="text-center">{{$taskdata['task_name']}}</th>
									@endforeach
									
									@foreach($stepsdatas as $stepsdata)
									<th colspan="2" class="text-center">{{$stepsdata['fms_what']}}</th>
									@endforeach
									<th class="text-center">Action</th>
								</tr>
								
						</thead>
						
					</table>
				
				</div>
				
				<?php /*?>
				<a  class="btn btn-primary waves-effect waves-classic float-right" onclick="addTextField({{ $tbl_cell }}, '<?php echo $fms_id; ?>')" href="javascript:void(0)">
					<i class="icon md-plus" aria-hidden="true"></i> Add New Row
				</a>
				<?php */?>
				
				@endif
				
          </div>
        </div>
        <!-- End Panel Table Add Row -->
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
			<h4 class="modal-title">User List</h4>
		</div>
        <div class="modal-body">
			<div id="user_update"></div>
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
			<div id="user_form">
			<?php 
					$user_form_html = '<form id="user_permit_form" onsubmit="event.preventDefault(); stepUserPermitSubmit()">
										<ul style="list-style:none;">
												<input type="hidden" name="_token" value="'.csrf_token().'">
												<input type="hidden" name="step_id" value="" id="user_step_id">';
												foreach(getUserList($fms_id) as $user)
												{
												$user_form_html.='<li>
																	<div class="checkbox-custom checkbox-success">
																		<input type="checkbox" name="user_id[]" value="'.$user['_id'].'" class="user_id_list" id="user_'.$user['_id'].'" onclick="user_id_list(this.value)">
																		<label>'.$user['full_name'].'</label>
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
								$user_form_html.='<button type="submit" name="user_permit" class="btn btn-primary block">Save</button>';
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


<!-- Modal -->
<button data-toggle="modal" data-target="#fabricator_model" id="fabricator_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="fabricator_model" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Select Fabricator</h4>
			</div>
			<div class="modal-body">
				<div class="fab_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveFabricator();" id="fabricatorForm">
							{!! $fabricatorHtml !!}
							<br>
							<input type="hidden" name="data_id" id="data_id" value="">
							<input type="hidden" name="step_id" id="step_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="fab_name" value="" id="fab_name">
							<input type="hidden" name="fab_id" value="" id="fab_id">
							<input type="hidden" name="fab_id_with_row" value="" id="fab_id_with_row">
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
<!-- fabricator model end -->


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
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveComment();" id="commentForm">
							@csrf
							<input type="hidden" name="data_id" id="cmnt_data_id" value="">
							<input type="hidden" name="step_id" id="cmnt_step_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="cmnt_id_with_row" value="" id="cmnt_id_with_row">
							<textarea class="form-control" placeholder="Comments" name="user_cmnt" id="user_cmnt"></textarea>
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
<!-- comment model end -->

@endif

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

<!-- Store Room Modal -->
<button data-toggle="modal" data-target="#store_room" id="store_room_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="store_room" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Select Store Room</h4>
			</div>
			<div class="modal-body">
				<div class="room_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POST" onsubmit="event.preventDefault(); saveCustomStorekRoom();" id="saveStoreRoom">
							<select class="form-control" id="store_room_id" name="store_room_id" required="">
								<option value="">Please Select Room</option>
								@foreach(get_stock_room_list_api() as $key=>$val)
									<option value="{{ $val->id }}">{{ $val->name }}</option>
								@endforeach						
							</select>
							<br>
							<input type="hidden" name="r_data_id" id="r_data_id" value="">
							<input type="hidden" name="r_fms_id" id="r_fms_id"  value="{{ $fms_id }}">
							<input type="hidden" name="r_step_id" id="r_step_id" value="">
							<input type="hidden" name="r_row_num" id="r_row_num" value="">
							<input type="hidden" name="r_planed_date" id="r_planed_date" value="">
							<input type="hidden" name="r_pm_check" id="r_pm_check" value="">
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
<!-- Store Room model end -->

@endsection

@section('mycustom_js')
<!--<script src="{{asset('resources/views')}}/js/fms_custom.js"></script>-->
@endsection

@section('custom_script')
<script>
	function openDeteTimeModel(dtId, ac_date, stpId, row_num_stage){
		$('#currentDateTime').val('');
		$('#currentDateTime').val(ac_date);
		$('#actual_data_id').val(dtId);
		$('#actual_step_id').val(stpId);
		$('#actual_id_with_row').val(row_num_stage);
		$('#change_date_time_model_open').trigger('click');
	}	

	function update_date_and_time(){
		var actual_id_with_row = $('#actual_id_with_row').val();
		var currentDateTime = $('#currentDateTime').val();
		var stpId = $('#actual_step_id').val();
		var row_num = $('#actual_id_with_row').val();
		var formData = $('#update_date_and_time_form').serialize();
		var ajax_url = "<?php echo route('ajax_set_date_and_time_sample'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res);					
					// current_date
					var jsonData = JSON.parse(res);
					
					$('#set_date_'+stpId+'_'+row_num).html('');
					$('#set_date_'+stpId+'_'+row_num).html(jsonData.current_date);
					
					$('#step_'+stpId+'_'+row_num).show();
					$('#step_'+stpId+'_'+row_num).attr('rel', jsonData.date_in_ymdhis);
					
					//$('#'+stpId+'_'+row_num).text(jsonData.current_date);
					if(jsonData.msg=='success'){
						$('.data_update').html('');
						$('.data_update').html('<p class="text-success">Date updated successfully.</p>');
						location.reload();
					}
					
				}
			});
	}

	function commentModel(fms_data_id, step_id, cmnt_id_with_row){
		$('.cmnt_update').html('');
		$('#cmnt_data_id').val('');
		$('#cmnt_data_id').val(fms_data_id);
		$('#cmnt_step_id').val('');
		$('#cmnt_step_id').val(step_id);
		$('#cmnt_id_with_row').val(cmnt_id_with_row);
		
		var comntText = $('.cmnt_'+cmnt_id_with_row).text();
		$('#user_cmnt').val(comntText);
		
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
					$('#cmntlink_'+cmnt_id_with_row).text('Commented');				
					console.log(jsonData);
				}
		  });
	
	}
	
	function fabricatorModel(fms_data_id, step_id, fab_id_with_row){
		$('#data_id').val('');
		$('#data_id').val(fms_data_id);
		$('#step_id').val('');
		$('#step_id').val(step_id);
		$('#fab_id_with_row').val(fab_id_with_row);
		$('#fabricator_model_open').trigger('click');
	}
	
	function saveFabricator(){
		var fab_id_with_row = $('#fab_id_with_row').val();
		var formData = $('#fabricatorForm').serialize();
		var ajax_url = "<?php echo route('ajax_savefabricator_in_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					var jsonData = JSON.parse(res);
					$('.fab_update').html('');
					$('.fab_update').html('<p class="text-success">'+jsonData.msg+'</p>');				
					$('#fblink_'+fab_id_with_row).html(jsonData.fabricator);				
					console.log(jsonData);
				}
		  });
	
	}
	
	function fillFabricatorId(idVal)
	{
		var fbName = $('#'+idVal).val();
		var fbid = $('#'+idVal+' option:selected').data('fbid');
		$('#fab_name').val('');
		$('#fab_id').val('');
		$('#fab_name').val(fbName);
		$('#fab_id').val(fbid);
		//console.log(fbName+'=========='+fbid);
	}
	
	


	function fmsFormSubmit(frmid)
	{
		var formData = $('#'+frmid).serialize();
		var ajax_url = "<?php echo route('ajax_savefmsdata'); ?>";
		$.ajax({
			type:'POST',
			url:ajax_url,
			data:formData,
			success:function(result){
					console.log(result);
					//location.reload();
					$('#'+frmid).remove();
					return false;
					},
			error: function(result)
					{
						//console.log(result);
						return false;
					}
		});
		
	}
	
	
	function fillStepData(thisVal, taskId)
	{
		//debugger;
		var taskIdFirst = taskId;
		var taskId = taskId.split('_');
		var rowNum = taskId[0];
		taskId = taskId[1];
		
		var  task_hr = $('#'+rowNum+'_task_hr_'+taskId).val(); //1_task_hr_5c87927b0f0e7517140015ec
		
		var Hr = task_hr, dayByhr='';
			dayByhr = Math.floor(Hr/8);
		var reminder = Hr%8;
		if(reminder!=0)
		{
			dayByhr=dayByhr+1;
		}
		
		//console.log('taskId=='+taskId);
		
		
		var ajax_url_get_date = "<?php echo route('ajax_url_get_date'); ?>";
		
		$.ajax({
					method:'GET',
					url:ajax_url_get_date,
					data:{thisVal:thisVal, rowNum:rowNum, task_hr:task_hr, taskId:taskId, fms_id:'{{$fms_id}}'},
					success:function(result){
							
							if(result!=='no-date')
							{
								$('.'+taskIdFirst).val(result);
								$('.'+taskIdFirst).focus();
							}
							//console.log(result+'==='+taskIdFirst);
							console.log(result);
							return false;
							},
					error:function(result)
							{
								//console.log(result);
								return false;
							}
				});
	}
	
	function fillLinkStep(thisVal,thisId)
	{
		// thisId = 1_stepId_5c91e30a0f0e751e3400431c
		//debugger;
		var rowNum1 = thisId.split('_'); 
			rowNum = rowNum1[0];
		var stepId = rowNum1[1]+'_'+rowNum1[2];
		//$('.'+thisId).val(thisVal); 1_after_stepId_5c91e30a0f0e751e3400431c
		
		//console.log(thisVal+'---'+thisId);
		var task_hr= $('#'+rowNum+'_after_'+stepId).val();
		
		var ajax_url_get_date = "<?php echo route('ajax_url_get_date'); ?>";
		
		$.ajax({
					method:'GET',
					url:ajax_url_get_date,
					data:{thisVal:thisVal,rowNum:rowNum, task_hr:task_hr, fms_id:'{{$fms_id}}'},
					success:function(result){
							
							/* if(result!=='no-date')
							{
								$('.'+taskIdFirst).val(result);
								$('.'+taskIdFirst).focus();
							} */
							//console.log(result+'==='+taskIdFirst);
							$('.'+thisId).val(result);
							$('.'+thisId).focus();
							//console.log(result+'------------kkkkkkkkkk');
							return false;
							},
					error:function(result)
							{
								//console.log(result);
								return false;
							}
				});
				
	}
	
	function fillLinkStep2(thisVal, thisId)
	{
		//debugger;
		//console.log(thisVal+'---22222---22======='+thisId);
		var rowNum1 = thisId.split('_'); 
			rowNum = rowNum1[0];
		var stepId = rowNum1[1]+'_'+rowNum1[2]
		//$('.'+thisId).val(thisVal); 1_after_stepId_5c91e30a0f0e751e3400431c
		
		//console.log(thisVal+'---'+thisId);
		var task_hr= $('#'+rowNum+'_after_'+stepId).val();
		
		var ajax_url_get_date = "<?php echo route('ajax_url_get_date'); ?>";
		
		$.ajax({
					method:'GET',
					url:ajax_url_get_date,
					data:{thisVal:thisVal,rowNum:rowNum, task_hr:task_hr, fms_id:'{{$fms_id}}'},
					success:function(result){
							
							/* if(result!=='no-date')
							{
								$('.'+taskIdFirst).val(result);
								$('.'+taskIdFirst).focus();
							} */
							//console.log(result+'==='+taskIdFirst);
							$('.'+thisId).val('');
							if(result!='no-date')
							{
								$('.'+thisId).val(result);
								$('.'+thisId).focus();
							}
							
							//console.log(result+'------------kkkkkkkkkk');
							return false;
							},
					error:function(result)
							{
								//console.log(result);
								return false;
							}
				});
	}
	
</script>


<!--<link rel="stylesheet" href="{{asset('resources/views')}}/mmenu/assets/examples/css/forms/validation.css">-->
<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
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
	
	function OrderEmbroiderySave(thisVal,stepId, dataId, rowNum){
		var currVal = thisVal;
		var dataId = dataId;
		var stepId = stepId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		
		//if(currVal!='' || currVal==0){
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
		
		//$('#loader').hide();
		//}
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
		var store_room_id = $(this).attr('store_room_id');
		var process = $(this).attr('process');
		//return false;
		var ajax_url = "<?php echo route('ajax_updateActualDateSample'); ?>";
		$.ajax({
			type:'GET',
			url:ajax_url,
			data:{data_id:data_id,fms_id:fms_id, step_id:stepId,row_num_step:row_num_step,planed_date:planed_date,pm_check:pm_check,store_room_id:store_room_id, process:process},
			success:function(result){
					//console.log(result); return false;
					var res = JSON.parse(result);
					console.log(res);
					var step_id = res.step_id;
					var row_num_step = res.row_num_step;
					
					$('#step_'+step_id+'_'+row_num_step).after(res.resHtml['date']);
					$('#'+stepIdMain).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					
					
					$('#step_'+step_id+'_'+row_num_step).remove();
					//$('.activate_timestamp'+'_'+row_num_step+':first').show();
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
	/* 
	debugger;
	var checkedBoxLen = $('.user_id_list :checked').size();
	if(checkedBoxLen<1)
	{
		alert('Please check at leat one user right.');
		return false;
	}
	return false; 
	*/
	
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
						$('#user_update').html('<p class="alert text-success text-center">User added successfully.</p>');
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
  $('.user_permit').click(function() {
	  //debugger;
	  var step_id = $(this).attr('id');
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
				
				//console.log(arKey);				
				//console.log(arKey[0]);
				$('.user_id_list').prop('checked', false);
				
				for(var i=0; i<arKey.length; i++)
				{
					
					//console.log(jsonData[arKey[i]]);
					//console.log((jsonData[arKey[i]]).length);
					
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
/*$(document).ready(function() {
$("table").each(function() {
 var x = $(".task_left_fix").position();
    console.log("Top position: " + x.top + " Left position: " + x.left);
});
});*/

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
 $(this).css({ "top": y.top - 123 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});




});



// for patching steps fill 
function patchingSteps(fms_id) {
	console.log(fms_id);
	var fms_id_main = fms_id;
	
	var fms_id_new = fms_id.split('_');
		fms_id = fms_id_new[2];
	var row_id = fms_id_new[1];
	//alert(row_id);
	var patching_id = $('#'+fms_id_main).val();
	var ajax_url = "<?php echo route('ajax_getpatchingids'); ?>";
	$.ajax({
			method:'get',
			url:ajax_url,
			data:{fms_id:fms_id, patching_id:patching_id},
			success:function(result){
						console.log(JSON.parse(result));
						var jsonData = JSON.parse(result);
						
						for(var i=0; i<jsonData.steps_id.length; i++){
							console.log(jsonData.steps_id[i]);
							console.log(row_id+'_stepId_'+jsonData.steps_id[i]);
							$('#'+row_id+'_stepId_'+jsonData.steps_id[i]).val('kk');
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

function customStorekRoom(stepIdMain,planed_date,data_id,pmChk){
			//alert('dfgvdsgdsgf');
			var stepIdSplit = stepIdMain.split('_');
			var stepId = stepIdSplit[1];

			var row_num_step = stepIdSplit[2];

			var planed_date = planed_date;

			var data_id = data_id;

			var fms_id = '<?php echo $fms_id; ?>';

			var pm_check = pmChk;
			
			$('#r_data_id').val(data_id);
			$('#r_step_id').val(stepId);
			$('#r_row_num').val(row_num_step);
			$('#r_planed_date').val(planed_date);
			$('#r_pm_check').val(pm_check);
	
	$('#store_room_model_open').trigger('click');
}

function saveCustomStorekRoom(){
		//alert('dfgvdsgdsgf');
		var stepId = $('#r_step_id').val();
		
		var row_num_step = $('#r_row_num').val();

		var planed_date = $('#r_planed_date').val();
		
		var data_id = $('#r_data_id').val();
		
		var fms_id = '<?php echo $fms_id; ?>';
		
		var pm_check = $('#r_pm_check').val();
		var store_room_id = $('#store_room_id').val();
		
		var ajax_url = "<?php echo route('ajax_updateActualDateSample'); ?>";
		$.ajax({
			type:'GET',
			url:ajax_url,
			data:{data_id:data_id,fms_id:fms_id, step_id:stepId,row_num_step:row_num_step,planed_date:planed_date, pm_check:pm_check, store_room_id:store_room_id},
			success:function(result){
					//console.log(result); return false;
					var res = JSON.parse(result);
					console.log(res);
					var step_id = res.step_id;
					var row_num_step = res.row_num_step;
					
					$('#step_'+step_id+'_'+row_num_step).after(res.resHtml['date']);
					//$('#'+stepIdMain).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					$('#step_'+step_id+'_'+row_num_step).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					
					
					$('#step_'+step_id+'_'+row_num_step).hide();
					//$('.activate_timestamp'+'_'+row_num_step+':first').show();
					$('.fms_stage_'+row_num_step).html('');
					$('.fms_stage_'+row_num_step).html(res.stage_text);
					$('#room_'+pm_check).hide();
					$('.room_update').html('<p class="text-success">Order Packed and Store room Updated.</p>');
					return false;
					},
			error: function(result)
					{
						//console.log(result);
						return false;
					}
		});
}


function clicentOrderSave(thisVal,taskId, dataId, rowNum){
		var currVal = thisVal;
		var dataId = dataId;
		var taskId = taskId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		if(currVal!=''){
			$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updateClientOrder'); ?>";
			$.ajax({
				type:'GET',
				url:ajax_url,
				data:{fms_id:fms_id,dataId:dataId,taskId:taskId,currVal:currVal},
				success:function(result){					
						console.log(result);
						//var res = JSON.parse(result);
						$('#loader').hide();
						
						//console.log(res);
						/* $('.activate_timestamp'+'_'+rowNum+':first').show();
						if(res.stage_text_color!='' && res.stage_text!=''){
							$('.fms_stage_'+rowNum).html('');
							var stageHtml = '<span style="color:'+res.stage_text_color+'">'+res.stage_text+'</span>';
							$('.fms_stage_'+rowNum).html(stageHtml);
						}   */
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

</script>
@endsection

