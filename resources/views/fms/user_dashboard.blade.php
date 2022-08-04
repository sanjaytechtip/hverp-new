@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Buyer/Brand Dashboard')
@section('pagecontent')
<div class="page">
      <div class="page-content">
        @if (\Session::has('success'))
					  <div class="alert alert-success text-center">
						<p>{{ \Session::get('success') }}</p>
					  </div><br />
		@endif
		@if (\Session::has('error'))
					  <div class="alert alert-danger text-center">
						<p>{{ \Session::get('error') }}</p>
					  </div><br />
		@endif
        <!-- Panel Table Add Row -->
		<?php 
			//$store_room = get_stock_room_list_api();
			$store_room = array();
			$roomArr=array();
			foreach($store_room as $rkey=>$rval){
				$roomArr[$rval->id] = $rval->name;
			}
			
			$routeName = Route::currentRouteName();
			// userfmslist 
			$userQuery  = '';
			$buyer_id  = '';
			$brand_id  = '';
			$company_name  = '';
			$brand_name  = '';
			$login_type  = '';
			$userLoginData = array();
			$queryArr = array();
			$pi_from = '';
			$pi_to = '';
			$user_id='';
			$login_name = '';
			$pstData =array();
			if($routeName=='user_dashboard'){
				$userLoginData = Session::get('userLogin');
				$login_type = $userLoginData['login_type'];
				
				/* $oid = (array) $userLoginData['_id'];
				$user_id = $oid['oid'];
				var_dump($user_id);
				pr($userLoginData);die; */
				
				if(!empty($userLoginData)){
					
					if($login_type=='buyer_login'){
						$company_name = $userLoginData['company_name'];
						$login_name = $userLoginData['company_name'];
						$queryArr = array(
											'customer_data'=>$company_name
										);
					}else{
						$brand_name = $userLoginData['brand_name'];
						$login_name = $userLoginData['brand_name'];
						$queryArr = array(
											'brand_data'=>$brand_name
										);
					}
					
					$user_id = $userLoginData['user_id'];
					$pstData = getPstData($user_id);
					
				}else{
					
					if(isset($_GET) && array_key_exists('customer_data',$_GET) && $_GET['customer_data']!=''){
						$buyer_id = $_GET['customer_data'];
						$company_name = $_GET['customer_data'];
						$login_name = $_GET['customer_data'];
						$queryArr['customer_data'] = $company_name;
						
						$o_id = (array) getCustomerId($company_name);
						$user_id = $o_id['oid'];
						
					}
					
					if(isset($_GET) && array_key_exists('brand_data',$_GET) && $_GET['brand_data']!=''){
						 $brand_id = $_GET['brand_data'];
						$brand_name =$_GET['brand_data'];
						$login_name =$_GET['brand_data'];
						$queryArr['brand_data'] = $brand_name;
					}
					//$user_id = $user_id
					//pr($user_id); die;
					if($user_id!=''){
						$pstData = getPstData($user_id);
					}
				}
				//pr($userLoginData);
			}
			//echo $userQuery;
			if(isset($_GET) && array_key_exists('pi_from', $_GET)){
				$pi_from = $_GET['pi_from'];
			}else{
				$pi_from = date('d-m-Y', strtotime("-1 years"));
			}
			
			if(isset($_GET) && array_key_exists('pi_to', $_GET)){
				$pi_to = $_GET['pi_to'];
			}else{
				$pi_to = date('d-m-Y');
			}
			
			$queryArr['pi_from'] = $pi_from;
			$queryArr['pi_to'] = $pi_to;
			$userQuery  = '?search=s&'.http_build_query($queryArr);
			
				//pr($pstData); die;
			
		?>

			
		
        <div class="panel">
			<div class="panel-body"> 
				<div class="page-content container-fluid">
					<h2>Welcome to {{WEBSITE_NAME}}</h2>
					<form>
					<div class="row ">
						
							<br/>
							@if(empty($userLoginData))
						
								<div class="col-md-4">
								Company Name: 
								<select class="required form-control" id="buyer_name" name="customer_data" data-plugin="select2" data-placeholder="Type Buyer name" data-minimum-input-length="2">
									<option value="">-- Select --</option>
								</select>
								</div>
								<div class="col-md-4">
									Brand Name: 
										<select class="required form-control" id="brand_name" name="brand_data" data-plugin="select2" data-placeholder="Type Brand name" data-minimum-input-length="2">
											<option value="">-- Select --</option>
										</select>
								</div>
								
							@endif
								
							@if($login_type=='buyer_login')
								<div class="col-md-4">
									Company Name: 
									<input type="text" value="{{ $company_name }}">
								</div>
							@elseif($login_type=='brand_login')
								<div class="col-md-4">
									Brand Name: 
									<input type="text" value="{{ $brand_name }}">
								</div>
							@endif
					</div>
					
					<div class="row ">
						<div class="col-md-2">
							<label>From:</label>
							<input type="text" class="form-control date_with_dmy" id="pi_from" autocomplete="off" name="pi_from" value="{{ @$pi_from }}">
						</div>
						
						<div class="col-md-2">
							<label>To:</label>
							<input type="text"  autocomplete="off"  id="pi_to" class="form-control date_with_dmy" name="pi_to" value="{{ @$pi_to }}">
						</div>
						
						<div class="col-md-1"><label>&nbsp;</label>
						  <button type="submit" class="btn btn-block btn-primary waves-effect waves-classic" name="submit">GO</button>
						</div>
						
						<div class="col-md-1"><label>&nbsp;</label>
						  <a href="{{ route('user_dashboard') }}" class="btn btn-block btn-primary waves-effect waves-classic" name="submit">Reset</a>
						</div>
						
						
						
					</div>
					</form>
					<br/>
					<div class="row">
						<?php 
							if(empty($userLoginData) && ($buyer_id!='' || $brand_id!='') || !empty($userLoginData)){
						?>
						<div class="col-md-12">
							<p>1. <u><a href="{{ route('fmsdata', '5e79feefd049043d090fdbb2') }}{{ $userQuery }}" target="_blank">BULK ORDER REPORT</a></u></p>
							<p>2. <u><a href="{{ route('fmsdata', '5eb254aa0f0e751788000094') }}{{ $userQuery }}" target="_blank">SAMPLING ORDER REPORT</a></u></p>
							<p>3. <u><a href="{{ route('fmsdata', '5f3522250f0e7503b80030d7') }}{{ $userQuery }}" target="_blank">SAMPLE CARD REPORT</a></u></p>
							<p>4. <u><a href="#">MIS</a></u></p>
							<p>5. <u><a href="javascript:void(0)" data-toggle="modal" data-target="#enquiryModal">NEW ENQUIRY</a></u></p>
							<p>6. <u><a href="javascript:void(0)" data-toggle="modal" data-target="#pstModal">PROBLEM SOLVING TICKET</a></u></p>
						</div>
						<?php 
							}
						?>
					</div>
				
				@if(!empty($pstData))
					<table class="table table-bordered">
						<thead>
							<th class="bg-info" style="color:#fff !important;">Date of PST</th>
							<th class="bg-info" style="color:#fff !important;">PST No.</th>
							<th class="bg-info" style="color:#fff !important;">Problem faced </th>
							<th class="bg-info" style="color:#fff !important;">Positex Remarks</th>
							<th class="bg-info" style="color:#fff !important;">Status (Open/ Closed)</th>						
							<th class="bg-info" style="color:#fff !important;">Date of PST Closed</th>
						</thead>
						<tbody id="fb_table_body">
						
							@foreach($pstData as $pdata)
								<tr>
									<td>{{ date('d M Y', strtotime($pdata['created_at'])) }}</td>
									<td>{{ $pdata['pst_no'] }}</td>
									<td>
										<?php 
											$text = strip_tags($pdata['problem_faced']);
											echo substr($text, 0, 50);
										?>
									</td>
									<td>{{ $pdata['positex_remarks'] }}</td>
									<td>{{ $pdata['status'] }}</td>
									<td>{{ $pdata['pst_closed_at'] }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
		@endif		
        <!-- End Panel Table Add Row -->
				
					
				</div>
			
				
			
			</div>
        </div>
		
		
      </div>
    </div>

<div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Enquiry Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		<div id="msg-update"></div>
      </div>
		<form action="" method="post" onsubmit="event.preventDefault(); return senEnqMail();" id="invoice-form">
			<div class="modal-body">
			  <div class="form-group">
				<label for="message-text" class="col-form-label">Enquiry Message:</label>
				<textarea class="form-control note-codable" name="message" role="textbox" aria-multiline="true" id="message-text"></textarea>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" name="submit">Send Email</button>
			</div>
		</form>
    </div>
  </div>
</div>

<div class="modal fade" id="pstModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Problem Solving Ticket Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
		<div id="msg-update"></div>
      </div>
		<form action="" method="post" onsubmit="event.preventDefault(); return senPstMail();" id="pst-form">
			<div class="modal-body">
			  <div class="form-group">
				<label for="message-text" class="col-form-label">Problem faced:</label>
				<textarea class="form-control note-codable" name="message" role="textbox" aria-multiline="true" id="pst-message-text"></textarea>
			  </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" name="submit">Send Email</button>
			</div>
		</form>
    </div>
  </div>
</div>

@endsection

@section('custom_validation_script')
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="{{asset(PUBLIC_FOLDER.'theme')}}/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> 
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 


<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script> 
<script src="http://localhost/ppfms/public/theme/global/custom/js/summernote.min.js"></script><!-- For Editor --> 
<script>
$('body').on('focus', ".date_with_dmy", function() {
		$('.date_with_dmy').datepicker({
			dateFormat: 'dd-mm-yy'
		});
	});
	$(function () { 
		
		var buyerArr = '<?php print_r(getAllBuyer()); ?>';
		buyerArr = JSON.parse(buyerArr);
		//console.log(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		
		var buyer_id_set = '<?php echo $buyer_id; ?>';
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			var sel = '';
			if(buyer_id_set==buyerData[i].value){
				sel = ' selected';
			}
			buyerHtml+='<option value="'+buyerData[i].value+'" '+sel+'>'+buyerData[i].value+'</option>';
		}
		$('#buyer_name').html(buyerHtml);
		//console.log(buyerHtml);
		
		var brandArr = '<?php print_r(getBrandsList()); ?>';
		brandArr = JSON.parse(brandArr);
		//console.log(brandArr);
		var brandData = $.map(brandArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		
		var brand_id_set = '<?php echo $brand_id; ?>';
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<brandData.length; i++){
			var sel = '';
			if(brand_id_set==brandData[i].value){
				sel = ' selected';
			}
			buyerHtml+='<option value="'+brandData[i].value+'" '+sel+'>'+brandData[i].value+'</option>';
		}
		$('#brand_name').html(buyerHtml);
		
		
		/* for text editor  */
		var login_name = '{{ $login_name }}';
		$('#message-text').summernote('code','');
		var html = '<p>Hello Ms. Tanya,</p><p>I am interested in your below products.</p><p><br></p><p><br></p><p><br></p><p>Thank You,<br/>'+login_name+'</p>';
		 $('#message-text').summernote('code',html);
		 
		 
		 /* for PST */
		 var login_name = '{{ $login_name }}';
		$('#pst-message-text').summernote('code','');
		var html = '<p>Hello Mr. Dilip,</p><p>We are facing the below problem, for which would like to get a resolution from your side :</p><p><br></p><p><br></p><p><br></p><p>Thank You,<br/>'+login_name+'</p>';
		 $('#pst-message-text').summernote('code',html);
		
	});
	
	
	
	function senEnqMail(){
		var formData = $('#invoice-form').serialize();
		var login_name = '{{ $login_name }}';
			var ajax_url = "<?php echo route('sendenqemail'); ?>";
			$('#loader').show();
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					$('#loader').hide();
					console.log(res); //return false;
					if(res=='1'){
						alert('Enquiry was Sent successfully!')
						$('#msg-update').html('');
						$('#msg-update').html('<h3 class="text-success">Enquiry was Sent successfully!</h3>');
						//return false;
						location.reload();
					}
				}
			});
	}
	
	function senPstMail(){
		//alert('hi444'); return false;
		var formData = $('#pst-form').serialize();
		var login_name = '{{ $login_name }}';
			var ajax_url = "<?php echo route('sendpstemail'); ?>";
			$('#loader').show();
			$.ajax({
				type:'POST',
				url:ajax_url,
				data:formData,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				success:function(res){
					$('#loader').hide();
					console.log(res); //return false;
					if(res=='1'){
						alert('Problem Solving Ticket was sent successfully!')
						//$('#msg-update').html('');
						//$('#msg-update').html('<h3 class="text-success">Problem Solving Ticket was sent successfully!</h3>');
						//return false;
						location.reload();
					}
				}
			});
	}
	
</script>


@endsection

