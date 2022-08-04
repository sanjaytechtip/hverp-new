@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Employee Payment View')
@section('pagecontent')
<div class="page">
    <div class="page-content container-fluid">
		<div class="row justify-content-center"> 
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item"><a href="{{ route('payment_list') }}">Payment List</a></li> 
				  <li class="breadcrumb-item">Payment View</li>
				</ol>
				<?php 
				$inv_Arr = request()->route()->parameters; 
				$inv = $inv_Arr['inv_no'];
				?>
				<h3>Payment View: #{{ $inv }} </h3>
				<div class="card">
						<?php
							/* if(!empty($data)){
								$fms_id = $data[0]['fms_id'];
								
							}else{
								$fms_id='';
							} */
							
							if( request()->get('order_no') ){
								$active_order_no = request()->get('order_no');
							}else{
								$active_order_no='';
							}
							
							$itemsArrAll = get_item_list_api();
							$itemsArr = $itemsArrAll['itemIdCodeArr'];
							//pr($itemsArr);  die;
							
							if(!empty($activeData)){
								$activeData =$activeData;
								$search_type=$activeData['search_type'];
								$search_id=$activeData['search_id'];
								$dt_from = $activeData['dt_from'];
								$dt_to = $activeData['dt_to'];
							}else{
								$activeData ='';
								$search_type='';
								$search_id='';
								$dt_from = '';
								$dt_to = '';
							}
							
							
							$item_price_arr = get_item_list_with_price_api();
							//pr($item_price_arr); die;
							//pr($qty_task_id); die;
							/* echo $tk['_id'];
							pr($tk); */
						?>
					
					@if(!empty($all_data))
						<?php 
							//pr($all_data); die('view====');
							//pr($all_data['p_data']); die('view====');
							$p_data = $all_data['p_data'][0];
							//pr($p_data); die('view===='); 
						
							$sr=1; 
							$grPrice=0;
							$s_emp_id=0;
							
							$price_type=''; 
							$search_type = $p_data['search_type'];
							$total_price = $p_data['total_price'];
							if($search_type=='fabricator_list'){
								$price_type='fb_price';
								$s_emp_id = Request()->fabricator_list;
							}elseif($search_type=='master_list'){
								$price_type='cutting_amt';
								$s_emp_id = Request()->master_list;
							}elseif($search_type=='patchers_list'){
								$price_type='patching_amt';
								$s_emp_id = Request()->patchers_list;
							}elseif($search_type=='tailor_list'){
								$price_type='cutting_amt';
								$s_emp_id = Request()->tailor_list;
							}elseif($search_type=='h_embroider_list'){
								$price_type='cutting_amt';
								$s_emp_id = Request()->h_embroider_list;
							}elseif($search_type=='m_embroider_list'){
								$price_type='cutting_amt';
								$s_emp_id = Request()->m_embroider_list;
							}
						?>
							<table class="table table-bordered">
								<thead>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Sr. No</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Order No.</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">FMS Name</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Item Name</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">QTY</th>
									<th class="bg-info task_left_fix" style="color:#fff !important;">Price</th>
									@if($search_type!='patchers_list' && $search_type!='tailor_list' && $search_type!='tailor_list' && $search_type!='h_embroider_list' && $search_type!='m_embroider_list')
									<th class="bg-info task_left_fix" style="color:#fff !important;">Cutting</th>
									@endif
									<th width="50" class="bg-info task_left_fix" style="color:#fff !important;">Total (Price*Qty)</th>
								</thead>
								
								<tbody id="fb_table_body">
									@foreach($all_data['fms_data'] as $row)
										<?php 
										//pr($row); die;
										
										//$fms_id = '5d0cd734d0490449dd7eb122';
										$fms_id = $row['fms_id'];
										$fms_type = getFmsDetailsById($fms_id)['fms_type'];

										/* for task id qty column */
										if($fms_type!='custom_order' && $fms_type!='sample_order'){
											$task_data = getTaskIdByColumnValue($fms_id, 'qty');
											$qty_task_oid  = (array) $task_data['_id'];
											$qty_task_id = $qty_task_oid['oid'];
										}
										
										/* for task id items column */
										$task_data_items = getTaskIdByColumnValue($fms_id, 'items');
										$items_task_oid  = (array) $task_data_items['_id'];
										$items_task_id = $items_task_oid['oid'];
										
										//pr($items_task_id); die;
										
										/* for task id orders column */
										$task_data_orders = getTaskIdByColumnValue($fms_id, 'orders');
										$orders_task_oid  = (array) $task_data_orders['_id'];
										$orders_task_id = $orders_task_oid['oid'];
										
										
										
										
										if(array_key_exists($row[$items_task_id], $item_price_arr)){
											
											if($fms_type=='custom_order' || $fms_type=='sample_order'){
												$qty = 1;
											}else{
												$qty = $row[$qty_task_id];
											}
											
											$cutting_price = $item_price_arr[$row[$items_task_id]]['cutting_amt'];
											
											if($fms_type=='sample_order'){
												
												if($search_type=='tailor_list'){
													$item_price = $item_price_arr[$row[$items_task_id]]['tailor_price'];
												}else if($search_type=='h_embroider_list'){
													$item_price = $item_price_arr[$row[$items_task_id]]['h_embr_cost'];
												}else if($search_type=='m_embroider_list'){
													$item_price = $item_price_arr[$row[$items_task_id]]['m_embr_cost'];
												}
												
											}else{
												$item_price = $item_price_arr[$row[$items_task_id]][$price_type];
											}
											//$item_price = 0;
											
											$order_no = $row[$orders_task_id];
											//$active_order_no
										?>
										<tr>
											<td>{{ $sr }}</td>
											<td <?php echo $order_no==$active_order_no?'class="font-weight-bold text-success"':'';?>>{{ $order_no }}</td>
											<td>{{ getFmsNameById($fms_id) }}</td>
											<td>{{ $itemsArr[$row[$items_task_id]] }}</td>
											<td>{{ $qty }}</td>
											<td>{{ $item_price }}</td>
											@if($search_type!='patchers_list' && $search_type!='tailor_list' && $search_type!='h_embroider_list' && $search_type!='m_embroider_list')
											<td>
												@if(array_key_exists('cutting_price', $row['inv'][$search_type]))
													{{ $cutting_price }}
												@endif
											</td>
											@endif
											<?php 
												$price = (($item_price)*($qty)); 
												$grPrice+=$price;
												?>
											<td>{{ $price }}</td>
										</tr>
										<?php 
										$sr++; 
										} ?>
										
									@endforeach
									
									<?php if($search_type=='patchers_list' || $search_type=='tailor_list' || $search_type=='tailor_list' || $search_type=='h_embroider_list' || $search_type=='m_embroider_list'){
										$colspan = 7;
									}else{
										$colspan = 8;
									} ?>
									<tr>
										<td colspan="{{ $colspan }}" style="text-align:right"><strong>Total Price: {{ $total_price }}</strong></td>
									</tr>
									
								</tbody>
							</table>
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


@endsection