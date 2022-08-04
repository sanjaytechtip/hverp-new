@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'Custom FMS Data')

@section('pagecontent')
<style type="text/css">
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
.link_disabled {
  pointer-events: none;
  cursor: default;
}
.ui-datepicker{z-index: 15 !important;}
</style>
<?php
				/* test API */
				/* echo $ttkk = updateAntyaPmProductionStatus_api('29504', '3');
				die('from view');  */
				
				$userScoreArrMain = getUserIdAccessOfThisFms($fms['_id']);
				/* START client_list API */
				$client_lists = get_client_list_api();
				$clientListArr = array();
				foreach($client_lists as $client_list){
					$clientListArr[$client_list->r_id] = $client_list->r_nick_name;
				} 
				//pr($clientListArr); die;
				/* END client_list API */
				
				/* START master list API */
				$masterLists = get_master_list_api();
				$masterListArr = array();
				foreach($masterLists as $masterList){
					$masterListArr[$masterList->emp_id] = $masterList->f_name;
				}
				
				/* tailor list api */
				
				$tailorLists = get_tailor_list_api();
				$tailorListArr = array();
				foreach($tailorLists as $tailorList){
					//$tailorListArr[$tailorList->id] = $tailorList->f_name;
					array_push($tailorListArr, $tailorList->id);
				}
				 
				//pr($tailorListArr); die('111111');
				
				
				$fabLists = get_fabricator_api();
				
				//$fab_id_arr = get_fabricator_ids_api();
				
				$fabListArr = array();
				$fabListArrNew = array();
				
				
				$fabLists_new = get_fabricator_new_api();
				foreach($fabLists_new as $fabList_new){
					//$fabListArrNew[$fabList_new->id] = $fabList_new->f_name;
					array_push($fabListArrNew, $fabList_new->id);
				}
				
				$fabDropdown='';
				$fabDropdown.='<select class="form-control text-center" name="fab_id"  id="fab_option_id" style="width:160px;margin-top: 7px;margin-right: 5px;"><option value="">--Select Fabricator--</option>';
					
						$fabDropdown.='<optgroup label="New List">';
						
						foreach($fabLists_new as $fabList_new){
							$selected_fab = $fab_id==$fabList_new->id?' selected':'';
							$fabDropdown.='<option value='.$fabList_new->id.' '.$selected_fab.'>'.$fabList_new->f_name.'</option>';
						}
						$fabDropdown.='</optgroup>';
					
						$fabDropdown.='<optgroup label="Old List">';
						foreach($fabLists as $fabList){
							if(!in_array($fabList->id, $fabListArrNew)){
								$fabListArr[$fabList->id] = $fabList->f_name;
								$selected_fab = $fab_id==$fabList->id?' selected':'';
								$fabDropdown.='<option value='.$fabList->id.' '.$selected_fab.'>'.$fabList->f_name.'</option>';
							}
						}
						$fabDropdown.='</optgroup>';
					
				$fabDropdown.='</select>';
				
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
				//$itemsArr = get_item_list_api();
				/* Item list API */
				$itemsArrAll = get_item_list_api();
				$itemsArr = $itemsArrAll['itemIdCodeArr'];
			
				/* Item option list */
				$onlyOption='';
				$itemOption='';
				$itemOption.='<select class="form-control text-center" name="item_list" id="itemOptionList">';
				
				$onlyOption.='<option value="">--Select Item--</option>';
				foreach($itemsArr as $ikey=>$ival){
					if($item_id==$ikey){
						$selectedOp = 'selected';
					}else{
						$selectedOp='';
					}
					$onlyOption.='<option value="'.$ikey.'" '.$selectedOp.'>'.$ival.'</option>';
				}
				
				$itemOption.=$onlyOption;
				$itemOption.='</select>';
				
				$currentUserId = Auth::user()->id;
			?>
