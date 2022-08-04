@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View Purchase Order Page')

@section('pagecontent')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style type="text/css">
.table-wrap table{ border:1px solid #dddddd; font-size:14px }
.table-wrap th, .table-wrap td{ padding:10px 10px; border-bottom:1px solid #ddd; border-right:1px solid #ddd; }
.table-wrap th{ background:#fafbfb; white-space:nowrap; color:#999; font-weight:bold; padding:7px 10px; }
.purchaseorder-box{ clear:both; }
.purchaseorder-box .search-form{ margin-bottom:5px; }
.purchaseorder-box .search-form .search-form-box{ width:100%; clear:both; float:left; }
.purchaseorder-box .search-form .search-form-box > div{ float:left; margin:0 10px 10px 0; }
.purchaseorder-box .search-form hr{ clear:both; width:100%; }
.purchaseorder-box .search-form .search-form-box .contact-form{ width:250px; }
.purchaseorder-box .search-form .search-form-box .date-form{ width:180px; }
.purchaseorder-box .search-form .search-form-box > div label{ margin:0 0 2px; display:block; }
.purchaseorder-box .search-form .search-form-box > div input,
.purchaseorder-box .search-form .search-form-box > div select{ padding:8px 10px; border:1px solid #dddddd; }
.purchaseorder-box .search-form .search-form-box > div select{ padding-right:30px; display:inline-block; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts{ float:right; margin-right:0; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts label{ width:auto; display:inline-block; margin:0 5px 0 0; font-weight:bold; }
.purchaseorder-box .search-form .search-form-box .contact-form.right-amounts select{ width:calc(100% - 100px); }
.purchaseorder-box .table-wrap{ margin-bottom:15px; }
.purchaseorder-box .add-row{ width: 100%; clear: both; padding: 0 0 15px; overflow:hidden; border-bottom:1px solid #e0e0e0; }
.purchaseorder-box .add-left{ float: left; }
.purchaseorder-box .total-right{ float: right; margin-bottom:30px; }
.purchaseorder-box .total-right .subtotal-box{ border-bottom:1px solid #ddd; width: 300px; box-sizing: border-box; padding:8px 0 8px 30px; overflow: hidden; }
.purchaseorder-box .total-right .subtotal-box label{ margin:0; float:left; }
.purchaseorder-box .total-right .subtotal-box span{ float:right; }
.purchaseorder-box .total-right .subtotal-box.grand-total{ font-weight: bold; font-size: 150%;}
.purchaseorder-box .address-wrap{ clear:both; border-top:1px solid #e0e0e0; padding-top:20px; }
.purchaseorder-box .address-wrap label{ margin:0 0 2px; display:block; font-weight:bold; }
.purchaseorder-box .form-box{ margin-bottom:15px; }
.purchaseorder-box textarea{ height:110px; }
.purchaseorder-box .save-row{ padding:15px 0 0; clear: both; border-top:1px solid #e0e0e0; }
.delivery-address{ position:relative; z-index:99; display:inline-table; }
.delivery-address .dropdown-box{ position:absolute; background:#fff; top:99.99%; left:0; display:none; width:300px; }
.delivery-address.clicked .dropdown-box{ display:block; }
.delivery-address > a{ display:inline-table; padding:5px 20px 5px 0; }
.delivery-address > a:after { display: inline-block; width: 0; height: 0; margin-left: .2431rem; vertical-align: .2431rem; content: ""; border-top: .286rem solid; border-right: .286rem solid transparent;  border-bottom: 0; border-left: .286rem solid transparent; }
.delivery-address ul{ margin:0; padding:0; list-style:none; border:1px solid #e0e0e0; max-height:160px; overflow:auto; }
/* width */
.delivery-address ul::-webkit-scrollbar { width:10px; }
/* Track */
.delivery-address ul::-webkit-scrollbar-track { background:#f1f1f1; }
/* Handle */
.delivery-address ul::-webkit-scrollbar-thumb { background:#888; border-radius: 10px; }
/* Handle on hover */
.delivery-address ul::-webkit-scrollbar-thumb:hover { background:#555; }

.delivery-address ul li{ padding:10px; border-bottom:1px solid #e0e0e0; cursor:pointer; }
.delivery-address ul li.link-tab{ padding:0; }
.delivery-address ul li.link-tab a{ padding:10px; display:block; }
.delivery-address ul li.link-tab a:hover{ text-decoration:underline; }
.delivery-address ul li:hover{ background-color:#fafafa; }
.delivery-address ul li h2{ margin:0 0 8px; font-size:15px; }
.delivery-address ul li p:last-child{ margin-bottom:0; }
.table-wrap input { min-height:32px; color:#999; }
.table-wrap td{ height:32px; }
.typeahead { background-color: #FFFFFF; }
.tt-query { box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset; }
.tt-hint { color: #999999; }
.tt-menu { background-color: #FFFFFF; border: 1px solid rgba(0, 0, 0, 0.2); border-radius: 8px; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2); margin-top: 12px; padding: 8px 0; width: 190px; }
.tt-suggestion { font-size: 15px;  /* Set suggestion dropdown font size */ padding: 3px 10px; }
.tt-suggestion:hover { cursor: pointer; background-color: #0097CF; color: #FFFFFF; }
.tt-suggestion p { margin: 0; }
.dashboard .card { height: calc(100% - 90px); background:none;}
.table td, .table th { padding: 5px; vertical-align: top; border-top: 1px solid #e0e0e0; }
.two-button button{ float:left; border-radius:5px 0 0 5px; border-right:1px solid rgba(0, 0, 0, 0.1); padding:5px 15px !important; }
.two-button button + button{ border-radius:0 5px 5px 0; border-right:1px solid rgba(0, 0, 0, 0); outline:none; padding:5px 10px !important; }
.card-body{ background:#fff; -ms-flex: 1 1 auto; flex: 1 1 auto; padding: 1.429rem; }
.history-box{ margin-top:30px;  background:#fff; -ms-flex: 1 1 auto; flex: 1 1 auto; padding:0; }
.history-box h2{ font-size:15px; margin:0; padding:0 0 10px; border-bottom:1px solid #e0e0e0; }
.history-form{ padding:1.429rem; background:#fafbfb; display:none; }
.history-box.clicked .history-form{ display:block; }
.history-box.clicked .note-box{ display:none; }
.textarea-box{ padding:0 0 15px 0; }
.textarea-box textarea{ height:100px; }
.note-box{ padding:15px 0; }
label.error{color:#FF0000;}
.auto-fill-field{
	background-color : #d1d1d1;
}

/* for auto select option */
.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-no-suggestion { padding: 2px 5px;}
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: bold; color: #000; }
.autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
.big-table table td input[type="number"] {
    width: 117px !important;
}
</style>

<?php
/* $path= $edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'];
$pdf = @getPOPDFdata($path);
$data = count($pdf);

$sorted_pdf = array();
$sorted_pdf1 = array();
for($i=0;$i<$data;$i++){
	
	if(strpos($pdf[$i],"R")){
		array_push($sorted_pdf, $pdf[$i]);
	}else{
		array_push($sorted_pdf1, $pdf[$i]);
	}
	
}
$new_pdf = array_merge($sorted_pdf1, $sorted_pdf);

$html = '<select class="form-control" name="download" onchange="download(this.value)">';
$html .= '<option value="">---Select PDF---</option>';
for($i=0;$i<$data;$i++){
$html .= '<option value=/'.$new_pdf[$i].'>'.$new_pdf[$i].'</option>';
}
$html .='</select>';
 */
 
 $partshipped='';
	if(isset($_GET) && !empty($_GET) && $_GET['partshipped']!=''){
		$partshipped=$_GET['partshipped'];
		$path= $edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'-P'.$partshipped;
		$pdf = @getPOPDFdata($path);
		//pr($pdf); die;		
		$data = count($pdf);
		$sorted_pdf = array();
		$sorted_pdf1 = array();
		for($i=0;$i<$data;$i++){
				if(strpos($pdf[$i],"R")){
					array_push($sorted_pdf, $pdf[$i]);
				}else{
					array_push($sorted_pdf1, $pdf[$i]);
				}
		}
		$new_pdf = array_merge($sorted_pdf1, $sorted_pdf);
		//pr($new_pdf); die;
		$html = '<select class="form-control" name="download" onchange="download_part(this.value)">';
		$html .= '<option value="">---Select PDF---</option>';
			for($i=0;$i<$data;$i++){
				$html .= '<option value=/'.$new_pdf[$i].'>'.$new_pdf[$i].'</option>';
			}
		$html .='</select>';
	}else{
		$path= $edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'];
		$pdf = @getPOPDFdata($path);
		//pr($pdf); die;
		$data = count($pdf);
		$sorted_pdf = array();
		$sorted_pdf1 = array();
		for($i=0;$i<$data;$i++){
				if(strpos($pdf[$i],"R")){
					array_push($sorted_pdf, $pdf[$i]);
				}else{
					array_push($sorted_pdf1, $pdf[$i]);
				}
		}
		$new_pdf = array_merge($sorted_pdf1, $sorted_pdf);
		
		//pr($new_pdf); die;
		$html = '<select class="form-control" name="download" onchange="download(this.value)">';
		$html .= '<option value="">---Select PDF---</option>';
			for($i=0;$i<$data;$i++){
				$html .= '<option value=/'.$new_pdf[$i].'>'.$new_pdf[$i].'</option>';
			}
		$html .='</select>';
	
	}

$pi_oid = (array) $edit_purchase_invoice['_id'];
$pi_id = $pi_oid['oid'];
	$data_status = '';
if(array_key_exists('data_status', $edit_purchase_invoice)){
	$data_status = $edit_purchase_invoice['data_status'];
}
?>

<div class="page creat-fms-box">
	<div class="page-header">
		<h1 class="page-title">{{ __('View Purchase Order') }}</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('purchaselisting') }}">Purchase Order Management</a></li>
			<li class="breadcrumb-item">{{ __('View Purchase Order') }}</li>
		</ol>
		<div class="page-header-actions custom-page-heading">
			<div class="row no-space w-400">
				<div class="col-sm-4">
					<div class="counter">
						<span class="counter-number font-weight-medium">&nbsp;</span>
						<div class="counter-label">{!! $html !!}</div>
					</div>
				</div>
				
					<?php 
						$rev='';
						$pi_v_data = getPuInvVersionsById($pi_id);
						
						/* if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
							$pi_v_data = $pi_v_data['pi_version'];
							$pi_cnt = count($pi_v_data);
							$rev_cnt = $pi_cnt-1;
							
							if($rev_cnt==0){
								$rev='';
							}else{
								$rev = '-R'.($rev_cnt);
							}
							
						} */
						
						if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
							$pi_v_data_res = $pi_v_data['pi_version'];
							$pi_cnt = count($pi_v_data_res);
							$rev_cnt = $pi_cnt-1;
										
							if($rev_cnt==0){
								$rev='';
							}else{
								$rev = '-R'.($rev_cnt);
							}
						}
			
						$rev_p ='';
						if($partshipped!=''){
							$rev_p = '-P'.$partshipped;
							
							if(!empty($pi_v_data) && array_key_exists('pi_version', $pi_v_data)){
							
								$pi_v_data_res = $pi_v_data['pi_version'];
								$pi_cnt = count($pi_v_data_res);
								$rev_cnt = $pi_cnt-1;
											
								if($rev_cnt==0){
									$rev='';
								}else{
									$rev = '-R'.($rev_cnt);
								}
							}
						}
						
					?>
		
				<div class="col-sm-5">
					<div class="counter">
						<span class="counter-number font-weight-medium">PO NO. </span>
						<div class="counter-label">PO-POS-{{ $edit_purchase_invoice['financial_year'] }}-{{ $edit_purchase_invoice['po_serial_number'] }}{{ $rev_p }}{{ $rev }}</div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="counter">
						<span class="counter-number font-weight-medium">DATE</span>
						<div class="counter-label">{{ DateFormate($edit_purchase_invoice['created_at']) }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-content container-fluid">
		<div class="row justify-content-center"> @if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
			<br />
			@endif
			<div class="col-md-12">
				<form class="" id="invoiceform" action="{{ url('admin/purchaseupdate/'.$edit_purchase_invoice['_id']) }}" method="post" autocomplete="on" onsubmit="return validate();">
					@csrf
					<div class="card">
						<div class="card-body">
							<div class="row" style="margin:0;">
								<div class="col-lg-12 col-md-12 col-sm-12 full-form-box">
									<div class="row">
										<div class="contact-form bs-example contact-heading col-lg-12 col-md-12 ">
											<label for="contact"><strong>Supplier Info</strong></label>
										</div>
										<?php //pr($edit_purchase_invoice); die; 
											/* 
												@foreach(getSupplierData() as $supplier)
												<option value="{{ $supplier['_id'] }}" @if($supplier['_id']==$edit_purchase_invoice['brand_name']) selected @endif>{{ $supplier['company_name'] }}</option>
												@endforeach
											*/
										?>
										<div class="contact-form bs-example col-lg-3 col-md-4"  style="margin-top:10px;">
											<label for="contact">Name</label>
											<select class="required form-control" id="brand_name" name="brand_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2" disabled>
												<option value="">-- Select --</option>
											</select>
											
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Address</label>
											<input type="text" name="customer_address" id="customer_address" class="required form-control auto-fill-field" value="{{ $edit_purchase_invoice['customer_address'] }}" disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">State</label>
											<input type="text" name="buyer_state" id="buyer_state" class="required form-control auto-fill-field" value="{{ $edit_purchase_invoice['buyer_state'] }}" disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">City</label>
											<input type="text" name="buyer_city" id="buyer_city" class="form-control auto-fill-field" value="{{ $edit_purchase_invoice['buyer_city'] }}" disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Contact Person</label>
											<input type="text" name="buyer_contact_person" id="buyer_contact_person" class="form-control auto-fill-field" value="{{ $edit_purchase_invoice['buyer_contact_person'] }}"  disabled />
										</div>
										<?php 
											if(array_key_exists('supplier_info',$edit_purchase_invoice)){
												$supplier_info=$edit_purchase_invoice['supplier_info'];
											}else{
												$supplier_info='';
											}
										?>
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">GST No.</label>
											<input type="text" name="supplier_info" id="supplier_info" class="form-control auto-fill-field" value="{{ $supplier_info }}"  disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Phone No.</label>
											<input type="text" name="phone_no" id="phone_no" class="form-control auto-fill-field" value="{{ $edit_purchase_invoice['phone_no'] }}"  disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Po Type</label>
											<select class="form-control" id="po_type" name="po_type" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['po_type']=='Provisional') selected @endif value="Provisional">Provisional</option>
												<option @if($edit_purchase_invoice['po_type']=='Confirmed') selected @endif value="Confirmed">Confirmed</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Order Type</label>
											<select class="form-control" id="order_type" name="order_type" disabled>
												<option @if($edit_purchase_invoice['order_type']=='Import') selected @endif value="Import">Import</option>
												<option @if($edit_purchase_invoice['order_type']=='Local') selected @endif value="Local">Local</option>
												<option @if($edit_purchase_invoice['order_type']=='job_work_order') selected @endif value="job_work_order">Job Work Order</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Fob Sample Required</label>
											<select class="form-control" id="fob_sample" name="fob_sample" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['fob_sample']=='Yes') selected @endif value="Yes">Yes</option>
												<option @if($edit_purchase_invoice['fob_sample']=='No') selected @endif value="No">No</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Test Report Required</label>
											<select class="form-control" id="test_report" name="test_report" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['test_report']=='Yes') selected @endif value="Yes">Yes</option>
												<option @if($edit_purchase_invoice['test_report']=='No') selected @endif value="No">No</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Inspection Report Required</label>
											<select class="form-control" id="inspection_report" name="inspection_report" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['inspection_report']=='Yes') selected @endif value="Yes">Yes</option>
												<option @if($edit_purchase_invoice['inspection_report']=='No') selected @endif value="No">No</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Payment Terms</label>
											{!! getOptionDropdown('purchase_payment_terms', 'payment_terms', '--Select--',$edit_purchase_invoice['payment_terms'],'disabled id="payment_term"') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Quantity Variation(+/- %)</label>
											<select class="form-control" id="i" name="quantity_variation" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['quantity_variation']==3) selected @endif value="3">3</option>
												<option @if($edit_purchase_invoice['quantity_variation']==5) selected @endif value="5">5</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Incoterms</label>
											{!! getOptionDropdown('incoterms', 'incoterms', '--Select--',$edit_purchase_invoice['incoterms'],'disabled') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Final Destination</label>
											{!! getOptionDropdown('final_destination', 'final_destination', '--Select--',$edit_purchase_invoice['final_destination'],'disabled') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Mode Of Transport</label>
											{!! getOptionDropdown('mode_of_transport', 'mode_of_transport', '--Select--',$edit_purchase_invoice['mode_of_transport'],'disabled') !!}
										</div>
									
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Transporter Name</label>
											<select class="form-control date_with_time" name="transporter_name">
												@foreach(getOptionArray('transporter_name') as $res)
													<option @if($res==$edit_purchase_invoice['transporter_name']) selected @endif >{{ $res }}</option>
												@endforeach
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Transit Time</label>
											<select class="required form-control" id="transit_time" name="transit_time" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['transit_time']=='20 Days') selected @endif value="20 Days">20 Days</option>
												<option @if($edit_purchase_invoice['transit_time']=='35 Days') selected @endif value="35 Days">35 Days</option>
												<option @if($edit_purchase_invoice['transit_time']=='2 Days') selected @endif value="2 Days">2 Days</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">PI Reference</label>
											<input type="text" name="pi_reference" id="pi_reference" class="form-control" value="{{ $edit_purchase_invoice['pi_reference'] }}" disabled />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Order Status</label>
											<select class="form-control" id="order_status" name="order_status" disabled>
												<option value="">---Select---</option>
												<option @if($edit_purchase_invoice['order_status']=='Self Order') selected @endif value="Self Order">Self Order</option>
												<option @if($edit_purchase_invoice['order_status']=='Buyer Specific Order') selected @endif value="Buyer Specific Order">Buyer Specific Order</option>
											</select>
										</div>
										
										<?php 
											if(array_key_exists('documents_required_from_supplier',$edit_purchase_invoice)){
												$documents_required_from_supplier=explode(',',$edit_purchase_invoice['documents_required_from_supplier']);
											}else{
												$documents_required_from_supplier='';
											}
										?>
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Documents required from Supplier</label>
											<select disabled class="form-control date_with_time" multiple="multiple" data-plugin="select2" name="documents_required_from_supplier[]">
												@foreach(getOptionArray('documents_required_from_supplier') as $res)
													<option @if(in_array($res, $documents_required_from_supplier)) selected @endif>{{ $res }},</option>
												@endforeach
											</select>
										</div>
										
										<?php 
											if(array_key_exists('documents_prepared_by',$edit_purchase_invoice)){
												$documents_prepared_by=$edit_purchase_invoice['documents_prepared_by'];
											}else{
												$documents_prepared_by='';
											}
										?>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Documents Prepared By</label>
											<select class="form-control date_with_time" name="documents_prepared_by">
												@foreach(getOptionArray('documents_prepared_by') as $res)
													<option @if($res==$documents_prepared_by) selected @endif >{{ $res }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="purchaseorder-box">
								<hr />
								<div class="table-wrap big-table">
									<table class="editable-table table table-striped" id="editableTable1">
										<thead>
											<tr>
												<th width="100">Item No.</th>
												<th>Colour</th>
												<th>Factory Code</th>
												<th>Description</th>
												<th>Finish</th>
												<th>Lab Dip</th>
												<th>ETD</th>
												<th width="90">HSN Code</th>
												<th>Remark</th>
												<th>Order Qty</th>
												<th>S/S Qty</th>
												<th>Unit(Mt/Kg)</th>
												<th width="80">Unit Price</th>
												<th width="90">GST %</th>
												<th width="40">Amount</th>
												<th width="40">&nbsp;</th>
											</tr>
										</thead>
										<tbody id="results">
											<?php 
												$oid = (array) $edit_purchase_invoice['_id'];
												$po_id = $oid['oid'];
												$rowDatas = getPoRowDataByPoId($po_id);
													/* pr($rowDatas); 
													echo $po_id;
													die('==='); */ 
													$i=0;
											?>
											
											@foreach($rowDatas as $rowData)
										   <?php $i++; 
											//pr($rowData); die;
										   ?>
											<tr id="data-row-{{ $i }}">
												<td data-text="Item">
													<?php /*?>
													<select required class="form-control width-full item" cus="{{$i}}" rel="item" id="tmp_id_item_{{$i}}" name="row[{{$i}}][item]">
														<option value="">-- Select --</option>
														@foreach($items as $allitems)
															<option @if($allitems->_id==$edit_purchase_invoice['row'][$i]['item']) selected @endif value="{{ $allitems->_id }}">{{ $allitems->article_no }}</option>
														@endforeach
													</select>
													<?php */?>
													<?php 
														$itm = getAllArticles($rowData['item']);
														//pr($it);
													?>
													
													<input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" value="{{ $itm['article_no'] }}" required  disabled />
													<input type="hidden" name="row[{{$i}}][item]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{ $rowData['item'] }}"  disabled />
													
												</td>
												<td data-text="Colour">
													<select required class="form-control width-full" cus="{{$i}}" rel="colour" id="tmp_id_colour_{{$i}}" name="row[{{$i}}][colour]" data-plugin="select2" data-placeholder="Type color" data-minimum-input-length="2" rel="colour" disabled>
														<option value="">--Select--</option>
														{!! colorDataDetails($rowData['item'],$rowData['colour']) !!}
													</select>
												</td>
												<td data-text="Factory Code"><input type="text" class="form-control width-full factory-code auto-fill-field" cus="{{$i}}" rel="ss-qty" id="tmp_id_factory_{{$i}}" name="row[{{$i}}][factory_code]" value="{{ $rowData['factory_code'] }}" disabled /></td>
												
												<td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]" disabled>{{ $rowData['description'] }}</textarea></td>
												
												<td data-text="Finish"><textarea class="form-control width-full" rel="finish" id="tmp_id_finish_{{$i}}" style="height:35px;"  name="row[{{$i}}][finish]" disabled>{{ $rowData['finish'] }}</textarea></td>
                          
												<td data-text="Lab Dip No"><input type="text" min="0" class="form-control width-full bulk-qty" cus="{{$i}}" rel="bulk-qty" id="tmp_id_lab_dip_{{$i}}" name="row[{{$i}}][lab_dip_no]" value="{{ $rowData['lab_dip_no'] }}" disabled /></td>
												
												<td data-text="ETD">
												<input type="text" autocomplete="off" class="form-control width-full etd" cus="{{$i}}" rel="etd" id="tmp_id_etd_{{$i}}" name="row[{{$i}}][etd]" value="{{ $rowData['etd'] }}" disabled ></td>
												
												<td data-text="HSN Code">
													<input type="text" readonly="readonly" class="form-control width-full item hsn_code" cus="{{$i}}" rel="hsn_code" id="tmp_id_hsn_code_{{$i}}" name="row[{{$i}}][hsn_code]" value="{{ $rowData['hsn_code'] }}" disabled />
												</td>
							
												<td data-text="Remark"><textarea class="form-control width-full" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]" disabled>{{ $rowData['remark'] }}</textarea></td>
												
											
												
												<td data-text="Order Qty"><input type="number" required onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full bulk-qty" cus="{{$i}}" rel="order-qty" id="tmp_id_order_qty_{{$i}}" name="row[{{$i}}][order_qty]" value="{{ $rowData['order_qty'] }}" disabled /></td>
												
												<td data-text="S/S Qty"><input type="number" onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full bulk-qty" cus="{{$i}}" rel="ss-qty" id="tmp_id_ss_qty_{{$i}}" name="row[{{$i}}][ss_qty]" value="{{ $rowData['ss_qty'] }}" disabled /></td>
							
												<td data-text="Unit Price">
													<select class="form-control width-full unit" rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]" aria-invalid="false" disabled>
														<option @if($rowData['unit']=='Mtr') selected="selected" @endif value="Mtr">Mtr.</option> 
														<option @if($rowData['unit']=='Kg') selected="selected" @endif value="Kg">Kg.</option> 
														<option @if($rowData['unit']=='Yd') selected="selected" @endif value="Yd">Yd.</option>
														<option @if($rowData['unit']=='Pcs') selected="selected" @endif value="Pcs">Pcs.</option>
													</select>
												</td>
												
												<td data-text="Unit Price"><input type="number" required onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full unit-price" cus="{{$i}}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]" value="{{ $rowData['unit_price'] }}" disabled /></td>
												
												<td data-text="GST %"><input type="number" readonly="readonly" min="0" max="100" class="form-control width-full gst" rel="gst" cus="{{$i}}" id="tmp_id_gst_{{$i}}" name="row[{{$i}}][gst]" value="{{ $rowData['gst'] }}" disabled/></td>
												
												<td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">{{ number_format($rowData['amount'],2) }}</td>
												<input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" name="row[{{$i}}][amount]" value="{{ $rowData['amount'] }}" disabled />
												
												<td><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								<div class="add-row">
									<div class="total-right">
										<div class="subtotal">
											<div class="subtotal-box">
												<label>Subtotal</label>
												<strong> <span id="subtotal">{{ number_format($edit_purchase_invoice['subtotal'],2) }}</span> <span class="currency-symbol"> $ </span> </strong>
											</div>
											<input type="hidden" class="subtotal" name="subtotal" id="subtotal_val" value="{{ $edit_purchase_invoice['subtotal'] }}" disabled />
											
											<div class="subtotal-box">
												<label>Advance</label>
												<strong><span id="advance_amnt">0.00</span> <span class="currency-symbol"> $ </span> </strong>
												<input type="hidden" name="advance_percent" id="advance_percent" value=""/>
											</div>
											
											<!--<div class="subtotal-box">
												<label>Sampling Charges</label>
												<strong><span id="sampling-charge">Rs 0.00</span></strong> 
											</div>
											<input type="hidden" class="sampling-charge" name="sampling_charge" />-->
											<div class="subtotal-box">
												<label>Delivery Charges</label>
												<strong><input type="number" style="width:40% !important; float:right;" min="0" class="form-control" id="delivery-charge" name="delivery_charge"  value="{{ $edit_purchase_invoice['delivery_charge'] }}" disabled /></strong>
											</div>
											<div class="subtotal-box">
												<label>GST</label>
												<strong><span id="gst-total">Rs {{ number_format($edit_purchase_invoice['gst'],2) }}</span></strong> 
											</div>
											<input type="hidden" class="gsttotal" name="gst" />
											<div class="subtotal-box grand-total">
												<label><strong>TOTAL</strong></label>
												<strong><span id="grandtotal">{{ number_format($edit_purchase_invoice['total'],2) }}</span> <span class="currency-symbol"> $ </span></strong>
											</div>
											<input type="hidden" class="grandtotal" name="total" value="{{ $edit_purchase_invoice['total'] }}" />
										</div>
									</div>
									
									<div class="total-right">
										<div class="subtotal">
										  <div class="subtotal-box">
											<label>Mtr.</label>
											<strong><span id="all_mtr">00.00</span></strong></div>
											<input type="hidden" class="subtotal-val" name="subtotal" />
										  <div class="subtotal-box">
											<label>Kg.</label>
											<strong><span id="all_kg">00.00</span></strong> </div>
											 <div class="subtotal-box">
											<label>Yd.</label>
											<strong><span id="all_yd">00.00</span></strong> </div>
											
											<div class="subtotal-box">
											<label>Pcs.</label>
											<strong><span id="all_pcs">00.00</span></strong> </div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" name="changes" value="1" class="changes" />
				</form>
			</div>
		</div>
	</div>
</div>

<script>
		
		window.onload = setTimeout(function(){
			calculateAllUnits();
			setAdvancePercentage();
		}, 5000)
		
		
		$(function(){
				$(document).on('keyup mouseup change click','.bulk-qty,.ss-qty',function(){
					calculateAllUnits();
				});
		})
		
		function itemUnitCal(){
			calculateAllUnits();
		}
		
		function calculateAllUnits(){
					var Mtr=0; var Kg =0; var Yd =0; var Pcs=0;				 
					var rowCount = ($('#editableTable1 tr').length)-1;
					var bulkqty=0; var ssqty=0;					 
					for(var i=1; i<=rowCount; i++){
						if($('#tmp_id_order_qty_'+i).val()>0){
							bulkqty = parseFloat($('#tmp_id_order_qty_'+i).val());
						}else{
							bulkqty=0;
						}
						
						/* if($('#tmp_id_ss_qty_'+i).val()>0){
							ssqty = parseFloat($('#tmp_id_ss_qty_'+i).val());
						}else{
							ssqty=0;
						} */
						
						if($('#tmp_id_unit_'+i).val()=='Mtr'){
							Mtr = Mtr+bulkqty+ssqty;
						}else if($('#tmp_id_unit_'+i).val()=='Kg'){
							Kg = Kg+bulkqty+ssqty;
						}else if($('#tmp_id_unit_'+i).val()=='Yd'){
							Yd = Yd+bulkqty+ssqty;
						}else if($('#tmp_id_unit_'+i).val()=='Pcs'){
							Pcs = Pcs+bulkqty+ssqty;
						}												
					}
					$('#all_mtr').text(Mtr.toFixed(2));
					$('#all_kg').text(Kg.toFixed(2));
					$('#all_yd').text(Yd.toFixed(2));					
					$('#all_pcs').text(Pcs.toFixed(2));					
				};
				
				
		function setAdvancePercentage(){
			var thisVal = $("#payment_term option:selected").text();
			
			var advance_val = 0;
			var advance = thisVal.includes('Advance');
				if(advance){
					advance_val = thisVal.substring(0,2);
				}else{
					 advance_val = 0;
				}
			//alert('hii======'+advance_val);
			var subTotal = $('#subtotal_val').val();
			if(advance_val>0 && subTotal>0){
				$('#advance_percent').val(advance_val);
				
				var advamnt =  parseFloat(subTotal*advance_val)/100;
				$('#advance_amnt').html(advamnt.toFixed(2));
			}else{
				$('#advance_percent').val('0');
				$('#advance_amnt').html('0.00');
			}
			//return advance_val;
	}
	</script>
	
@endsection
	@section('custom_validation_script')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
    $("#invoiceform").validate({});

});
</script>




<script>
	$(function () { 
		
		changeCurrency('<?php echo $edit_purchase_invoice['order_type']; ?>');
		var supplierArr = '<?php print_r(getAllSupplier()); ?>';
		supplierArr = JSON.parse(supplierArr);
		//console.log(buyerArr);
		var buyerData = $.map(supplierArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
		$('#brand_name').html(buyerHtml);
		$('#brand_name').val('<?php echo $edit_purchase_invoice['brand_name']; ?>');
		//console.log(buyerHtml);
		
		
function changeCurrency(thisVal){
	
	//allCalculateRation(this,1);
	
	if(thisVal=='Import'){
		$('.currency-symbol').html('$');
		
		
	}else{
		$('.currency-symbol').html('Rs ')
		
		
		
		
	}
}
	});
</script>

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>


<script type="text/javascript">
function download(d) {
        if (d == '') return false;
		window.open('<?php echo url('public/po_invoice/'.$edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'/')?>'+d, '_blank');
		
}

function download_part(d) {
        if (d == '') return false;
		window.open('<?php echo url('public/po_invoice/'.$edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'-P'.$partshipped.'/')?>'+d, '_blank');	
}
</script>
@endsection