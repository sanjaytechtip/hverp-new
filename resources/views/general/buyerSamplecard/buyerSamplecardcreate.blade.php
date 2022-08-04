@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Buyer Sample Card')

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


/* for auto fill inputs */
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
    width: 117px !important;
}
</style>
<div class="page creat-fms-box">
	<div class="page-header">
		<h1 class="page-title">{{ __('Add Buyer Sample Card') }}</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{ route('buyersamplecardlisting') }}">Buyer Sample Card Listing</a></li>
			<li class="breadcrumb-item">{{ __('Add Buyer Sample Card') }}</li>
		</ol>
		<div class="page-header-actions custom-page-heading">
			<div class="row no-space w-400">
				<?php //$dd = getAllArticles(); pr($dd); die;?>
				<?php //$dd = getAllBuyer(); pr($dd); die;?>
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
		<div class="row justify-content-center"> 
			@if (\Session::has('success'))
			<div class="alert alert-success">
				<p>{{ \Session::get('success') }}</p>
			</div>
			<br />
			@endif
			<div class="col-md-12">
				<div class="card">
						<form class="" action="{{ route('buyersamplecardcreate') }}" method="get">
						<div class="card-body">
							<div class="row" style="margin:0;">
								<div class="col-lg-3 col-md-3 col-sm-12">
									<div class="contact-form bs-example contact-heading">
										<label for="contact"><strong>Select Category</strong></label>
										<select class="required form-control" name="category[]" id="category_type" multiple="multiple" data-plugin="select2" data-placeholder="--Select--" required>
												@foreach(getOptionArray('buyer_sample_card_category') as $category)
												
													<?php 
														$sel='';
														if(isset($_GET) && $_GET['category']!='' && in_array($category, $_GET['category'])){
															$sel=' selected';
														}
													?>
													
													<option {{ $sel }}>{{ $category }}</option>
												@endforeach
											</select>
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-12">
									<button type="submit" name="approve" value="approve" style="margin-top:28px;" class="btn btn-success">Submit</button>
									<a href="{{ route('buyersamplecardcreate') }}"  style="margin-top:28px;" class="btn btn-primary">Reset</a>
								</div>
								
							</div>
							</div>
							</form>
				</div>
			
				<form class="" action="{{ url('admin/samplecardadd') }}" id="invoiceform" method="post">
					@csrf
					<div class="card">
						<div class="card-body">
									<div class="contact-form bs-example contact-heading">
										<label for="contact"><strong>Customer</strong></label>
									<a href="{{ route('buyer-registration') }}" target="_blank" style="text-decoration:underline;"> Register New Customer</a>
									</div>
							<div class="row" style="margin:0;">
								<div class="col-lg-6 col-md-6 col-sm-12">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example auto-complete-div" style="margin-top:10px;">
											<label for="contact">Company Name<span class="required" style="color: #e3342f;">*</span></label>						
											
											<?php /*?>
											<select class="required form-control" id="customer_name" name="customer_name" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="2">
												<option value="">-- Select --</option>
											</select>
											<?php */?>
											<input type="text" name="customer_name" value="" class="form-control" id="customer_name" />
											<input type="hidden" name="customer_id" value="" class="form-control" id="customer_id" />
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
											<label for="contact">Customer Type<span class="required" style="color: #e3342f;">*</span></label>
											<select class="required form-control" name="customer_type" id="customer_type" required>
												<option value="">-- Select --</option>
												<option>New</option>
												<option>Existing</option>
											</select>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Address</label>
												<textarea class="form-control" rows="4" name="address" id="customer_address"></textarea>
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">City</label>
												<input type="text" name="city" id="city" class="form-control " />
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Name</label>
												<input type="text" name="name" id="name" class="form-control" />
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Phone</label>
												<input type="text" name="phone" id="phone" class="form-control" />
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example auto-complete-div" style="margin-top:10px;">
												<label for="contact">Season<span class="required" style="color: #e3342f;">*</span></label>
												<input type="text" name="season" id="season" class="form-control" />
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Email id<span class="required" style="color: #e3342f;">*</span></label>
												<input type="text" name="email" id="email" class="form-control" />
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Sales Person</label>
												{!! getUserByDesignation(SALES_AGENT,'sales_person','---Select---','') !!}
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Enquiry Type (Sample Card/Quotation)<span class="required" style="color: #e3342f;">*</span></label>
												<input type="text" name="enquiry_type" required id="enquiry_type" class="form-control" />
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">CRM Enquiry No<span class="required" style="color: #e3342f;">*</span></label>
												<input type="text" name="crm_enquery" required id="crm_enquery" class="form-control" />
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">CRM</label>											
												{!! getUserByDesignation('crm','crm_assign','---Select---','', 'id="crm_assign"') !!}
											</div>
										</div>
										
										<div class="col-lg-6 col-md-6 col-sm-12">
											<div class="contact-form bs-example" style="margin-top:10px;">
												<label for="contact">Merchan</label>
												{!! getUserByDesignation('merchant','merchant_name','---Select---','', 'id="merchant_name"') !!}
											</div>
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
									<th width="50">Item No.</th>
									<th>Description</th>
									<th>Remark</th>
									<th width="80">Price</th>
									<th width="80">Unit</th>
									<th width="40">Amount</th>
									<th width="40">&nbsp;</th>
								  </tr>
								</thead>
								<tbody id="results">
									<?php
										$cat_res = array();
										if(isset($_GET) && $_GET['category']!=''){
											$cat_res = getArticleByCategory($_GET['category']);
										}
										
										//var unit_price_max = ui.item.max_price;
										//var unit = ui.item.unit;
										//pr($cat_res); die;
									?>
									<?php if(empty($cat_res)){ ?>
									@for($i=1;$i<=5;$i++)
									<tr id="data-row-{{ $i }}">
										<td data-text="Item">
										<input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
										<input type="hidden" name="row[{{$i}}][item]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="" />
										</td>
									 
										<td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]"></textarea></td>
										
										<td data-text="remark"><textarea class="form-control width-full auto-fill-field" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]"></textarea></td>
									  
										<td data-text="Unit Price"><input type="text" autocomplete="off" min="0" class="form-control width-full unit-price numbers-only" cus="{{$i}}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]" />
										</td>
										
										<td data-text="Unit"><input type="text" autocomplete="off" style="width:80px;" class="form-control width-full unit" cus="{{$i}}" rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]" />
										</td>
									  
										<td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right"></td>
										<input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" name="row[{{$i}}][amount]" />
										<td><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
									</tr>
									@endfor
									<?php }else{?>
									<?php $i=1; ?>
									@foreach($cat_res as $res)
									<?php  
									$description = $res['description'].', '.$res['composition'].', '.$res['count_construct'].', '.$res['gsm'].', '.$res['width']; 
									?>
									<tr id="data-row-{{ $i }}">
										<td data-text="Item">
										<input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" value="{{ $res['article_no'] }}" required />
										<input type="hidden" name="row[{{$i}}][item]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{ $res['_id'] }}" />
										</td>
									 
										<td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_{{$i}}" style="height:35px;"  name="row[{{$i}}][description]">{{ $description }}</textarea></td>
										
										<td data-text="remark"><textarea class="form-control width-full auto-fill-field" rel="remark" id="tmp_id_remark_{{$i}}" style="height:35px;"  name="row[{{$i}}][remark]"></textarea></td>
									  
										<td data-text="Unit Price"><input type="text" autocomplete="off" min="0" class="form-control width-full unit-price numbers-only" cus="{{$i}}" value="{{ $res['max_price'] }}" rel="unit-price" id="tmp_id_unit_price_{{$i}}" name="row[{{$i}}][unit_price]" />
										</td>
										
										<td data-text="Unit"><input type="text" autocomplete="off" style="width:80px;" class="form-control width-full unit" cus="{{$i}}" rel="unit" id="tmp_id_unit_{{$i}}" name="row[{{$i}}][unit]" value="{{ $res['unit'] }}" />
										</td>
									  
										<td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right"></td>
										<input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" name="row[{{$i}}][amount]" />
										<td><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
									</tr>
									<?php $i++; ?>
									@endforeach
									<?php }?>
								</tbody>
                      </table>
						</div>
							<div class="add-row">
								<div class="add-left">
									<div class="dropdown two-button">
										<button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>
										<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> </button>
										<div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>
									</div>
								</div>
								
								<div class="total-right">
									<div class="subtotal">
										<div class="subtotal-box">
											<label>Subtotal</label>
											<strong><span id="subtotal">Rs 0.00</span></strong>
										</div>
											<input type="hidden" class="subtotal" name="subtotal" />
										<div class="subtotal-box">
											<label>Delivery Charges</label>
											<strong>
											<input type="text" style="width:40% !important; float:right;" min="0" class="form-control numbers-only" id="delivery-charge" name="delivery_charge" />
											</strong>
										</div>
										
										<div class="subtotal-box grand-total">
											<label><strong>TOTAL</strong></label>
											<strong><span id="grandtotal">Rs 0.00</span></strong>
										</div>
										<input type="hidden" class="grandtotal" name="total" />
									</div>
								</div>
							</div>
						<div class="save-row">
							<div class="save-right float-right">
								<button type="submit" name="approve" value="approve" class="btn btn-success">Submit</button>
								<a href="{{ url('admin/buyersamplecardlisting') }}"  class="btn btn-secondary">Cancel</a> 
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
	    var html = '<tr id="data-row-'+rowCount+'"> <td data-text="Item"><input type="text" name="item_drop" class="autocomplete-dynamic form-control" id="itemrel_'+rowCount+'" required /><input type="hidden" name="row['+rowCount+'][item]" id="tmp_id_item_'+rowCount+'" cus="'+rowCount+'" rel="item" value="" /></td> <td data-text="Description"><textarea class="form-control width-full auto-fill-field" rel="description" id="tmp_id_desc_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][description]"></textarea> </td> <td data-text="remark"><textarea class="form-control width-full auto-fill-field" rel="remark" id="tmp_id_remark_'+rowCount+'" style="height:35px;" name="row['+rowCount+'][remark]"></textarea> </td> <td data-text="Unit Price"><input type="text" autocomplete="off" min="0" class="form-control width-full unit-price numbers-only" cus="'+rowCount+'" rel="unit-price" id="tmp_id_unit_price_'+rowCount+'" name="row['+rowCount+'][unit_price]" /> </td> <td data-text="Unit"><input type="text" autocomplete="off" class="form-control width-full unit" style="width:80px;" cus="'+rowCount+'" rel="unit" id="tmp_id_unit_'+rowCount+'" name="row['+rowCount+'][unit]" /> </td>  <td data-text="Amount" id="data-row-amount-'+rowCount+'" class="amount" style="font-weight:bold;" align="right"> </td><input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'+rowCount+'" name="row['+rowCount+'][amount]" /> <td><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';
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
							var unit_price_max = ui.item.max_price;
							var unit = ui.item.unit;
						
							var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
								description= description.replace(/\\/g, ''); 
								
						
							
							jQuery('#itemrel_'+rowNum).val(ui.item.value);
							jQuery('#tmp_id_item_'+rowNum).val(ui.item.id);
							
							jQuery('#tmp_id_desc_'+rowNum).val(description);
							jQuery('#tmp_id_unit_price_'+rowNum).val(unit_price_max);
							if(unit_price_max!='' && unit_price_max>0){
								jQuery('#data-row-amount-'+rowNum).html('Rs '+eval(unit_price_max).toFixed(2));
								jQuery('#tmp_id_amount_'+rowNum).val(unit_price_max);
								jQuery('#tmp_id_unit_price_'+rowNum).trigger('click');
							}
							
							if(unit!=''){
								jQuery('#tmp_id_unit_'+rowNum).val(unit);
							}else{
								jQuery('#tmp_id_unit_'+rowNum).val('');
							}
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
		  $(document).on('change','#customer_name___old',function(){
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
						//console.log(msg);  return false;
					data = JSON.parse(msg);
						$('#customer_address').html(data['customer_delivery_address']);
						$('#city').val(data['customer_city']);
						//$('#customer_state').val(data['customer_state']);
						$('#phone').val(data['customer_phone']);
						$('#email').val(data['email_id_md']);
						$('#crm_assign').val(data['crm_assign']);
						$('#merchant_name').val(data['merchant_name']);
					}
					});
			  }else{
				$('#customer_address').html('');
				$('#city').val('');
				$('#phone').val('');
				$('#crm_assign').val('');
				$('#merchant_name').val('');
			  }
		  });
	   });
    </script>
	
