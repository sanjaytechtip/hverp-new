@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit Sale Order')
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
    width: 80px !important;
}
.color-field{
	width: 300px !important;
}
.ui-tooltip  {display:none !important;}
</style>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('Edit Sale Order') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('order_to_dispatch') }}">Order to Dispatch : List</a></li>
      <li class="breadcrumb-item">{{ __('Edit Sale Order') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
        <div class="col-sm-6">
          <div class="counter"> <span class="counter-number font-weight-medium">DATE</span>
            <div class="counter-label">{{ date('d-m-Y H:i:s',strtotime($saleorder_details['created_at'])) }}</div>
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
        <form class="" action="{{ url('admin/saleorder-edit/'.$saleorder_details['id']) }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();">
          @csrf
          <div class="card">
          <div class="card-body">
            <div class="row form-new-box-wrap new-quotationcreate new-fix-quotationcreate">
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-new-box">
                  <label>Customer</label>
                  <input type="text" autocomplete="off" readonly value="{{ getCustomerNamefromId($saleorder_details['customer_name'])}}" class="form-control">
                  <input type="hidden" id="customer_name" name="customer_name" autocomplete="off" readonly value="{{$saleorder_details['customer_name']}}" class="form-control">
                </div>
                <div class="row form-three-box">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <label>Type</label>
                    <select class="form-control" id="saleorder_type" name="saleorder_type">
                      <option @if($saleorder_details['saleorder_type']=='A') selected @endif value="A">A</option>
                      <option @if($saleorder_details['saleorder_type']=='B') selected @endif value="B">B</option>
                      <option @if($saleorder_details['saleorder_type']=='C') selected @endif value="C">C</option>
                      <option @if($saleorder_details['saleorder_type']=='D') selected @endif value="D">D</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>SrNo.</label>
                    <input type="text" autocomplete="off" value="{{$saleorder_details['saleorder_srno']}}" id="saleorder_srno" name="saleorder_srno" required readonly class="form-control">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>No.</label>
                    <input type="text" autocomplete="off" id="saleorder_no" readonly name="saleorder_no" value="{{$saleorder_details['saleorder_no']}}" class="form-control">
                  </div>
                </div>
                
                @if($saleorder_details['type']==3)
                <input type="hidden" name="sale_order_type" value="direct_order">
                @endif
                @if($saleorder_details['type']==2)
                <input type="hidden" name="sale_order_type" value="replacement_order">
                @endif
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="row form-three-box">
  <div class="col-lg-4 col-md-4 col-sm-12">
    <label>SO. Date</label>
    <input type="text" autocomplete="off" id="saleorder_date" value="{{globalDateformatNet($saleorder_details['saleorder_date'])}}" required class="form-control date_with_time small_field_custom datefield" name="saleorder_date">
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 ">
    <label>Delivery Date</label>
    <input type="text" autocomplete="off" id="saleorder_due_date" value="@if($saleorder_details['saleorder_due_date']!='1970-01-01'){{ globalDateformatNet($saleorder_details['saleorder_due_date'])}} @endif" class="form-control date_with_time small_field_custom datefield" name="saleorder_due_date">
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 ">
    <label>Ref. Date</label>
    <input type="text" autocomplete="off" id="saleorder_ref_date" value="@if($saleorder_details['saleorder_ref_date']!='1970-01-01'){{ globalDateformatNet($saleorder_details['saleorder_ref_date'])}} @endif" class="form-control date_with_time small_field_custom datefield" name="saleorder_ref_date">
  </div>
</div>
<div class="row form-three-box">
  <div class="col-lg-4 col-md-4 col-sm-12">
    <label>Priority</label>
    <select name="saleorder_priority" required class="form-control">
      <option @if($saleorder_details['saleorder_priority']=='Normal') selected @endif value="Normal">Normal</option>
      <option @if($saleorder_details['saleorder_priority']=='Medium') selected @endif value="Medium">Medium</option>
      <option @if($saleorder_details['saleorder_priority']=='Urgent') selected @endif value="Urgent">Urgent</option>
    </select>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 ">
    <label>Single Lot Order?</label>
    <div>
      <label class="radio-inline">
        <input type="radio" @if($saleorder_details['single_order']=='no') checked @elseif($saleorder_details['single_order']=='') checked @endif required name="single_order" id="seasonWinter" value="no"> No </label>
      <label class="radio-inline">
        <input type="radio" @if($saleorder_details['single_order']=='yes') checked @endif required name="single_order" id="seasonSummer" value="yes"> Yes </label>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 ">
    <label>Advance Amount (%)</label>
    <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" autocomplete="off" name="adv_amount" value="@if($saleorder_details['adv_amount']!=''){{$saleorder_details['adv_amount']}}@else 0 @endif" class="form-control">
  </div>
</div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 ">
                <div class="form-new-box">
                  <label>Remarks</label>
                  <textarea name="saleorder_remarks" class="form-control">{{$saleorder_details['saleorder_remarks']}}</textarea>
                </div>
                
                
                <div class="row form-two-box">
  <div class="col-lg-6 col-md-6 col-sm-12 ">
    <label>Customer Ref No.</label>
    <input name="saleorder_ref_no" id="saleorder_ref_no" required autocomplete="off" value="{{$saleorder_details['saleorder_ref_no']}}" class="form-control" type="text" >
    <label id="saleorder_ref_error" style="display:none;" class="error">Customer Ref No already exist. Please enter another one.</label>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-12 ">
    <label>Satus</label>
    <select name="saleorder_status" id="saleorder_status" required class="form-control">
      <option @if($saleorder_details['saleorder_status']=='Saved') selected @endif value="Saved">Saved</option>
      <option @if($saleorder_details['saleorder_status']=='Pending Approval') selected @endif value="Pending Approval">Pending Approval</option>
      <option @if($saleorder_details['saleorder_status']=='Approved') selected @endif value="Approved">Approved</option>
    </select>
  </div>
</div>
                
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 recurring-order">
    <label class="col-sm-12 col-sm-12 control-label checkbox-wrap">
    <input disabled type="checkbox" @if($saleorder_details['is_recurring']==1) checked @endif name="is_recurring" id="is_recurring" value="1">
    <div class="check-box-wrap"> Recurring Order?
    <small>Recurring Orders re-occur on certain days or dates of every week, month or year. </small>
    </div>
    </label>
    <div class="row form-three-box">
    <div class="col-lg-4 col-md-4 col-sm-12 recurring_type" @if($saleorder_details['recurring_type']=='' || $saleorder_details['recurring_type']==0) style="display:none;" @endif >
    <select name="recurring_type" id="recurring_type" required class="form-control">
    <option value="">Select</option>
    @foreach(getRecurringData() as $key=>$recurring_data)
    <option @if($saleorder_details['recurring_type']==$key) selected @endif value="{{$key}}">{{$recurring_data}}</option>
    @endforeach
    </select>
    </div>
    
    <div class="row col" id="result-recurring-type">
    {!! get_recurring_type_data($saleorder_details['recurring_type'],$saleorder_details['date_of_yearly'],$saleorder_details['month_of_yearly'],$saleorder_details['date_of_quaterly'],$saleorder_details['month_of_quaterly'],$saleorder_details['date_of_month'],$saleorder_details['date_of_forthnigthly'],$saleorder_details['day_of_week']) !!}
    </div>
  </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 recurring-order">
                <label class="col-sm-12 col-sm-12 control-label checkbox-wrap">
                <input disabled type="checkbox" @if($saleorder_details['is_scheduled']==2) checked @endif name="is_scheduled" id="is_scheduled" value="2">
                <div class="check-box-wrap"> Scheduled Order?</div>
                </label> 
              </div>
            </div>

            <div class="purchaseorder-box quotationcreate-box-wrap">
             
              <label for="contact"><strong>Item Details</strong></label>
              <br/>
              <span id="ajax-cust"></span>
              <div class="table-wrap big-table scrollfix-wrap">
                <table class="editable-table table table-striped quotationcreate-table left-fix-table" id="editableTable1">
                  <thead>
                    <tr>
                      <th width="10" class="sl-th task_left_fix">Sl</th>
                      <th width="10" class="vendor-sku-th task_left_fix">Cust Ref No.</th>
                      <th width="50" class="item-th task_left_fix">Item</th>
                      <th class="vendor-sku-th">Vendor SKU</th>
                      <th class="sap-code-th">SAP Code</th>
                      <th class="hsn-code-th">HSN Code</th>
                      <th class="tax-th">Tax</th>
                      <th width="110" class="packing-name-th">Packing Name</th>
                      <th width="120" class="list-price-th">List Price</th>
                      <th width="80" class="brand-th">Rate</th>
                      <th width="110" class="stock-th">Stock</th>
                      <th width="50" class="mrp-th">MRP</th>
                      <th width="80" class="disc-th">Disc %</th>
                      <th width="90" class="discount-th">Discount</th>
                      <th width="90" class="qty-th">Qty</th>
                      <th width="110" class="date-th">Date</th>
                      <th width="40" class="net-rate-th">Net Rate</th>
                      <th width="40" class="tax-amount-th">Tax Amount</th>
                      <th width="40" class="amount-th">Amount</th>
                      <th width="40" class="action-th">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="results">
                  
                  @php
                  $i = 1;
                  @endphp
                  @foreach($saleorder_item_details as $key=>$item_details)
                  <tr id="data-row-{{ $i }}">
                    <td data-text="Sl" class="task_left_fix">{{ $i }}</td>
                    <td data-text="Cust Ref No." class="task_left_fix"><input type="hidden" name="row[{{$i}}][schedule_type]" value="{{$item_details['schedule_type']}}"><input type="text" value="{{$item_details['cust_ref_no']}}" name="row[{{$i}}][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_{{$i}}" required /></td>
                    <td data-text="Item" class="task_left_fix"><input type="text" readonly value="{{$item_details['item_name']}}" name="row[{{$i}}][item_name]" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
                      <input type="hidden" name="row[{{$i}}][item_id]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{$item_details['item_id']}}" />
                      <input type="hidden" name="row[{{$i}}][sale_order_item_id]" id="tmp_sale_order_id_{{$i}}" cus="{{$i}}" rel="item" value="{{$item_details['id']}}" />
                      @if($saleorder_details['is_scheduled']==2 && $item_details['schedule_type']==2 || $saleorder_details['is_scheduled']=='' && $item_details['schedule_type']==0)
                      <small><a data-toggle="modal" cus="{{$i}}" class="search-pop-up small-btn" data-target="#myModal" href="javascript:void(0);">+ Add Item</a></small>
                      @endif
                    </td>
                    <td data-text="Vendor SKU"><input type="text" readonly value="{{$item_details['vendor_sku']}}" name="row[{{$i}}][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_{{$i}}" required /></td>
                    <td data-text="SAP Code"><input type="text" readonly name="row[{{$i}}][sap_code]" value="{{$item_details['sap_code']}}" class="autocomplete-dynamic form-control" id="sap_code_rel_{{$i}}" /></td>
                    <td data-text="HSN Code"><input type="text" readonly value="{{$item_details['hsn_code']}}" name="row[{{$i}}][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_{{$i}}" required /></td>
                    <td data-text="Tax"><input type="text" @if($saleorder_details['is_scheduled']==2 && $item_details['schedule_type']==0) readonly @endif value="{{$item_details['tax_rate']}}" name="row[{{$i}}][tax_rate]" cus="{{$i}}" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_{{$i}}" required /></td>
                    <td data-text="Packing Name"><input type="text" readonly value="{{$item_details['packing_name']}}" name="row[{{$i}}][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_{{$i}}" /></td>
                    <td data-text="List Price"><input type="text" readonly value="{{$item_details['list_price']}}" name="row[{{$i}}][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_{{$i}}" /></td>
                    <td data-text="Rate"><input @if($saleorder_details['is_scheduled']==2 && $item_details['schedule_type']==0) readonly @endif type="text" value="{{$item_details['rate']}}" name="row[{{$i}}][rate]" cus="{{$i}}" class="autocomplete-dynamic item-rate form-control" id="rate_rel_{{$i}}" /></td>
                    <td data-text="Stock"><input type="text" @if($saleorder_details['is_scheduled']==2 && $item_details['schedule_type']==0) readonly @endif value="{{$item_details['stock']}}" readonly name="row[{{$i}}][stock]" class="autocomplete-dynamic form-control" id="stock_rel_{{$i}}" /></td>
                    <td data-text="MRP"><input type="text" readonly value="{{$item_details['mrp']}}" name="row[{{$i}}][mrp]" cus="{{$i}}" class="autocomplete-dynamic form-control mrp" id="mrp_rel_{{$i}}" /></td>
                    <td data-text="Dis %"><input type="number" @if($saleorder_details['is_scheduled']==2 && $item_details['schedule_type']==0) readonly @endif value="{{$item_details['discount_per']}}" name="row[{{$i}}][discount_per]" cus="{{$i}}" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_{{$i}}" /></td>
                    <td data-text="Discount"><input type="number" readonly value="{{$item_details['discount']}}" name="row[{{$i}}][discount]" class="autocomplete-dynamic form-control" id="discount_rel_{{$i}}" /></td>
                    <td data-text="Quantity"><input type="number" value="{{$item_details['quantity']}}" name="row[{{$i}}][quantity]" cus="{{$i}}" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_{{$i}}" /></td>
                    <td data-text="Date"> <input type="text" value="{{$item_details['scheduled_date']}}" name="row[{{$i}}][scheduled_date]" cus="{{$i}}" class="autocomplete-dynamic ss-scheduled_date form-control date_with_time small_field_custom datefield" id="scheduled_date_rel_{{$i}}" /> </td>
                    <td data-text="Net Rate" id="data-row-net-rate-{{ $i }}" class="netrate" style="font-weight:bold;" align="right">{{$item_details['net_rate']}}</td>
                    <td data-text="Tax Amount" id="data-row-tax-amount-{{ $i }}" class="taxamount" style="font-weight:bold;" align="right">{{$item_details['tax_amount']}}</td>
                    <td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">{{$item_details['amount']}}</td>
                    <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_{{$i}}" value="{{$item_details['net_rate']}}" name="row[{{$i}}][net_rate]" />
                    <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_{{$i}}" value="{{$item_details['tax_amount']}}" name="row[{{$i}}][tax_amount]" />
                    <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_{{$i}}" value="{{$item_details['amount']}}" name="row[{{$i}}][amount]" />
                    <td>@if($item_details['schedule_type']==2)<a href="javascript:void(0);" onclick="return scheduledRow({{$i}});" class="btn btn-sm btn-icon btn-pure btn-default on-default waves-effect waves-classic" title="Scheduled Task"><i class="icon md-copy" aria-hidden="true"></i></a>@else <span style="width:41px; display:inline-block;"></span> @endif @if($item_details['id']=='') <a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a> @else <a href="javascript:void(0);" onclick="return removeRowAjax({{ $i }},'{{$item_details['id']}}');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a> @endif </td>
                  </tr>
                  @php
                  $i++;
                  @endphp
                  @endforeach
                    </tbody>
                  
                </table>
              </div>
              <div class="add-row">
                <div class="add-left">
                  <div class="dropdown">
                    <button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>
                    <div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>
                  </div>
                </div>
              </div>
              <div class="row quotationcreate-wrap">
                <div class="col-lg-6 col-md-6">
                  <h2>Other Charges</h2>
                  <div class="select-form-box">
                    <div class="input-form-box">
                      <select class="form-control charge-other">
                        <option value="">--Select--</option>
                        
                  @foreach($other_charges as $charges_other)
                  
                        <option value="{{$charges_other['other_charges']}}">{{$charges_other['other_charges']}}</option>
                        
                  @endforeach
                
                      </select>
                    </div>
                    <div class="input-form-box input-form-box-input">
                      <input type="number" min="0" class="form-control charges-val"/>
                    </div>
                    <div class="input-form-box input-form-box-buttob"><a href="javascript:void(0);" class="btn btn-primary add-other waves-effect waves-classic">+</a></div>
                  </div>
                  <div class="table-box">
                    <table cellpadding="0" cellspacing="0" width="100%" class="editable-table table-border table table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Value</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      
                      @php
                      $sum=0;
                      @endphp
                      @foreach($other_charges_data as $key=>$charges)
                      <tr id='san-{{$key}}'>
                        <td>{{$charges['other_charges_name']}}</td>
                        <td class='ch-val'>{{$charges['other_charges_val']}}</td>
                        <td><a href='javascript:void(0);' onclick='return removeOther({{$key}})' class='btn btn-danger waves-effect waves-classic'><i class='icon md-minus' style='margin:0;'></i></a></td>
                        <input type='hidden' name='other_charges_name[]' value='{{$charges['other_charges_name']}}'>
                        <input type='hidden' name='other_charges_val[]' value="{{$charges['other_charges_val']}}" >
                      </tr>
                      @php
                      $sum = $sum+$charges['other_charges_val'];
                      @endphp
                      @endforeach
                      <tr id="results-charges">
                        <td><strong>Total</strong></td>
                        <td id="total-other">{{$sum}}</td>
                        <td></td>
                      </tr>
                        </tbody>
                      
                    </table>
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 form-new-box-wrap">
                  <h2>Sale Order Contact Information</h2>
                  <div class="form-new-box">
                    <label>Name</label>
                    <input type="text" value="{{$saleorder_details['saleorder_contact_name']}}" required name="saleorder_contact_name" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Department</label>
                    <input type="text" value="{{$saleorder_details['saleorder_contact_department']}}" autocomplete="off" id="saleorder_contact_department" name="saleorder_contact_department" required class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Phone</label>
                    <input type="text" value="{{$saleorder_details['saleorder_contact_phone']}}" required name="saleorder_contact_phone" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Email</label>
                    <input type="email" value="{{$saleorder_details['saleorder_contact_email']}}" required name="saleorder_contact_email" class="form-control">
                  </div>
                </div>
                <div class="col-lg-3 col-md-3 form-new-box-wrap">
                  <h2>Totals</h2>
                  <div class="form-new-box">
                    <label>Sub Total</label>
                    <input type="text" value="{{$saleorder_details['saleorder_subtotal']}}" name="saleorder_subtotal" readonly id="sub-total" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Sale Tax</label>
                    <input type="text" autocomplete="off" value="{{$saleorder_details['saleorder_saletax']}}" name="saleorder_saletax" id="sale-tax" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Tax Amount</label>
                    <input type="text" autocomplete="off" readonly value="{{$saleorder_details['saleorder_tax_amount']}}" name="saleorder_tax_amount" id="tax-amount" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Grand Total</label>
                    <input type="text" id="grand-total" value="{{$saleorder_details['saleorder_grand_total']}}" readonly name="saleorder_grand_total" class="form-control">
                  </div>
                </div>
                <div class="col-lg-12 quotationcreate-button-wrap"> 
                  <!--<button type="button" class="btn btn-primary waves-effect waves-classic">Save PDF</button>-->
                  <button type="submit" onSubmit="return validate();" class="btn btn-primary btn-data-save waves-effect waves-classic">Save</button>
                  <!--<button type="button" class="btn btn-primary waves-effect waves-classic">Cancel</button>--> 
                  <!--<button type="button" class="btn btn-primary waves-effect waves-classic">Exit</button>--> 
                  <!--<button type="button" class="btn btn-primary waves-effect waves-classic">Print</button>--> 
                  <!--<button type="button" class="btn btn-primary waves-effect waves-classic">Terms</button>--> 
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search Items</h4>
      </div>
      <div class="modal-body search-data-items">
        <form method="POST" id="reloadform" action="" class="custom-inline-form item-form-apply">
          @csrf
          <div class="new-form-box">
            <div class="form-wrap small-box">
              <label>Vendor SKU</label>
              <input class="form-control" type="text" id="vendor_sku" name="vendor_sku" value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap">
              <label>Name</label>
              <input class="form-control" type="text" id="name" name="name" value="{{@$name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Synonyms</label>
              <input class="form-control" type="text" id="synonyms" name="synonyms" value="{{@$synonyms}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Grade</label>
              <input class="form-control" type="text" id="grade" name="grade" value="{{@$grade}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Brand</label>
              <input class="form-control" type="text" id="brand" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Packing</label>
              <input class="form-control" type="text" id="packing_name" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>HSN Code</label>
              <input class="form-control" type="text" id="hsn_code" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder="">
            </div>
          </div>
          <div class="new-form-box new-form-box-radio-box">
            <div class="new-form-radio-box">
              <input type="radio" id="html" name="is_verified" @if($is_verified=='TRUE') checked @endif value="TRUE">
              <label for="html">Verified</label>
            </div>
            <div class="new-form-radio-box">
              <input type="radio" id="css" @if($is_verified=='FALSE') checked @endif name="is_verified" value="FALSE">
              <label for="css">Unverified</label>
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic order_search" type="button" name="order_search">Search</button>
            </div>
            <div class="form-wrap form-wrap-submit"> <a id="reload" href="javascript:void(0);" class="btn btn-primary reload waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
          </div>
        </form>
        <div id="data-table-result">
          <table class="table table-bordered t1" id="data-table">
            <thead>
            <th class="bg-info">Apply</th>
              <th class="bg-info">Vendor SKU</th>
              <th class="bg-info">Group Name</th>
              <th class="bg-info">HSN Code</th>
              <th class="bg-info">Grade</th>
              <th class="bg-info">Brand</th>
              <th class="bg-info">Packing Name</th>
              <th class="bg-info">List Price</th>
              <th class="bg-info">MRP</th>
              <!--<th class="bg-info">Net Rate</th>-->
              <th class="bg-info">Stock</th>
                </thead>
            <tbody id="results-pop">
            
            @if(!empty($datalist))
            @foreach($datalist as $user)
            @php
            $user = (array)$user;
            @endphp
            <tr>
              <td style="text-align:center;" class="white-space-normal"><input id="{{$user['id']}}" value="{{$user['vendor_sku']}}-{{$user['name']}}-{{$user['hsn_code']}}-{{$user['grade']}}-{{$user['brand']}}-{{$user['packing_name']}}-{{$user['list_price']}}-{{$user['mrp']}}-{{$user['net_rate']}}-{{$user['stock']}}" name="apply-radio" type="radio"></td>
              <td style="text-align:center;" class="white-space-normal">{{$user['vendor_sku']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['name']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['hsn_code']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['grade']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['brand']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['packing_name']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['list_price']}}</td>
              <td style="text-align:center;" class="white-space-normal">{{$user['mrp']}}</td>
              <!--<td style="text-align:center;" class="white-space-normal">{{$user['net_rate']}}</td>-->
              <td style="text-align:center;" class="white-space-normal">{{$user['stock']}}</td>
            </tr>
            @endforeach
            @else
            <tr>
              <td colspan="8">No record found.</td>
            </tr>
            @endif
              </tbody>
            
          </table>
        </div>
        <div class="container" style="margin-top:10px;">
          <div class="box">
            <ul id="example-2" class="pagination">
            </ul>
            <div class="show"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-apply" data-dismiss="modal">Apply</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
	 
	 var itemHtml='@foreach($items as $allitems)<option value="{{ $allitems->_id }}">{{ $allitems->article_no}}</option>@endforeach';
	 
	 for(var i = 1; i <= count_of_row; i++) {
	 rowCount++;
   if ($("input[name='is_scheduled']:checked" ).val() === "2") {
      var sch_html = '<a href="javascript:void(0);" onclick="return scheduledRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default waves-effect waves-classic" title="Scheduled Task"><i class="icon md-copy" aria-hidden="true"></i></a>';
      $type= 2;  
      }else if($("input[name='is_scheduled']:checked" ).val() === "1"){
      var sch_html = '';
      $type= 1;
      }else{
      var sch_html = '';
      $type= 0; 
      }
	    var html = '<tr id="data-row-'+rowCount+'"><td data-text="Sl" class="task_left_fix">'+rowCount+'</td><td data-text="Cust Ref No." class="task_left_fix"><input type="hidden" name="row['+rowCount+'][schedule_type]" value="'+$type+'"><input type="text" name="row['+rowCount+'][cust_ref_no]" class="autocomplete-dynamic form-control" id="cust_ref_no_rel_'+rowCount+'" required /></td><td data-text="Item" class="task_left_fix"><input type="text" name="row['+rowCount+'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'+rowCount+'" readonly required /><input type="hidden" name="row['+rowCount+'][item_id]" id="tmp_id_item_'+rowCount+'" cus="'+rowCount+'" rel="item" value="" /><small><a data-toggle="modal" cus="'+rowCount+'" class="search-pop-up small-btn" data-target="#myModal" href="javascript:void(0);">+ Add Item</a></small> </td> <td data-text="Vendor SKU"> <input type="text" name="row['+rowCount+'][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'+rowCount+'" readonly required /> </td> <td data-text="SAP Code"> <input type="text" readonly name="row['+rowCount+'][sap_code]" class="autocomplete-dynamic form-control" id="sap_code_rel_'+rowCount+'" /> </td><td data-text="HSN Code"> <input type="text" name="row['+rowCount+'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'+rowCount+'" readonly required /> </td> <td data-text="Tax"> <input type="text" name="row['+rowCount+'][tax_rate]" cus="'+rowCount+'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'+rowCount+'" required /> </td> <td data-text="Packing Name"> <input type="text" name="row['+rowCount+'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'+rowCount+'" readonly/> </td> <td data-text="List Price"> <input type="text" name="row['+rowCount+'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'+rowCount+'" readonly /> </td> <td data-text="Rate"> <input type="text" cus="'+rowCount+'" name="row['+rowCount+'][rate]" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'+rowCount+'" /> </td> <td data-text="Stock"> <input type="text" name="row['+rowCount+'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'+rowCount+'" readonly /> </td> <td data-text="MRP"> <input type="text" name="row['+rowCount+'][mrp]" cus="'+rowCount+'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'+rowCount+'" readonly /> </td> <td data-text="Dis %"> <input type="number" cus="'+rowCount+'" name="row['+rowCount+'][discount_per]" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'+rowCount+'" /> </td> <td data-text="Discount"> <input type="number" name="row['+rowCount+'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'+rowCount+'" readonly /> </td> <td data-text="Quantity"> <input type="number" name="row['+rowCount+'][quantity]" cus="'+rowCount+'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'+rowCount+'" /> </td> <td data-text="Date"> <input type="text" value="{{date('d-m-Y')}}" name="row['+rowCount+'][scheduled_date]" cus="'+rowCount+'" class="autocomplete-dynamic ss-scheduled_date form-control date_with_time small_field_custom datefield" id="scheduled_date_rel_'+rowCount+'" /> </td><td data-text="Net Rate" id="data-row-net-rate-'+rowCount+'" class="netrate" style="font-weight:bold;" align="right"></td> <td data-text="Tax Amount" id="data-row-tax-amount-'+rowCount+'" class="taxamount" style="font-weight:bold;" align="right"></td><td data-text="Amount" id="data-row-amount-'+rowCount+'" class="amount" style="font-weight:bold;" align="right"></td> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'+rowCount+'" name="row['+rowCount+'][amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'+rowCount+'" name="row['+rowCount+'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'+rowCount+'" name="row['+rowCount+'][tax_amount]" /><td>'+sch_html+'<a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';
	     $('#results').append(html);
		 
		
fixed_width();

		 
		 
		 }
     $('.date_with_time').datepicker({
    dateFormat: 'dd-mm-yy',
    changeMonth: true,
    changeYear: true,
    });
		 $(".selectsearch").select2({minimumInputLength: 2});
		 
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
    //if(row!=1){
    $('#data-row-'+row).remove();
		calculateAllUnits(row);
	//}
  }
  
  function removeRowAjax(row,id)
  {
   $('#data-row-'+row).remove();
   calculateAllUnits(row);  
   if(id!='')
   {
        $.ajax({
        type: "GET",
        url: "{{ url('admin/ajax_order_item_delete')}}/"+id,
        data: {id:id},
        success: function(msg){
        }
        });   
   }
  }
  
  </script> 
<script>
       $(function(){
		  $(document).on('change','#customer_name',function(){
			 var customer_id = $(this).val();
			 
			 //alert(customer_id); return false;
			 
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
					//console.log(msg); return false;
						//$('#customer_address').val(data['customer_delivery_address']);
						$('#customer_address').html(data['customer_delivery_address']);
						$('#customer_country').val(data['customer_country']);
						$('#customer_city').val(data['customer_city']);
						$('#customer_state').val(data['customer_state']);
						$('#customer_contact_person').val(data['customer_contact_person']);
						$('#customer_phone').val(data['customer_phone']);
						$('#gst_no').val(data['gst']);
						$('#crm_assign').val(data['crm_assign']);
						$('#merchant_name').val(data['merchant_name']);
						$('#payment_term option[value="'+data['payment_term']+'"]').attr('selected','selected');
						
						$('#sales_agent option[value="'+data['agent']+'"]').attr('selected','selected');
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
				
				$('#sales_agent').val('');
				$('#sales_agent option[value=""]').attr('selected','selected');
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
							$('#buyer_country').val(data['buyer_country']);
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
	  
		$( "select[name='order_type']" ).on('change', function(){
		   if($(this).val()=='Sampling'){
			   $('#order_sampling_type').val(40);
			   $('#order_sampling_type').focus();
		   }else{
			   $('#order_sampling_type').val('');
			   $('#order_sampling_type').focus();
		   }
		   
		})
	  
     
  });
  </script> 
<script>
		$(function(){
				$(document).on('keyup mouseup change click','.ss-qty,.tax_rate,.item-rate,.item-disc',function(){
				    var id = $(this).attr('cus');
					calculateAllUnits(id);
				});
		});
		
		function calculateAllUnits(id){
            var mrp = $('#mrp_rel_'+id).val();
            var qty = $('#quantity_rel_'+id).val();
            var rate = isNaN($('#rate_rel_'+id).val()) ? 0 : $('#rate_rel_'+id).val();
            var discount_per = isNaN($('#discount_per_rel_'+id).val()) ? 0 : $('#discount_per_rel_'+id).val();
            var tax = isNaN($('#tax_rate_rel_'+id).val()) ? 0 : $('#tax_rate_rel_'+id).val();
            var total_disc = eval((eval(rate)*eval(discount_per))/100);
            $('#discount_rel_'+id).val(eval(total_disc).toFixed(2));
            var discount = isNaN($('#discount_rel_'+id).val()) ? 0 : $('#discount_rel_'+id).val();
            var net_rate_amount = isNaN(eval((eval(rate)-eval(discount))*qty)) ? 0 : eval((eval(rate)-eval(discount))*qty);
            var amount = eval((rate-discount)*qty);
            var tax_amount = eval((amount*tax)/100);
            var final_amount = amount+tax_amount;
            
            if(qty!='' && mrp!='')
            {
            $('#data-row-amount-'+id).html(eval(final_amount).toFixed(2));
            $('#data-row-tax-amount-'+id).html(eval(tax_amount).toFixed(2));
            $('#tmp_id_amount_'+id).val(eval(final_amount).toFixed(2));
            $('#tmp_id_net_rate_'+id).val(eval(net_rate_amount).toFixed(2));
            $('#tmp_id_tax_amount_'+id).val(eval(tax_amount).toFixed(2));
            $('#data-row-net-rate-'+id).html(eval(net_rate_amount).toFixed(2));
            }else{
            $('#data-row-amount-'+id).html(eval(final_amount).toFixed(2));
            $('#data-row-tax-amount-'+id).html(eval(tax_amount).toFixed(2));
            $('#tmp_id_amount_'+id).val(eval(final_amount).toFixed(2));
            $('#tmp_id_net_rate_'+id).val(eval(net_rate_amount).toFixed(2));
            $('#tmp_id_tax_amount_'+id).val(eval(tax_amount).toFixed(2));
            $('#data-row-net-rate-'+id).html(eval(net_rate_amount).toFixed(2));
            }
            var subTotal = 0;
            $(".netrate").each(function () {
            var stval = parseFloat($(this).html());
            subTotal += isNaN(stval) ? 0 : stval;
            });
            $('#sub-total').val(subTotal.toFixed(2));
            
            
            var grand_total_amount = 0;
            $(".amount").each(function () {
            var stval = parseFloat($(this).html());
            grand_total_amount += isNaN(stval) ? 0 : stval;
            });
            
            
            var grand_tax_amount = 0;
            $(".taxamount").each(function () {
            var stval = parseFloat($(this).html());
            grand_tax_amount += isNaN(stval) ? 0 : stval;
            });
            $('#tax-amount').val(grand_tax_amount.toFixed(2));
            
            var subTotal_other = 0;
            $(".ch-val").each(function () {
            var ch_val = parseFloat($(this).html());
            subTotal_other += isNaN(ch_val) ? 0 : ch_val;
            });
            
            var sal_tax = isNaN(parseFloat($('#sale-tax').val())) ? 0 : parseFloat($('#sale-tax').val());
            
            var tax_amount = isNaN(parseFloat($('#tax-amount').val())) ? 0 : parseFloat($('#tax-amount').val());
            //alert(grand_total_amount+"-"+subTotal_other+"-"+sal_tax+"-"+tax_amount);
            var total = parseFloat(grand_total_amount+subTotal_other+sal_tax);
            $('#grand-total').val(total.toFixed(2));
		}
	</script> 
@endsection
@section('custom_validation_script') 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> 
<script>
$(document).ready(function() {
    
    $.validator.addMethod("notEqualToGroup", function (value, element, options) {
        var elems = $(element).parents('form').find(options[0]);
        var valueToCompare = value;
        var matchesFound = 0;
        $.each(elems, function () {
            thisVal = $(this).val();
            if (thisVal == valueToCompare) {
                matchesFound++;
            }
        });
        if (this.optional(element) || matchesFound <= 1) {
            return true;
        } 
    },"Please enter a Unique Value.");
    
    $("#invoiceform").validate({
    //     rules: {
    //     cust_ref_no : {
    //         required : true,
    //         notEqualToGroup: ['.cust_ref_no']
    //     }
    // }
        
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
		//$('#customer_name').val('<?php echo $saleorder_details['customer_name']; ?>');
		$('#buyer_name').html(buyerHtml);
		//console.log(buyerHtml);
	});
</script> 
<script>
    $(function(){
        
        $(document).on('click','.order_search',function(){
                $('#loader').show();
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                var formulario =  $(".item-form-apply");
                    $.ajax({
                    url: "{{ url('admin/so_net_rate_search') }}" ,
                    type: "POST",
                    data: formulario.serialize(),
                    success: function( response ) {
                    //alert(response); return false;

                    if(response!='')
                    {
                    $('#loader').hide();
                    var returnedData = JSON.parse(response);
                   $('#data-table-result').html(returnedData.html);
                    
                    //alert(returnedData.record_count);
                    $('#example-2').pagination({
                    total: returnedData.record_count, 
                    current: 1,
                    length: 10,
                    size: 2,
                    prev: 'Previous',
                    next: 'Next',
                    click: function(options, refresh, $target){
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var vendor_sku = $("#vendor_sku").val();
                    var name = $("#name").val();
                    var synonyms = $("#synonyms").val();
                    var grade = $("#grade").val();
                    var brand = $("#brand").val();
                    var packing_name = $("#packing_name").val();
                    var hsn_code = $("#hsn_code").val();
                    if($('input[name="is_verified"]').is(':checked'))
                    {
                    var is_verified = $('input[name="is_verified"]').val();
                    }else
                    {
                    var is_verified = '';
                    }
                    
                    $.ajax({
                    url: "{{ url('admin/so_net_rate_search') }}" ,
                    type: "POST",
                    data: {page:options.current,brand:brand,vendor_sku:vendor_sku,name:name,synonyms:synonyms,grade:grade,packing_name:packing_name,hsn_code:hsn_code,is_verified:is_verified},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    var returnedData = JSON.parse(response);
                    $('#data-table-result').html(returnedData.html);  
                    }
                    }
                    }); 
                    }
                    });   
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('.btn-apply').show();
                    }else{
                   $('#data-table-result').html(''); 
                   //$('#data-table').DataTable({searching: false,lengthChange: false,pageLength: 10});
                   $('#loader').hide();
                   $('.btn-apply').hide();
                    }
                    }
                    }); 
        });
    });
</script> 
<script>
    $(function(){
        $(document).on('click','#reload',function(){
            //alert();
           $('#reloadform')[0].reset();
           $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
    size: 2,
    prev: 'Previous',
    next: 'Next',
    click: function(options, refresh, $target){
        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
            url: "{{ url('admin/so_net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
           $('.order_search').trigger('click');
        });
    });
</script> 
<script>
    $(function(){
       $(document).on('click','.search-pop-up',function(){
          var id = $(this).attr('cus'); 
          $('.btn-apply').prop('id',id);
       }); 
    });
</script> 
<script>
    $(document).ready(function(){
        $(".btn-apply").click(function(){
            var id_data = $(this).attr('id');
            var radioValue = $("input[name='apply-radio']:checked").val();
            var customer_name = $('#customer_name').val();
           var id = $("input[name='apply-radio']:checked").attr('id');
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
           if(id!='')
           {
            $.ajax({
            url: "{{ url('admin/item_search_data_so') }}" ,
            type: "POST",
            data: {customer_name:customer_name,id:id},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            
            $.ajax({
            url: "{{ url('admin/item_search_data_saleorder') }}" ,
            type: "POST",
            data: {customer_name:customer_name,id:id},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            var data_ajax = jQuery.parseJSON(response);
            //var html = '<strong>Cust. Ref No : </strong>'+NullCheck(data_ajax.quotation_ref_no)+', <strong>Sr No : </strong>'+NullCheck(data_ajax.SrNo)+', <strong>Quotation Till Date : </strong>'+NullCheck(data_ajax.created_date);
            var html = '<strong>Last Rate : </strong>'+NullCheck(data_ajax.rate);
            ('#ajax-cust').html(html);
            }
            }
            });
                
            var data = jQuery.parseJSON(response);
            $('#tmp_id_item_'+id_data).val(data.id);
            $('#itemrel_'+id_data).val(data.name+'-'+data.brand);
            $('#vendor_sku_rel_'+id_data).val(data.vendor_sku);
            $('#hsn_code_rel_'+id_data).val(data.hsn_code);
            $('#tax_rate_rel_'+id_data).val(data.tax_rate);
            $('#grade_rel_'+id_data).val(data.grade);
            $('#brand_rel_'+id_data).val(data.brand);
            $('#packing_name_rel_'+id_data).val(data.packing_name);
            $('#list_price_rel_'+id_data).val(data.list_price);
            $('#stock_rel_'+id_data).val(data.stock);
            $('#mrp_rel_'+id_data).val(data.mrp);
            $('#rate_rel_'+id_data).val(data.net_rate);
            $('#discount_per_rel_'+id_data).val(data.dis_per);
            $('#discount_rel_'+id_data).val(data.total_disc);
            $('#sap_code_rel_'+id_data).val(data.sap_code);
            $('#loader').hide();
            }
            }
            });    
           }
        });
    });
