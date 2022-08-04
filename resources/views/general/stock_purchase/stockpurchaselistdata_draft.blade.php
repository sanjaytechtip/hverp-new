@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Stock Purchase Data List Draft')
@section('pagecontent')
<style>
.ui-menu{ max-width: 200px; z-index: 99999;}
</style>
<div class="page">
	<div class="page-header">
		<h1 class="page-title">Stock Purchase Data List Draft	</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('stockpurchaselist') }}">Stock Purchase List</a></li>
			<li class="breadcrumb-item">Stock Purchase Data List Draft</li>
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
		?>
		<div class="panel">
			<div class="panel-body">
				@if(!empty($data))
				<p><strong>Packing Slip No.:</strong> POS/PPS/{{ get_financial_year() }}/{{ $packing_slip_no }}</p>
				<table class="table table-bordered">
					<thead>
					    <th class="bg-info text-light">Sr.No</th>
					    <th class="bg-info" style="color:#fff !important;">Article No.</th>
					    <th class="bg-info" style="color:#fff !important;">Color</th>
						<th class="bg-info" style="color:#fff !important;">Po No</th>
						<th class="bg-info" style="color:#fff !important;">Lot No</th>
						<th class="bg-info" style="color:#fff !important;">Roll No</th>						
						<th class="bg-info" style="color:#fff !important;">QTY</th>
						<th class="bg-info" style="color:#fff !important;">Unit</th>
						<th class="bg-info" style="color:#fff !important;">RIC</th>
						<th class="bg-info" style="color:#fff !important;">Purchase Date</th>
						<th class="bg-info" style="color:#fff !important;">Action</th>
					</thead>
					<tbody id="fb_table_body">
					
						<?php $i=1; $RIC_cnt = 0; $disabled=''; ?>
						
				    	@foreach($data as $row)
						<?php 
						//pr($row); 
						$stock_purchase_id = $row['stock_purchase_id'];
						$o_id = (array) $row['_id'];
						$row_id = $o_id['oid'];
						
						// bg-danger
						$RIC = $row['RIC'];
						$chkRIC = checkDuplicateRIC($RIC);
						//pr($chkRIC);
						
						if($chkRIC){
							$RIC_cnt++;
							$bgtr = ' class="bg-warning text-dark"';
						}else{
							$bgtr = '';
						}
						?>
						<tr <?php echo $bgtr;?>>
							<td><?php echo $i; ?></td>
							<td>{{ $row['item_data']['i_value'] }}</td>
							<td>{{ $row['color'] }}</td>
							<td>{{ $row['po_no'] }}</td>
							<td>{{ $row['lot_no'] }}</td>
							<td>{{ $row['roll_no'] }}</td>
							<td>{{ $row['qty'] }}</td>
							<td>{{ $row['unit'] }}</td>
							<td>{{ $RIC }}</td>
							<td>{{ date('d M Y', strtotime($row['created_at'])) }}</td>
							<td>
								<?php if($chkRIC){ ?>
										
										<a href="javascript:void(0)" title="Edit" onclick="openModel('{{ $stock_purchase_id }}','{{ $row_id }}','{{ $row['item_data']['i_id'] }}', '{{ $row['item_data']['i_value'] }}', '{{ $row['color'] }}','{{ $row['lot_no'] }}','{{ $row['roll_no'] }}', '{{ $row['qty'] }}','{{ $row['unit'] }}');" class="btn btn-pure btn-success icon  waves-effect waves-classic waves-effect waves-classic">
										<i class="icon md-edit" aria-hidden="true"></i> 
										</a>
										
										<a onclick="return confirm('Are you sure you want to delete?');" title="Delete" href="{{ route('stockpurchaselistdata_draft_delete', array( $row['stock_purchase_id'],$row['_id'])) }}" class="btn btn-pure btn-danger icon  waves-effect waves-classic waves-effect waves-classic"><i class="icon md-delete" aria-hidden="true"></i> </a>
										
								<?php } ?>
							</td>
						</tr>
						<?php $i++; ?>
						@endforeach
						<tr>
							<td colspan="11">
								<?php 
									if($RIC_cnt>0){
										$disabled ='disabled';
									}
								?>
								<a onclick="return confirm('Are you sure you want to approved this purchase data?');" title="Delete" href="{{ route('stockpurchaselistdata_draft_to_save', array( $row['stock_purchase_id'])) }}" style="float:right;" class="btn btn-pure btn-success icon waves-effect waves-classic waves-effect waves-classic"><button type="submit" name="submit" class="btn btn-success" <?php echo $disabled;?>><i class="icon md-check" aria-hidden="true"></i> Approve</button></a>
							
							</td>
						</tr>
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" id="model_open"></a>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
	<form method="POST" onsubmit="purchaseSave();" id="item_purchase_form">
		<input type="hidden" name="stock_purchase_id" id="stock_purchase_id" value="" />
		<input type="hidden" name="row_id" id="row_id" value="" />
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Purchase Item Data</h4>
      </div>
		<div class="modal-body">
		<div class="data_update"></div>        
		<table class="table table-bordered">
					<thead>
					    <th class="bg-dark text-light">Article No.</th>
					    <th class="bg-dark text-light">Color</th>
						<th class="bg-dark text-light">Lot No</th>
						<th class="bg-dark text-light">Roll No</th>						
						<th class="bg-dark text-light">QTY</th>
						<th class="bg-dark text-light">Unit</th>
					</thead>
			<tbody id="fb_table_body">
					<?php $i=0; ?>
					<tr>
						<td data-text="Item">
							<input type="text" name="i_value" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
							<input type="hidden" name="i_id" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="" />
                        </td>
						<td data-text="color">
								<select required class="form-control width-full" cus="{{$i}}" rel="color" id="tmp_id_colour_{{$i}}" name="color">
								</select>
                        </td>
						<td data-text="lot_no">
								<input type="number" autocomplete="off" class="form-control width-full" cus="{{$i}}" id="tmp_id_lot_no_{{$i}}" name="lot_no" required />
							</td>
							
							<td data-text="roll_no">
								<input type="number" autocomplete="off" class="form-control width-full" cus="{{$i}}" id="tmp_id_roll_no_{{$i}}" name="roll_no" required />
							</td>
							
							<td data-text="qty">
								<input type="number" autocomplete="off" required min="0" class="form-control width-full bulk-qty numbers-only" cus="{{$i}}" rel="bulk-qty" id="tmp_id_qty_{{$i}}" name="qty" />
							</td>
							
							<td data-text="Unit">
								<select class="form-control width-full" required rel="unit" id="tmp_id_unit_{{$i}}" name="unit">
								  <option value="Mtr">Mtr.</option>
								  <option value="Kg">Kg.</option>
								  <option value="Yd">Yd.</option>
								  <option value="Pcs">Pcs.</option>
								</select>
							</td>
					</tr>
					
			</tbody>
		</table>
		</div>
      <div class="modal-footer">
	  <button type="submit" name="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>
  </div>
