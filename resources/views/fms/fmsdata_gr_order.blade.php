@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'GR FMS Data')

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
			<div class="row custom-header">
				<div class="col-md-2 logo-box custom-logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
				
				<div class="col-md-3 search-form-wrap custom-date-box">
					<select class="form-control float-left" onchange="location = '{{route('fmsdata', $fms['_id'])}}/'+this.options[this.selectedIndex].value;" id="order_range" style="margin-top: 14px; width:150px;">
						<option value=""> --Select Month-- </option>
						{!! fms_order_month_dropdown_new($fms['_id'], $order_range) !!}
					</select>
				</div>
				
				
				
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
				
				<div class="col-md-2 pagination-wrap"></div>
				<div class="col-md-4 pagination-wrap">
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
			@endif
				
			</div>
			
        <div class="panel-body">
			<div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>			
			<?php
					/* START client_list API */
				$client_lists = get_client_list_api();
				$clientListArr = array();
				foreach($client_lists as $client_list){
					$clientListArr[$client_list->r_id] = $client_list->r_nick_name;
				} 
			
				/* Get the user who has access of this fms */
				$userScoreArrMain = getUserIdAccessOfThisFms($fms['_id']);
				/* echo "====";
				pr($userScoreArrMain);				
				echo "====";
				die;  */
				
				/* START master list API */
				$masterLists = get_master_list_api();
				$masterListArr = array();
				foreach($masterLists as $masterList){
					$masterListArr[$masterList->emp_id] = $masterList->f_name;
				}
				/* END master list API */
				
				/* START patchers list API */
				$patcherLists = get_patchers_list_api();
				$patcherListArr = array();
				foreach($patcherLists as $patcherList){
					$patcherListArr[$patcherList->emp_id] = $patcherList->f_name;
				}
				/* END patchers list API */
			
				/* START embroider list API */
				$embroiders = get_embroider_list_api();
				$embroidersArr = array();
				foreach($embroiders as $embroider){
					$embroidersArr[$embroider->emp_id] = $embroider->f_name;
				}
				/* END embroider list API */
				
				$fms_id = $fms['_id'];
				
				/* Item list API */
				$itemsArrAll = get_item_list_api();
				$itemsArr = $itemsArrAll['itemIdCodeArr'];
				/* Item option list */
				$itemOption='';
				$itemOption.='<select class="form-control text-center" name="item_list" id="itemOptionList">';
				$itemOption.='<option value="">--Select--</option>';
				foreach($itemsArr as $ikey=>$ival){
					$itemOption.='<option value="'.$ikey.'">'.$ival.'</option>';
				}
				$itemOption.='</select>';
			
				$currentUserId = Auth::user()->id;
				
				
				//pr($fmsdatas_pg); die;
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
								if($cnt==$no)
								{
									$styleBorder='style="border-right:5px solid green;"';
								}else{
									$styleBorder='';
								}
								$step_no_cnt++;
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
										{{ __('#') }}{{ $step_no_cnt }} <?php echo $stepsdata['fms_what'].'</th>';
									}else if($input_type1=='fabricator')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php echo $stepsdata['fms_what'].'</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php echo $stepsdata['fms_what'].'</th>';
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
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $step_no_cnt }} <?php	echo $stepsdata['fms_what'].'</th>';
									}
									
								}else{
									//$step_no_cnt++;
									
									/* $black_bg='';
									if($stepsdata['fms_what']=='Fabric is ready'){
										$black_bg = " black-bg";
									} */
								?>
									<th class="text-center font-weight-bold step-title black-bg" colspan="2"> 
										<div class="auth-icon" style="margin-top: 5px;">
											
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
											{{ __('#') }}{{ $step_no_cnt }}
											{!! $stepsdata['fms_what'] !!}
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
							
							<th class="text-center font-weight-bold" colspan="<?php echo $userAccessCnt;?>">
								MIS Report
							</th>
							
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
							
							
							
							<?php 
							//$currentUserId
							if(!userHasRight()){
								echo '<th>'.$userScoreArrMain[$currentUserId]['full_name'].'</th>';
							}else{
								foreach($userScoreArrMain as $theKey=>$theVal){ ?>
									<th><?php echo $theVal['full_name'];?></th>
								<?php }
							}
							?>
							
							@if( userHasRight())
							<th width="12%">Action</th>
							@endif
							
						</tr>
						
						
				</thead>
			  
				<tbody id="fmsTable">
						<?php 
							$row_num=0;
							//$pgNum = ($fmsdatas_pg->currentPage()-1)*100+1;
							$pgNum = ($fmsdatas_pg['current_page']-1)*100+1;
						?>
						@foreach($fmsdatas as $fmsdata)
						<?php $userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); ?>
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
						<td class="text-center task_left_fix">{{ $pgNum }}</td>
							<!---Task data-->
							<?php 
								$pgNum++;
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
								
								$no=0; $row_num++;
								$cnt = count($taskdatas);
								$k=1;
								$order_no ='';
							?>
							@foreach($taskdatas as $taskdata)
								<?php
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
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">{{changeDateToDmy($fmsdata[$taskdata['_id']])}}</td>
								<?php }else if($taskdata['custom_type']=='stage')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									<div class="fms_stage_row{{ $row_num }}">
										@if(is_array($fmsdata[$taskdata['_id']]) && array_key_exists('stage_text', $fmsdata[$taskdata['_id']]))
										<span style="color:{{$fmsdata[$taskdata['_id']]['stage_text_color']}}">
											{{$fmsdata[$taskdata['_id']]['stage_text']}}
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
								<?php }else if($taskdata['input_type']=='order_qty' && $taskdata['custom_type']=='qty')
									{ ?>
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
												<?php if(!empty($fmsdata[$taskdata['_id']])){?>
												@if(userHasRight())
													<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', 'update_qty');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}" class="text-danger"><strong>{{$fmsdata[$taskdata['_id']]}}</strong></a>
												<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;">{{$fmsdata[$taskdata['_id']]}}</div>
												@else
													{{$fmsdata[$taskdata['_id']]}}
												@endif
												
												
												<?php }else{ ?>
												
													<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', 'update_qty');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}" class="btn btn-lightgray"><strong>Add Value</strong></a>
													<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;"></div>
													
												<?php }?>
									</td>
									<?php }else if($taskdata['custom_type']=='text_fabricator')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									<span id="fbtext_row{{ $row_num }}">{{$fmsdata['fabricator']}}</span>
									
								</td>
								<?php }else if($taskdata['custom_type']=='client_list'){ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									<?php 
										$dropdown_id=$fmsdata[$taskdata['_id']];	
										echo $clientListArr[$dropdown_id];
									?>
								</td>
								<?php }else{
								
								if($k==3){ ?> 
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									
										@if(userHasRight())
											<a href="javascript:void(0)" onclick="taskItemModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', '{{$fmsdata[$taskdata['_id']]}}');"  id="itemlink_row{{$row_num}}_{{ $taskdata['_id'] }}">
												<span id="row{{$row_num}}_{{ $taskdata['_id'] }}">
													{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
												</span>
											</a>
										@else
											{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
										@endif
										
									
									</td>
								<?php }else{
								?>
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
									<?php 
									if($taskdata['custom_type']=='fabricators'){
									?>
									
										<?php if(!empty($fmsdata[$taskdata['_id']])){ ?>
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');">
											<span id="row{{$row_num}}_{{ $taskdata['_id'] }}">{{$fmsdata[$taskdata['_id']]}}</span>
										</a>
										<?php }else{ ?>
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');">Click</a>
										<?php }
									}else if($taskdata['input_type']=='builtin_options' && $taskdata['custom_type']=='items'){?> 
										{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
									<?php }else{ ?>
											{{$fmsdata[$taskdata['_id']]}}
									<?php } ?>
										
									</td>
								<?php
									}
									
								}
								$k++;
								?>
							@endforeach
							<!---Stap data-->
							<?php 
							$ac_and_dc_date = ''; 
							$ac_and_dc_date_flag = ''; 
							$kk=1;
							$acDatePrev=array();
							$delaypercentShow=0;
							//$userScoreArr = getUserIdAccessOfThisFms($fms['_id']);
							?>
							@foreach($stepsdatas as $stepsdata)

							<?php 
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
								/* ********************* 14 june 19 ******************** */
								
								if (in_array(1, $userRightArr) || userHasRight()) { ?>
								<?php 
								$plane_date='';
								if(array_key_exists('planed',$fmsdata[$stepsdata['_id']])){
										$plane_date = $fmsdata[$stepsdata['_id']]['planed'];
								}
								
							if (strtotime($plane_date)>strtotime(0) && array_key_exists('planed',$fmsdata[$stepsdata['_id']]))
							{
									$endTime = checkStepEndtime($fmsdata[$stepsdata['_id']]['planed']);
									$planeAlert='';
									if($endTime<5 && empty($fmsdata[$stepsdata['_id']]['actual'])){
										$planeAlert = 'style="background:#f6f041;"';
									}else{
										$planeAlert = '';
									}
									
								?>
								<td class="text-center">{{changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed'])}}
								<?php 
									
										$delaypercent_main = getTimeDelay($fmsdata,$stepsdata, $stepsdata['_id']);
										$delaypercent = $delaypercent_main>0?'-'.abs($delaypercent_main):$delaypercent_main;
										echo '<span style="font-size:7px;">('.$delaypercent.'%)</span>';
										
										$delaypercentShow = $delaypercentShow+$delaypercent;
										
										if(!empty($stepsdata['user_right'])){
											foreach($stepsdata['user_right'] as $uKey=>$uVal){
												
												if(!empty($userScoreArr[$uKey]['score']) && !empty($userScoreArr[$uKey]['count'])){
												if(in_array(2, $uVal)){
													if($delaypercent_main>0 || $fmsdata[$stepsdata['_id']]['actual']!=''){
														$userScoreArr[$uKey]['score']= $userScoreArr[$uKey]['score']+$delaypercent_main;
														$userScoreArr[$uKey]['count']=$userScoreArr[$uKey]['count']+1;
													}else{
														$userScoreArr[$uKey]['score']= abs($userScoreArr[$uKey]['score']+$delaypercent_main);
														$userScoreArr[$uKey]['count']=$userScoreArr[$uKey]['count']+0;
														
													}
												}
												
												}
											}
										}
										
										 //pr($userScoreArr);
										$delaypercent='';
								?>
								
								</td>
								
									<?php
										$actual='';
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];
										
										$planed_date=$fmsdata[$stepsdata['_id']]['planed'];
										
										if($actual_date!=''){
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
											
											
											echo '<td class="text-center" style="background:'.$dateHtml['bgcolor'].'; color:'.$dateHtml['textcolor'].'"><a href="javascript:void(0)" id="'.$stepsdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModel('.$dtId.','.$ac_date.', '.$stpId.', '.$row_num_stage.')">'.$dateHtml['date']."</a></td>";
											
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
									<td class="text-center" <?php echo $planeAlert; ?>>
									<?php
									/* $tt = '';
									if($kk>3){
										if($acDatePrev[$kk-3]!=''){
										$ac_and_dc_date = '';
										$tt = $acDatePrev[$kk-3];
										}
									}  
									
									if(empty($tt)){
										$ac_and_dc_date = ' style="display:none"';
									} */
									
									if($kk==3){
										$ac_and_dc_date = '';
									}
									
									$pmChk = '';
									if(array_key_exists('pm_check', $stepsdata)){
										$pmChk =$order_no;
									}
									
									
									?>
									
									@if(in_array(2, $userRightArr) || userHasRight())
									<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}"> <i class="icon md-time" aria-hidden="true"></i></a>
									@endif
									</td>
									<?php
									}
									 
								}else{?>
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
										
										
										if($fmsdata[$stepsdata['_id']]['none_date']!=''){ ?>
											<td class="text-center" colspan="2"> 
											{{ $fmsdata[$stepsdata['_id']]['none_date'] }}
											</td>
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										@if(in_array(2, $userRightArr) || userHasRight())
										<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}"> <i class="icon md-time" aria-hidden="true"></i></a>
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
																					
										if(array_key_exists('delivery_date', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['delivery_date']!=''){
										?>
											<td>{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}</td>
											<td>
												@if(in_array(2, $userRightArr) || userHasRight())
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" <?php echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
												@endif
											</td>
										
										<?php }?>
										
										<?php } ?>
									
									<?php
									/***** for Fabric Comments ****/
									$custom_options='';
									$built_options='';
									
									/* start  chek stepid exit or not  */
									
									if(array_key_exists('fms_when_type', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['fms_when_type']==6){
										$custom_options=$fmsdata[$stepsdata['_id']]['fms_when_type'];
										$custom_options_id=$fmsdata[$stepsdata['_id']]['input_type'];
										$fabric_comments='';
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
										
										$custom_opt_Html.='</select></td>';
										
										if (in_array(3, $userRightArr) || userHasRight()){
											echo $custom_opt_Html;
										}else{
											echo '<td class="text-center" colspan="2">'.$fabric_comments.'</td>';
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
											
										}elseif($fmsdata[$stepsdata['_id']]['built_options_id']=='master_name'){
											
											if(array_key_exists('dropdown_id', $fmsdata[$stepsdata['_id']])){
												$dropdown_id = $fmsdata[$stepsdata['_id']]['dropdown_id'];
											}else{
												$dropdown_id='';
											}
											$built__opt_Html='';
											
											$built__opt_Html.='<td class="text-center" colspan="2"><select name="built_opt_row" class="form-control" onchange="OrderEmbroiderySave(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
											
											foreach($masterListArr as $key=>$val){
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
													if(array_key_exists($dropdown_id, $masterListArr)){
														echo $masterListArr[$dropdown_id]; 
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
								?>
										<td class="text-center" colspan="2">
										<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
										<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
										<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
										<?php }else{ ?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray"><strong>Add Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
										<?php }?>
										</td>
								<?php } ?>
								
								<?php if($stepsdata['fms_when']['fms_when_type']==4 && $stepsdata['fms_when']['input_type']=='fabricator'){ ?>
										<td class="text-center" colspan="2">
										<?php if(array_key_exists('fabricator', $fmsdata[$stepsdata['_id']])){?>
										
										
										@if (in_array(3, $userRightArr) || userHasRight())
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}');"  id="fblink_row{{$row_num}}_{{ $stepsdata['_id'] }}">
											<span id="row{{$row_num}}_{{ $stepsdata['_id'] }}">{{$fmsdata[$stepsdata['_id']]['fabricator']}}</span>
										</a>
										@else
											{{$fmsdata[$stepsdata['_id']]['fabricator']}}
										@endif
										
										
										
										<?php }else{ ?>
										
										@if (in_array(3, $userRightArr) || userHasRight())
										<a href="javascript:void(0)" onclick="fabricatorModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}');" id="fblink_row{{$row_num}}_{{ $stepsdata['_id'] }}">Click</a>
										@else
										No data
										@endif
										<?php }?>
										</td>
										
								<?php } ?>
								
								<?php 
								if($stepsdata['fms_when']['fms_when_type']==8 && $stepsdata['fms_when']['input_type']=='patching'){ 
								?>
										<td class="text-center" colspan="2">
											Patching Dropdown
										</td>
										
								<?php } ?>
								

									
									
								<?php } ?>
								
								
								<?php } $kk++; ?>
								
								
								
								
								
							<?php }else{?>
								<td class="text-center" colspan="2">
									<?php 
									//$klll=  updateStepidIntoDtata($stepsdata['_id'], $fmsdata['_id']);
									//echo $fmsdata['_id'];
									//pr($klll);
									?>
									No Data
								</td>
							<?php } ?>
							@endforeach
							
							
							<?php 
							/*
							foreach($userScoreArr as $theKey=>$theVal){
									if($theVal['score']>0){?>
									<td>
										<?php 
											//echo $theVal['full_name'].'=='.$theVal['score'].'==count=='.$theVal['count'];
											echo $aveRage = round(($theVal['score']/$theVal['count']),2);
										?>
									</td>
									<?php }else{
											echo "<td>0</td>";
							}
							}
							*/
							?>
							
							<?php
							//pr($userScoreArr); die; 
							if(!userHasRight()){
								
								if($userScoreArr[$currentUserId]['score']>0){
								echo '<td>-'.round(($userScoreArr[$currentUserId]['score']/$userScoreArr[$currentUserId]['count']),2).'%';
								
									/* echo "==score==".$userScoreArr[$currentUserId]['score']."==count==".$userScoreArr[$currentUserId]['count']; */
									
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
							?>
							
							@if( userHasRight())
							<td class="text-center">
								
								<a href="javascript:void(0)" class="btn btn-danger" onclick="deleteRow({{$row_num}}, '{{ $fmsdata['_id']}}')">DELETE</a>
								
							</td>
							@endif
							
						</tr>
						@endforeach
						<?php 
						
							$taskArr = implode(',', $taskArr);							
							$stepArr = implode(',', $stepArr);							
							$fms_id = $fms['_id'];
							
						?>
						
						
				</tbody>
            </table>
			
			
			
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
@endif

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

<!-- Item List Modal -->
<button data-toggle="modal" data-target="#item_model" id="item_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="item_model" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Select </h4>
			</div>
			<div class="modal-body">
				<div class="fab_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveItem();" id="saveItemForm">
							{!! $itemOption !!}
							<br>
							<input type="hidden" name="data_id" id="data_id_item" value="">
							<input type="hidden" name="task_id" id="task_id_item" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="current_item_id" value="" id="current_item_id">
							<input type="hidden" name="item_id_with_row" value="" id="item_id_with_row">
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
<!-- item model end -->

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
						<form action="" method="POSt" onsubmit="event.preventDefault(); update_date_and_time();" id="update_date_and_time_form">
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
<!-- fabricator model end -->


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
	
	/* 27 july 2019 */
	
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
					console.log(res);					
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


	function commentModel(fms_data_id, step_id, cmnt_id_with_row, cmntRight){
		$('.cmnt_update').html('');
		$('#cmnt_data_id').val('');
		$('#cmnt_data_id').val(fms_data_id);
		$('#cmnt_step_id').val('');
		$('#cmnt_step_id').val(step_id);
		$('#cmnt_id_with_row').val(cmnt_id_with_row);
		
		var comntText = $('.cmnt_'+cmnt_id_with_row).text();
		$('#user_cmnt').val(comntText);
		
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
		var rowId = fab_id_with_row.split('_');
			rowId = rowId[0];
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
						$('#fbtext_'+rowId).html('');
					$('#fbtext_'+rowId).html(jsonData.fabricator);				
					console.log(jsonData);
				}
		  });
	
	}
	
	/* saveItemDropDown	*/
	function taskItemModel(fms_data_id, task_id, item_id_with_row, currentItemId){
		$('.fab_update').html('');
		$('#data_id_item').val('');
		$('#data_id_item').val(fms_data_id);
		$('#task_id_item').val('');
		$('#task_id_item').val(task_id);
		$('#item_id_with_row').val(item_id_with_row);
		$("#itemOptionList").val(currentItemId);
		$('#item_model_open').trigger('click');
	}
	
	function saveItem(){
		var selectedtext = $("#itemOptionList option:selected").text();
		var item_id_with_row = $('#item_id_with_row').val();
		var rowId = item_id_with_row.split('_');
			rowId = rowId[0];
		var formData = $('#saveItemForm').serialize();
		var ajax_url = "<?php echo route('ajax_saveItem_in_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					var jsonData = JSON.parse(res);
					$('#'+item_id_with_row).html('');			
					$('#'+item_id_with_row).html(selectedtext);
					$('.fab_update').html('<p class="text-success">'+jsonData.msg+'</p>');
							
				}
		  });
	
	}
	/*END saveItemDropDown	*/
	
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
		
		var  task_hr = $('#'+rowNum+'_task_hr_'+taskId).val();
		
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
		var rowNum1 = thisId.split('_'); 
			rowNum = rowNum1[0];
		var stepId = rowNum1[1]+'_'+rowNum1[2];
		
		//console.log(thisVal+'---'+thisId);
		var task_hr= $('#'+rowNum+'_after_'+stepId).val();
		
		var ajax_url_get_date = "<?php echo route('ajax_url_get_date'); ?>";
		
		$.ajax({
					method:'GET',
					url:ajax_url_get_date,
					data:{thisVal:thisVal,rowNum:rowNum, task_hr:task_hr, fms_id:'{{$fms_id}}'},
					success:function(result){
							$('.'+thisId).val(result);
							$('.'+thisId).focus();
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
		var rowNum1 = thisId.split('_'); 
			rowNum = rowNum1[0];
		var stepId = rowNum1[1]+'_'+rowNum1[2];
		var task_hr= $('#'+rowNum+'_after_'+stepId).val();
		
		var ajax_url_get_date = "<?php echo route('ajax_url_get_date'); ?>";
		
		$.ajax({
					method:'GET',
					url:ajax_url_get_date,
					data:{thisVal:thisVal,rowNum:rowNum, task_hr:task_hr, fms_id:'{{$fms_id}}'},
					success:function(result){
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
		
		var pm_check = $(this).attr('pm_check')
		
		var ajax_url = "<?php echo route('ajax_updateActualDate'); ?>";
		$.ajax({
			type:'GET',
			url:ajax_url,
			data:{data_id:data_id,fms_id:fms_id, step_id:stepId,row_num_step:row_num_step,planed_date:planed_date, pm_check:pm_check},
			success:function(result){
					//console.log(result);
					var res = JSON.parse(result);
					console.log(res);
					var step_id = res.step_id;
					var row_num_step = res.row_num_step;
					
					$('#step_'+step_id+'_'+row_num_step).after(res.resHtml['date']);
					$('#'+stepIdMain).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					
					
					$('#step_'+step_id+'_'+row_num_step).remove();
					$('.activate_timestamp'+'_'+row_num_step+':first').show();
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
 //$(this).css({ "top": y.top - 123 + "px"});
 $(this).css({ "top": y.top - 53 + "px"});
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

</script>
@endsection

