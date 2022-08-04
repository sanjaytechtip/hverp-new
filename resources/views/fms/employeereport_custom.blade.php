@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Employee Report')
@section('pagecontent')
<div class="page">
    <div class="page-content container-fluid">
		<div class="row justify-content-center">
					@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div><br />
					@endif
			<div class="col-md-12">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="{{ route('payment_list') }}">Payment List</a></li>
					<li class="breadcrumb-item">Create Payment</li>
				</ol>
				<div class="card">					
					<div class="card-body_rename">
						<?php
							$fms_id='';
							$fabLists = get_fabricator_api();
				
							$fabListArr = array();
							$fabListArrNew = array();
							$fabDropdown='';
							$fabLists_new = get_fabricator_new_api();
							foreach($fabLists_new as $fabList_new){
								array_push($fabListArrNew, $fabList_new->id);
							}
							
							$fabDropdown.='<select class="form-control" name="fabricator_list" id="fabricator"><option value="">--Select Fabricator--</option>';
								
									$fabDropdown.='<optgroup label="New List">';						
									foreach($fabLists_new as $fabList_new){
										//$selected_fab = $fab_id==$fabList_new->id?' selected':'';
										$fabDropdown.='<option value='.$fabList_new->id.'>'.$fabList_new->f_name.'</option>';
									}
									$fabDropdown.='</optgroup>';
								
									$fabDropdown.='<optgroup label="Old List" disabled>';
									foreach($fabLists as $fabList){
										if(!in_array($fabList->id, $fabListArrNew)){
											$fabListArr[$fabList->id] = $fabList->f_name;
											//$selected_fab = $fab_id==$fabList->id?' selected':'';
											$fabDropdown.='<option value='.$fabList->id.'>'.$fabList->f_name.'</option>';
										}
									}
									$fabDropdown.='</optgroup>';								
							$fabDropdown.='</select>';
							
							/* START patchers list API */
							$patcherLists = get_patchers_list_api();
							
							$patcherListArr = array();
							$patcherDropdown='';
							$patcherDropdown.='<select class="form-control text-center" name="patchers_list" id="masterOptionList"><option  value="">--Select Patchers--</option>';
							foreach($patcherLists as $patcherList){
								$patcherListArr[$patcherList->emp_id] = $patcherList->f_name;
								$patcherDropdown.='<option value='.$patcherList->emp_id.'>'.$patcherList->f_name.'</option>';
							}
							$patcherDropdown.='</select>';
							/* END patchers list API */
						
							/* START embroider list API embroiderDropdown */
							$embroiders = get_embroider_list_api();
							
							// hand embroider
							$h_embroidersArr = array();
							$h_embroiderDropdown='';
							$h_embroiderDropdown.='<select class="form-control text-center" name="h_embroider_list" id="masterOptionList"><option  value="">--Select Master--</option>';
							foreach($embroiders as $embroider){
								$h_embroidersArr[$embroider->emp_id] = $embroider->f_name;
								$h_embroiderDropdown.='<option value='.$embroider->emp_id.'>'.$embroider->f_name.'</option>';
							}
							$h_embroiderDropdown.='</select>';
							
							$m_embroidersArr = array();
							$m_embroiderDropdown='';
							$m_embroiderDropdown.='<select class="form-control text-center" name="m_embroider_list" id="masterOptionList"><option  value="">--Select Master--</option>';
							foreach($embroiders as $embroider){
								$m_embroidersArr[$embroider->emp_id] = $embroider->f_name;
								$m_embroiderDropdown.='<option value='.$embroider->emp_id.'>'.$embroider->f_name.'</option>';
							}
							$m_embroiderDropdown.='</select>';
							
							/* END embroider list API */
							
							$itemsArrAll = get_item_list_api();
							$itemsArr = $itemsArrAll['itemIdCodeArr'];
							//pr($itemsArr);  die;
							
							$display_none = 'style="display:none;"';
							
							
							
							if(!empty($activeData)){
								$activeData =$activeData;
								$search_type=$activeData['search_type'];
								$search_id=$activeData['search_id'];
								$dt_from = $activeData['dt_from'];
								$dt_to = $activeData['dt_to'];
								$fms_type = $activeData['fms_type'];
							}else{
								$activeData ='';
								$search_type='';
								$search_id='';
								$dt_from = '';
								$dt_to = '';
								$fms_type = '';
							}
							
							//pr($item_price_arr); die;
							//pr($qty_task_id); die;
							/* echo $tk['_id'];
							pr($tk); */
						?>
						<form method="POST" action="{{ route('employeereport') }}" id="emp_form">
							@csrf
							<input type="hidden" name="fms_id" value="">
							<div class="form-group row">
								
								<div class="col-md-2">
									<label for="menu_name">{{ __('FMS Type') }}</label>
									<select name="fms_type" class="form-control">
										<option value="">--Select--</option>
										<option value="stock_and_f3p_fms" <?php echo $fms_type=='stock_and_f3p_fms'?'selected':''; ?>>F-3 P & Stock FMS</option>
										<option value="custom_fms" <?php echo $fms_type=='custom_fms'?'selected':''; ?>>Custom FMS</option>
									</select>
								</div>
								
								
								<div class="col-md-2">
									<label for="menu_name">{{ __('Search Type') }}</label>
									<select name="search_type" class="form-control" onchange="searchType(this.value);">
										<option value="">--Select--</option>
										@foreach(empReportArr() as $key=>$val)											
											<option value="{{ $key }}" <?php echo $key==$search_type?'selected':''; ?> >{{ $val }}</option>
										@endforeach
									</select>
								</div>
								
								<div class="col-md-2 search_list" id="fabricator_list" <?php echo $search_type!="fabricator_list"?$display_none:''; ?>>
									<label for="menu_name">{{ __('Fabricator') }}</label>
									{!! $fabDropdown !!}
								</div>
								 
								<div class="col-md-2 search_list" id="patchers_list" <?php echo $search_type!="patchers_list"?$display_none:''; ?>>
									<label for="menu_name">{{ __("Patcher's") }}</label>
									{!! $patcherDropdown !!}
								</div>
								
								<div class="col-md-2">
									<label for="menu_name">{{ __("Date From") }}</label>
									<input autocomplete="off" type="text" class="form-control date_with_time" name="dt_from" value="{{$dt_from}}">
								</div>
								
								<div class="col-md-2">
									<label for="menu_name">{{ __("Date To") }}</label>
									<input autocomplete="off" type="text" class="form-control date_with_time" name="dt_to" value="{{$dt_to}}">
								</div>
								
								<div class="col-md-1">
									<label for="search">&nbsp;</label>
									<button type="submit" class="btn btn-primary waves-effect waves-classic form-control">
									Search</button>
								</div>
								
								<div style="float:left; margin-top:30px;"><a href="{{ route('employeereport') }}" class="btn btn-success"><i class="icon md-refresh" aria-hidden="true"></i> Reload</a></div>
								
							</div>
						</form>
						
						
					</div>
					
					@if(!empty($data))
						<?php 
							$sr=1; 
							$grPrice=0;
							$s_emp_id=0;
							
							$price_type='';
							if($search_type=='fabricator_list'){
								$price_type='fb_price';
								$s_emp_id = Request()->fabricator_list;
							}elseif($search_type=='master_list'){
								$price_type='cutting_amt';
								$s_emp_id = Request()->master_list;
							}elseif($search_type=='patchers_list'){
								$price_type='patching_amt';
								$s_emp_id = Request()->patchers_list;
							}
						?>
						<form action="{{ route('emp_payment') }}" method="POST">
							@csrf
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="search_type" value="<?php echo $_POST['search_type']; ?>">
							<input type="hidden" name="s_emp_id" value="<?php echo $s_emp_id; ?>">
							<table class="table table-bordered">
								<thead>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Sr. No</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Order No.</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">FMS Name</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Item Name</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">QTY</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Price</th>
									@if($search_type!='patchers_list')
									<th class="bg-info task_left_fix" style="color:#fff !important;">Cutting</th>
									@endif
									<th width="50" class="bg-info task_left_fix" style="color:#fff !important;">Total (Price*Qty)</th>
									<th width="50" class="bg-info task_left_fix" style="color:#fff !important;">Check Price</th>
								</thead>
								
								<tbody id="fb_table_body">
									<?php //pr($data['fms_ids']); die; ?>
									<?php 
										$item_price_arr = get_item_list_with_price_api();
										$fms_ids = $data['fms_ids'];										
										//pr($data); die;
									?>
									<?php
										unset($data['fms_ids']);
										 //pr($data); die; 
									?>
									<?php $ik=0; ?>
									@foreach($data as $rows)
										<?php 
											//echo $fms_ids[0].'==='; die;
											/* for task id qty column  $fms_id */
											$fms_id = $fms_ids[$ik];
											
											/* $task_data = getTaskIdByColumnValue($fms_id, 'qty');											
											$qty_task_oid  = (array) $task_data['_id'];
											$qty_task_id = $qty_task_oid['oid']; */
											
											/* for task id items column */
											$task_data_items = getTaskIdByColumnValue($fms_id, 'items');
											$items_task_oid  = (array) $task_data_items['_id'];
											$items_task_id = $items_task_oid['oid'];
											//pr($items_task_id); echo $fms_id ; die;
											
											/* for task id orders column */
											$task_data_orders = getTaskIdByColumnValue($fms_id, 'orders');
											$orders_task_oid  = (array) $task_data_orders['_id'];
											$orders_task_id = $orders_task_oid['oid'];
											//pr($orders_task_id); die;											
											//pr($task_data); die;
											
											/* if($ik==1){
												pr($task_data); die;
											} */
										?>
									
									@foreach($rows as $row)
									
										<?php //pr($row); echo $price_type."1111".$items_task_id;  pr($item_price_arr); die;
										if(array_key_exists($row[$items_task_id], $item_price_arr)){
											//$qty = $row[$qty_task_id];
											$qty = 1;
											$item_price = $item_price_arr[$row[$items_task_id]][$price_type];
											$cutting_price = $item_price_arr[$row[$items_task_id]]['cutting_amt'];
											
										?>
										<?php  //echo $ik.'======'; pr($item_price); //die; ?>
											<tr>
												<td>{{ $sr }}</td>
												<td>{{ $row[$orders_task_id] }}</td>
												<td>{{ getFmsNameById($fms_id) }}</td>
												<td>{{ $itemsArr[$row[$items_task_id]] }}</td>
												<td>{{ $qty }}</td>
												<td>{{ $item_price }}</td>
												@if($search_type!='patchers_list')
												<td>
													<label>
														<input type="checkbox" name="data[{{ $row['_id'] }}][cutting_price]" id="chk_cutting_{{$sr}}" value="<?php echo $cutting_price*$qty;?>" disabled onClick="addTotalValCutting(this.value, this.id);"> {{ $cutting_price }}
													</label>
												</td>
												@endif
												<?php 
													$price = (($item_price)*($qty)); 
													$grPrice+=$price;
													?>
												<td>{{ $price }}</td>
												<td>
													<?php if(!array_key_exists('inv', $row) || $row['inv']=="") { ?>
														<label>
														<input type="checkbox" name="data[{{ $row['_id'] }}][main_price]" id="chk_price_{{$sr}}" value="{{ $price }}" onClick="addTotalVal(this.value, this.id);">
														</label>
													<?php }elseif(!array_key_exists($search_type, $row['inv'])){ ?> 
														
														<label>
															<input type="checkbox" name="data[{{ $row['_id'] }}][main_price]" id="chk_price_{{$sr}}" value="{{ $price }}" onClick="addTotalVal(this.value, this.id);">
														</label>
														
													<?php } ?>
													<?php /* ?>
													@if(!array_key_exists('inv', $row) || $row['inv']=="")
													<label>
														<input type="checkbox" name="data[{{ $row['_id'] }}][main_price]" id="chk_price_{{$sr}}" value="{{ $price }}" onClick="addTotalVal(this.value, this.id);">
													</label>
													@endif
													<?php */ ?>
												</td>
											</tr>
											<?php 
											$sr++; 
										} ?>
									@endforeach
									
									<?php  $ik++; ?>
									
									@endforeach
									
									<?php if($search_type=='patchers_list'){
										$colspan = 7;
									}else{
										$colspan = 8;
									} ?>
									
									<tr>
										<td colspan="{{ $colspan }}" style="text-align:right"><strong>Total Price: {{ $grPrice }}</strong></td>
										<td>
												<strong><span id="all_price"></span></strong><br/>
												<input type="hidden" name="total_price" id="total_price" value="">
												<button type="submit" class="btn btn-success">Save</button>
											
										</td>
										
									</tr>
									
								</tbody>
							</table>
						</form>
					@endif
					
				</div>
				
			</div>
			
		</div>
	</div>  