</script> 
<script>
    function removeOther(m)
    {
        $('#san-'+m).remove();
        var subTotal_other = 0;
        $(".ch-val").each(function () {
        var ch_val = parseFloat($(this).html());
        subTotal_other += isNaN(ch_val) ? 0 : ch_val;
        });
        $('#total-other').html(subTotal_other);
        var grand_total = $('#sub-total').val();
            if(grand_total!='')
            {
                var grand_total_check = grand_total;
            }else{
                var grand_total_check = 0;
            }
            //alert(grand_total_check);
            
             var sal_tax = isNaN(parseFloat($('#sale-tax').val())) ? 0 : parseFloat($('#sale-tax').val());
        
        var tax_amount = isNaN(parseFloat($('#tax-amount').val())) ? 0 : parseFloat($('#tax-amount').val());
            
            
            var total = parseFloat(grand_total_check)+parseFloat(subTotal_other)+parseFloat(sal_tax)+parseFloat(tax_amount);
            if(grand_total!=''){
		    $('#grand-total').val(total.toFixed(2));
            }
    }
</script> 
<script>
    $(function(){
        $(document).on('keyup mouseup change click','#sale-tax',function(){
            var subTotal_other = 0;
        $(".ch-val").each(function () {
        var ch_val = parseFloat($(this).html());
        subTotal_other += isNaN(ch_val) ? 0 : ch_val;
        });
        $('#total-other').html(subTotal_other);
        var grand_total = $('#sub-total').val();
            if(grand_total!='')
            {
                var grand_total_check = grand_total;
            }else{
                var grand_total_check = 0;
            }
            //alert(grand_total_check);
            
             var sal_tax = isNaN(parseFloat($('#sale-tax').val())) ? 0 : parseFloat($('#sale-tax').val());
        
        var tax_amount = isNaN(parseFloat($('#tax-amount').val())) ? 0 : parseFloat($('#tax-amount').val());
            
            
            var total = parseFloat(grand_total_check)+parseFloat(subTotal_other)+parseFloat(sal_tax)+parseFloat(tax_amount);
            if(grand_total!=''){
		    $('#grand-total').val(total.toFixed(2));
            }
        });
    });