<script>
	$(function(){

     $(document).on('keyup mouseup change click','.unit-price,#delivery-charge,.remove-row, #order_type_option',function(){
		//debugger;
	      var id = $(this).attr('cus');
		  var delivery_charge = parseFloat($( "#delivery-charge" ).val());
		  var delivery_charge_val = isNaN(delivery_charge) ? 0 : delivery_charge;
		 
		  var qty_val_cal = 1;
		  var qty_val = isNaN(qty_val_cal) ? 0 : qty_val_cal;


		  var price_val = $('#tmp_id_unit_price_'+id).val();
		  
		  var max = 0;
		  $('.gst').each(function() {
		  var value = parseFloat($(this).val());
		  max = (value > max) ? value : max;
		  });
		  
		  if(qty_val!='' && price_val!='')
				{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val)*price_val)));
				}else{
				$('#data-row-amount-'+id).html('Rs '+eval(((qty_val)*price_val)).toFixed(2));
				$('#tmp_id_amount_'+id).val(eval(((qty_val)*price_val)));	
				}
		 var subTotal = 0;
		 $(".amount_hidden").each(function () {
			var stval = parseFloat($(this).val());
			subTotal += isNaN(stval) ? 0 : stval;
			});
		$('#subtotal').html('Rs '+subTotal.toFixed(2));
		$('.subtotal').val(subTotal.toFixed(2));
		
		var totalGst = parseFloat(((subTotal+delivery_charge_val)*max)/100).toFixed(2); 
		$('.gsttotal').val(totalGst);
		$('#gst-total').html('Rs '+parseFloat(totalGst).toFixed(2));
		
		var total = parseFloat(subTotal)+parseFloat(delivery_charge_val)+parseFloat(totalGst); 
		
		$('#grandtotal').html('Rs '+total.toFixed(2));
		$('.grandtotal').val(total.toFixed(2));
	 });
  });
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
					console.log(ui);
					
					var id_new = jQuery(this).attr('id');
					var rowNum = id_new.split('_')[1];
					console.log(id_new);
					console.log(rowNum);
					console.log(ui.item.colour);
					var item_color_arr = ui.item.colour.split(',');
					
					var factory_code = ui.item.factory_code;
					var unit_price_max = ui.item.max_price;
					var unit = ui.item.unit;
					var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, ''); 
					
				 
					var fabric_finish = ui.item.fabric_finish;
					var hsn_code = ui.item.hsn_code;

					var gst_code = ui.item.gst_code;
					
					jQuery('#itemrel_'+rowNum).val(ui.item.value);
					jQuery('#tmp_id_item_'+rowNum).val(ui.item.id);
					
					
				jQuery('#tmp_id_desc_'+rowNum).val(description);
				jQuery('#tmp_id_unit_price_'+rowNum).val(unit_price_max);	
				if(unit_price_max!='' && unit_price_max>0){
					jQuery('#data-row-amount-'+rowNum).html('Rs '+eval(unit_price_max).toFixed(2));
					jQuery('#tmp_id_amount_'+rowNum).val(unit_price_max);
					jQuery('#tmp_id_unit_price_'+rowNum).trigger('click');
				}
				
				if(unit!=''){
					jQuery('#tmp_id_unit_'+rowNum).val(unit);
				}else{
					jQuery('#tmp_id_unit_'+rowNum).val('');
				}
			}
		}); 
   
});

