@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Purchase Order Page')
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
	.purchaseorder-box .total-right .subtotal-box span.currency-symbol{ padding-right:3px; }
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
	
	.add-new-item{
		text-decoration:underline;
	}
	
	/* for auto fill inputs */
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

.numbers-only{
    width: 80px !important;
}

.color-field{
	width: 300px !important;
}
</style>
<div class="page creat-fms-box">
	<?php 
		//$data = getmatcharticle();
		//pr($data); die; 
	?>
	<div class="page-header">
		<h1 class="page-title">{{ __('Add Purchase Order') }}</h1>		
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('purchaselisting') }}">Purchase Order Management</a></li>
			<li class="breadcrumb-item">{{ __('Add Purchase Order') }}</li>
		</ol>
		<div class="page-header-actions custom-page-heading">
			<div class="row no-space w-400">
				<div class="col-sm-6">
					<div class="counter">
						<span class="counter-number font-weight-medium">DATE</span>
						<div class="counter-label">{{ DateFormate(date('d-M-Y')) }}</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-content container-fluid">
		<div class="row justify-content-center"> @if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div><br />
			@endif
			<div class="col-md-12">
				<form class="" action="{{ url('admin/purchaseadd') }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">
					@csrf
					<div class="card">
						<div class="card-body">
							<div class="row" style="margin:0;">
								<div class="col-lg-12 col-md-12 col-sm-12 full-form-box">
									<div class="row">
										<div class="contact-form bs-example contact-heading col-lg-12 col-md-12 ">
											<label for="contact"><strong>Supplier Info</strong></label>
										</div>
										<div class="contact-form bs-example col-lg-3 col-md-4 auto-complete-div"  style="margin-top:10px;">
											<label for="contact">Name<span class="required" style="color: #e3342f;">*</span></label>
											<a href="{{ route('add_supplier') }}" target="_blank" class="add-new-item"> Add New Supplier</a>
											
											<?php /*?>
												
												<option value="">-- Select --</option>
												@foreach($suppliers as $supplier)
												<option value="{{ $supplier['_id'] }}">{{ $supplier['company_name'] }}</option>
												@endforeach
											
											<?php */?>
											
											
											<select class="required form-control" id="brand_name" name="brand_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2">
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Address<span class="required" style="color: #e3342f;">*</span></label>
											<input type="text" name="customer_address" id="customer_address" class="required form-control auto-fill-field">
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">State<span class="required" style="color: #e3342f;">*</span></label>
											<input type="text" name="buyer_state" id="buyer_state" class="required form-control auto-fill-field" />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">City</label>
											<input type="text" name="buyer_city" id="buyer_city" class="form-control auto-fill-field" />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Contact Person</label>
											<input type="text" name="buyer_contact_person" id="buyer_contact_person" class="form-control auto-fill-field" />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">GST No.</label>
											<input type="text" name="supplier_info" id="supplier_info" class="form-control auto-fill-field" />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Phone No.</label>
											<input type="text" name="phone_no" id="phone_no" class="form-control auto-fill-field" />
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Po Type</label>
											<select class="form-control" id="po_type" name="po_type">
												<option value="Confirmed">Confirmed</option>
												<option value="Provisional">Provisional</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Order Type</label>
											<select class="form-control" id="order_type" name="order_type" onchange="changeCurrency(this.value); return allCalculateRation(this,1);">
												<option value="Import">Import</option>
												<option value="Local">Local</option>
												<option value="job_work_order">Job Work Order</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Fob Sample Required</label>
											<select class="form-control" id="fob_sample" name="fob_sample">
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Test Report Required</label>
											<select class="form-control" id="test_report" name="test_report">
												<option value="">---Select---</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Inspection Report Required</label>
											<select class="form-control" id="inspection_report" name="inspection_report">
												<option value="">---Select---</option>
												<option value="Yes">Yes</option>
												<option value="No">No</option>
											</select>
										</div>
										
										<div class="required contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Payment Terms<span class="required" style="color: #e3342f;">*</span></label>
											{!! getOptionDropdown('purchase_payment_terms', 'payment_terms', '--Select--','', 'id="payment_term"') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Quantity Variation(+/- %)</label>
											<select class="form-control" id="i" name="quantity_variation">
												<option value="">---Select---</option>
												<option value="3">3</option>
												<option value="5" selected>5</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Incoterms<span class="required" style="color: #e3342f;">*</span></label>
											{!! getOptionDropdown('incoterms', 'incoterms', '--Select--','') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Final Destination<span class="required" style="color: #e3342f;">*</span></label>
											{!! getOptionDropdown('final_destination', 'final_destination', '--Select--','') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Mode Of Transport<span class="required" style="color: #e3342f;">*</span></label>
											{!! getOptionDropdown('mode_of_transport', 'mode_of_transport', '--Select--','') !!}
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="Transporter Name">Transporter Name</label>
											<select class="form-control date_with_time" name="transporter_name">
												<option value="">--Select--</option>
												@foreach(getOptionArray('transporter_name') as $res)
													<option>{{ $res }}</option>
												@endforeach
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Transit Time<span class="required" style="color: #e3342f;">*</span></label>
											{!! getOptionDropdownNovalue('transit_time', 'transit_time', '--Select--','','id="transit_time"') !!}
											<?php /*?><select class="required form-control" id="transit_time" name="transit_time">
												<option value="">---Select---</option>
												<option value="20 Days">20 Days</option> transit_time
												<option value="35 Days">35 Days</option>
												<option value="2 Days">2 Days</option>
											</select><?php */?>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">PI Reference</label>
											<?php /* ?><input type="text" name="pi_reference" id="pi_reference" class="form-control" /><?php */?>
											
											<select name="pi_reference" id="pi_reference" class="form-control" data-plugin="select2" data-placeholder="Type POS Number" data-minimum-input-length="2">
												<option value="">--Select--</option>
												@foreach(getAllPiRefrences() as $pi_row)
													<option>
													PI-POS-{{ date('Y', strtotime($pi_row['created_at'])) }}-{{ $pi_row['po_serial_number'] }}
													</option>
												@endforeach
											</select>
											
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Order Reason</label>
											<select class="form-control" id="order_status" name="order_status">
												<option value="">---Select---</option>
												<option value="Self Order">Self Order</option>
												<option value="Buyer Specific Order">Buyer Specific Order</option>
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Documents required from Supplier</label>
											<select class="form-control date_with_time" multiple="multiple" data-plugin="select2" name="documents_required_from_supplier[]">
												@foreach(getOptionArray('documents_required_from_supplier') as $res)
													<option>{{ $res }}</option>
												@endforeach
											</select>
										</div>
										
										<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
											<label for="contact">Documents Prepared By</label>
											<select class="form-control date_with_time" name="documents_prepared_by">
											<option value="">---Select---</option>
												@foreach(getOptionArray('documents_prepared_by') as $res)
													<option>{{ $res }}</option>
												@endforeach
											</select>
										</div>
										
									</div>
								</div>
							</div>
							<div class="purchaseorder-box">
								<hr />
								<div class="table-wrap big-table">
                                <style type="text/css">
                                

                                </style>
									<a href="{{ route('articlelist') }}" class="add-new-item" target="_blank">Add New item/Article</a>                                    
									<table class="editable-table table table-striped inner-fix-table" id="editableTable1" border="0" cellspacing="0" cellpadding="0">
										<thead>
											<tr>
												<th width="100" class="fix-th">Item No.</th>
												<th class="fix-th2">Colour</th>
												<th>Factory Code</th>
												<th>Description</th>
												<th>Finish</th>
												<th>Lab Dip</th>
												<th>ETD</th>
												<th width="90">HSN Code</th>
												<th>Remark</th>
												<th width="80">Order Qty</th>
												<th>S/S Qty</th>
												<th>Unit(Mt/Kg)</th>
												<th width="80">Unit Price</th>
												<th width="90">GST %</th>
												<th width="40">Amount</th>
												<th width="40">&nbsp;</th>
											</tr>
										</thead>
										<tbody id="results">
											@for($i=1;$i<=5;$i++)
											<tr id="data-row-{{ $i }}">
												<td data-text="Item"  class="fix-th">
													
												<input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
												<input type="hidden" name="row[{{$i}}][item]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="" />
												</td>
												<td data-text="Colour"  class="fix-th2">
													<select required class="form-control width-full color-field" cus="{{$i}}" rel="colour" id="tmp_id_colour_{{$i}}" name="row[{{$i}}][colour]"></select>
													
												</td>
												<td data-text="Description"><input type="text" class="form-control width-full factory-code auto-fill-field" cus="{{$i}}" rel="ss-qty" id="tmp_id_factory_{{$i}}" name="row[{{$i}}][factory_code]" /></td>
												
												<td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]"></textarea></td>
												
												<td data-text="Description"><textarea class="form-control width-full" rel="finish" id="tmp_id_finish_{{$i}}" style="height:35px;"  name="row[{{$i}}][finish]"></textarea></td>
                          
												<td data-text="Bulk Qty">
												
												<?php /* ?><input type="text" min="1" class="form-control width-full bulk-qty" cus="{{$i}}" rel="bulk-qty" id="tmp_id_lab_dip_{{$i}}" name="row[{{$i}}][lab_dip_no]" /><?php */ ?>
												
												<select class="form-control width-full select-article" cus="{{$i}}" rel="lab_dip_no" id="tmp_id_lab_dip_{{$i}}" name="row[{{$i}}][lab_dip_no]">
												  <option value="Approved">Approved</option>
												  <option value="Pending">Pending</option>
												</select>
												
												</td>
												
												<td data-text="ETD">
												<input type="text" autocomplete="off" class="form-control width-full etd" cus="{{$i}}" rel="etd" id="tmp_id_etd_{{$i}}" name="row[{{$i}}][etd]" required></td>
												
												<td data-text="HSN Code">
													<input type="text" readonly="readonly" class="form-control width-full item hsn_code" cus="{{$i}}" rel="hsn_code" id="tmp_id_hsn_code_{{$i}}" name="row[{{$i}}][hsn_code]" />
												</td>
							
												<td data-text="Remark"><textarea class="form-control width-full" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]"></textarea></td>
												
												<?php /*?><td data-text="Test Method"><textarea class="form-control width-full" rel="test_method" id="tmp_id_test_method_{{$i}}" style="height:35px;"  name="row[{{$i}}][test_method]"></textarea></td><?php */?>
												
												<td data-text="Order Qty"><input type="text" autocomplete="off" required onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full bulk-qty numbers-only" cus="{{$i}}" rel="order-qty" id="tmp_id_order_qty_{{$i}}" name="row[{{$i}}][order_qty]"></td>
												
												<td data-text="S/S Qty"><input type="text" autocomplete="off" onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full bulk-qty numbers-only" cus="{{$i}}" rel="ss-qty" id="tmp_id_ss_qty_{{$i}}" name="row[{{$i}}][ss_qty]"></td>
							
												<td data-text="Unit Price">
													<select class="form-control width-full unit" onchange="itemUnitCal()" rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]" aria-invalid="false">
														<option value="Mtr">Mtr.</option> 
														<option value="Kg">Kg.</option> 
														<option value="Yd">Yd.</option>
														<option value="Pcs">Pcs.</option>
													</select>
												</td>
												
												<td data-text="Unit Price"><input type="text" autocomplete="off" required onkeyup="return allCalculateRation(this,1);" min="0" class="form-control width-full unit-price numbers-only" cus="{{$i}}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]"></td>
												
												<td data-text="GST %"><input type="number" readonly="readonly" min="0" max="100" class="form-control width-full gst" rel="gst" cus="{{$i}}" id="tmp_id_gst_{{$i}}" name="row[{{$i}}][gst]" /></td>
												
												<td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right"></td>
												<input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" name="row[{{$i}}][amount]" />
												
												<td><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
											</tr>
											@endfor
										</tbody>
									</table>
								</div>
								<div class="add-row">
									<div class="add-left">
										<div class="dropdown two-button">
											<button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> </button>
											<div class="dropdown-menu"> 
												<a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> 
												<a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> 
												<a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> 
											</div>
										</div>
									</div>
									<div class="total-right">
										<div class="subtotal">
											<div class="subtotal-box">
												<label>Subtotal</label>
												<strong><span id="subtotal">0.00</span> <span class="currency-symbol">$ </span></strong>
											</div>
											<input type="hidden" class="subtotal" name="subtotal" />
											
											<div class="subtotal-box">
												<label>Advance</label>
												<strong><span id="advance_amnt">0.00</span> <span class="currency-symbol">$ </span></strong>
												<input type="hidden" name="advance_percent" id="advance_percent" value=""/>
											</div>
											<!--<div class="subtotal-box">
												<label>Sampling Charges</label>
												<strong><span id="sampling-charge">Rs 0.00</span></strong> 
											</div>
											<input type="hidden" class="sampling-charge" name="sampling_charge" />-->
											<div class="subtotal-box">
												<label>Delivery Charges</label>
												<strong><input type="text" autocomplete="off" style="width:40% !important; float:right;" min="0" class="form-control numbers-only" id="delivery-charge" name="delivery_charge" /></strong>
											</div>
											<div class="subtotal-box">
												<label>GST</label>
												<strong> <span id="gst-total">00.00</span> </strong> 
											</div>
											<input type="hidden" class="gsttotal" name="gst" />
											
											<div class="subtotal-box grand-total">
												<label><strong>TOTAL</strong></label>
												<strong><span id="grandtotal">0.00</span> <span class="currency-symbol">$ </span></strong>
											</div>
											<input type="hidden" class="grandtotal" name="total" />
										</div>
									</div>
									
									<div class="total-right">
										<div class="subtotal">
										  <div class="subtotal-box">
											<label>Mtr.</label>
											<strong><span id="all_mtr">00.00</span></strong></div>
											<input type="hidden" class="subtotal" name="subtotal" />
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
								<div class="save-row">
									<div class="save-right float-right">
										<button type="submit" name="approve" value="approve" class="btn btn-success">Submit</button>
										<a href="{{ url('admin/invoicelisting') }}"  class="btn btn-secondary">Cancel</a> 
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){
			function split( val ) {
			return val.split( /,\s*/ );
			}
			function extractLast( term ) {
			return split( term ).pop();
			} 
		$(document).on('click','.add-new-line',function(){
			var count_of_row = $(this).attr('cus');
			var rowCount = $('#editableTable1 >tbody >tr').length;
			for(var i = 1; i <= count_of_row; i++) {
				rowCount++;
				var html = '<tr id="data-row-'+rowCount+'"> <td data-text="Item" class="fix-th"><input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_'+rowCount+'" required /><input type="hidden" name="row['+rowCount+'][item]" id="tmp_id_item_'+rowCount+'" cus="'+rowCount+'" rel="item" value="" /></td> <td data-text="Colour" class="fix-th2"><select required class="form-control width-full color-field" cus="'+rowCount+'" rel="colour" id="tmp_id_colour_'+rowCount+'" name="row['+rowCount+'][colour]"></select></td> <td data-text="Description"><input type="text" class="form-control width-full auto-fill-field" rel="factory-code" id="tmp_id_factory_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][factory_code]"> </td> <td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][description]"></textarea> </td> <td data-text="Finish"><textarea class="form-control width-full" rel="finish" id="tmp_id_finish_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][finish]"></textarea> </td> <td data-text="Lab Dip"><select class="form-control width-full select-article" rel="lab_dip_no" id="tmp_id_lab_dip_'+rowCount+'" name="row['+rowCount+'][lab_dip_no]"> <option value="Approved">Approved</option><option value="Pending">Pending</option></select> </td> <td data-text="ETD Qty"><input type="text" autocomplete="off" class="form-control width-full etd" cus="'+rowCount+'" rel="etd" id="tmp_id_etd_'+rowCount+'" name="row['+rowCount+'][etd]" required /> </td> <td data-text="HSN Code"><input type="text" readonly="readonly" class="form-control width-full item hsn_code" cus="'+rowCount+'" rel="hsn-code" id="tmp_id_hsn_code_'+rowCount+'" name="row['+rowCount+'][hsn_code]" /> </td> <td data-text="Remark"><textarea class="form-control width-full" rel="remark" id="tmp_id_remark_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][remark]"></textarea> </td> <td data-text="Order Qty"><input type="text" min="0" onkeyup="return allCalculateRation(this,1);" required class="form-control width-full order-qty numbers-only" cus="'+rowCount+'" rel="order-qty" autocomplete="off" id="tmp_id_order_qty_'+rowCount+'" name="row['+rowCount+'][order_qty]" /> </td> <td data-text="S/S Qty"><input type="text" min="0" onkeyup="return allCalculateRation(this,1);" class="form-control width-full ss-qty numbers-only" cus="'+rowCount+'" rel="ss-qty" autocomplete="off" id="tmp_id_ss_qty_'+rowCount+'" name="row['+rowCount+'][ss_qty]" /> </td> <td data-text="Unit"><select onchange="itemUnitCal()" class="form-control width-full unit" rel="unit" cus="'+rowCount+'" id="tmp_id_unit_'+rowCount+'" name="row['+rowCount+'][unit]" ><option value="Mtr">Mtr.</option><option value="Kg">Kg.</option><option value="Yd">Yd.</option><option value="Pcs">Pcs.</option> </select></td> <td data-text="Unit Price"><input type="text" min="0" onkeyup="return allCalculateRation(this,1);" required class="form-control width-full unit-price numbers-only" cus="'+rowCount+'" rel="unit-price" autocomplete="off" id="tmp_id_unit_price_'+rowCount+'" name="row['+rowCount+'][unit_price]" /> </td> <td data-text="GST %"><input type="number" min="0" max="100" class="form-control width-full gst" readonly rel="gst" cus="'+rowCount+'" id="tmp_id_gst_'+rowCount+'" name="row['+rowCount+'][gst]" /> </td> <td data-text="Amount" id="data-row-amount-'+rowCount+'" class="amount" style="font-weight:bold;" align="right"> </td><input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'+rowCount+'" name="row['+rowCount+'][amount]" /> <td><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';
				$('#results').append(html);
			}
			$(".etd").datepicker({
				format: "yyyy-mm-dd",
				autoclose: true,
				todayHighlight: true
			});
			$(".selectsearch").select2({minimumInputLength: 2});
			
			/* for ato pi item */
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
				
					//var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
					var description = ui.item.description+', '+ui.item.composition+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, '');
						
					var remark_count = ui.item.count_construct;
					    remark_count= remark_count.replace(/\\/g, '');
						
				 
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
					/* 5 Oct 2020 END*/
					
					/* New code for color start */						
						/* var availableTags =item_color_arr;						
						 jQuery('#tmp_id_colour_'+rowNum)
						  .on( "keydown", function( event ) {
							if ( event.keyCode === jQuery.ui.keyCode.TAB &&
								jQuery( this ).autocomplete( "instance" ).menu.active ) {
							  event.preventDefault();
							}
						  })
						  .autocomplete({
							minLength: 0,
							source: function( request, response ) {
							  response( jQuery.ui.autocomplete.filter(
								availableTags, extractLast( request.term ) ) );
							},
							focus: function() {
							  return false;
							},
							select: function( event, ui ) {
							  var terms = split( this.value );
							  this.value =ui.item.value;
							  return false;
							}
						  });	 */			
					/* New code for color End */
							
							jQuery('#tmp_id_factory_'+rowNum).val('');
							jQuery('#tmp_id_factory_'+rowNum).val(factory_code);
							
							jQuery('#tmp_id_finish_'+rowNum).val('');
							jQuery('#tmp_id_finish_'+rowNum).val(fabric_finish);
							
							jQuery('#tmp_id_desc_'+rowNum).val(description);
							jQuery('#tmp_id_remark_'+rowNum).val(remark_count);
							jQuery('#tmp_id_hsn_code_'+rowNum).val(hsn_code);
							jQuery('#tmp_id_gst_'+rowNum).val(gst_code);
								
						}
					});
			/* for ato pi item */
			
			/* remove character from text field */
			$(".numbers-only").keypress(function(event) {
			if((event.which > 47 && event.which < 58) || (event.which == 46 || event.which == 8))
			{
			}else{
				event.preventDefault();
				}
			}).on('paste', function(event) {
				event.preventDefault();
			});
			
			
			
		});
		
		
		/* remove character from text field */
		$(".numbers-only").keypress(function(event) {
			if((event.which > 47 && event.which < 58) || (event.which == 46 || event.which == 8))
			{
			}else{
				event.preventDefault();
			}
		}).on('paste', function(event) {
			event.preventDefault();
		});
		
		
		
	});
