@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Fabricator Data')
@section('pagecontent')
<div class="page">
    <div class="page-content container-fluid">
		<h2>{{ __('Fabricator Data') }}</h2>
		<div class="row justify-content-center">
					@if (\Session::has('success'))
					  <div class="alert alert-success">
						<p>{{ \Session::get('success') }}</p>
					  </div><br />
					@endif
			<div class="col-md-12">
				<div class="card">
					
					<div class="card-body">
						<?php 
							//pr($fabdatas);
							if(!empty($fabdatas)){
								$fms_id = $fabdatas[0]['fms_id'];
								
								$fbfinalArr = get_fabricator_data_details($fabdatas, $fms_id);
								
							}
							//echo "<pre>"; print_r($fbfinalArr); die;
							
							$fbdata = get_fms_babricators_by_fms_id($fms_id);
							//echo "<pre>"; print_r($fbdata); die;
							$dropdownHtml='';
							if(!empty($fbdata)){
								$dropdownHtml = '<select class="form-control" name="fabricator" id="fabricator" onchange="getfabricatorDataAjax();">';
								$dropdownHtml .= '<option value="">--- Select ---</option>';
								
								$checkFabricator = array();
								foreach($fbdata as $fbkey=>$fbval)
								{	
									if(!in_array($fbval['fb_id'], $checkFabricator)){
										array_push($checkFabricator, $fbval['fb_id']);
										$dropdownHtml .= '<option data-fbId="'.$fbval['fb_id'].'">'.$fbval['fabricator'].'</option>';
									}
															
								}
								
								$dropdownHtml .= '</select>';
							} 
							//echo $dropdownHtml; die;
						?>
						<form method="POST" action="" onsubmit="event.preventDefault();" id="fb_form">
							@csrf
							<input type="hidden" name="fms_id" value="{{ $fms_id }}">
							<input type="hidden" name="fb_name" id="fb_name" value="">
							<input type="hidden" name="fb_id" id="fb_id" value="">
							<div class="form-group row">
								<label for="menu_name" class="col-md-4 col-form-label text-md-right">{{ __('Fabricator') }}</label>
								<div class="col-md-4">
									{!! $dropdownHtml !!}
								</div>
							</div>
						</form>
						
						
					</div>
					
					
					<table class="table table-bordered">
						<thead>
							<th>Sr. No</th>
							<th>Fabricator Name</th>
							<th>Order No.</th>
							<th>Item Name</th>
							<th>QTY</th>
							<th>Price</th>
							<th>Total (QTY*Price+250)</th>
						<thead>
						
						<tbody id="fb_table_body">
							<?php $sr=1; ?>
							@foreach($fbfinalArr as $fbdatakey=>$fbdataval)
							<tr>
								<td>{{ $sr }}</td>
								<td>{{ $fbdataval['fabricator'] }}</td>
								<td>{{ $fbdataval['order_no'] }}</td>
								<td>iccc</td>
								<td>{{ $fbdataval['order_qty'] }}</td>
								<td>ippp</td>
								<td>adhfj</td>
								
								<?php /*?> 
								<td>{{ $sr }}</td>
								<td>{{ $fbdataval['fabricator'] }}</td>
								<td>{{ $fbdataval['order_no'] }}</td>
								<td>{{ $fbdataval['item']['i_code'] }}</td>
								<td>{{ $fbdataval['order_qty'] }}</td>
								<td>{{ $fbdataval['item']['fb_price'] }}</td>
								<td>{{ ($fbdataval['order_qty']*$fbdataval['item']['fb_price'])+250 }}</td>
								<?php */?>
							</tr>
							<?php $sr++; ?>
							@endforeach
							
						</tbody>
					</table>
					
				</div>
				
			</div>
			
		</div>
	</div>  
</div>
@endsection

@section('custom_script')
<script>
	function getfabricatorDataAjax()
	{
		//debugger;
		var fbName = $('#fabricator').val();
		var fbid = $('#fabricator option:selected').data('fbid');
		
		$('#fb_name').val(fbName);
		$('#fb_id').val(fbid);
		//alert(fbName+'====='+fbid);
		$('#loader').show();
		var data = $('#fb_form').serialize();		
		
		var ajax_url = "<?php echo route('ajax_getFabricatorData'); ?>";
		$.ajax({
		method:'POST',
		url:ajax_url,
		data:data,
		success:function(result){
				//console.log((result)); 
				//console.log(JSON.parse(result));
				$('#fb_table_body').html('');
				var data = JSON.parse(result);
				var tblHtml = '';
				var i=1;
				jQuery.each(data, function(index, item) {
					//console.log(item);
					
					tblHtml +='<tr><td>'+i+'</td>';
								tblHtml +='<td>'+item['fabricator']+'</td>';
								tblHtml +='<td>'+item['order_no']+'</td>';
								tblHtml +='<td>'+item['item']['i_code']+'</td>';
								tblHtml +='<td>'+item['order_qty']+'</td>';
								tblHtml +='<td>'+item['item']['fb_price']+'</td>';
								//tblHtml +='<td>'+(item['order_qty'])*()+'</td>';
								tblHtml +='<td>'+((item['order_qty']*item['item']['fb_price'])+250)+'</td>';
					tblHtml +='</tr>';
					
					i++;
				});
				$('#fb_table_body').html(tblHtml);
				$('#loader').hide();
				return false;
				},
		error:function(result)
				{
					console.log(result);
					$('#loader').hide();
					return false;
				}
		});
		
	}
</script>
@endsection