</script>


<script>
	/* $(function () { 
		var buyerArr = '<?php //print_r(getAllBuyer()); ?>';
		buyerArr = JSON.parse(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
	}); */
</script>

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script>
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(function() {
		
		//searchsamplecard
		/* for ato customer_name */
			jQuery("#customer_name").autocomplete({
				minLength: 2,
				source: "{{ route('searchcustomer') }}?searchby=customer_name",
				select: function( event, ui ) {
							event.preventDefault();
							console.log(ui);
							jQuery('#customer_name').val(ui.item.value);	
							
							//////////////
							var customer_name;
								customer_name=ui.item.value;
								if(customer_name!=''){
									$.ajax({
									type: "GET",
									url: "{{ url('admin/ajaxCustomerDetailsByname')}}/"+customer_name,
									data: {customer_name:customer_name},
									success: function(msg){
										console.log(msg);  //return false;
									data = JSON.parse(msg);
									
										$('#customer_address').html('');
										$('#customer_id').val('');
										$('#city').val('');
										$('#phone').val('');
										$('#email').val('');
										$('#crm_assign').val('');
										$('#merchant_name').val('');
									
										$('#customer_address').html(data['customer_delivery_address']);
										$('#customer_id').val(data['customer_id']);
										$('#city').val(data['customer_city']);
										$('#phone').val(data['customer_phone']);
										$('#email').val(data['email_id_md']);
										$('#crm_assign').val(data['crm_assign']);
										$('#merchant_name').val(data['merchant_name']);
									}
									});
								}else{
									$('#customer_address').html('');
									$('#customer_id').val('');
									$('#city').val('');
									$('#phone').val('');
									$('#email').val('');
									$('#crm_assign').val('');
									$('#merchant_name').val('');
								}
							
							/////////////
							
						}
			});
		/* for ato customer_name */
		
	});
</script>

<script>
$.noConflict();
</script>

@endsection