</div>

@endsection
@section('custom_validation_script')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $("#invoiceform").validate({});
});


function purchaseSave(){ 
		event.preventDefault();
		/* var actual_id_with_row_cal = $('#actual_id_with_row_cal_fup').val();
		var currentDateTime_cal = $('#currentDateTime_cal_fup').val();
		var stpId = $('#actual_step_id_cal_fup').val();
		var row_num = $('#actual_id_with_row_cal_fup').val(); */
		
		var formData = $('#item_purchase_form').serialize();
		var ajax_url = "<?php echo route('ajaxSavepurchaseItem'); ?>";
		$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					console.log(res); //return false;					
					$('.data_update').html('');
					if(res==1){
						$('.data_update').html('<h4 class="text-success text-center">Data updated successfully.</h4>');
						location.reload();
					}else{
						$('.data_update').html('<p class="text-danger">Data not updated!!</p>');
					}
					
				}
			});
	}
</script>

<script>
	
	function openModel(purchaseId, RowId, itemId, itemName, color, lot_no, roll_no, qty, unit){
		//alert();
		$('.data_update').html('');
		$('#stock_purchase_id').val(purchaseId);
		$('#row_id').val(RowId);
		$('#tmp_id_item_0').val(itemId);
		$('#itemrel_0').val(itemName);
		$('#tmp_id_colour_0').html('<option>'+color+'</option>');
		$('#tmp_id_lot_no_0').val(lot_no);
		
		$('#tmp_id_roll_no_0').val(roll_no);
		$('#tmp_id_qty_0').val(qty);
		$('#tmp_id_unit_0').val(unit);
		
		$('#model_open').trigger('click');
		//$(".selectsearch").select2({minimumInputLength: 2});
	}
</script> 

<script>
  $(function(){
     $(document).on('change','.item',function(){
	      var id = $(this).attr('cus');
		  var item_val = $('#tmp_id_item_'+id).val();
		  if(item_val!='')
		  {
			$.ajax({
			type: "GET",
			url: "{{ url('admin/ajaxItemData')}}/"+item_val,
			data: {item_val:item_val},
			success: function(msg){
			data = JSON.parse(msg);
            //alert(msg); return false;
			$('#tmp_id_colour_'+id).html(data['colour']);
			
			$('#tmp_id_desc_'+id).val(data['description']);
			$('#tmp_id_hsn_code_'+id).val(data['hsn_code']);
			$('#tmp_id_gst_'+id).val(data['gst_code']);
			
			}
			});
		  }else{
			$('#tmp_id_colour_'+id).html('');  
		  }
	 });
  });
  </script>
<script>
	$(function () {
		
		
		function split( val ) {
		return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		return split( term ).pop();
		}
		
		jQuery(".autocomplete-dynamic").autocomplete({
			minLength: 2,
			source: "{{ route('getmatcharticle') }}",
			select: function( event, ui ) {
					event.preventDefault();
					//console.log(ui);
					
					var id_new = jQuery(this).attr('id');
					var rowNum = id_new.split('_')[1];
					console.log(id_new);
					console.log(rowNum);
					console.log(ui.item.colour);
					var item_color_arr = ui.item.colour.split(',');
					
					var factory_code = ui.item.factory_code;
				
					var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, ''); 
						
					var fabric_finish = ui.item.fabric_finish;
					var hsn_code = ui.item.hsn_code;

					var gst_code = ui.item.gst_code;
					
					jQuery('#itemrel_'+rowNum).val(ui.item.value);
					jQuery('#tmp_id_item_'+rowNum).val(ui.item.id);
					
					/* 5 Oct 2020 */
					var item_options = '<option value="">--Select--</option>';
					if(item_color_arr.length>0){
						for(var i=0; i<item_color_arr.length; i++){
							if(item_color_arr[i]!=''){
								item_options += '<option>'+item_color_arr[i]+'</option>';
							}
						}
					}
					jQuery('#tmp_id_colour_'+rowNum).empty();
					jQuery('#tmp_id_colour_'+rowNum).append(item_options);
					
				
				jQuery('#tmp_id_desc_'+rowNum).val(description);
				//jQuery('#tmp_id_remark_'+rowNum).val(remark_count);
				jQuery('#tmp_id_hsn_code_'+rowNum).val(hsn_code);
				jQuery('#tmp_id_gst_'+rowNum).val(gst_code);
					
			}
		}); 
   
});

</script>

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$.noConflict();
</script>

@endsection
