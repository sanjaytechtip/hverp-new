@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'View Sale Order')
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
</style>
<div class="page creat-fms-box">
  <div class="page-header">
    <h1 class="page-title">{{ __('View Sale Order') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('order_to_dispatch') }}">Order to Dispatch : List</a></li>
      <li class="breadcrumb-item">{{ __('View Sale Order') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
        <div class="col-sm-6">
          <div class="counter"> <span class="counter-number font-weight-medium">DATE</span>
            <div class="counter-label">{{ date('d-m-Y H:i:s',strtotime($saleorder_details['created_at'])) }}</div>
          </div>
        </div>
        @if(!empty($saleorder_details_revised))
        <div class="col-sm-6">
          <div class="counter"> <span class="counter-number font-weight-medium">Revise Sale Order</span>
            <div class="counter-label">
              <select class="form-control" id="saleorder_revised">
                <option>-- Select Sale Order --</option>
                
                
            @php
            $i=1;
            @endphp
            @foreach($saleorder_details_revised as $revised)
            
                
                <option value="{{$revised['id'].'/'.$saleorder_details['id']}}">Revise {{$i}}, {{date('d-m-Y H:i A',strtotime($revised['revised_date']))}}</option>
                
                
            @php
            $i++;
            @endphp
            @endforeach
            
              
              </select>
            </div>
          </div>
        </div>
        @endif </div>
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
                </div>
                <div class="row form-three-box">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <label>Type</label>
                    <input type="text" autocomplete="off" readonly value="{{ $saleorder_details['saleorder_type']}}" class="form-control">
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
                @endif </div>
              <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="row form-three-box">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <label>SO. Date</label>
                    <input type="text" autocomplete="off" readonly id="saleorder_date" value="{{globalDateformatNet($saleorder_details['saleorder_date'])}}" required class="form-control small_field_custom" name="saleorder_date">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Delivery Date</label>
                    <input type="text" autocomplete="off" readonly id="saleorder_due_date" value="@if($saleorder_details['saleorder_due_date']!='1970-01-01'){{ globalDateformatNet($saleorder_details['saleorder_due_date'])}} @endif" class="form-control small_field_custom" name="saleorder_due_date">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Ref. Date</label>
                    <input type="text" autocomplete="off" readonly id="saleorder_ref_date" value="@if($saleorder_details['saleorder_ref_date']!='1970-01-01'){{ globalDateformatNet($saleorder_details['saleorder_ref_date'])}} @endif" class="form-control small_field_custom" name="saleorder_ref_date">
                  </div>
                </div>
                <div class="form-new-box">
                  <label>Customer Ref No.</label>
                  <input name="saleorder_ref_no" readonly required autocomplete="off" value="{{$saleorder_details['saleorder_ref_no']}}" class="form-control" type="text" >
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 ">
                <div class="form-new-box">
                  <label>Remarks</label>
                  <textarea name="saleorder_remarks" readonly class="form-control">{{$saleorder_details['saleorder_remarks']}}</textarea>
                </div>
                <div class="row form-two-box">
                  <div class="col-lg-6 col-md-6 col-sm-12 ">
                    <label>Priority</label>
                    <input type="text" autocomplete="off" readonly value="{{ $saleorder_details['saleorder_priority']}}" class="form-control">
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12 ">
                    <label>Satus</label>
                    <input readonly required autocomplete="off" value="{{$saleorder_details['saleorder_status']}}" class="form-control" type="text" >
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 recurring-order">
    <label class="col-sm-12 col-sm-12 control-label checkbox-wrap">
    <input type="checkbox" disabled @if($saleorder_details['is_recurring']==1) checked @endif name="is_recurring" id="is_recurring" value="1">
    <div class="check-box-wrap"> Recurring Order?
    <small>Recurring Orders re-occur on certain days or dates of every week, month or year. </small>
    </div>
    </label>
    <div class="row form-three-box">

    <div class="col-lg-4 col-md-4 col-sm-12 recurring_type" @if($saleorder_details['recurring_type']=='' || $saleorder_details['recurring_type']==0) style="display:none;" @endif >
    <select name="recurring_type" readonly id="recurring_type" required class="form-control">
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
                <input type="checkbox" disabled @if($saleorder_details['is_scheduled']==2) checked @endif name="is_scheduled" id="is_scheduled" value="2">
                <div class="check-box-wrap"> Scheduled Order?</div>
                </label> 
              </div>
            </div>
            <div class="purchaseorder-box quotationcreate-box-wrap">
              <hr />
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
                      <th width="40" class="net-rate-th">Net Rate</th>
                      <th width="40" class="tax-amount-th">Tax Amount</th>
                      <th width="40" class="amount-th">Amount</th>
                    </tr>
                  </thead>
                  <tbody id="results">
                  
                  @php
                  $i = 1;
                  @endphp
                  @foreach($saleorder_item_details as $key=>$item_details)
                  <tr id="data-row-{{ $i }}">
                    <td data-text="Sl" class="task_left_fix">{{ $i }}</td>
                    <td data-text="Cust Ref No." class="task_left_fix"><input type="text" readonly value="{{$item_details['cust_ref_no']}}" name="row[{{$i}}][cust_ref_no]" class="autocomplete-dynamic cust_ref_no form-control" id="cust_ref_no_rel_{{$i}}" required /></td>
                    <td data-text="Item" class="task_left_fix"><input type="text" readonly value="{{$item_details['item_name']}}" name="row[{{$i}}][item_name]" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
                      <input type="hidden" name="row[{{$i}}][item_id]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{$item_details['item_id']}}" /></td>
                    <td data-text="Vendor SKU"><input type="text" readonly value="{{$item_details['vendor_sku']}}" name="row[{{$i}}][vendor_sku]" class="autocomplete-dynamic form-control" id="vendor_sku_rel_{{$i}}" required /></td>
                    <td data-text="HSN Code"><input type="text" readonly value="{{$item_details['hsn_code']}}" name="row[{{$i}}][hsn_code]" class="autocomplete-dynamic form-control" id="hsn_code_rel_{{$i}}" required /></td>
                    <td data-text="Tax"><input type="text" readonly value="{{$item_details['tax_rate']}}" name="row[{{$i}}][tax_rate]" cus="{{$i}}" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_{{$i}}" required /></td>
                    <td data-text="Packing Name"><input type="text" readonly value="{{$item_details['packing_name']}}" name="row[{{$i}}][packing_name]" class="autocomplete-dynamic form-control" id="packing_name_rel_{{$i}}" /></td>
                    <td data-text="List Price"><input type="text" readonly value="{{$item_details['list_price']}}" name="row[{{$i}}][list_price]" class="autocomplete-dynamic form-control" id="list_price_rel_{{$i}}" /></td>
                    <td data-text="Rate"><input type="text" readonly value="{{$item_details['rate']}}" name="row[{{$i}}][rate]" cus="{{$i}}" class="autocomplete-dynamic item-rate form-control" id="rate_rel_{{$i}}" /></td>
                    <td data-text="Stock"><input type="text" readonly value="{{$item_details['stock']}}" readonly name="row[{{$i}}][stock]" class="autocomplete-dynamic form-control" id="stock_rel_{{$i}}" /></td>
                    <td data-text="MRP"><input type="text" readonly value="{{$item_details['mrp']}}" name="row[{{$i}}][mrp]" cus="{{$i}}" class="autocomplete-dynamic form-control mrp" id="mrp_rel_{{$i}}" /></td>
                    <td data-text="Dis %"><input type="number" readonly value="{{$item_details['discount_per']}}" name="row[{{$i}}][discount_per]" cus="{{$i}}" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_{{$i}}" /></td>
                    <td data-text="Discount"><input type="number" readonly value="{{$item_details['discount']}}" name="row[{{$i}}][discount]" class="autocomplete-dynamic form-control" id="discount_rel_{{$i}}" /></td>
                    <td data-text="Quantity"><input type="number" readonly value="{{$item_details['quantity']}}" name="row[{{$i}}][quantity]" cus="{{$i}}" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_{{$i}}" /></td>
                    <td data-text="Net Rate" id="data-row-net-rate-{{ $i }}" class="netrate" style="font-weight:bold;" align="right">{{$item_details['net_rate']}}</td>
                    <td data-text="Tax Amount" id="data-row-tax-amount-{{ $i }}" class="taxamount" style="font-weight:bold;" align="right">{{$item_details['tax_amount']}}</td>
                    <td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">{{$item_details['amount']}}</td>
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
                    <div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>
                  </div>
                </div>
              </div>
              <div class="row quotationcreate-wrap">
                <div class="col-lg-6 col-md-6">
                  <h2>Other Charges</h2>
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
                        <td>&nbsp;</td>
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
                    <input type="text" readonly value="{{$saleorder_details['saleorder_contact_name']}}" required name="saleorder_contact_name" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Department</label>
                    <input type="text" readonly value="{{$saleorder_details['saleorder_contact_department']}}" autocomplete="off" id="saleorder_contact_department" name="saleorder_contact_department" required class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Phone</label>
                    <input type="text" readonly value="{{$saleorder_details['saleorder_contact_phone']}}" required name="saleorder_contact_phone" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Email</label>
                    <input type="email" readonly value="{{$saleorder_details['saleorder_contact_email']}}" required name="saleorder_contact_email" class="form-control">
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
                    <input type="text" readonly autocomplete="off" value="{{$saleorder_details['saleorder_saletax']}}" name="saleorder_saletax" id="sale-tax" class="form-control">
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
                  <a href="{{route('order_to_dispatch')}}" class="btn btn-primary btn-data-save waves-effect waves-classic">Back</a> 
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
              <input class="form-control" type="text" name="vendor_sku" value="{{@$vendor_sku}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap">
              <label>Name</label>
              <input class="form-control" type="text" name="name" value="{{@$name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Synonyms</label>
              <input class="form-control" type="text" name="synonyms" value="{{@$synonyms}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Grade</label>
              <input class="form-control" type="text" name="grade" value="{{@$grade}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Brand</label>
              <input class="form-control" type="text" name="brand" value="{{@$brand}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>Packing</label>
              <input class="form-control" type="text" name="packing_name" value="{{@$packing_name}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap small-box">
              <label>HSN Code</label>
              <input class="form-control" type="text" name="hsn_code" value="{{@$hsn_code}}" autocomplete="off" placeholder="">
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
        <table class="table table-bordered t1">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-apply" data-dismiss="modal">Apply</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('custom_validation_script') 
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
		$('#customer_name').val('<?php echo $saleorder_details['customer_name']; ?>');
		$('#buyer_name').html(buyerHtml);
		//console.log(buyerHtml);
	});
</script> 
<script>
    $(function(){
       $(document).on('change','#saleorder_revised',function(){
          var id = $(this).val();
          var url = '{{url("admin/saleorder-revised/")}}';
          window.open(url+'/'+id, 'Sale Order'); 
       }); 
    });
</script> 

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="https://dev-demo.info/antyafms/theme/require/jquery-ui-timepicker-addon.css" />
<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> 
@endsection 