</script> 
<script>
	function removeRow(row){
		if(row!=1){
			$('#data-row-'+row).remove();
		}
	}
</script> 
<script>
    $(document).ready(function () {
		$(".etd").datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			todayHighlight: true
		});
    });
</script> 
<script>
       $(function(){
		  $(document).on('change','#brand_name',function(){
			 var customer_id = $(this).val();
			 
			 $("#payment_term option:selected").each(function(){
				  $(this).removeAttr("selected");
				});
				
				$("#order_type option:selected").each(function(){
				  $(this).removeAttr("selected");
				});
				
			  if(customer_id!=''){
					$.ajax({
					type: "GET",
					url: "{{ url('admin/ajaxGetSupplierDetails')}}/"+customer_id,
					data: {customer_id:customer_id},
					success: function(msg){
						//console.log(msg); return false;
						data = JSON.parse(msg);
					//alert(msg); return false;
						$('#customer_address').val(data['address']);
						$('#buyer_city').val(data['city']);
						$('#buyer_state').val(data['state']);
						$('#buyer_contact_person').val(data['contact_person']);
						$('#supplier_info').val(data['gst']);
						$('#phone_no').val(data['contact_no']);
						$('#payment_term option[value="'+data['payment_terms']+'"]').attr('selected','selected');
						$('#order_type option[value="'+data['location']+'"]').attr('selected','selected');
						
						if(data['location']=='Import'){
							$('.currency-symbol').html('$');
						}else{
							$('.currency-symbol').html('Rs')
						}
					}
					});
			  }else{
				$('#customer_address').val('');
				$('#buyer_city').val('');
				$('#buyer_state').val('');
				$('#buyer_contact_person').val('');
				$('#supplier_info').val('');
				$('#phone_no').val('');
				$('#payment_term').val('');
				$('#order_type').val('');
				$('#payment_term option[value=""]').attr('selected','selected');			
				$('#order_type option[value=""]').attr('selected','selected');			
			  }
		  });
	   });
    </script>

