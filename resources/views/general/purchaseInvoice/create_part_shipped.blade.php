@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Create Part Shipped Page')

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

.big-table table td input[type="number"] {
    width: 117px !important;
}

/* for auto select option */
.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
.autocomplete-no-suggestion { padding: 2px 5px;}
.autocomplete-selected { background: #F0F0F0; }
.autocomplete-suggestions strong { font-weight: bold; color: #000; }
.autocomplete-group { padding: 2px 5px; font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }

.numbers-only{
    width: 80px !important;
}

.color-field{
	width: 300px !important;
}
</style>
<?php
//pr($edit_purchase_invoice); die('ppppp');
/* $path= $edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'];
$pdf = @getPIPDFdata($path);
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
$html .='</select>'; */

$pi_oid = (array) $edit_purchase_invoice['_id'];
$pi_id = $pi_oid['oid'];
	$data_status = '';
if(array_key_exists('data_status', $edit_purchase_invoice)){
	$data_status = $edit_purchase_invoice['data_status'];
}
?>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('Create Part Shipped') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('invoicelisting') }}">Proforma Invoice List</a></li>
      <li class="breadcrumb-item">{{ __('Create Part Shipped') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
		<div class="col-sm-4"></div>
        <div class="col-sm-4">
          <div class="counter">
             <span class="counter-number font-weight-medium">PI NO. </span>
             <div class="counter-label">PI-POS-{{ $edit_purchase_invoice['financial_year'] }}-{{ $edit_purchase_invoice['po_serial_number'] }}</div></div>
        </div>
        <div class="col-sm-4">
          <div class="counter">
          <span class="counter-number font-weight-medium">DATE</span>
            <div class="counter-label">{{ date('d M, Y') }}</div>
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

            <form class="" id="invoiceform" action="{{ url('admin/store_part_shipped/'.$edit_purchase_invoice['_id']) }}" method="post" autocomplete="off" onsubmit="return validate();">
              @csrf
			  <input type="hidden" name="po_serial_number" value="{{ $edit_purchase_invoice['po_serial_number'] }}">
              <div class="card">
                <div class="card-body">
                  <div class="row" style="margin:0;">
                    
                    <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="contact-form bs-example contact-heading">
                      <label for="contact"><strong>Customer</strong></label>
                    </div>
                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Company Name</label>
                        <select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2">
                        <option value="">-- Select --</option>
                       
                        </select>
                      </div>
					  
                      </div>
					  
					   <div class="col-lg-6 col-md-6 col-sm-12">
						  <div class="contact-form bs-example" style="margin-top:10px;">
							<label for="contact">Address</label>
							<select name="customer_address" id="customer_address" class="required form-control">
                        <option value="">--- Select Address ---</option>
                        {!! customerDeliveryAddress($edit_purchase_invoice['customer_name'],$edit_purchase_invoice['customer_address']) !!}
                        </select>
						  </div>
                      </div>
					  
					   
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">Country</label>
								<input type="text" name="customer_country" id="customer_country" class="form-control auto-fill-field" value="{{ @$edit_purchase_invoice['customer_country'] }}" />
							</div>
						</div>
						
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">City</label>
                        <input type="text" name="customer_city" id="customer_city" value="{{ $edit_purchase_invoice['customer_city'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
					  
					  <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">State</label>
                        <input type="text" name="customer_state" id="customer_state" value="{{ $edit_purchase_invoice['customer_state'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
					  
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">Contact Person</label>
								<input type="text" name="customer_contact_person" value="{{ $edit_purchase_invoice['customer_contact_person'] }}" id="customer_contact_person" class="form-control auto-fill-field" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">GST No</label>
								<input type="text" name="gst_no" id="gst_no" value="{{ $edit_purchase_invoice['gst_no'] }}" class="form-control auto-fill-field" />
							</div>
						</div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Phone</label>
                        <input type="text" name="customer_phone" id="customer_phone" value="{{ $edit_purchase_invoice['customer_phone'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
                      </div>
                      </div>
                      
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example contact-heading">
                        <label for="contact"><strong>Delivery Address</strong></label>
                      </div>
                      <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Company Name</label>
                        <select class="required form-control" id="buyer_name" name="buyer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2">
                        <option value="">-- Select --</option>
                        </select>
                      </div>					  
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Address</label>
                        <select name="buyer_address" id="buyer_address" class="required form-control">
                        <option value="">--- Select Address ---</option>
                        {!! buyerDeliveryAddress($edit_purchase_invoice['buyer_name'],$edit_purchase_invoice['buyer_address']) !!}
                        </select>
                      </div>
                      </div>
					  
					  <div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">Country</label>
								<input type="text" name="buyer_country" id="buyer_country" class="form-control auto-fill-field" value="{{ @$edit_purchase_invoice['buyer_country'] }}" />
							</div>
						</div>
					  
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">City</label>
                        <input type="text" name="buyer_city" id="buyer_city" value="{{ $edit_purchase_invoice['buyer_city'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">State</label>
                        <input type="text" name="buyer_state" id="buyer_state" value="{{ $edit_purchase_invoice['buyer_state'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">Contact Person</label>
								<input type="text" name="buyer_contact_person" value="{{ $edit_purchase_invoice['buyer_contact_person'] }}" id="buyer_contact_person" class="form-control auto-fill-field" />
							</div>
						</div>
						<?php 
							if(array_key_exists('buyer_gst_no',$edit_purchase_invoice)){
								$buyer_gst_no = $edit_purchase_invoice['buyer_gst_no'];
							}else{
								$buyer_gst_no='';
							}
						?>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">GST No</label>
								<input type="text" name="buyer_gst_no" id="buyer_gst_no" value="{{ $buyer_gst_no }}" class="form-control auto-fill-field" />
							</div>
						</div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Phone</label>
                        <input type="text" name="buyer_phone" id="buyer_phone" value="{{ $edit_purchase_invoice['buyer_phone'] }}" class="form-control auto-fill-field" />
                      </div>
                      </div>
                      </div>
                    </div>
                    
					<?php 
						$notEdit='';
						if($data_status==1){
							$notEdit=' readonly';
						}
					?>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 full-form-box">
                    <div class="row">
                    <div class="contact-form bs-example contact-heading col-lg-12 col-md-12 ">
                        <label for="contact"><strong>Buyer</strong></label>
                      </div>
                    
                      
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Agent/Sales Person Code </label>						
						{!! getUserByDesignation(SALES_AGENT,'sales_agent','---Select---',$edit_purchase_invoice['sales_agent']) !!}
                       </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Brand Name </label>
                        {!! getBrandsDropDown('brand_name','---Select---', $edit_purchase_invoice['brand_name']) !!} 
                        </div>
                     
                         <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Order Type </label>
						
						@if($data_status==1)
							<input type="text" name="order_type" readonly value="{{ $edit_purchase_invoice['order_type'] }}" class="form-control auto-fill-field" />
						@else
							{!! getOptionDropdown('order_type','order_type', '---Select---',$edit_purchase_invoice['order_type'], 'id="order_type_option"') !!}
						@endif
                         
						 
						 
                        </div>
                         <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Order Sampling Type (%)</label>
                        <input type="number" name="order_sampling_type" value="{{ $edit_purchase_invoice['order_sampling_type'] }}" id="order_sampling_type" class="form-control auto-fill-field" />
                        </div>
                        
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">FOB Sample Approval :</label>
                        <select class="required form-control" id="fob_sample_approval" name="fob_sample_approval">
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['fob_sample_approval']=='Buyer') selected @endif value="Buyer">Buyer</option>
                          <option @if($edit_purchase_invoice['fob_sample_approval']=='Self') selected @endif value="Self">Self</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">FPT/ Testing Approval :</label>
                        <select class="required form-control" id="fpt_testing_approval" name="fpt_testing_approval">
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['fpt_testing_approval']=='Required') selected @endif value="Required">Required</option>
                          <option @if($edit_purchase_invoice['fpt_testing_approval']=='Not Required') selected @endif value="Not Required">Not Required</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">PAYMENT TERM (Agreed)</label>						
							<select class="required form-control" id="payment_term" name="payment_term">
							<option value="">---Select---</option>
							@foreach(getOptionArray('payment_term') as $key=>$val)
								<option value="{{ $key }}"  @if($edit_purchase_invoice['payment_term']==$key) selected @endif>{{ $val }}</option>
							@endforeach
							</select>
							
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">QUANTITY VARIATION (+/- %)</label>
                        <select class="form-control" id="quantity_variation" name="quantity_variation">
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='3') selected @endif value="3">3</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='5') selected @endif value="5">5</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='10') selected @endif value="10">10</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Buyer PO No</label>
                        <input type="text" name="buyer_po_number"  value="{{ $edit_purchase_invoice['buyer_po_number'] }}" class="form-control" />
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Status</label>
                        <select class="required form-control" id="status" name="status">
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['status']=='Confirmed') selected @endif value="Confirmed">Confirmed</option>
                          <option @if($edit_purchase_invoice['status']=='Unconfirmed') selected @endif value="Unconfirmed">Unconfirmed</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Mode of Transport</label>
                        {!! getOptionDropdown('mode_of_transport','mode_of_transport', '---Select---', $edit_purchase_invoice['mode_of_transport']) !!} </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Company of Transport</label>
                        <input type="text" name="company_of_transport" value="{{ $edit_purchase_invoice['company_of_transport'] }}" class="form-control" />
                      </div>
					  
						<div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
							<label for="contact">CRM Enquiry No.<span class="required" style="color: #e3342f;">*</span></label>
							<input type="text" name="crm_enquiry_no" class="form-control" value="{{ @$edit_purchase_invoice['crm_enquiry_no'] }}" required />
						</div>
						
					 <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Proforma Invoice Date</label>
                        <input type="text" name="proforma_invoice_date" value="{{ date('Y-m-d') }}" class="form-control proforma_invoice_date" required />
                      </div>
					  
						<input type="hidden" name="crm_assign" id="crm_assign" value="{{ @$edit_purchase_invoice['crm_assign'] }}" />
						<input type="hidden" name="merchant_name" id="merchant_name" value="{{ @$edit_purchase_invoice['merchant_name'] }}" />
						<input type="hidden" name="data_status" id="data_status" value="{{ @$edit_purchase_invoice['data_status'] }}" />
						
						
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
                            <th>Description</th>
                            <th>Remark</th>
                            <th width="80">Bulk Qty</th>
                            <th width="80">S/S Qty</th>
                            <th width="110">Unit (Mt/Kg)</th>
                            <th width="120">Lab Dip</th>
                            <th width="110">ETD</th>
                            <th width="90">HSN Code</th>
                            <th width="80">Unit Price</th>
                            <th width="90">GST %</th>
                            <th width="40">Amount</th>
                            <th width="40">&nbsp;</th>
                          </tr>
                        </thead>
                        <tbody id="results">
                        <?php 
						$oid = (array) $edit_purchase_invoice['_id'];
						$pi_id = $oid['oid'];
						$rowDatas = getPiRowDataByPiId($pi_id);
						
							/* pr($rowDatas); 
							echo $pi_id;
							die('==='); */
							
							$i=0;
						?>
                       @foreach($rowDatas as $rowData)
					   <?php $i++; 
						//pr($rowData); die;
					   ?>
                        <tr id="data-row-{{ $i }}">
                          <td data-text="Item">
							
							<?php 
								$itm = getAllArticles($rowData['item']);
								//pr($it);
								$oid = (array) $rowData['_id'];
								$row_id = $oid['oid'];
							?>
							
							<input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" value="{{ $itm['article_no'] }}" required />
							<input type="hidden" name="row[{{$i}}][item]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{ $rowData['item'] }}" />
							
							<input type="hidden" name="row[{{$i}}][row_id]" id="tmp_id_row_id_{{$i}}"  value="{{ $row_id }}" />
							
                            </td>
                          <td data-text="Colour">
							
							
								 
								 <select required class="form-control width-full" cus="{{$i}}" rel="colour" id="tmp_id_colour_{{$i}}" name="row[{{$i}}][colour]">
								 <option>{{ $rowData['colour'] }}</option>
								</select>
							
                            </td>
                          <td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]">{{ $rowData['description'] }}</textarea></td>
                          <td data-text="Remark"><textarea class="form-control width-full" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]">{{ $rowData['remark'] }}</textarea></td>
                          <td data-text="Bulk Qty"><input type="text" autocomplete="off" required onkeyup="return allCalculateRation(this,{{$i}});" min="0" class="form-control width-full bulk-qty numbers-only" cus="{{$i}}" rel="bulk-qty" id="tmp_id_bulkqty_{{$i}}" name="row[{{$i}}][bulkqty]" value="{{$rowData['bulkqty'] }}" /></td>
                          <td data-text="S/S Qty"><input type="text" autocomplete="off" min="0" class="form-control width-full ss-qty numbers-only" value="{{ $rowData['ssqty'] }}" cus="{{$i}}" rel="ss-qty" id="tmp_id_ssqty_{{$i}}" name="row[{{$i}}][ssqty]" /></td>
                          <td data-text="Unit"><select class="form-control width-full" onchange="itemUnitCal()" required rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]">
                              <option @if($rowData['unit']=='Mtr') selected="selected" @endif value="Mtr">Mtr.</option>
                              <option @if($rowData['unit']=='Kg') selected="selected" @endif value="Kg">Kg.</option>
                              <option @if($rowData['unit']=='Yd') selected="selected" @endif value="Yd">Yd.</option>
							   <option @if($rowData['unit']=='Pcs') selected="selected" @endif value="Pcs">Pcs.</option>
                            </select></td>
                          <td data-text="Lab Dip"><select class="form-control width-full" rel="lab_dip" id="tmp_id_lab_dip_{{$i}}" name="row[{{$i}}][lab_dip]">
                              <option @if($rowData['lab_dip']=='Required') selected="selected" @endif value="Required">Required</option>
                              <option @if($rowData['lab_dip']=='Approved') selected="selected" @endif value="Approved">Approved</option>
                            </select></td>
                            </td>
                          <td data-text="ETD"><input type="text" required autocomplete="off" class="form-control width-full etd" cus="{{$i}}" rel="etd" id="tmp_id_etd_{{$i}}" value="{{ $rowData['etd'] }}" name="row[{{$i}}][etd]" /></td>
                          <td data-text="HSN Code">
                            <input type="text" readonly="readonly" class="form-control width-full item hsn_code" cus="{{$i}}" value="{{ $rowData['hsn_code'] }}" rel="hsn_code" id="tmp_id_hsn_code_{{$i}}" name="row[{{$i}}][hsn_code]" />
                            </td>
                          <td data-text="Unit Price"><input type="text" autocomplete="off" required min="0" class="form-control width-full unit-price numbers-only" value="{{ $rowData['unit_price'] }}" cus="{{$i}}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]" />
                          <td data-text="GST %"><input type="number" readonly="readonly" min="0" value="{{ $rowData['gst'] }}" max="100" class="form-control width-full gst" rel="gst" cus="{{$i}}" id="tmp_id_gst_{{$i}}" name="row[{{$i}}][gst]" /></td>
                          <td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">Rs {{ number_format($rowData['amount'],2) }}</td>
                          <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" value="{{ $rowData['amount'] }}" name="row[{{$i}}][amount]" />
						  
							<td>
							<a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a>
							</td>
						  
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
                            <strong><span id="subtotal">Rs {{ number_format($edit_purchase_invoice['subtotal'],2) }}</span></strong></div>
                            <input type="hidden" class="subtotal" value="{{ $edit_purchase_invoice['subtotal'] }}" name="subtotal" />
                          <div class="subtotal-box">
                            <label>Sampling Charges</label>
                            <strong><span id="sampling-charge">Rs {{ number_format($edit_purchase_invoice['sampling_charge'],2) }}</span></strong> </div>
                            <input type="hidden" class="sampling-charge" value="{{ $edit_purchase_invoice['sampling_charge'] }}" name="sampling_charge" />
                          <div class="subtotal-box">
                            <label>Delivery Charges</label>
                            <strong>
                            <input type="text" style="width:40% !important; float:right;" value="{{ $edit_purchase_invoice['delivery_charge'] }}" class="form-control numbers-only" id="delivery-charge" name="delivery_charge" />
                            </strong>
                            </div>
                          <div class="subtotal-box">
                            <label>GST</label>
                            <strong><span id="gst-total">Rs {{ number_format($edit_purchase_invoice['gst'],2) }}</span></strong> </div>
                            <input type="hidden" class="gsttotal" name="gst" value="{{ $edit_purchase_invoice['gst'] }}" />
                          <div class="subtotal-box grand-total">
                            <label><strong>TOTAL</strong></label>
                            <strong><span id="grandtotal">Rs {{ number_format($edit_purchase_invoice['total'],2) }}</span></strong></div>
                          <input type="hidden" class="grandtotal" value="{{ $edit_purchase_invoice['total'] }}" name="total" />
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
							<strong><span id="all_pcs">00.00</span></strong>
                            </div>
							
                        </div>
                      </div>
                    </div>
                    <div class="save-row">
                      <div class="save-right float-right">
					  <input type="hidden" name="financial_year" value="{{ $edit_purchase_invoice['financial_year'] }}" />
                        <button type="submit" name="approve" value="approve" class="btn btn-success approve">Create Part Shipped</button>
                        <a href="{{ url('admin/invoicelisting') }}"  class="btn btn-secondary">Cancel</a> </div>
                    </div>
                  </div>
                </div>
              </div>
			  <input type="hidden" name="changes" value="0" class="changes" />
            </form>
 
      </div>
    </div>
  </div>