</script> 
<script>
    $(function(){
        $(document).on('keyup mouseup change click','#tax-amount',function(){
            var subTotal_other = 0;
        $(".ch-val").each(function () {
        var ch_val = parseFloat($(this).html());
        subTotal_other += isNaN(ch_val) ? 0 : ch_val;
        });
        $('#total-other').html(subTotal_other);
        var grand_total = $('#sub-total').val();
            if(grand_total!='')
            {
                var grand_total_check = grand_total;
            }else{
                var grand_total_check = 0;
            }
            //alert(grand_total_check);
            
             var sal_tax = isNaN(parseFloat($('#sale-tax').val())) ? 0 : parseFloat($('#sale-tax').val());
        
        var tax_amount = isNaN(parseFloat($('#tax-amount').val())) ? 0 : parseFloat($('#tax-amount').val());
            
            
            var total = parseFloat(grand_total_check)+parseFloat(subTotal_other)+parseFloat(sal_tax)+parseFloat(tax_amount);
            if(grand_total!=''){
		    $('#grand-total').val(total.toFixed(2));
            }
        });
    });
</script> 
<script>
    $(function(){
        var i=1;
       $(document).on('click','.add-other',function(){
           i++;
             var charge_other = $('.charge-other').val();
             var charges_val = $('.charges-val').val();
             if(charge_other!='' && charges_val!=''){
             var html ="<tr id='san-"+i+"'><td>"+charge_other+"</td><td class='ch-val'>"+charges_val+"</td><td><a href='javascript:void(0);' onclick='return removeOther("+i+")' class='btn btn-danger waves-effect waves-classic'><i class='icon md-minus' style='margin:0;'></i></a></td><input type='hidden' name='other_charges_name[]' value='"+charge_other+"'><input type='hidden' name='other_charges_val[]' value='"+charges_val+"' ></tr>";
            $('#results-charges').before(html);
            $('.charge-other').val('');
            $('.charges-val').val('');
            var subTotal_other = 0;
            $(".ch-val").each(function () {
            var ch_val = parseFloat($(this).html());
            subTotal_other += isNaN(ch_val) ? 0 : ch_val;
            });
            
            var grand_total = $('#sub-total').val();
            if(grand_total!='')
            {
                var grand_total_check = grand_total;
            }else{
                var grand_total_check = 0;
            }
            //alert(grand_total_check);
            
            
            var sal_tax = isNaN(parseFloat($('#sale-tax').val())) ? 0 : parseFloat($('#sale-tax').val());
        
        var tax_amount = isNaN(parseFloat($('#tax-amount').val())) ? 0 : parseFloat($('#tax-amount').val());
            
            
            var total = parseFloat(grand_total_check)+parseFloat(subTotal_other)+parseFloat(sal_tax)+parseFloat(tax_amount);
            if(grand_total!=''){
		    $('#grand-total').val(total.toFixed(2));
            }
            $('#total-other').html(subTotal_other);
             }
       });
       //$(":input:not([name=customer_name])").prop("disabled", true);
    });