<script>
	$(function(){
		$(document).on('keyup mouseup change click','.bulk-qty,.order-qty,.ss-qty,.unit-price,#order_sampling_type,#delivery-charge,.remove-row',function(){
			var id = $(this).attr('cus');
			var order_type = $( "#order_sampling_type" ).val();
			var delivery_charge = parseFloat($( "#delivery-charge" ).val());
			var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
			
			
		 
			var qty_val_cal = parseFloat($('#tmp_id_order_qty_'+id).val());
			var qty_val = isNaN(qty_val_cal) ? 0 : qty_val_cal;
			var ssqty_va_cal = parseFloat($('#tmp_id_ss_qty_'+id).val());
			var ssqty_val = isNaN(ssqty_va_cal) ? 0 : ssqty_va_cal;
			var price_val = $('#tmp_id_unit_price_'+id).val();
		  
			var max = 0;
			$('.gst').each(function() {
				var value = parseFloat($(this).val());
				max = (value > max) ? value : max;
			});
			
		/* 	if(qty_val!='' && price_val!='') {
				$('#data-row-amount-'+id).html(eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));
			}else{
				$('#data-row-amount-'+id).html(eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));	
			} */
			
			if(qty_val!='' && price_val!='') {
				$('#data-row-amount-'+id).html(eval(((qty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val)*price_val)));
			}else{
				$('#data-row-amount-'+id).html(eval(((qty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val)*price_val)));	
			}
			
			var subTotal = 0;
			$(".amount_hidden").each(function () {
				var stval = parseFloat($(this).val());
				subTotal += isNaN(stval) ? 0 : stval;
			});
			$('#subtotal').html(subTotal.toFixed(2));
			$('.subtotal').val(subTotal.toFixed(2));
			
			
			
			/* var advance_amnt = 0;
				advance_amnt = 25; */
				
				
			//normal append
			var order_type = $('#order_type').val();
			if(order_type=='Local'){
				$('#gst-total').html('Rs '+parseFloat((subTotal*max)/100).toFixed(2));
				$('.gsttotal').val(parseFloat((subTotal*max)/100).toFixed(2));
			}else{
				$('#gst-total').html('');
				$('.gsttotal').val('');
			}
			
			
			var gsttotal = parseFloat($( ".gsttotal" ).val());
			var gsttotal = isNaN(gsttotal) ? 0 : gsttotal;
			
			var sampling_charges = parseFloat(subTotal*order_type)/100;
			$('#sampling-charge').html('Rs '+sampling_charges.toFixed(2));
			$('.sampling-charge').val(sampling_charges.toFixed(2));
			
			var total = parseFloat(subTotal)+parseFloat(delivery_charge_val)+parseFloat(gsttotal);
			$('#grandtotal').html(total.toFixed(2));
			$('.grandtotal').val(total.toFixed(2));
			
			/* set advance amount */
			setAdvancePercentage();			
			
		});
		
		$('#payment_term').on('change', function(){
			setAdvancePercentage();
		})
		
	});
	
	
	function setAdvancePercentage(){
			var thisVal = $("#payment_term option:selected").text();
			
			var advance_val = 0;
			var advance = thisVal.includes('Advance');
				if(advance){
					advance_val = thisVal.substring(0,2);
				}else{
					 advance_val = 0;
				}
			
			var subTotal = $('.subtotal').val();
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

<script>
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
function changeCurrency(thisVal){
	
	//allCalculateRation(this,1);
	
	if(thisVal=='Import'){
		$('.currency-symbol').html('$');
		
		$('#gst-total').html('00.00');
		$('.gsttotal').val('');
		
		var delivery_charge = parseFloat($( "#delivery-charge" ).val());
		var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
		var gsttotal = parseFloat($( ".gsttotal" ).val());
		var gsttotal = isNaN(gsttotal) ? 0 : gsttotal;

		var subTotal = $('.subtotal').val();
		var total = parseFloat(subTotal)+parseFloat(delivery_charge_val)+parseFloat(gsttotal);
		$('#grandtotal').html(total.toFixed(2));
		$('.grandtotal').val(total.toFixed(2));
		
	}else{
		$('.currency-symbol').html('Rs')
		
		
		var max = 0;
			$('.gst').each(function() {
				var value = parseFloat($(this).val());
				max = (value > max) ? value : max;
			});
		var subTotal = $('.subtotal').val();
		$('#gst-total').html('Rs '+parseFloat((subTotal*max)/100).toFixed(2));
		$('.gsttotal').val(parseFloat((subTotal*max)/100).toFixed(2));
		
		
		var delivery_charge = parseFloat($( "#delivery-charge" ).val());
		var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
		var gsttotal = parseFloat($( ".gsttotal" ).val());
		var gsttotal = isNaN(gsttotal) ? 0 : gsttotal;

		var total = parseFloat(subTotal)+parseFloat(delivery_charge_val)+parseFloat(gsttotal);
		$('#grandtotal').html(total.toFixed(2));
		$('.grandtotal').val(total.toFixed(2));
		
		
	}
}
</script>
<script>
	$(function () { 
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
		//console.log(buyerHtml);
	});
</script>

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$(function() {
		
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
				
					//var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
					var description = ui.item.description+', '+ui.item.composition+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, '');
						
					var remark_count = ui.item.count_construct;
					    remark_count= remark_count.replace(/\\/g, '');
				 
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
					/* 5 Oct 2020 END*/
					
					/* New code for color start */						
						/* var availableTags =item_color_arr;						
						 jQuery('#tmp_id_colour_'+rowNum)
						  .on( "keydown", function( event ) {
							if ( event.keyCode === jQuery.ui.keyCode.TAB &&
								jQuery( this ).autocomplete( "instance" ).menu.active ) {
							  event.preventDefault();
							}
						  })
						  .autocomplete({
							minLength: 0,
							source: function( request, response ) {
							  response( jQuery.ui.autocomplete.filter(
								availableTags, extractLast( request.term ) ) );
							},
							focus: function() {
							  return false;
							},
							select: function( event, ui ) {
							  var terms = split( this.value );
							  this.value =ui.item.value;
							  return false;
							}
						  }); */				
					/* New code for color End */
				
				jQuery('#tmp_id_factory_'+rowNum).val('');
				jQuery('#tmp_id_factory_'+rowNum).val(factory_code);
				
				jQuery('#tmp_id_finish_'+rowNum).val('');
				jQuery('#tmp_id_finish_'+rowNum).val(fabric_finish);
				
				jQuery('#tmp_id_desc_'+rowNum).val(description);
				jQuery('#tmp_id_remark_'+rowNum).val(remark_count);
				jQuery('#tmp_id_hsn_code_'+rowNum).val(hsn_code);
				jQuery('#tmp_id_gst_'+rowNum).val(gst_code);
					
			}
		});
	});
</script>
<script>
$.noConflict();
</script>
@endsection