</div>
@endsection

@section('custom_script')
<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>

<script>
	$('body').on('focus', ".date_with_time", function() {
		$('.date_with_time').datepicker({
			dateFormat: 'dd-mm-yy',
		});
	});
	
	var search_list = '<?php echo $search_type; ?>';
	var search_id = '<?php echo $search_id; ?>';
	
	$('#'+search_list+' select').val(search_id);
	
	function addTotalVal(thisVal, thisId){
		var idArr = thisId.split('_');
		var idNum = idArr[2];
		var totalVal = Number($('#all_price').text());
		var total_price = Number($('#total_price').val());
		var thisVal = Number(thisVal);
			if(totalVal<1){
				totalVal=0;
			}
		
		var chk = $('#'+thisId).prop("checked");
		
		var chk_cutting = $('#chk_cutting_'+idNum).prop("checked");
		var cuttingVal=0;
		if(chk==true){
			if(chk_cutting==true){
				cuttingVal =  Number($('#chk_cutting_'+idNum).val());
			}
			$('#all_price').text(totalVal+thisVal+cuttingVal);
			$('#total_price').val(totalVal+thisVal+cuttingVal);
			
			//enable data_id
			$('#data_id_'+idNum).attr('disabled', false);
			cuttingChk(chk, idNum)
			
		}else if(chk==false){
			
			if(chk_cutting==true){
				cuttingVal =  Number($('#chk_cutting_'+idNum).val());
			}
			
			$('#all_price').text(totalVal-thisVal-cuttingVal);
			$('#total_price').val(totalVal-thisVal-cuttingVal);
			
			//disabled data_id
			$('#data_id_'+idNum).attr('disabled', true);
			cuttingChk(chk, idNum)
			
		}
		
	}
	
	function cuttingChk(chk, idNum){
		//alert(chk+'--'+idNum);
		if(chk){
			$('#chk_cutting_'+idNum).attr('disabled', false);
		}else{
			$('#chk_cutting_'+idNum).prop('checked', false);
			$('#chk_cutting_'+idNum).attr('disabled', true);
		}
	}
	
	function addTotalValCutting(thisVal, thisId){
		var idArr = thisId.split('_');
		var idNum = idArr[2];
		var totalVal = Number($('#all_price').text());
		var total_price = Number($('#total_price').val());
		var thisVal = Number(thisVal);
			if(totalVal<1){
				totalVal=0;
			}
		
		var chk = $('#'+thisId).prop("checked");
		if(chk==true){
			$('#all_price').text(totalVal+thisVal);
			$('#total_price').val(totalVal+thisVal);
		}else if(chk==false){
			$('#all_price').text(totalVal-thisVal);
			$('#total_price').val(totalVal-thisVal);
			
		}
	}
	
	
	function searchType(thisVal)
	{
		$('.search_list').hide();
		$('#'+thisVal).show();
	}
	
</script>
@endsection