<div class="page no-border" id="fms_data">
			
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
			<div class="row custom-header">
				<div class="col logo-box custom-logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
				<div class="col search-form-wrap custom-date-box">
						<?php
							if(isset($_GET) && (isset($_GET['from_date'])!='') &&  (isset($_GET['to_date'])!='') )
							{
								$from_date 	= $_GET['from_date'];
								$to_date	= $_GET['to_date'];
							}else{
								$from_date 	= '';
								$to_date 	= '';
							}
						?>
					
						<form action="{{route('fmsdata', [$fms['_id'], 0, 0])}}" method="GET" class="form-inline">
							<input type="text" name="from_date" autocomplete="off" class="form-control date_short" value="{{ $from_date }}" placeholder="Choose date" required style="width:120px;">
							 To <input type="text" name="to_date" autocomplete="off" class="form-control date_short" value="{{ $to_date }}" placeholder="Choose date" required style="width:120px;">
							<button type="submit" class="btn btn-primary">Submit</button>
						</form>
				</div>
                <div class="col search-form-wrap  custom-seargh-box">
					<div class="form-inline">                    
						<select class="form-control mb-2 mr-sm-2" onchange="location = '{{route('fmsdata', $fms['_id'])}}/'+this.options[this.selectedIndex].value;" id="order_range" style="margin-top: 14px; width:150px">
								<option value=""> --Select Month-- </option>
								{!! fms_order_month_dropdown_new($fms['_id'], $order_range) !!}
						</select>
						<select class="form-control mb-2 mr-sm-2" onchange="location = '{{route('fmsdata', $fms['_id'])}}?item_id='+this.options[this.selectedIndex].value;" id="order_range" style="margin-top: 14px;  width:150px">
							{!! $onlyOption !!}
						</select>                    
						<input class="form-control mb-2 mr-sm-2" type="number" placeholder="Order Number" id="order_number" autocomplete="off" value="{{ $orderNum>0?$orderNum:'' }}" style="margin-top: 14px;  width:150px">
						<button type="submit" name="search_order" class="btn btn-primary  mb-2" onClick="getOrderNumber();" style="margin-top: 14px;"> Search </button>
					</div>
                </div>
				
				<div class="col pagination-wrap custom-pagination-box">
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
						@endif
				</div>
				
			</div>
			
          <div class="panel-body">
			<div id="row_deleted" class="text-center alert-success alert" style="display:none;"></div>
			
			
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
										<?php	echo $stepsdata['fms_what'].'</th>';
									}else if($input_type1=='fabricator')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										<?php	echo $stepsdata['fms_what'].'</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										$step_no_cnt++;
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
											{{ __('#') }}{{ $step_no_cnt }}
										<?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==6)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										<?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										<?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										<?php	echo $stepsdata['fms_what'].'</th>';
									}
									
								}else{
									$step_no_cnt++;
									
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
							$row_num=0;
							$custom_pattern_val='';
						?>
						@foreach($fmsdatas as $fmsdata)
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
						<td class="text-center task_left_fix sr_no">{{ $row_num+1 }}</td>
							<!---Task data-->
							<?php 
								
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
								$order_Dt = '';
								$delivery_date='';
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
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">{{changeDateToDmyHi($fmsdata[$taskdata['_id']])}}
									<?php $order_Dt=$fmsdata[$taskdata['_id']]; ?>
								</td>
								<?php }else if($taskdata['custom_type']=='stage')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix stage-col">
									<div class="fms_stage_row{{ $row_num }}">
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
											<span class="{{ $comp_icon_cls }}{{ $out_date }}" style="color:{{$fmsdata[$taskdata['_id']]['stage_text_color']}}">
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
								<?php }else if($taskdata['custom_type']=='client_list'){ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									<?php 
									
											$dataId = $fmsdata['_id'];
											$dataTaskId = $taskdata['_id'];
											$custom_opt_Html='';
											$tId = "'".$dataTaskId."'";
											$dtId = "'".$dataId."'";
											$row_num_stage = "'row".$row_num."'";
											
											$dropdown_id=$fmsdata[$taskdata['_id']];
											$built__opt_Html='';
											$built__opt_Html.='<select name="built_opt_row" class="form-control" onchange="clicentOrderSave(this.value, '.$tId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>'; 
											
											foreach($clientListArr as $key=>$val){
												if($key==$dropdown_id){
													$selc = " selected";
												}else{
													$selc="";
												}
												$built__opt_Html.='<option value="'.$key.'" '.$selc.'>'.$val.'</option>';
											}
											
											$built__opt_Html.='</select>';
										echo $built__opt_Html;
									?>
									
								</td>
								<?php }else if($taskdata['custom_type']=='text_fabricator')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
								
									<span id="fbtext_row{{ $row_num }}">{{$fmsdata['fabricator']}}</span>
									
								</td>
								<?php }else if($taskdata['custom_type']=='text')
								{ ?>
								<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
											<?php if(!empty($fmsdata[$taskdata['_id']])){?>
											<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}" class="text-danger1"><strong>{{$fmsdata[$taskdata['_id']]['comment']}}</strong></a>
											<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;">{{$fmsdata[$taskdata['_id']]['comment']}}</div>
											
											<?php }else{ ?>
											
												<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}" class="btn btn-lightgray"><strong>Add Value</strong></a>
												<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;"></div>
												
											<?php }?>
								</td>
								<?php }else if($taskdata['custom_type']=='delivery_date')
										{ ?>
										<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
											<?php 
											$delivery_date = $fmsdata[$taskdata['_id']]; 
											echo changeDateToDmyHi($delivery_date); 
											?>
										</td>
										<?php }else if($taskdata['custom_type']=='days_available')
										{ ?>
										<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
											<?php 
												if($delivery_date!=''){
													echo $dateDiff = dateDiff($order_Dt, $delivery_date);
												}
												 
											?>
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
									<?php }else if($taskdata['input_type']=='custom_options'){
										
										$custom_options_id = $taskdata['custom_type']; 
										$custom_opt_rows = getBuiltOptionlistById($custom_options_id);
										
										$dataId = $fmsdata['_id'];
										$taskdata = $taskdata['_id'];
										
										$custom_opt_Html='';
										$tId = "'".$taskdata."'";
										$dtId = "'".$dataId."'";
										$row_num_stage = "'row".$row_num."'";
										$custom_opt_Html.='<select name="custom_opt_row" id="row_'.$dataId.'" class="form-control" onchange="savePattern(this.value, '.$tId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
										
										//$custom_val = $fmsdata[$taskdata];
										$custom_pattern_val = $fmsdata[$taskdata];
										
										foreach($custom_opt_rows as $custom_opt_row){
											if($custom_opt_row==$custom_pattern_val){
												$selc = " selected";
											}else{
												$selc="";
											}
											
											$custom_opt_Html.='<option value="'.$custom_opt_row.'" '.$selc.'>'.$custom_opt_row.'</option>';
											
										}
										
										echo $custom_opt_Html.='</select>';
										?>
										
										
										
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
							$none_date_flage='';
							$step17Act='';
							$step18Act='';
							$step18Act = '';
							$step17plane = '';
							$step18Act = '';
							$style2='';
							?>
							@foreach($stepsdatas as $stepsdata)

							<?php 
							
							$activateQc1='';
							
							if(array_key_exists('tailor_id', $fmsdata[$stepsdata['_id']])){
								$t_id= $fmsdata[$stepsdata['_id']]['tailor_id'];
								if($t_id!=''){
									$activateQc1=1;
								}
							}else if(array_key_exists('fabricator', $fmsdata[$stepsdata['_id']])){
								$activateQc1=1;
							}
							
							if(array_key_exists($stepsdata['_id'], $fmsdata)) {
								
								$userRightArr = array();
								if(!empty($stepsdata['user_right'])){
									$step_user_right = $stepsdata['user_right'];
									if(array_key_exists($currentUserId,$step_user_right)){
										$userRightArr = $stepsdata['user_right'][$currentUserId];
									}
								}
								
								
								$saveCmnt='';
								if(in_array(3, $userRightArr) || userHasRight()){
									$saveCmnt=1;
								}else{
									$saveCmnt='';
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
										$planeAlert = 'style="background:'.BG_YELLOW.';"';
									}else{
										$planeAlert = '';
									}
									
								?>
								<td class="text-center">{{changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed'])}}</td>
								
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
									
									
									if($kk==3){
										$ac_and_dc_date = '';
									}
									
									if($ac_and_dc_date!=''){
										$none_date_flage=1;
									}
									
									$link_class='';
									$timeClass='';
									if($stepsdata['_id']=='5d2c114fd049041cc660fa45'){
										
										$link_class = $custom_pattern_val=='Standard'?' link_disabled':'';
									
										if($custom_pattern_val=='Standard'){
											$timeClass = 'update_ac_date btn btn-success'.$link_class;
										}else{
											$timeClass = 'update_ac_date activate_timestamp_row'.$row_num.' btn btn-success';
										}
									
										
									}else{
										$timeClass = 'update_ac_date activate_timestamp_row'.$row_num.' btn btn-success';
									}
									
									
									if($custom_pattern_val=='Standard'){
										$ac_and_dc_date_flag='';
										$ac_and_dc_date='';
									} 
									
										$pmChk1 = '';
										/* if(array_key_exists('pm_check', $stepsdata)){
											$pmChk1 =$order_no;
										} */
										
										$process = '';	
										if(array_key_exists('process', $stepsdata)){
											$process = $stepsdata['process'];
											$pmChk1 =$order_no;
											$ac_and_dc_date='';
										}
									
									?>
									
									@if(in_array(2, $userRightArr) || userHasRight())
									<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" <?php echo $ac_and_dc_date; ?> pm_check="{{ $pmChk1 }}" process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
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
										
										
										
										if($fmsdata[$stepsdata['_id']]['none_date']!=''){ 
												// if step 17
												//$step17Act ='';
												if($stepsdata['_id']=='5d2c114fd049041cc660fa52'){
													// save for step 18
													$step17Act = $fmsdata[$stepsdata['_id']]['none_date'];
												}
												
												if($stepsdata['_id']=='5d2c114fd049041cc660fa53'){
													// save for step 18
													$step18Act = $fmsdata[$stepsdata['_id']]['none_date'];
													$step17plane = date('Y/m/d H:i:s', strtotime('+1 hour'.$step17Act));
													$step18Act = date('Y/m/d H:i:s', strtotime($step18Act));
													
														if($step17plane!='' && $step18Act!=''){
															if($step17plane<$step18Act){
																$style2=' style="background:'.BG_RED.'; color:#fff"';
															}else{
																$style2=' style="background:'.BG_GREEN.'; color:#fff"';
															}
														}
														//echo "==555";
												}else{
													$style2='';
												}
												
											// echo '---17p=='.$step17plane.'==18act='.$step18Act; 
										?>
											<td class="text-center" colspan="2" <?php echo $style2; ?>>
											{{changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date'])}}
											
											</td>
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										
										<?php 
											$none_date_val = $fmsdata[$stepsdata['_id']]['none_date'];
											if($none_date_val=='' && $none_date_flage==''){
												$ac_and_dc_date = '';
												$none_date_flage=1;
											}else{
												$ac_and_dc_date = ' style="display:none"';
											}
											
										
										// if step 17
										//$step17Act ='';
										if($stepsdata['_id']=='5d2c114fd049041cc660fa52'){
											// save for step 18
											$step17Act = $fmsdata[$stepsdata['_id']]['none_date'];
										}
										
										// step 18
										// update pm status 
										$pmChk = '';
										if(array_key_exists('pm_check', $stepsdata)){
											$pmChk =$order_no;
										}
										
										
										if($stepsdata['_id']=='5d2c114fd049041cc660fa53'){ ?>
											@if(in_array(2, $userRightArr) || userHasRight())
											<a href="javascript:void(0)" class="update_ac_date_chkpm activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="<?php echo $step17Act;?>" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" step_17val="<?php echo $step17Act;?>" <?php echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
										@endif
										<?php }else{
											
											if($activateQc1==1){
												$ac_and_dc_date='';
											}
											
											$pattern_standard=0;
											if($custom_pattern_val=='Standard' && $stepsdata['_id']=='5d2c114fd049041cc660fa45' && $stepsdata['_id']!='5d2c114fd049041cc660fa47'){
												$pattern_standard=1;
												$ac_and_dc_date='';
											}
											
											if($stepsdata['_id']=='5d2c114fd049041cc660fa47'){
												$ac_and_dc_date='';
											}
										?>
										@if(in_array(2, $userRightArr) || userHasRight() && $pattern_standard!=1)
										<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" <?php echo $ac_and_dc_date; ?> {{ $activateQc1 }} pm_check="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
										@endif
										<?php }
										
										?>
										
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
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" <?php echo $ac_and_dc_date; ?> pm_check="" process=""> <i class="icon md-time" aria-hidden="true"></i></a>
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
										$custom_opt_Html.='<td class="text-center" colspan="2"><select name="custom_opt_row" id="row_'.$dataId.'" class="form-control" onchange="saveFabricCmnt(this.value, '.$sId.', '.$dtId.', '.$row_num_stage.')"><option value="">--Select--</option>';
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
											
											
										}elseif($fmsdata[$stepsdata['_id']]['built_options_id']=='tailor_name'){	
											
											echo '<td class="text-center" colspan="2">';
											$fab_id = $fmsdata['fb_id'];
												$fabDropdown='';	
												$row_num_fab = "'row".$row_num."'";
												$selected_fab='';
												$fabDropdown.='<select class="form-control text-center" name="master_list" onchange="tailorSave(this,'.$sId.', '.$dtId.', '.$row_num_fab.', '.$order_no.');"><option value="">--Select--</option>';												
												
												$fabDropdown.='<optgroup label="New List">';
												
												foreach($fabLists_new as $fabList_new){
													//$selected_fab = $fab_id==$fabList_new->id?' selected':'';
													$fabDropdown.='<option value='.$fabList_new->id.' '.$selected_fab.'>'.$fabList_new->f_name.'</option>';
												}
												$fabDropdown.='</optgroup>';
											
												$fabDropdown.='<optgroup label="Old List" disabled>';
												foreach($fabLists as $fabList){
													if(!in_array($fabList->id, $fabListArrNew)){
														$fabListArr[$fabList->id] = $fabList->f_name;
														//$selected_fab = $fab_id==$fabList->id?' selected':'';
														$fabDropdown.='<option value='.$fabList->id.' '.$selected_fab.'>'.$fabList->f_name.'</option>';
													}
												}
												$fabDropdown.='</optgroup>';
												
												echo $fabDropdown.='</select>';

											echo '</td>';
											
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
									
								<?php if($stepsdata['fms_when']['fms_when_type']==8){ ?>
										<td class="text-center" colspan="2">
										
										<?php if($stepsdata['fms_when']['input_type']=='text_field'){ ?>
											<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){ ?>
											
											<a href="javascript:void(0)" onclick="textboxModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="textboxlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger1"><strong>{{$fmsdata[$stepsdata['_id']]['comment']}}</strong></a>
											<div class="textbox_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
											
											<?php }else{ ?>
											
												<a href="javascript:void(0)" onclick="textboxModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="textboxlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray"><strong>Add Value</strong></a>
												<div class="textbox_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
												
											<?php } ?>
										<?php } ?>
										
										
										<?php if($stepsdata['fms_when']['input_type']=='qc2_packing'){ ?>
											<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
											<?php }else{ ?>
												<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray"><strong>Add Comment</strong></a>
												<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
											<?php }?>
										<?php } ?>
										
										<?php if($stepsdata['fms_when']['input_type']=='check_pm'){ ?>
											<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
											<?php }else{ ?>
												<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray"><strong>Add Comment</strong></a>
												<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
											<?php }?>
										<?php } ?>
										
										</td>
								<?php } ?>	
								
								<?php if($stepsdata['fms_when']['fms_when_type']==7 && $stepsdata['fms_when']['input_type']=='tailor_name')
								{
											$dataId = $fmsdata['_id'];
											$dataStepId = $stepsdata['_id'];
											$custom_opt_Html='';
											$sId = "'".$dataStepId."'";
											$dtId = "'".$dataId."'";
											$row_num_fab = "'row".$row_num."'";
									?>
										
										
										<?php if(array_key_exists('tailor_id', $fmsdata[$stepsdata['_id']])){ ?> 
										<td class="text-center" colspan="2">
												@if (in_array(3, $userRightArr) || userHasRight())
												<?php	
														$fab_id =  $fmsdata[$stepsdata['_id']]['tailor_id'];
														
														$fabDropdown='';												
														$fabDropdown.='<select class="form-control text-center" name="tailor_list" onchange="tailorSave(this,'.$sId.', '.$dtId.', '.$row_num_fab.', '.$order_no.');"><option value="">--Select--</option>';
														
														$fabDropdown.='<optgroup label="New List">';
														foreach($tailorLists as $fabList_new){
															$selected_fab = $fab_id==$fabList_new->id?' selected':'';
															$fabDropdown.='<option value='.$fabList_new->id.' '.$selected_fab.'>'.$fabList_new->f_name.'</option>';
														}
														
														
														$fabDropdown.='</optgroup>';
													
														$fabDropdown.='<optgroup label="Old List" disabled>';
														foreach($fabLists as $fabList){
															if(!in_array($fabList->id, $tailorListArr)){
																$fabListArr[$fabList->id] = $fabList->f_name;
																$selected_fab = $fab_id==$fabList->id?' selected':'';
																$fabDropdown.='<option value='.$fabList->id.' '.$selected_fab.'>'.$fabList->f_name.'</option>';
															}
														}
														$fabDropdown.='</optgroup>';
														
														echo $fabDropdown.='</select>';
													?>
												
												@else
													{{ $fmsdata[$stepsdata['_id']]['tailor'] }}
												@endif
												</td>
										<?php }else{ ?>
													<td class="text-center" colspan="2">
													@if (in_array(3, $userRightArr) || userHasRight())
										
													<?php	
															$fab_id = '';
															$fabDropdown='';												
															$fabDropdown.='<select class="form-control text-center" name="tailor_list" onchange="tailorSave(this,'.$sId.', '.$dtId.', '.$row_num_fab.', '.$order_no.');"><option value="">--Select--</option>';
															
															$fabDropdown.='<optgroup label="New List">';
															foreach($tailorLists as $fabList_new)
															{
																$selected_fab = $fab_id==$fabList_new->id?' selected':'';
																$fabDropdown.='<option value='.$fabList_new->id.' '.$selected_fab.'>'.$fabList_new->f_name.'</option>';
															}
															
															$fabDropdown.='</optgroup>';
														
															$fabDropdown.='<optgroup label="Old List" disabled>';
															foreach($fabLists as $fabList){
																if(!in_array($fabList->id, $tailorListArr)){
																	$fabListArr[$fabList->id] = $fabList->f_name;
																	$fabDropdown.='<option value='.$fabList->id.'>'.$fabList->f_name.'</option>';
																}
															}
															$fabDropdown.='</optgroup>';
															
															echo $fabDropdown.='</select>';
														?>
													
													@else
														{{$fmsdata[$stepsdata['_id']]['tailor']}}
													@endif
													</td>
										<?php } ?>
										
								<?php } ?>
								
								<?php if($stepsdata['fms_when']['fms_when_type']==4 && $stepsdata['fms_when']['input_type']=='notes'){ ?>
										<td class="text-center" colspan="2">
										<?php if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){ ?>
										<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="text-danger"><strong>Read Comment</strong></a>
										<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;">{{$fmsdata[$stepsdata['_id']]['comment']}}</div>
										<?php }else{ ?>
											<a href="javascript:void(0)" onclick="commentModel('{{ $fmsdata['_id'] }}', '{{ $stepsdata['_id'] }}' , 'row{{$row_num}}_{{ $stepsdata['_id'] }}', '{{ $saveCmnt }}');" id="cmntlink_row{{$row_num}}_{{ $stepsdata['_id'] }}" class="btn btn-lightgray"><strong>Add Comment</strong></a>
											<div class="cmnt_row{{$row_num}}_{{ $stepsdata['_id'] }}" style="display:none;"></div>
										<?php }?>
										
										</td>
								<?php } ?>
								<?php if($stepsdata['fms_when']['fms_when_type']==4 && $stepsdata['fms_when']['input_type']=='fabricator'){										
											$dataId = $fmsdata['_id'];
											$dataStepId = $stepsdata['_id'];
											$custom_opt_Html='';
											$sId = "'".$dataStepId."'";
											$dtId = "'".$dataId."'";
											$row_num_fab = "'row".$row_num."'";
								
										?>
										
										<?php if(array_key_exists('fabricator', $fmsdata[$stepsdata['_id']]) && $fmsdata[$stepsdata['_id']]['fabricator']!=''){ ?>
										<td class="text-center" colspan="2">
										@if (in_array(3, $userRightArr) || userHasRight())
										
										<?php	
												$activateQc1=1;
												$fab_id = $fmsdata['fb_id'];
												$fabDropdown='';												
												$fabDropdown.='<select class="form-control text-center" name="master_list" onchange="fabricatorSave(this,'.$sId.', '.$dtId.', '.$row_num_fab.', '.$order_no.');"><option value="">--Select--</option>';
												
												
												$fabDropdown.='<optgroup label="New List">';
												
												foreach($fabLists_new as $fabList_new){
													$selected_fab = $fab_id==$fabList_new->id?' selected':'';
													$fabDropdown.='<option value='.$fabList_new->id.' '.$selected_fab.'>'.$fabList_new->f_name.'</option>';
												}
												$fabDropdown.='</optgroup>';
											
												$fabDropdown.='<optgroup label="Old List" disabled>';
												foreach($fabLists as $fabList){
													if(!in_array($fabList->id, $fabListArrNew)){
														$fabListArr[$fabList->id] = $fabList->f_name;
														$selected_fab = $fab_id==$fabList->id?' selected':'';
														$fabDropdown.='<option value='.$fabList->id.' '.$selected_fab.'>'.$fabList->f_name.'</option>';
													}
												}
												$fabDropdown.='</optgroup>';
												
												echo $fabDropdown.='</select>';
											?>
										
										@else
											{{$fmsdata[$stepsdata['_id']]['fabricator']}}
										@endif
										</td>
										<?php }else{ ?>
										
										<td class="text-center" colspan="2">
										@if (in_array(3, $userRightArr) || userHasRight())
										
										<?php	
												//$fab_id = $fmsdata['fb_id'];
												$fabDropdown='';												
												$fabDropdown.='<select class="form-control text-center" name="master_list" onchange="fabricatorSave(this,'.$sId.', '.$dtId.', '.$row_num_fab.', '.$order_no.');"><option value="">--Select--</option>';
												
												
												$fabDropdown.='<optgroup label="New List">';
												
												foreach($fabLists_new as $fabList_new){
													$fabDropdown.='<option value='.$fabList_new->id.'>'.$fabList_new->f_name.'</option>';
												}
												$fabDropdown.='</optgroup>';
											
												$fabDropdown.='<optgroup label="Old List" disabled>';
												foreach($fabLists as $fabList){
													if(!in_array($fabList->id, $fabListArrNew)){
														$fabListArr[$fabList->id] = $fabList->f_name;
														$fabDropdown.='<option value='.$fabList->id.'>'.$fabList->f_name.'</option>';
													}
												}
												$fabDropdown.='</optgroup>';
												
												echo $fabDropdown.='</select>';
											?>
										
										@else
											{{$fmsdata[$stepsdata['_id']]['fabricator']}}
										@endif
										
										</td>
										<?php } ?>
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
									$klll=  updateStepidIntoDtata($stepsdata['_id'], $fmsdata['_id']);
									//echo $fmsdata['_id'];
									//pr($klll);
									?>
									No Data
								</td>
							<?php } ?>
							
							<?php 
								
							?>
							@endforeach
							
							@if( userHasRight() || $currentUserId =='5d2c4a4cd0490451ca641d72')
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

<!-- Text-Box Modal -->
<button data-toggle="modal" data-target="#textbox_model" id="textbox_model_open" style="display:none;"></button>
	<div class="modal fade modal-info user-list-box" id="textbox_model" role="dialog">
		<div class="modal-dialog modal-sm">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title">Text Value</h4>
			</div>
			<div class="modal-body">
				<div class="text_update"></div>			
				<div class="pop_form row">
					<div class="col-md-12">
						<form action="" method="POSt" onsubmit="event.preventDefault(); saveTextbox();" id="textboxForm">
							@csrf
							<input type="hidden" name="data_id" id="text_data_id" value="">
							<input type="hidden" name="step_id" id="text_step_id" value="">
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="cmnt_id_with_row" value="" id="text_id_with_row">
							<input type="text" class="form-control" placeholder="Text Value" name="user_cmnt" id="user_text">
							<br>
							<button type="submit" class="btn btn-success" id="save_text_btn" style="display:none;">Save</button>
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
	
	/* textbox model step */
	function textboxModel(fms_data_id, step_id, cmnt_id_with_row, cmntRight){
		$('.text_update').html('');
		$('#text_data_id').val('');
		$('#text_data_id').val(fms_data_id);
		$('#text_step_id').val('');
		$('#text_step_id').val(step_id);
		$('#text_id_with_row').val(cmnt_id_with_row);
		
		var comntText = $('.textbox_'+cmnt_id_with_row).text();
		$('#user_text').val(comntText);
		
		if(cmntRight==1){
			$('#save_text_btn').show();
		}else{
			$('#save_text_btn').hide();
		}
		
		$('#textbox_model_open').trigger('click');
	}
	
	function saveTextbox(){
		var cmnt_id_with_row = $('#text_id_with_row').val();
		var user_cmnt = $('#user_text').val();
		var formData = $('#textboxForm').serialize();
		var ajax_url = "<?php echo route('ajax_savecomment_in_data'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); cmntlink_
					var jsonData = JSON.parse(res);
					$('.text_update').html('');
					$('.text_update').html('<p class="text-success">'+jsonData.msg+'</p>');				
					$('.text_'+cmnt_id_with_row).text(user_cmnt);				
					$('#textboxlink_'+cmnt_id_with_row).removeClass('btn btn-success');				
					$('#textboxlink_'+cmnt_id_with_row).html('<strong>'+user_cmnt+'</strong>').removeClass('btn-lightgray').addClass('text-danger');				
					console.log(jsonData);
				}
		  });
	
	}
	/* textbox model step END*/
	
	
	/* textbox model task */
	function textboxTaskModel(fms_data_id, task_id, cmnt_id_with_row){
		$('.text_task_update').html('');
		$('#text_task_data_id').val('');
		$('#text_task_data_id').val(fms_data_id);
		$('#text_task_id').val('');
		$('#text_task_id').val(task_id);
		$('#text_task_id_with_row').val(cmnt_id_with_row);
		
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
	
	function fillFabricatorId(idVal){
		var fbName = $('#'+idVal).val();
		var fbid = $('#'+idVal+' option:selected').data('fbid');
		$('#fab_name').val('');
		$('#fab_id').val('');
		$('#fab_name').val(fbName);
		$('#fab_id').val(fbid);
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
	
	$('body').on('focus', ".date_short", function() {
		$('.date_short').datepicker({
			dateFormat: 'yy-mm-dd'
		});
	});
	
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
					
						//console.log(result); return false;
						var res = JSON.parse(result);
						$('#loader').hide();						
						//$('.activate_timestamp'+'_'+rowNum+':first').show();
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
	
	function savePattern(thisVal,taskId, dataId, rowNum){
		var currVal = thisVal;
		var dataId = dataId;
		//for deactivate pattern/dtandared
		var stepId = '5d2c114fd049041cc660fa45';  
		var taskId = taskId;
		var rowNum = rowNum;
		var fms_id = '<?php echo $fms_id;?>';
		
		if(currVal!=''){
		$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updatePattern'); ?>";
			$.ajax({
				type:'GET',
				url:ajax_url,
				data:{fms_id:fms_id,dataId:dataId,taskId:taskId,currVal:currVal},
				success:function(result){
					
						console.log(result);
						var res = JSON.parse(result);
						$('#loader').hide();
						
						/* deactivate pattern/dtandared */
						if(currVal=='Standard'){
							//$('#cmntlink_'+rowNum+'_'+stepId).removeClass('text-danger').addClass('link_disabled');
							$('#step_'+stepId+'_'+rowNum).removeClass('activate_timestamp_'+rowNum).addClass('link_disabled');
							//$('.activate_timestamp_'+rowNum).removeClass('activate_timestamp_'+rowNum).addClass('link_disabled');
							// step_5d2c114fd049041cc660fa45_row6
							//activate_timestamp_row6
							//$('#cmntlink_'+rowNum+'_'+stepId).removeClass('text-danger').addClass('link_disabled');
						}else{
							//$('#cmntlink_'+rowNum+'_'+stepId).addClass('text-danger').removeClass('link_disabled');
							$('#step_'+stepId+'_'+rowNum).removeClass('link_disabled').addClass('activate_timestamp_'+rowNum);
						}
						
						location.reload();
						
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
		//var pm_check = ""; pm_check="" process=""
		
		var ajax_url = "<?php echo route('ajax_updateActualDate'); ?>";
		$.ajax({
			type:'GET',
			url:ajax_url,
			data:{data_id:data_id,fms_id:fms_id, step_id:stepId,row_num_step:row_num_step,planed_date:planed_date, pm_check:pm_check,process:process},
			success:function(result){
					//console.log(result);
					var res = JSON.parse(result);
					console.log(res);
					var step_id = res.step_id;
					//var row_num_step = res.row_num_step;
					
					$('#step_'+step_id+'_'+row_num_step).after(res.resHtml['date']);
					$('#'+stepIdMain).parent().css({"background": ""+res.resHtml['bgcolor']+"", "color": ""+res.resHtml['textcolor']+""});
					
					
					$('#step_'+step_id+'_'+row_num_step).remove();
					
					if(process!='emb_ready'){
							$('.activate_timestamp'+'_'+row_num_step+':first').removeAttr('style');
							$('.activate_timestamp'+'_'+row_num_step+':first').show();
					}
					
					
					$('.fms_stage_'+row_num_step).html('');
					$('.fms_stage_'+row_num_step).html(res.stage_text);
					
					if(step_id=="5d2c114fd049041cc660fa52"){
						$('#step_5d2c114fd049041cc660fa53_'+row_num_step).attr('step_17val', res.resHtml['date_main']);
					}
					
					return false;
					},
			error: function(result)
					{
						//console.log(result);
						return false;
					}
		});
		
	});
	

$('.update_ac_date_chkpm').on('click', function(event) {
		//debugger;
		var stepIdMain = $(this).attr('id');
		var stepIdSplit = stepIdMain.split('_');
		var stepId = stepIdSplit[1];
		
		var row_num_step = stepIdSplit[2];

		var planed_date = $(this).attr('step_17val');
		
		var data_id = $(this).attr('data_id');
		
		var pm_check = $(this).attr('pm_check');
		
		var fms_id = '<?php echo $fms_id; ?>';
		
		var ajax_url = "<?php echo route('ajax_updateActualDate_pm'); ?>";
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
 //$(this).css({ "top": y.top - 125 + "px"});
 $(this).css({ "top": y.top - 55 + "px"});
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

function getOrderNumber(){
	var order_number = $('#order_number').val();
	var fms_url = "<?php echo route('fmsdata', $fms['_id']) ?>";
	var newUrl = fms_url+'/0/'+order_number;
	window.location=newUrl;
}


function fabricatorSave(thisObj,stepId, dataId, rowNum, data_o_id){

		var fab_id = thisObj.value;
		var data_id = dataId;
		var step_id = stepId;
		var rowId = rowNum;
		var data_o_id = data_o_id;
		
		var fms_id = '<?php echo $fms_id;?>';		
		var fab_name = thisObj.options[thisObj.selectedIndex].innerHTML;
		
		var formData = 'data_id='+data_id+'&data_o_id='+data_o_id+'&step_id='+step_id+'&fms_id='+fms_id+'&fab_name='+fab_name+'&fab_id='+fab_id;
		
		if(fab_id!=''){
			$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updateFabricator'); ?>";
			
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
					var jsonData = JSON.parse(res);
					//alert('.activate_timestamp'+'_'+rowId+':first');
					
					//$('.activate_timestamp'+'_'+rowId+':first').removeAttr('style');
					//$('.activate_timestamp'+'_'+rowId+':first').show();
					$('#step_5d2c114fd049041cc660fa4c_'+rowId).show();
					
					$('#loader').hide();
					/* $('#fbtext_'+rowId).html('');
					$('#fbtext_'+rowId).html(jsonData.fabricator); */
					//$('.activate_timestamp'+'_'+rowId+':first').show();					
					console.log(jsonData);
					//location.reload();
				}
			});
		
		}
	}
	
	
	function tailorSave(thisObj,stepId, dataId, rowNum, data_o_id){
		var fab_id = thisObj.value;
		var data_id = dataId;
		var step_id = stepId;
		var rowId = rowNum;
		var data_o_id = data_o_id;
		
		var fms_id = '<?php echo $fms_id;?>';		
		var fab_name = thisObj.options[thisObj.selectedIndex].innerHTML;
		
		var formData = 'data_id='+data_id+'&data_o_id='+data_o_id+'&step_id='+step_id+'&fms_id='+fms_id+'&fab_name='+fab_name+'&fab_id='+fab_id;
		var k='ttt';
		//return false; ajax_updateTailor
		
		if(fab_id!=''){
			$('#loader').show();
			var ajax_url = "<?php echo route('ajax_updateTailor'); ?>";
			
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					//console.log(res); return false;
					var jsonData = JSON.parse(res);
					
					//$('.activate_timestamp'+'_'+rowId+':first').removeAttr('style');
					$('#step_5d2c114fd049041cc660fa4c_'+rowId).show();
					
					$('#loader').hide();
					/* $('#fbtext_'+rowId).html('');
					$('#fbtext_'+rowId).html(jsonData.fabricator); */
					//$('.activate_timestamp'+'_'+rowId+':first').show();					
					console.log(jsonData);
					//location.reload();
				}
			});
		
		}
	}
</script>
@endsection