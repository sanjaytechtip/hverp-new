@extends('layouts.fms_print_layouts')

@section('pageTitle', 'FMS Data Print')

@section('pagecontent')

<style type="text/css">
 @media print {
	 .print-none{ display:none; }	
}
</style>

<div class="page no-border" id="fms_data" style="margin-top:30px;">
		
      <div class="page-content">
        <!-- Panel Table Add Row -->
        <div class="panel">
		<div class="container">
			<div class="row">
			
				<div class="col-md-6">
					
					<h2 class="panel-title">{{$fms['fms_name']}}</h2>
					
				</div>
				<div class="col-md-6 print-none">				
				
				<input type="button" value="Print" onclick="window.print();" class="btn-primary float-right" style="padding:5px 10px; cursor: pointer;">
				<select class="form-control float-right" onchange="location = '{{route('printfms', $fms['_id'])}}/'+this.options[this.selectedIndex].value;" id="order_range" style="width:150px; margin-right:15px;">
					{!! fms_order_month_dropdown($fms['_id'], $order_range) !!}
				</select>
				</div>
				</div>
			</div>
			<div class="container">
			<div class="row">
			
          <div class="col-md-12">
			<?php
				
				$fms_id = $fms['_id'];
				
				/* Item list API */
				//$itemsArr = get_item_list_api();
				$itemsArrAll = get_item_list_api();
				$itemsArr = $itemsArrAll['itemIdCodeArr'];
				
				//pr($itemsArr['mainItemArr']); die;
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
			
            <table class="table table-bordered table-hover table-striped tbale_header" cellspacing="0" id="fmsdataTable">
				<thead>
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
							<th class="bg-info text-white task_left_fix">Sr. No</th>
							@foreach($taskdatas as $taskdata)
							
							<?php 
								$taskArr[$no] = $taskdata['_id'];
								
								$tbl_cell++;
								$no++;
								
							?>
							<th class="bg-info text-white task_left_fix">{{$taskdata['task_name']}}</th>
							@endforeach  
							
						</tr>
						
						
				</thead>
			  
				<tbody id="fmsTable">
						<?php 
							$row_num=0;
							//$pgNum = ($fmsdatas_pg->currentPage()-1)*100+1;
							//$pgNum = ($fmsdatas_pg['current_page']-1)*100+1;
							$pgNum=1;
						?>
						@foreach($fmsdatas as $fmsdata)
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
							?>
							@foreach($taskdatas as $taskdata)
								<?php
									$no++;
								if($taskdata['custom_type']=='date')
								{ ?>
								<td class="text-center task_left_fix">{{changeDateToDmy($fmsdata[$taskdata['_id']])}}</td>
								<?php }else if($taskdata['custom_type']=='stage')
								{ ?>
								<td class="text-center task_left_fix">
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
								<td class="text-center task_left_fix">
								
									@if(empty($patching))
										<a href="{{ route('insertpatching', $fms_id) }}">Enter Patching Details</a>
									@else
									{!! $patching !!}
									@endif
									
								</td>
								<?php }else if($taskdata['input_type']=='order_qty' && $taskdata['custom_type']=='qty')
									{ ?>
									<td class="text-center task_left_fix">
												{{$fmsdata[$taskdata['_id']]}}
									</td>
									<?php }else if($taskdata['custom_type']=='text_fabricator')
								{ ?>
								<td class="text-center task_left_fix">
								
									<span id="fbtext_row{{ $row_num }}">{{$fmsdata['fabricator']}}</span>
									
								</td>
								<?php }else{
								
								if($k==3){ ?> 
									<td class="text-center task_left_fix">
										{{ $itemsArr[$fmsdata[$taskdata['_id']]] }}
									</td>
								<?php }else{
								?>
									<td class="text-center task_left_fix">
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
						</tr>
						@endforeach
				</tbody>
            </table>
          </div>
		  </div>
		</div>
		</div>
        <!-- End Panel Table Add Row -->
      </div>
    </div>
	
@endsection