</div>
<script>
	$(function(){
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
		calculateAllUnits();
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
    $(document).ready(function () {
    $(".proforma_invoice_date").datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
    });
    });
    </script> 

	<script>
       $(function(){
		  $(document).on('change','#customer_name',function(){
			 var customer_id = $(this).val();
			 
			 //alert(customer_id);
			 
			 $("#payment_term option:selected").each(function(){
				  $(this).removeAttr("selected");
				});
				
			  if(customer_id!=''){
					$.ajax({
					type: "GET",
					url: "{{ url('admin/ajaxCustomerDetails')}}/"+customer_id,
					data: {customer_id:customer_id},
					success: function(msg){
						//console.log(msg); return false;
					data = JSON.parse(msg);
					//alert(msg); return false;
						//$('#customer_address').val(data['customer_delivery_address']);
						$('#customer_address').html(data['customer_delivery_address']);
						$('#customer_city').val(data['customer_city']);
						$('#customer_state').val(data['customer_state']);
						$('#customer_contact_person').val(data['customer_contact_person']);
						$('#customer_phone').val(data['customer_phone']);
						$('#gst_no').val(data['gst']);
						$('#crm_assign').val(data['crm_assign']);
						$('#merchant_name').val(data['merchant_name']);
						$('#payment_term option[value="'+data['payment_term']+'"]').attr('selected','selected');
					}
					});
			  }else{
				$('#customer_address').html('');
				$('#customer_city').val('');
				$('#customer_state').val('');
				$('#customer_contact_person').val('');
				$('#customer_phone').val('');
				$('#gst_no').val('');
				$('#crm_assign').val('');
				$('#merchant_name').val('');
				$('#payment_term').val('');
				$('#payment_term option[value=""]').attr('selected','selected');				
			  }
		  });
	   });
    </script>
    <script>
       $(function(){
		  $(document).on('change','#buyer_name',function(){
			 var buyer_id = $(this).val();
			  if(buyer_id!=''){
					$.ajax({
					type: "GET",
					url: "{{ url('admin/ajaxBuyerDetails')}}/"+buyer_id,
					data: {buyer_id:buyer_id},
					success: function(msg){
					data = JSON.parse(msg);
					//alert(msg); return false;
					$('#buyer_address').html(data['buyer_address']);
					$('#buyer_city').val(data['buyer_city']);
					$('#buyer_state').val(data['buyer_state']);
					$('#buyer_contact_person').val(data['buyer_contact_person']);
					$('#buyer_phone').val(data['buyer_phone']);
					$('#buyer_gst_no').val(data['gst']);
					}
					});
			  }else{
				$('#buyer_address').html('');
					$('#buyer_city').val('');
					$('#buyer_state').val('');
					$('#buyer_contact_person').val('');
					$('#buyer_phone').val('');  
					$('#buyer_gst_no').val('');  
			  }
		  });
	   });
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
			
			 var order_type = $( "select[name='order_type']" ).val();
		  var delivery_charge = parseFloat($( "#delivery-charge" ).val());
		  var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
		  if(order_type=='sampling(40%)'){
			var order_type = 40;  
		  }else if(order_type=='sampling(20%)'){
			var order_type = 20; 
		  }else{
			var order_type = 0; 
		  }
		  var qty_val_cal = parseFloat($('#tmp_id_bulkqty_'+id).val());
		  var qty_val = isNaN(qty_val_cal) ? 0 : qty_val_cal;
		  var ssqty_va_cal = parseFloat($('#tmp_id_ssqty_'+id).val());
		  var ssqty_val = isNaN(ssqty_va_cal) ? 0 : ssqty_va_cal;
		  var price_val = $('#tmp_id_unit_price_'+id).val();
		  
		  var max = 0;
		  $('.gst').each(function() {
		  var value = parseFloat($(this).val());
		  max = (value > max) ? value : max;
		  });
		  
		  
		  
		  if(qty_val!='' && price_val!='')
				{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));
				}else{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));	
				}
		 var subTotal = 0;
		 $(".amount_hidden").each(function () {
			var stval = parseFloat($(this).val());
			subTotal += isNaN(stval) ? 0 : stval;
			});
		$('#subtotal').html('Rs '+subTotal.toFixed(2));
		$('.subtotal').val(subTotal.toFixed(2));
	
		var sampling_charges = parseFloat(subTotal*order_type)/100;
		$('#sampling-charge').html('Rs '+sampling_charges.toFixed(2));
		$('.sampling-charge').val(sampling_charges.toFixed(2));
		
		var totalGst = parseFloat(((subTotal+sampling_charges+delivery_charge_val)*max)/100).toFixed(2); 
		$('.gsttotal').val(totalGst);
		$('#gst-total').html('Rs '+parseFloat(totalGst).toFixed(2));
		
		var total = parseFloat(subTotal)+parseFloat(sampling_charges)+parseFloat(delivery_charge_val)+parseFloat(totalGst);
		
		$('#grandtotal').html('Rs '+total.toFixed(2));
		$('.grandtotal').val(total.toFixed(2));
			
			}
			});
		  }else{
			$('#tmp_id_colour_'+id).html('');
			$('#tmp_id_desc_'+id).val('');
			$('#tmp_id_hsn_code_'+id).val('');
			$('#tmp_id_gst_'+id).val('');  
		  }
	 });
  });
  </script>
  <script>
  $(function(){
	  
	  $( "select[name='order_type']" ).on('change', function(){
		   if($(this).val()=='Sampling'){
			   $('#order_sampling_type').val(40);
			   $('#order_sampling_type').focus();
		   }else{
			   $('#order_sampling_type').val('');
			   $('#order_sampling_type').focus();
		   }
		   
		})
	  
     $(document).on('keyup mouseup change click','.bulk-qty,.ss-qty,.unit-price,#order_sampling_type,#delivery-charge,.remove-row, #order_type_option',function(){
	      var id = $(this).attr('cus');
		  var order_type = $( "#order_sampling_type" ).val();
		  var delivery_charge = parseFloat($( "#delivery-charge" ).val());
		  var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
		 
		  var qty_val_cal = parseFloat($('#tmp_id_bulkqty_'+id).val());
		  var qty_val = isNaN(qty_val_cal) ? 0 : qty_val_cal;
		  var ssqty_va_cal = parseFloat($('#tmp_id_ssqty_'+id).val());
		  var ssqty_val = isNaN(ssqty_va_cal) ? 0 : ssqty_va_cal;
		  var price_val = $('#tmp_id_unit_price_'+id).val();
		  
		  var max = 0;
		  $('.gst').each(function() {
		  var value = parseFloat($(this).val());
		  max = (value > max) ? value : max;
		  });
		  
		  
		  
		  if(qty_val!='' && price_val!='')
				{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));
				}else{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val+ssqty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val+ssqty_val)*price_val)));	
				}
		 var subTotal = 0;
		 $(".amount_hidden").each(function () {
			var stval = parseFloat($(this).val());
			subTotal += isNaN(stval) ? 0 : stval;
			});
		$('#subtotal').html('Rs '+subTotal.toFixed(2));
		$('.subtotal').val(subTotal.toFixed(2));
		
		var sampling_charges = parseFloat(subTotal*order_type)/100;
		$('#sampling-charge').html('Rs '+sampling_charges.toFixed(2));
		$('.sampling-charge').val(sampling_charges.toFixed(2));

		var totalGst = parseFloat(((subTotal+sampling_charges+delivery_charge_val)*max)/100).toFixed(2); 
		$('.gsttotal').val(totalGst);
		$('#gst-total').html('Rs '+parseFloat(totalGst).toFixed(2));
		
		var total = parseFloat(subTotal)+parseFloat(sampling_charges)+parseFloat(delivery_charge_val)+parseFloat(totalGst);
		
		$('#grandtotal').html('Rs '+total.toFixed(2));
		$('.grandtotal').val(total.toFixed(2));
		
	 });
  });
  </script>

  
  <script>
		
		window.onload = setTimeout(function(){
			calculateAllUnits();
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
					var Mtr=0; var Kg =0; var Yd =0; var Pcs =0;					 
					var rowCount = ($('#editableTable1 tr').length)-1;
					var bulkqty=0; var ssqty=0;					 
					for(var i=1; i<=rowCount; i++){
						if($('#tmp_id_bulkqty_'+i).val()>0){
							bulkqty = parseFloat($('#tmp_id_bulkqty_'+i).val());
						}else{
							bulkqty=0;
						}
						
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
					$('#all_mtr').text(Mtr);
					$('#all_kg').text(Kg);
					$('#all_yd').text(Yd);					
					$('#all_pcs').text(Pcs);					
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
$(function(){
  $("form :input").change(function() {
  $(this).closest('form').data('changed', true);
});
$('.approve').click(function() {
  if($(this).closest('form').data('changed')) {
  //alert('changess'); 
  $('.changes').val(1);
  }else{
  $('.changes').val(0);
  //alert('no changess');
  }
});
});
</script>
<script type="text/javascript">
function download(d) {
        if (d == '') return false;
		window.open('<?php echo url('public/pi_invoice/'.$edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'/')?>'+d, '_blank');
		
}
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
					/* 5 Oct 2020 END*/
					
				jQuery('#tmp_id_desc_'+rowNum).val(description);
				jQuery('#tmp_id_hsn_code_'+rowNum).val(hsn_code);
				jQuery('#tmp_id_gst_'+rowNum).val(gst_code);
					
			}
		}); 
});
</script>

<script>
	$(function () { 
		var buyerArr = '<?php print_r(getAllBuyer()); ?>';
		buyerArr = JSON.parse(buyerArr);
		//console.log(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
		$('#customer_name').html(buyerHtml);
		$('#customer_name').val('<?php echo $edit_purchase_invoice['customer_name']; ?>');
		$('#buyer_name').html(buyerHtml);
		
		$('#buyer_name').val('<?php echo $edit_purchase_invoice['buyer_name']; ?>');
		//console.log(buyerHtml);
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