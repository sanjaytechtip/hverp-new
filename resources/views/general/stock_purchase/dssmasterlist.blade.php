@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'DSS Master List')
@section('pagecontent')
<div class="page">
	<div class="page-header">
		<h1 class="page-title">DSS Master List</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('stockpurchaselist') }}">Stock Purchase List</a></li>
			<li class="breadcrumb-item">DSS Master List</li>
		</ol>
	</div>
    <div class="page-content">
		<div class="row">
			@if (\Session::has('success'))
			<div class="col-md-12">
				<div class="alert alert-success">
					<p>{{ \Session::get('success') }}</p>
				</div>
			</div>
			@endif
			
		</div>
		<?php 
			//pr($data); die;
			$unique_items = get_item_from_purchase_data('item');
			$unique_color = get_item_from_purchase_data('color');
			$unique_po_no = get_item_from_purchase_data('po_no');
			$unique_lot_no = get_item_from_purchase_data('lot_no');
			
			$search_type='';
			$search_val='';
			if(isset($_GET) && $_GET['search_type']!=''){
				$search_type=$_GET['search_type'];
				$search_val=$_GET['search_val'];
			}
		?>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($data))
				<form method="get" action="">
				<table class="table table-bordered table-hover table-striped dataTable no-footer purchase-data-table">
					<thead>
					    <?php /*?><th class="bg-info" style="color:#fff !important;">Packing Slip No.</th> <?php */?>
					    <th class="bg-info" style="color:#fff !important;">
							Article No
							<div class="article-box">
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
						</th>
						<th class="bg-info" style="color:#fff !important;">
							Color
							<div class="article-box">
							<select id="item-color" multiple="multiple" name="color[]" >
									@foreach($unique_color as $ikey=>$i_item)
										<option value="{{ $i_item['color'] }}" <?php if(isset($_GET['color']) &&  in_array($i_item['color'], $_GET['color'])) { echo ' selected';} ?>>{{ $i_item['color'] }}</option>
									@endforeach
							</select>
							</div>
						</th>
						<th class="bg-info" style="color:#fff !important;">
							Po No
							<div class="article-box">
							<select id="item-po" multiple="multiple" name="po_no[]" >
										@foreach($unique_po_no as $ikey=>$i_item)
										<option value="{{ $i_item['po_no'] }}" <?php if(isset($_GET['po_no']) &&  in_array($i_item['po_no'], $_GET['po_no'])) { echo ' selected';} ?>>{{ $i_item['po_no'] }}</option>
									@endforeach
							</select>
							</div>
						</th>
						<th class="bg-info" style="color:#fff !important;">Lot No
						<select id="item-lot" multiple="multiple" name="lot_no[]" >
									@foreach($unique_lot_no as $ikey=>$i_item)
										<option value="{{ $i_item['lot_no'] }}" <?php if(isset($_GET['lot_no']) &&  in_array($i_item['lot_no'], $_GET['lot_no'])) { echo ' selected';} ?>>{{ $i_item['lot_no'] }}</option>
									@endforeach
							</select>
						</th>
						<th class="bg-info" style="color:#fff !important;">Roll No</th>
						<th class="bg-info" style="color:#fff !important;">Qty</th>
						<th class="bg-info" style="color:#fff !important;">Unit</th>
						<th class="bg-info" style="color:#fff !important;">Supplier Name</th>
						<th class="bg-info" style="color:#fff !important;">Invoice No.</th>
						<th class="bg-info" style="color:#fff !important;">RIC</th>
						<th class="bg-info" style="color:#fff !important;">Date | <button type="submit" class="btn btn-success">Search</button> <a href="{{ route('dssmasterlist') }}" > <i class="icon md-refresh" aria-hidden="true"></i> Reset </a></th>
					</thead>
					<tbody id="fb_table_body">
				    	@foreach($data as $row)
							<tr>
								<td>{{ $row['item_data']['i_value'] }}</td>
								<td>{{ $row['color'] }}</td>
								<td>{{ $row['po_no'] }}</td>
								<td>{{ $row['lot_no'] }}</td>
								<td>{{ $row['roll_no'] }}</td>
								<td>{{ $row['qty'] }}</td>
								<td>{{ $row['unit'] }}</td>
								<td>{{ getSupplierData($row['supplier_id'])[0]['company_name'] }}</td>
								<td>{{ $row['invoice_no'] }}</td>
								<td>{{ $row['RIC'] }}</td>
								<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				</form>
				@endif
				{{ $data->links() }}
			</div>
		</div>
	</div>
</div>
@endsection

@section('custom_script')
<script type="text/javascript" src="{{ URL::asset('public/multiselect/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		
		/* $('#order-status-qty').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		}); */
		
		$('#item-lot').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		
		$('#item-po').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		
		$('#item-color').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true
		});
		
		
		$('#item-select').multiselect({
			enableClickableOptGroups: true,
			enableCollapsibleOptGroups: true,
			enableFiltering: true,
			includeSelectAllOption: true,
			
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
	});
</script>
@endsection
