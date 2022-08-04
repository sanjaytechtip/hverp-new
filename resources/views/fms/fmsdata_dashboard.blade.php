@extends('layouts.fms_admin_layouts')

@section('pageTitle', 'FMS Data')

@section('pagecontent')
<style type="text/css">
body{
	overflow-y:hidden;
	overflow-x:auto;
}
#fms_data input.form-control{ min-width:100px; padding-left:5px; padding-right:5px; }
#fms_data input[type="date"]{ width:100px; -webkit-appearance:none; appearance:none; }
#fms_data select{ width:100px; }
.cell_done{background:{{ BG_GREEN }} !important;}

table.table.fixed_table td.emb-border {
    border-right: 3px solid #ff0000 !important;
}
table.table.fixed_table th.emb-border {
    border-right: 3px solid #ff0000 !important;
}
</style>
<div class="page no-border fms_dashboard" id="fms_data">
			
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
			<div class="row top-filter-row">
			
				<div class="col-md-3 logo-box">
					<header class="panel-heading">
						<div class="panel-title float-left">
							@include('../layouts/logo_client')
						</div>
					</header>
				</div>
				
				<div class="col-md-6 search-form-wrap float-left">
					<header class="form-inline">
						<div class="panel-title float-left">
							<span class="fms-name">{{$fms['fms_name']}} - DASHBOARD</span>
						</div>
					</header>
				</div>
				
                <div class="col-md-3 search-form-wrap float-left">
					<div class="form-inline float-right">
						<select class="form-control mb-2 mr-sm-2" onchange="location = '{{route('fmsdashboard', $fms['_id'])}}/'+this.options[this.selectedIndex].value;" id="order_range" style="margin-top: 14px; width:150px">
								<option> --Select Month-- </option>
								{!! fms_order_month_dropdown_new($fms['_id'], $order_range) !!}
						</select>
					</div>
                </div>
				
			</div>
        
			<div class="panel-body">			
			<?php
				$userScoreArrMain = getUserIdAccessOfThisFms($fms['_id']);
				
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
				//$itemsArr = get_item_list_api();
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
				
			
			?>
			
            <table class="table table-bordered table-hover table-striped tbale_header fixed_table" cellspacing="0" id="fmsdataTable">
				<thead>
						
							<?php
								$stepArr = array();
							?>
						
						<tr class="tbale_header  task_top_fix">
						
							<?php 
								$stp=0;
								$cnt = count($taskdatas);
								$stepid_with_stage_arr = array();
							?> 
							 
							
						</tr>
						<tr class="tbale_header text-center  task_top_fix">
							<?php 
								$taskArr = array();
								$tbl_cell=0;
								$no=0;
								$cnt = count($taskdatas);
							?>
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
							?>
							<th <?php echo $borderStyle; ?> class="bg-info text-white task_left_fix purple-bg" style="width:73px;">{{$taskdata['task_name']}}</th>
							
							@endforeach  
							
							@foreach($stepsdatas as $stepsdata)
							
							<?php 
								
								if($stepsdata['fms_what']=='Emb.'){
									$embroider=' emb-border';
								}else{
									$embroider='';
								}
							?>
							
							<th class="bg-dark text-white<?php echo $embroider; ?>">
									<div class="step_div">
									<?php 
									//echo str_replace(' ', '<br>', $stepsdata['fms_what']); 
									echo $stepsdata['fms_what']; 
									?>
									</div>
							</th>
							
							@endforeach
							
							
						</tr>
						
						
				</thead>
			  
				<tbody id="fmsTable">
						<?php 
							$row_num=0;
							
						?>
						@foreach($fmsdatas as $fmsdata)
						<?php $userScoreArr = $userScoreArrMain; //getUserIdAccessOfThisFms($fms['_id']); ?>
						<tr class="tbale_header text-center row_number_{{ $row_num+1 }}">
							<!---Task data-->
							<?php 								
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
								?>
								<td class="text-center task_left_fix"><a href="<?php echo route('fmsdata', [$fms['_id'],0, $fmsdata[$taskdata['_id']]]); ?>" target="_blank">{{$fmsdata[$taskdata['_id']]}}</a></td>
								<?php }else if($taskdata['custom_type']=='date')
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
								<?php }else if($taskdata['input_type']=='order_qty' && $taskdata['custom_type']=='qty')
									{ ?>
									<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
												<?php if(!empty($fmsdata[$taskdata['_id']])){?>
													<strong>{{$fmsdata[$taskdata['_id']]}}</strong>
												<?php }else{ ?>
												
												<?php }?>
									</td>
									<?php }else{ ?>
											<td <?php echo $borderStyle; ?> class="text-center task_left_fix">
												<?php 
												if($taskdata['input_type']=='builtin_options' && $taskdata['custom_type']=='items'){?> 
													{{ $itemsArr[$fmsdata[$taskdata['_id']]] }} 
												<?php }else{ ?>
														{{$fmsdata[$taskdata['_id']]}}
												<?php } ?>
											</td>
										<?php
										
									}
									$k++;
								?>
							@endforeach
							<!-- END task data -->
							<!-- Stap data -->
							<?php 
							$ac_and_dc_date = ''; 
							$ac_and_dc_date_flag = ''; 
							$kk=1;
							$acDatePrev=array();
							$delaypercentShow=0;
							
							///for dashboard edit for step data
							
							?>
							<!--- Step START in data --->
							@foreach($stepsdatas as $stepsdata)
							<?php 
								
								if($stepsdata['fms_what']=='Emb.'){
									$embroider=' emb-border';
								}else{
									$embroider='';
								}
							?>
							<?php 
								$styleBg='';
								if(array_key_exists('planed',$fmsdata[$stepsdata['_id']])){
									$plane_date = $fmsdata[$stepsdata['_id']]['planed'];
								}else{
									$plane_date='';
								}
								if (strtotime($plane_date)>strtotime(0) && array_key_exists('planed',$fmsdata[$stepsdata['_id']]))
									{
									
										$actual_date=$fmsdata[$stepsdata['_id']]['actual'];
										
										if(!empty($actual_date) && !empty($plane_date)){
											echo '<td class="text-center cell_done'.$embroider.'"></td>';
										}else{											
										?>
											<td class="text-center<?php echo $embroider;?>"></td>
										<?php
										} ?>
										
								<?php }elseif($stepsdata['fms_when']['fms_when_type']==4 && $stepsdata['fms_when']['input_type']=='notes'){
									if(array_key_exists('comment', $fmsdata[$stepsdata['_id']])){
										$styleBg =' style="background:'.BG_RED.' !important;"';
									}else{
										$styleBg ='style="background:'.BG_GREEN.' !important;"';
									}
								?>
								<td class="text-center<?php echo $embroider;?>" <?php echo $styleBg;?>></td>
								<?php }else{ ?> 
								<td class="text-center<?php echo $embroider;?>"></td>
								<?php }?>
							
							@endforeach
							<!--- Step end in data --->
							
						</tr>
						@endforeach
						
				</tbody>
            </table>
			
			
          </div>
        </div>
        <!-- End Panel Table Add Row -->
      </div>
    </div>




@endsection

@section('custom_script')

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
 $(this).css({ "top": y.top - 55 + "px"});
   /*console.log("Top position: " + x.top + " Left position: " + x.left);*/
});

});


</script>
@endsection

