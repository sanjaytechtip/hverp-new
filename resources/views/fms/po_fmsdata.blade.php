@extends('layouts.fms_admin_layouts')

@section('pageTitle', $fms['fms_name'])

@section('pagecontent')
<style type="text/css">
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
</style>

<?php
				//pr($fms); die;
				//pr($taskdatas); die;
				/* Get the user who has access of this fms */
				$userScoreArrMain = getUserIdAccessOfThisFms($fms['_id']);
				$fms_id = $fms['_id'];
				$currentUserId = Auth::user()->id;
					
			?>

<div class="page no-border" id="fms_data">
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
			<div class="row top-filter-row">
				<div class="col-md-1 logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
                
				<?php /*?><div class="col-md-9 search-form-wrap">
					<div class="form-inline"> 
						<form action="{{ route('fmsdata', $fms_id) }}" method="POST" onsubmit="return searchValidate();">
							@csrf
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							{!! $fabDropdown !!}
							
							<select class="form-control mb-2 mr-sm-2" name="order_month" id="order_range" style="margin-top: 14px; width:150px">
									<option value=""> --Select Month-- </option>
									{!! fms_order_month_dropdown_new($fms['_id'], $order_range) !!}
							</select>
							
							<select class="form-control mb-2 mr-sm-2" name="item_id" id="item_dropdown" style="margin-top: 14px;  width:150px">
								{!! $onlyOption !!}
							</select>  
							
							<input class="form-control mb-2 mr-sm-2" type="number" placeholder="Order Number" id="order_number" name="order_number" value="{{ $orderNum }}" style="margin-top: 14px;  width:150px" />
							
							<button type="submit" name="search_order" class="btn btn-primary mb-2" style="margin-top: 14px;" value="search"> Search </button>
							
							&nbsp;&nbsp;&nbsp;<input type="checkbox" name="completed" value="complete" id="check_completed_new" <?php if (Session::has('completed')){ echo "checked"; }?>> Completed
						</form>
					</div>
                </div>
				<?php */?>
				<div class="col-md-2 pagination-wrap">
						@if($fmsdatas_pg['total']>20)
						<?php 
							$noActive='';
							$current_pgnum='';
							
							if(isset($_GET) && !empty($_GET['page'])){
								$current_pgnum=$_GET['page'];
							}else{
								$current_pgnum='';
							}
							
							$noActive=1;
						?>
						<nav>
							<ul class="pagination">
								<?php 
									$completed='';
									if (Session::has('completed'))
									{ 
										$completed="complete";
									}
								?>
								<?php
								for($pg=1; $pg<=$fmsdatas_pg['last_page']; $pg++){
									if(!isset($_GET['page'])){
										$active = $pg==1?' active':'';
									}else{
										$active = $pg==$current_pgnum?' active':'';
									}
									
								?>	
								<li class="page-item<?php echo $active;?>">
									<?php /*?><a class="page-link" href="{{ $fmsdatas_pg['path'] }}?page={{ $pg }}&fab_id=&order_month={{ $order_range }}&item_id={{ $item_id }}&order_number={{ $orderNum }}&completed={{ $completed }}">{{ $pg }}</a><?php */?>
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
							<th colspan="<?php echo count($taskdatas)+1;?>" class="text-center font-weight-bold task_left_fix purple-bg-top">
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
							
							if (in_array(1, $userRightArr) || userHasRight()) {
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
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($input_type1=='fabricator')
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}
										
									$fms_when_type=$stepsdata['fms_when_type'];
									if($fms_when_type==5)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==6)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==7)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}else if($fms_when_type==8)
									{
										echo '<th class="text-center font-weight-bold step-title black-bg" colspan="2" rowspan="2">';
										?>
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
										{{ __('#') }}{{ $stepsdata['step'] }} <?php	echo $stepsdata['fms_what'].'</th>';
									}
									
								}else{
									$step_no_cnt++;
									
								?>
									<th class="text-center font-weight-bold step-title black-bg" colspan="2"> 
										<div class="auth-icon">
											
											@if( userHasRight() )
												<a href="javascript:void(0)" class="user_permit" id="{{ $stepsdata['_id'] }}"> <i class="icon md-more" aria-hidden="true" ></i></a>
											@endif
											{{ __('#') }}{{ $stepsdata['step'] }}
											{{ $stepsdata['fms_what'] }}
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
							<th class="text-center font-weight-bold" colspan="<?php echo $userAccessCnt;?>">MIS Report</th>
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
								
								//pr($taskdatas);  die('===');
							?>
							<th class="bg-info text-white task_left_fix purple-bg">Sr. No</th>
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
								?>
								<th class="bg-info text-white task_left_fix{{ $purple }}">{{$taskdata['task_name']}}</th>							
							@endforeach
							<?php //die('ufhus'); ?>
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
									if(!empty($stepsdata['fms_when']['input_type']) && !empty($stepsdata['fms_when']['input_type']!='custom_date'))
									{
										
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
							*/
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
							
							$pi_index=0;
							
							$oldPi='';
						?>
						@foreach($fmsdatas as $fmsdata)
						<?php
						//pr($fmsdata);  die('oooo');
						$pi_id=$fmsdata['po_id'];
						if($oldPi!=$pi_id){
							$pi_index=0;
							$piMain = getPoDataByPoId($pi_id);
							$piRows = getPoRowDataByPoId($pi_id)[$pi_index];
						}else{
							$piMain = getPoDataByPoId($pi_id);
							$piRows = getPoRowDataByPoId($pi_id)[$pi_index];
						}						
						$oldPi = $pi_id; 
						
						/* $pi_id=$fmsdata['po_id'];
						$piMain = getPoDataByPoId($pi_id);
						$piRows = getPoRowDataByPoId($pi_id)[$pi_index]; */						
						//pr($piRows); die;
						
						$pi_index++;
						
						$pacth='';
						$userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); ?>
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
						<td class="text-center task_left_fix sr_no">{{ $pgNum }}</td>
							<!---Task data-->
							<?php 
								$pgNum++;
								
								$no=0; $row_num++;
								$cnt = count($taskdatas);
								$k=1;
								$order_no ='';
								//pr($taskdatas); die();
							?>
							@foreach($taskdatas as $taskdata)
								<?php
																		
									//echo "==<br>";									
									//pr($taskdata); die;  
								
								/*
								$no++;									
								if($taskdata['field_type']=='orders')
								{
									$order_no = $fmsdata[$taskdata['_id']];
								}
								if($taskdata['field_type']=='date')
								{ ?>
								<td class="text-center task_left_fix">								
									@if(userHasRight())
									<?php 
											$stpId = '';
											$taskId = $taskdata['_id'];
											//$stpId = $taskdata['_id'];
											$dataId = $fmsdata['_id'];

											//$custom_opt_Html='';
											$taskId = "'".$taskId."'";
											$dtId = "'".$dataId."'";
											$row_num_stage = "'row".$row_num."'";
											
											$ac_date_order = "'".changeDateToDmy($fmsdata[$taskdata['_id']])."'";
											
											echo '<a href="javascript:void(0)" id="'.$taskdata['_id'].'_row'.$row_num.'" onclick="openDeteTimeModelOrder('.$dtId.','.$ac_date_order.', '.$taskId.', '.$row_num_stage.')">'.changeDateToDmy($fmsdata[$taskdata['_id']])."</a>";
											
									?>									
									@else
										{{changeDateToDmy($fmsdata[$taskdata['_id']])}}
									@endif
									
								</td>
								<?php }else if($taskdata['field_type']=='stage')
								{ ?>
								<td class="text-center task_left_fix stage-col">
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
								<?php }else if($taskdata['input_type']=='order_qty' && $taskdata['field_type']=='qty')
									{ ?>
									<td class="text-center task_left_fix">
												<?php if(!empty($fmsdata[$taskdata['_id']])){?>
												@if(userHasRight())
													<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', 'update_qty');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}"><strong>{{$fmsdata[$taskdata['_id']]}}</strong></a>
												<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;">{{$fmsdata[$taskdata['_id']]}}</div>
												<input type="hidden" name="hidden_qty" id="order_qty_{{ $order_no }}" value="{{$fmsdata[$taskdata['_id']]}}">
												@else
													{{$fmsdata[$taskdata['_id']]}}
												@endif
												
												
												<?php }else{ ?>
												
													<a href="javascript:void(0)" onclick="textboxTaskModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', 'update_qty');" id="textboxlink_row{{$row_num}}_{{ $taskdata['_id'] }}" class="btn btn-lightgray"><strong>Add Value</strong></a>
													<div class="textbox_row{{$row_num}}_{{ $taskdata['_id'] }}" style="display:none;"></div>
													
												<?php }?>
											
									</td>
									<?php }else if($taskdata['field_type']=='text_fabricator')
								{ ?>
								<td class="text-center task_left_fix">
								
									<span id="fbtext_row{{ $row_num }}">{{$fmsdata['fabricator']}}</span>
									
								</td>
								<?php }else{
								if($k==3){ ?> 
									<td class="text-center task_left_fix">
									
										@if(userHasRight())
											<a href="javascript:void(0)" onclick="taskItemModel('{{ $fmsdata['_id'] }}', '{{ $taskdata['_id'] }}' , 'row{{$row_num}}_{{ $taskdata['_id'] }}', '{{$fmsdata[$taskdata['_id']]}}');"  id="itemlink_row{{$row_num}}_{{ $taskdata['_id'] }}">
												<span id="row{{$row_num}}_{{ $taskdata['_id'] }}">
													{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
												</span>
											</a>
											
											
										<input type="hidden" name="hidden_item_id" id="order_item_{{ $order_no }}" value="{{ $fmsdata[$taskdata['_id']] }}">
									
										@else
											{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
										@endif
										
									</td>
								<?php }else{ ?>
									<td class="text-center task_left_fix">
										<?php /{{$fmsdata[$taskdata['_id']]}}  ?>
									</td>
								<?php
									}									
								}
								pr($fmsdata); die('---kkk---');
								$k++;
									bulkqty ssqty unit unit_price
								*/ 
								
								/* 
									@if($taskdata['field_type']=='po_serial_number')
										{{ $piMain['po_serial_number'] }}
									@elseif($taskdata['field_type']=='po_date')
										{{ $piMain['po_date'] }}
									@elseif($taskdata['field_type']=='po_date')
										{{ $piMain['po_date'] }}
									@elseif($taskdata['field_type']=='buyer_name')
										{{ $piMain['buyer_name'] }}
									@elseif($taskdata['field_type']=='buyer_po_number')
										{{ $piMain['buyer_po_number'] }}
									@elseif($taskdata['field_type']=='brand_name')
										{{ $piMain['brand_name'] }}
									@elseif($taskdata['field_type']=='agent_sales_person_code')
										{{ $piMain['agent_sales_person_code'] }}
									@elseif($taskdata['field_type']=='quantity_variation')
										{{ $piMain['quantity_variation'] }}
										
									@elseif($taskdata['field_type']=='item')
										{{ $piRows['item'] }}
									@elseif($taskdata['field_type']=='etd')
										{{ $piRows['etd'] }}
									@elseif($taskdata['field_type']=='bulkqty')
										{{ $piRows['bulkqty'] }}
									@elseif($taskdata['field_type']=='ssqty')
										{{ $piRows['ssqty'] }}
									@elseif($taskdata['field_type']=='unit')
										{{ $piRows['unit'] }}
									@elseif($taskdata['field_type']=='unit_price')
										{{ $piRows['unit_price'] }}
									@elseif($taskdata['field_type']=='colour')
										{{ $piRows['colour'] }}
									@else
										{{ $taskdata['task_name'] }}
									@endif
								*/
								?>
								<?php 
									/* $mainArrData = array('po_serial_number','proforma_invoice_date','buyer_name','buyer_po_number','brand_name','agent_sales_person_code','quantity_variation');									
									$rowArrData = array('item','etd','bulkqty','ssqty','unit','unit_price','colour');
									@if(in_array($taskdata['field_type'],$mainArrData))
										{{ $piMain[$taskdata['field_type']] }}
									@elseif(in_array($taskdata['field_type'],$rowArrData))
										{{ $piRows[$taskdata['field_type']] }}
									@else
										{{ $taskdata['task_name'] }}
									@endif */
								?>
								<td class="text-center task_left_fix">
									 
									@if($taskdata['field_type']=='timestamp')
										{{ changeDateToDmyHi($piMain['po_date']) }}
									@elseif($taskdata['field_type']=='po_no')
										{{ $piMain['po_serial_number'] }}
									@elseif($taskdata['field_type']=='po_date')
										{{ changeDateToDmyHi($piMain['po_date']) }}
									@elseif($taskdata['field_type']=='supplier_name')
										{{ GetBrandName($piMain['brand_name']) }}
									@elseif($taskdata['field_type']=='article_no')
										{{ getItemName($piRows['item']) }}
									@elseif($taskdata['field_type']=='color')
										{{ $piRows['colour'] }}
									@elseif($taskdata['field_type']=='order_qty')
										{{ $piRows['order_qty'] }}
									@elseif($taskdata['field_type']=='unit')
										{{ $piRows['unit'] }}
									@elseif($taskdata['field_type']=='price')
										{{ $piRows['unit_price'] }}
									@elseif($taskdata['field_type']=='mode_of_transport')
										{{ $piMain['mode_of_transport'] }}
									@elseif($taskdata['field_type']=='fob_sample_required')
										{{ $piMain['fob_sample'] }}
									@elseif($taskdata['field_type']=='s_s_required')
										---
									@elseif($taskdata['field_type']=='test_report_required')
										{{ $piMain['test_report'] }}
									@elseif($taskdata['field_type']=='transit_time')
										{{ $piMain['transit_time'] }}
									@elseif($taskdata['field_type']=='eta')
										--eta--
									@elseif($taskdata['field_type']=='po_type')
										{{ $piMain['po_type'] }}
									@elseif($taskdata['field_type']=='order_type')
										{{ $piMain['order_type'] }}
									@elseif($taskdata['field_type']=='po_status')
										<div class="fms_stage_row{{ $row_num }}">
											@if(array_key_exists($taskdata['_id'], $fmsdata))
												{!! $fmsdata[$taskdata['_id']]['stage_text'] !!}
											@endif
										</div>
									@endif
									
									
									
									@if($taskdata['field_type']=='po_serial_number')
										{{ $piMain['po_serial_number'] }}
									@elseif($taskdata['field_type']=='proforma_invoice_date')
										{{ changeDateToDmyHi($piMain['proforma_invoice_date']) }}
									@elseif($taskdata['field_type']=='buyer_name')
										{{ GetBuyerName($piMain['buyer_name']) }}
									@elseif($taskdata['field_type']=='buyer_po_number')
										{{ $piMain['buyer_po_number'] }}
									@elseif($taskdata['field_type']=='brand_name')
										{{ GetBrandName($piMain['brand_name']) }}
									@elseif($taskdata['field_type']=='agent_sales_person_code')
										{{ getSalesPerson($piMain['agent_sales_person_code']) }}
									@elseif($taskdata['field_type']=='quantity_variation')
										{{ $piMain['quantity_variation'] }}
										
									@elseif($taskdata['field_type']=='item')
										{{ getItemName($piRows['item']) }}
									@elseif($taskdata['field_type']=='etd')
										{{ changeDateToDmyHi($piRows['etd']) }}
									@elseif($taskdata['field_type']=='bulkqty')
										{{ $piRows['bulkqty'] }}
									@elseif($taskdata['field_type']=='ssqty')
										{{ $piRows['ssqty'] }}
									
									
									@elseif($taskdata['field_type']=='colour')
										{{ $piRows['colour'] }}
									@elseif($taskdata['field_type']=='pi_status')
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
							?>
							@foreach($stepsdatas as $stepsdata)
							<?php
							$patch='';
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
								
								$patchingType=1;
								if(array_key_exists('patching',$stepsdata)){
									$patchingType = $stepsdata['patching'];
									//pr($stepsdata); die;
								}
								
							if (strtotime($plane_date)>strtotime(0) && array_key_exists('planed',$fmsdata[$stepsdata['_id']]))
							{
									$planeAlert='';
									/* $endTime = checkStepEndtime($fmsdata[$stepsdata['_id']]['planed']);
									if($endTime<5 && empty($fmsdata[$stepsdata['_id']]['actual'])){
										$planeAlert = 'style="background:'.BG_YELLOW.';"';
									}else{
										$planeAlert = '';
									} */
									
								?>
								<td class="text-center">
								{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['planed']) }}
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
										
										if($actual_date!=''){
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
									?>									
									@if(in_array(2, $userRightArr) || userHasRight())
									<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{$fmsdata[$stepsdata['_id']]['planed']}}" data_id="{{$fmsdata['_id']}}" pm_check="{{ $pmChk }}" patch="{{ $patch }}"  <?php echo $ac_and_dc_date; ?> process="{{ $process }}"> <i class="icon md-time" aria-hidden="true"></i></a>
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
										
										
										/* $tt = '';
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
										} */
										
										
										if($fmsdata[$stepsdata['_id']]['none_date']!=''){
											$ac_and_dc_date_flag='';
											
										?>
											<td class="text-center" colspan="2"> 
											{{ changeDateToDmyHi($fmsdata[$stepsdata['_id']]['none_date']) }}
											</td>
										<?php }else{ ?>
										<td class="text-center" colspan="2">
										@if(in_array(2, $userRightArr) || userHasRight())
										<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="" data_id="{{$fmsdata['_id']}}" patch="" <?php echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
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
												<a href="javascript:void(0)" class="update_ac_date activate_timestamp_row{{$row_num}} btn btn-success" id="step_{{$stepsdata['_id']}}_row{{$row_num}}" rel="{{ $fmsdata[$stepsdata['_id']]['delivery_date'] }}" data_id="{{$fmsdata['_id']}}" patch="" <?php echo $ac_and_dc_date; ?>> <i class="icon md-time" aria-hidden="true"></i></a>
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
											
											//skip if pacthin not required 
											if($patch==0 && $patchingType==0){
												echo '<td class="text-center" colspan="2"></td>';
											}else{
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
								foreach($userScoreArr as $theKey=>$theVal){
									
									if($theVal['score']>0){
										$theCnt = $theVal['count']>0?$theVal['count']:1;
										$aveRage = round(($theVal['score']/$theCnt),2);
										
										echo '<td>-'.$aveRage.'%';
										
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
	


@endsection

@section('custom_script')
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
	
	function fabricatorModel(fms_data_id, step_id, fab_id_with_row, orderNum=''){
		$('#data_id').val('');
		$('#data_o_id').val('');
		$('#data_id').val(fms_data_id);
		$('#data_o_id').val(orderNum);
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
					location.reload();
				}
		  });	
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
					$('#loader').hide();
					$('#fbtext_'+rowId).html('');
					$('#fbtext_'+rowId).html(jsonData.fabricator);
					$('.activate_timestamp'+'_'+rowId+':first').show();					
					console.log(jsonData);
					//location.reload();
				}
			});
		
		}
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
							return false;
							},
					error:function(result)
							{
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

	function getMasterRecord(masterId){
		location = "{{ route('fmsdata', $fms['_id']) }}/?master_id="+masterId;
	}
	
	function getFabricatorRecord(fab_id){
		location = "{{ route('fmsdata', $fms['_id']) }}/?fab_id="+fab_id;
	}

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
 $(this).css({ "left": x.left - 865 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});

$("table.fixed_table tr.task_top_fix th").each(function() {
 var y = $(this).offset();
 /*alert("Top position: " + x.top + " Left position: " + x.left);
   console.log(x.left + "px" + " " + x.top + "px");*/
 //$(this).css({ "top": y.top - 123 + "px"});
 $(this).css({ "top": y.top - 55 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});




});


function getOrderNumber(){
	var order_number = $('#order_number').val();
		if(order_number==''){
			alert('Order Number is required.');
			$('#order_number').focus();
			return false;
		}
	var fms_url = "<?php echo route('fmsdata', $fms['_id']) ?>";
	var newUrl = fms_url+'/0/'+order_number;
	window.location=newUrl;
}


function searchValidate(){
	var fab_option_id = $('#fab_option_id').val();
	var order_range = $('#order_range').val();
	var item_dropdown = $('#item_dropdown').val();
	var order_number = $('#order_number').val();
	
	if(fab_option_id!='' || order_range!='' || item_dropdown!='' || order_number!=''){
		return true;
	}else{
		alert('At Least one search option required.');
		return false;
	}
}

</script>
@endsection