</script> 
<script>
    $(function(){
       $(document).on('change','#customer_name',function(){
          var id = $(this).val();
          if(id!='')
          {
          $(":input:not([name=contact])").prop("disabled", false);   
          $(".search-pop-up").attr("data-target","#myModal");
          }else{
          $(":input:not([name=customer_name])").prop("disabled", true); 
          $(".search-pop-up").removeAttr("data-target");
          }
       }); 
    });
    
    function NullCheck(d)
    {
        if(d===null)
        {
            return '';
        }else{
            return d;
        }
    }
</script> 
<script>
    $(function(){
       $(window).on('load',function(){
          var saleorder_type = $('#saleorder_type').val();
          var saleorder_srno = $('#saleorder_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#saleorder_no').val(saleorder_type+saleorder_srno);
       }); 
    });
    $(function(){
       $(document).on('change','#saleorder_type',function(){
          var saleorder_type = $('#saleorder_type').val();
          var saleorder_srno = $('#saleorder_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#saleorder_no').val(saleorder_type+saleorder_srno);
       }); 
    });
    $(function(){
       $(document).on('keyup','#saleorder_srno',function(){
          var saleorder_type = $('#saleorder_type').val();
          var saleorder_srno = $('#saleorder_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#saleorder_no').val(saleorder_type+saleorder_srno);
       }); 
    });
