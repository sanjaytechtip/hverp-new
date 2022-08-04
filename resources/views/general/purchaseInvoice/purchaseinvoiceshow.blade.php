@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Proforma Invoice')

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
</style>
<?php
	$partshipped='';
	if(isset($_GET) && !empty($_GET) && $_GET['partshipped']!=''){
		$partshipped=$_GET['partshipped'];
		$path= $edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'-P'.$partshipped;
		$pdf = @getPIPDFdata($path);
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
		$pdf = @getPIPDFdata($path);
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
?>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('Proforma Invoice') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
	  <li class="breadcrumb-item"><a href="{{ route('invoicelisting') }}">Proforma Invoice List</a></li>
      <li class="breadcrumb-item">{{ __('Proforma Invoice') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
	  <div class="col-sm-4">
          <div class="counter">
             <span class="counter-number font-weight-medium">&nbsp;</span>
             <div class="counter-label">{!! $html !!}</div></div>
           
        </div>
		<?php 
			$rev='';
			$pi_v_data = getPiVersionsById($pi_id);
			//pr($pi_v_data); die;
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
			//echo $rev_p.'==='.$rev; die; 	die('hiiiiii');
		?>
        <div class="col-sm-4">
          <div class="counter">
             <span class="counter-number font-weight-medium">PI NO. </span>
             <div class="counter-label">PI-POS-{{ $edit_purchase_invoice['financial_year'] }}-{{ $edit_purchase_invoice['po_serial_number'] }}{{ $rev_p }}{{ $rev }}</div>
			 
			 </div>
        </div>
        <div class="col-sm-4">
          <div class="counter">
          <span class="counter-number font-weight-medium">DATE</span>
            <div class="counter-label">{{ DateFormate($edit_purchase_invoice['proforma_invoice_date']) }}</div>
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

            <form class="" id="invoiceform" action="{{ url('admin/invoiceupdate/'.$edit_purchase_invoice['_id']) }}" method="post" autocomplete="on" onsubmit="return validate();">
              @csrf
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
                        <select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2" disabled>
                        <option value="">-- Select --</option>
                        @foreach($customer as $cus)
                        <option @if($edit_purchase_invoice['customer_name']==$cus->_id) selected @endif value="{{ $cus->_id }}">{{ $cus->company_name }}</option>
                        @endforeach
                        </select>
                      </div>
                      </div>
					  
					   <div class="col-lg-6 col-md-6 col-sm-12">
						  <div class="contact-form bs-example" style="margin-top:10px;">
							<label for="contact">Address</label>
							<input type="text" name="customer_address" id="customer_address" value="{{ $edit_purchase_invoice['customer_address'] }}" class="required form-control auto-fill-field" disabled>
						  </div>
                      </div>
					  
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">City</label>
                        <input type="text" name="customer_city" id="customer_city" value="{{ $edit_purchase_invoice['customer_city'] }}" class="form-control auto-fill-field" disabled />
                      </div>
                      </div>
					  
					  <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">State</label>
                        <input type="text" name="customer_state" id="customer_state" value="{{ $edit_purchase_invoice['customer_state'] }}" class="form-control auto-fill-field" disabled />
                      </div>
                      </div>
					  
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">Contact Person</label>
								<input type="text" name="customer_contact_person" value="{{ $edit_purchase_invoice['customer_contact_person'] }}" id="customer_contact_person" class="form-control auto-fill-field" disabled />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<div class="contact-form bs-example" style="margin-top:10px;">
								<label for="contact">GST No</label>
								<input type="text" name="gst_no" id="gst_no" value="{{ $edit_purchase_invoice['gst_no'] }}" class="form-control auto-fill-field" disabled />
							</div>
						</div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Phone</label>
                        <input type="text" name="customer_phone" id="customer_phone" value="{{ $edit_purchase_invoice['customer_phone'] }}" class="form-control auto-fill-field" disabled />
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
                        <select class="required form-control" id="buyer_name" name="buyer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2" disabled>
                        <option value="">-- Select --</option>
                        @foreach($customer as $cus)
                        <option @if($edit_purchase_invoice['buyer_name']==$cus->_id) selected @endif value="{{ $cus->_id }}">{{ $cus->company_name }}</option>
                        @endforeach
                        </select>
                      </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Address</label>
                        <select name="buyer_address" id="buyer_address" class="required form-control" disabled>
                        <option value="">--- Select Address ---</option>
                        {!! buyerDeliveryAddress($edit_purchase_invoice['buyer_name'],$edit_purchase_invoice['buyer_address']) !!}
                        </select>
                      </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">City</label>
                        <input type="text" name="buyer_city" id="buyer_city" value="{{ $edit_purchase_invoice['buyer_city'] }}" class="form-control auto-fill-field" disabled />
                      </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">State</label>
                        <input type="text" name="buyer_state" id="buyer_state" value="{{ $edit_purchase_invoice['buyer_state'] }}" class="form-control auto-fill-field" disabled />
                      </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Contact Person</label>
                        <input type="text" name="buyer_contact_person" value="{{ $edit_purchase_invoice['buyer_contact_person'] }}" id="buyer_contact_person" class="form-control auto-fill-field" disabled />
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
								<input type="text" name="buyer_gst_no" id="buyer_gst_no" value="{{ $buyer_gst_no }}" class="form-control auto-fill-field" disabled />
							</div>
						</div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                      <div class="contact-form bs-example" style="margin-top:10px;">
                        <label for="contact">Phone</label>
                        <input type="text" name="buyer_phone" id="buyer_phone" value="{{ $edit_purchase_invoice['buyer_phone'] }}" class="form-control auto-fill-field" disabled />
                      </div>
                      </div>
                      </div>
                    </div>
                    
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 full-form-box">
                    <div class="row">
                    <div class="contact-form bs-example contact-heading col-lg-12 col-md-12 ">
                        <label for="contact"><strong>Buyer</strong></label>
                      </div>
                    
                      
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Agent/Sales Person Code </label>						
						{!! getUserByDesignation(SALES_AGENT,'sales_agent','---Select---',$edit_purchase_invoice['sales_agent'],'disabled') !!}
                       </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Brand Name </label>
                        {!! getBrandsDropDown('brand_name','---Select---', $edit_purchase_invoice['brand_name'],'disabled') !!} 
                        </div>
                     
                         <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Order Type </label>
                         {!! getOptionDropdown('order_type','order_type', '---Select---',$edit_purchase_invoice['order_type'],'disabled', 'id="order_type_option"') !!}
                        </div>
                         <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Order Sampling Type (%)</label>
                        <input type="number" name="order_sampling_type" value="{{ $edit_purchase_invoice['order_sampling_type'] }}" id="order_sampling_type" class="form-control auto-fill-field" disabled />
                        </div>
                        
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">FOB Sample Approval :</label>
                        <select class="required form-control" id="fob_sample_approval" name="fob_sample_approval" disabled>
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['fob_sample_approval']=='Buyer') selected @endif value="Buyer">Buyer</option>
                          <option @if($edit_purchase_invoice['fob_sample_approval']=='Self') selected @endif value="Self">Self</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">FPT/ Testing Approval :</label>
                        <select class="required form-control" id="fpt_testing_approval" name="fpt_testing_approval" disabled>
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['fpt_testing_approval']=='Required') selected @endif value="Required">Required</option>
                          <option @if($edit_purchase_invoice['fpt_testing_approval']=='Not Required') selected @endif value="Not Required">Not Required</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">PAYMENT TERM (Agreed)</label>						
							<select class="required form-control" id="payment_term" name="payment_term" disabled>
							<option value="">---Select---</option>
							@foreach(getOptionArray('payment_term') as $key=>$val)
								<option value="{{ $key }}"  @if($edit_purchase_invoice['payment_term']==$key) selected @endif>{{ $val }}</option>
							@endforeach
							</select>
							
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">QUANTITY VARIATION (+/- %)</label>
                        <select class="form-control" id="quantity_variation" name="quantity_variation" disabled>
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='3') selected @endif value="3">3</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='5') selected @endif value="5">5</option>
                          <option @if($edit_purchase_invoice['quantity_variation']=='10') selected @endif value="10">10</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Buyer PO No</label>
                        <input type="text" name="buyer_po_number"  value="{{ $edit_purchase_invoice['buyer_po_number'] }}" class="form-control" disabled />
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Status</label>
                        <select class="required form-control" id="status" name="status" disabled>
                          <option value="">---Select---</option>
                          <option @if($edit_purchase_invoice['status']=='Confirmed') selected @endif value="Confirmed">Confirmed</option>
                          <option @if($edit_purchase_invoice['status']=='Unconfirmed') selected @endif value="Unconfirmed">Unconfirmed</option>
                        </select>
                      </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Mode of Transport</label>
                        {!! getOptionDropdown('mode_of_transport','mode_of_transport', '---Select---', $edit_purchase_invoice['mode_of_transport'],'disabled') !!} </div>
                      <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Company of Transport</label>
                        <input type="text" name="company_of_transport" value="{{ $edit_purchase_invoice['company_of_transport'] }}" class="form-control" disabled />
                      </div>
					  
					  <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
							<label for="contact">CRM Enquiry No.</label>
							<input type="text" name="crm_enquiry_no" class="form-control" value="{{ @$edit_purchase_invoice['crm_enquiry_no'] }}" disabled />
						</div>
						
					 <div class="contact-form bs-example col-lg-3 col-md-4" style="margin-top:10px;">
                        <label for="contact">Proforma Invoice Date</label>
                        <input type="text" name="proforma_invoice_date" value="{{ date('Y-m-d',strtotime($edit_purchase_invoice['proforma_invoice_date'])) }}" class="form-control proforma_invoice_date" required disabled />
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
							
							// edit_purchase_invoice						
							//echo $currentUserId = Auth::user()->name; die;
							$i=0;
						?>
                       @foreach($rowDatas as $rowData)
					   <?php $i++; 
						//pr($rowData); die;
					   ?>
                        <tr id="data-row-{{ $i }}">
                          <td data-text="Item">
						  <?php /*?>
                            <select required class="form-control width-full item" cus="{{$i}}" rel="item" id="tmp_id_item_{{$i}}" name="row[{{$i}}][item]" disabled>
                            <option value="">-- Select --</option>
                            @foreach($items as $allitems)
                            <option @if($allitems->_id==$rowData['item']) selected @endif value="{{ $allitems->_id }}">{{ $allitems->article_no }}</option>
                            @endforeach
                            </select>
							<?php */?>
							<?php 
								$itm = getAllArticles($rowData['item']);
							?>
							<input type="text" name="item_drop" class="form-control" id="itemrel_{{$i}}" readonly value="{{ $itm['article_no'] }}" required />
                            </td>
                          <td data-text="Colour">
                          <?php /* ?> <select required class="form-control width-full" cus="{{$i}}" rel="colour" id="tmp_id_colour_{{$i}}" name="row[{{$i}}][colour]" disabled>
                           <option value="">--Select--</option>
                           {!! colorDataDetails($rowData['item'],$rowData['colour']) !!}
                           </select><?php */ ?>
						   
						    <input type="text" required class="form-control width-full" cus="{{$i}}" rel="colour" id="tmp_id_colour_{{$i}}" name="row[{{$i}}][colour]" value="{{ $rowData['colour'] }}" disabled>
                            </td>
                          <td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]" disabled>{{ $rowData['description'] }}</textarea></td>
                          <td data-text="Remark"><textarea class="form-control width-full" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]" disabled>{{ $rowData['remark'] }}</textarea></td>
                          <td data-text="Bulk Qty"><input type="number" required onkeyup="return allCalculateRation(this,{{$i}});" min="1" class="form-control width-full bulk-qty" cus="{{$i}}" rel="bulk-qty" id="tmp_id_bulkqty_{{$i}}" name="row[{{$i}}][bulkqty]" value="{{$rowData['bulkqty'] }}" disabled /></td>
                          <td data-text="S/S Qty"><input type="number" min="1" class="form-control width-full ss-qty" value="{{ $rowData['ssqty'] }}" cus="{{$i}}" rel="ss-qty" id="tmp_id_ssqty_{{$i}}" name="row[{{$i}}][ssqty]" disabled /></td>
                          <td data-text="Unit"><select class="form-control width-full" onchange="itemUnitCal()" required rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]" disabled>
									<option @if($rowData['unit']=='Mtr') selected="selected" @endif value="Mtr">Mtr.</option>
									<option @if($rowData['unit']=='Kg') selected="selected" @endif value="Kg">Kg.</option>
									<option @if($rowData['unit']=='Yd') selected="selected" @endif value="Yd">Yd.</option>
									<option @if($rowData['unit']=='Pcs') selected="selected" @endif value="Pcs">Pcs.</option>
                            </select></td>
                          <td data-text="Lab Dip"><select class="form-control width-full" rel="lab_dip" id="tmp_id_lab_dip_{{$i}}" name="row[{{$i}}][lab_dip]" disabled>
                              <option @if($rowData['lab_dip']=='Required') selected="selected" @endif value="Required">Required</option>
                              <option @if($rowData['lab_dip']=='Approved') selected="selected" @endif value="Approved">Approved</option>
                            </select></td>
                            </td>
                          <td data-text="ETD"><input type="text" required autocomplete="off" class="form-control width-full etd" cus="{{$i}}" rel="etd" id="tmp_id_etd_{{$i}}" value="{{ $rowData['etd'] }}" name="row[{{$i}}][etd]" disabled /></td>
                          <td data-text="HSN Code">
                            <input type="text" readonly="readonly" class="form-control width-full item hsn_code" cus="{{$i}}" value="{{ $rowData['hsn_code'] }}" rel="hsn_code" id="tmp_id_hsn_code_{{$i}}" name="row[{{$i}}][hsn_code]" disabled />
                            </td>
                          <td data-text="Unit Price"><input type="number" required min="1" class="form-control width-full unit-price" value="{{ $rowData['unit_price'] }}" cus="{{$i}}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]" disabled />
                          <td data-text="GST %"><input type="number" readonly="readonly" min="1" value="{{ $rowData['gst'] }}" max="100" class="form-control width-full gst" rel="gst" cus="{{$i}}" id="tmp_id_gst_{{$i}}" name="row[{{$i}}][gst]" disabled /></td>
                          <td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">Rs {{ number_format($rowData['amount'],2) }}</td>
                          <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" value="{{ $rowData['amount'] }}" name="row[{{$i}}][amount]" disabled />
                         
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
                            <input type="hidden" class="subtotal" value="{{ $edit_purchase_invoice['subtotal'] }}" name="subtotal" disabled />
                          <div class="subtotal-box">
                            <label>Sampling Charges</label>
                            <strong><span id="sampling-charge">Rs {{ number_format($edit_purchase_invoice['sampling_charge'],2) }}</span></strong> </div>
                            <input type="hidden" class="sampling-charge" value="{{ $edit_purchase_invoice['sampling_charge'] }}" name="sampling_charge" />
                          <div class="subtotal-box">
                            <label>Delivery Charges</label>
                            <strong>
                            <input type="number" style="width:40% !important; float:right;" value="{{ $edit_purchase_invoice['delivery_charge'] }}" class="form-control" id="delivery-charge" name="delivery_charge" disabled />
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
                </div>
              </div>
			  <input type="hidden" name="changes" value="0" class="changes" />
            </form>
 
      </div>
    </div>
  </div>
</div>
 
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
						
						/* if($('#tmp_id_ssqty_'+i).val()>0){
							ssqty = parseFloat($('#tmp_id_ssqty_'+i).val());
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
					$('#all_mtr').text(Mtr);
					$('#all_kg').text(Kg);
					$('#all_yd').text(Yd);
					$('#all_pcs').text(Pcs);
				};
	</script>
  
@endsection
	@section('custom_validation_script')


<script type="text/javascript">
function download(d) {
        if (d == '') return false;
		window.open('<?php echo url('public/pi_invoice/'.$edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'/')?>'+d, '_blank');	
}



function download_part(d) {
        if (d == '') return false;
		window.open('<?php echo url('public/pi_invoice/'.$edit_purchase_invoice['financial_year'].'/'.$edit_purchase_invoice['po_serial_number'].'-P'.$partshipped.'/')?>'+d, '_blank');	
}
</script>
@endsection