</script> 
<script>
    $(function(){
        $(document).on('change','#saleorder_status',function(){
           var status = $(this).val();
           if(status=='Approved')
           {
               $('#saleorder_approved').prop('checked',true);
           }else{
               $('#saleorder_approved').prop('checked',false);
           }
        });
    });
</script> 


<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="https://dev-demo.info/antyafms/theme/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
<script>
$('.date_with_time').datepicker({
dateFormat: 'dd-mm-yy',
changeMonth: true,
changeYear: true,
});
</script>
<link rel="stylesheet" type="text/css" href="{{ url('css/pagination.min.css')}}">
<script src="{{ url('js/pagination.js')}}"></script> 
<script>
    $('#example-2').pagination({
    total: {{$itemlist_count}}, 
    current: 1,
    length: 10,
    size: 2,
    prev: 'Previous',
    next: 'Next',
    click: function(options, refresh, $target){
        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
            url: "{{ url('admin/so_net_rate_search') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              var returnedData = JSON.parse(response);
              $('#data-table-result').html(returnedData.html);  
            }
            }
            }); 
    }
});
</script> 
<script>
$('#saleorder_ref_no').keypress(function (e) {
    var allowedChars = new RegExp("^[0-9\-\/\A-Z\a-z]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (allowedChars.test(str)) {
        return true;
    }
    e.preventDefault();
    return false;
}).keyup(function() {
    // the addition, which whill check the value after a keyup (triggered by Ctrl+V)
    // We take the same regex as for allowedChars, but we add ^ after the first bracket : it means "all character BUT these"
    var forbiddenChars = new RegExp("[^0-9\-\/\A-Z\a-z]", 'g');
    if (forbiddenChars.test($(this).val())) {
        $(this).val($(this).val().replace(forbiddenChars, ''));
    }
});
</script><script>
    $(function(){
       $('#saleorder_ref_no').blur(function(){
           var saleorder_ref_no = $(this).val();
           var saleorder_id = '{{$saleorder_details['_id']}}';
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
                url: "{{ url('admin/saleorder_ref_no_search') }}" ,
                type: "POST",
                data: {saleorder_ref_no:saleorder_ref_no,saleorder_id:saleorder_id},
                success: function( response ) {
                //alert(response); return false;
                if(response==1)
                {
                $('#saleorder_ref_error').show();
                $('#saleorder_ref_no').focus(); 
                return false;
                }else{
                $('#saleorder_ref_error').hide();    
                }
                }
                });
       }); 
    });
</script> 

<script>
    $('input[name="is_recurring"]').click(function(){
   if ($("input[name='is_recurring']:checked" ).val() === "1") {
            $('.recurring_type').show();
            $('#result-recurring-type').show();
      } else {
        $('.recurring_type').hide();  
        $('#result-recurring-type').hide();
      }
});
</script>
<script>
  $(function(){
   $(document).on('change','#recurring_type',function(){
           var id = $(this).val();
		   if(id!='')
           {
		   $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
            url: "{{ url('admin/get-recurring-type-data') }}" ,
            type: "POST",
            data: {id:id},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            $('#result-recurring-type').html(response);
            }else{
            $('#result-recurring-type').html('');
            }
            }
            });
		   }else{
            $('#result-recurring-type').html('');
            }
		   });
  });
</script>
<script>
  function scheduledRow(m)
  {
       var cust_ref_no_rel    = $('#cust_ref_no_rel_'+m).val();
       var itemrel            = $('#itemrel_'+m).val();
       var tmp_id_item        = $('#tmp_id_item_'+m).val();
       var vendor_sku_rel     = $('#vendor_sku_rel_'+m).val();
       var sap_code_rel       = $('#sap_code_rel_'+m).val();
       var hsn_code_rel       = $('#hsn_code_rel_'+m).val();
       var tax_rate_rel       = $('#tax_rate_rel_'+m).val();
       var packing_name_rel   = $('#packing_name_rel_'+m).val();
       var list_price_rel     = $('#list_price_rel_'+m).val();
       var rate_rel           = $('#rate_rel_'+m).val();
       var stock_rel          = $('#stock_rel_'+m).val();
       var mrp_rel            = $('#mrp_rel_'+m).val();
       var discount_per_rel   = $('#discount_per_rel_'+m).val();
       var discount_rel       = $('#discount_rel_'+m).val();
       var quantity_rel       = $('#quantity_rel_'+m).val();
       var scheduled_date_rel = $('#scheduled_date_rel_'+m).val();
       var data_row_net_rate  = $('#data-row-net-rate-'+m).html();
       var data_row_tax_amount= $('#data-row-tax-amount-'+m).html();
       var data_row_amount    = $('#data-row-amount-'+m).html();
       var data_row_amount    = $('#data-row-amount-'+m).html();
       var tmp_id_net_rate    = $('#tmp_id_net_rate_'+m).val();
       var tmp_id_tax_amount  = $('#tmp_id_tax_amount_'+m).val();
       var tmp_id_amount      = $('#tmp_id_amount_'+m).val();

       var rowCount = $('#editableTable1 >tbody >tr').length;
       var element = $('#data-row-'+m).closest('tr');
       var sch_html = '<span style="width:41px; display:inline-block;"></span>';
	     $('<tr id="data-row-'+(rowCount+1)+'"><td class="pur-san1 task_left_fix" data-text="Sl"></td><td data-text="Cust Ref No" class="task_left_fix"><input type="hidden" name="row['+(rowCount+1)+'][schedule_type]" value="0"><input type="text" name="row['+(rowCount+1)+'][cust_ref_no]" class="autocomplete-dynamic form-control" value="'+cust_ref_no_rel+'" id="cust_ref_no_rel_'+(rowCount+1)+'" required /></td><td data-text="Item" class="task_left_fix"><input type="text" name="row['+(rowCount+1)+'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'+(rowCount+1)+'" value="'+itemrel+'" readonly required /><input type="hidden" name="row['+(rowCount+1)+'][item_id]" id="tmp_id_item_'+(rowCount+1)+'" cus="'+(rowCount+1)+'" rel="item" value="'+tmp_id_item+'" /></td> <td data-text="Vendor SKU"> <input type="text" name="row['+(rowCount+1)+'][vendor_sku]" value="'+vendor_sku_rel+'" class="autocomplete-dynamic form-control" id="vendor_sku_rel_'+(rowCount+1)+'" readonly required /> </td> <td data-text="SAP Code"> <input type="text" readonly name="row['+(rowCount+1)+'][sap_code]" class="autocomplete-dynamic form-control" value="'+sap_code_rel+'" id="sap_code_rel_'+(rowCount+1)+'" /> </td><td data-text="HSN Code"> <input type="text" name="row['+(rowCount+1)+'][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_'+(rowCount+1)+'" readonly required value="'+hsn_code_rel+'" /> </td> <td data-text="Tax"> <input type="text" name="row['+(rowCount+1)+'][tax_rate]" cus="'+(rowCount+1)+'" class="autocomplete-dynamic tax_rate form-control" readonly id="tax_rate_rel_'+(rowCount+1)+'" required value="'+tax_rate_rel+'" /> </td> <td data-text="Packing Name"> <input type="text" name="row['+(rowCount+1)+'][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_'+(rowCount+1)+'" readonly value="'+packing_name_rel+'"/> </td> <td data-text="List Price"> <input type="text" name="row['+(rowCount+1)+'][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_'+(rowCount+1)+'" readonly value="'+list_price_rel+'" /> </td> <td data-text="Rate"> <input type="text" cus="'+(rowCount+1)+'" name="row['+(rowCount+1)+'][rate]" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'+(rowCount+1)+'" value="'+rate_rel+'" readonly /> </td> <td data-text="Stock"> <input type="text" name="row['+(rowCount+1)+'][stock]" class="autocomplete-dynamic form-control" id="stock_rel_'+(rowCount+1)+'" readonly value="'+stock_rel+'" /> </td> <td data-text="MRP"> <input type="text" name="row['+(rowCount+1)+'][mrp]" cus="'+(rowCount+1)+'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'+(rowCount+1)+'" readonly value="'+mrp_rel+'" /> </td> <td data-text="Dis %"> <input type="number" cus="'+(rowCount+1)+'" name="row['+(rowCount+1)+'][discount_per]" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'+(rowCount+1)+'" value="'+discount_per_rel+'" readonly /> </td> <td data-text="Discount"> <input type="number" name="row['+(rowCount+1)+'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'+(rowCount+1)+'" readonly value="'+discount_rel+'" /> </td> <td data-text="Quantity"> <input type="number" name="row['+(rowCount+1)+'][quantity]" cus="'+(rowCount+1)+'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'+(rowCount+1)+'" value="'+quantity_rel+'" /> </td> <td data-text="Date"> <input type="text" value="'+scheduled_date_rel+'" name="row['+(rowCount+1)+'][scheduled_date]" cus="'+(rowCount+1)+'" class="autocomplete-dynamic ss-scheduled_date form-control date_with_time small_field_custom datefield" id="scheduled_date_rel_'+(rowCount+1)+'" /> </td> <td data-text="Net Rate" id="data-row-net-rate-'+(rowCount+1)+'" class="netrate" style="font-weight:bold;" align="right">'+data_row_net_rate+'</td> <td data-text="Tax Amount" id="data-row-tax-amount-'+(rowCount+1)+'" class="taxamount" style="font-weight:bold;" align="right">'+data_row_tax_amount+'</td><td data-text="Amount" id="data-row-amount-'+(rowCount+1)+'" class="amount" style="font-weight:bold;" align="right">'+data_row_amount+'</td> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'+(rowCount+1)+'" name="row['+(rowCount+1)+'][amount]" value="'+tmp_id_amount+'" /> <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'+(rowCount+1)+'" name="row['+(rowCount+1)+'][net_rate]" value="'+tmp_id_net_rate+'" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'+(rowCount+1)+'" name="row['+(rowCount+1)+'][tax_amount]" value="'+tmp_id_tax_amount+'" /><td>'+sch_html+'<a href="javascript:void(0);" onclick="return removeRow('+(rowCount+1)+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>').insertAfter(element);
	     var tbl_length = $('#editableTable1 >tbody >tr').length;
        $('.date_with_time').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        });
  }
</script>
@endsection 