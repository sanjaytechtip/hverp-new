@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Edit MRN')
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
    <h1 class="page-title">{{ __('Edit MRN') }}</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('mrn_list') }}">MRN : List</a></li>
      <li class="breadcrumb-item">{{ __('Edit MRN') }}</li>
    </ol>
    <div class="page-header-actions custom-page-heading">
      <div class="row no-space w-400">
        <div class="col-sm-6">
          <div class="counter"> <span class="counter-number font-weight-medium">DATE</span>
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
      </div>
      <br />
      @endif
      <div class="col-md-12">
        <form class="" action="{{ url('admin/mrn-edit/'.$mrn_details['id']) }}" id='invoiceform' method="post" autocomplete="on" onsubmit="return validate();" enctype="multipart/form-data">
          @csrf
          <div class="card">
          <div class="card-body">
            <div class="row form-new-box-wrap new-quotationcreate new-fix-quotationcreate">
              <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="form-new-box">
                  <label>Supplier</label>
                  <input type="text" autocomplete="off" readonly value="{{ getVendorNamefromId($mrn_details['supplier_name'])}}" class="form-control">
                  <input type="hidden" id="supplier_name" name="supplier_name" autocomplete="off" readonly value="{{$mrn_details['supplier_name']}}" class="form-control">
                 </div>
                <div class="row form-three-box">
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Receipt No.</label>
                    <input type="text" autocomplete="off" value="{{$mrn_details['mrn_receipt_no']}}" id="mrn_receipt_no" name="mrn_receipt_no" required readonly class="form-control">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Local/Central</label>
                    <select name="mrn_type" required class="form-control">
                      <option @if($mrn_details['mrn_type']=='LOCAL') selected @endif value="LOCAL">LOCAL</option>
                      <option @if($mrn_details['mrn_type']=='CENTRAL') selected @endif value="CENTRAL">CENTRAL</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>GRN</label>
                    <input type="text" autocomplete="off" value="{{$mrn_details['grn']}}" id="grn" name="grn" class="form-control">
                  </div>
                </div>
               
              </div>
              <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="row form-two-box">
                      <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <label>Date</label>
                        <input type="text" autocomplete="off" id="mrn_date" value="{{date('d-m-Y',strtotime($mrn_details['mrn_date']))}}" required class="form-control date_with_time small_field_custom datefield" name="mrn_date">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 ">
                        <label>Bill Date</label>
                        <input type="text" autocomplete="off" id="mrn_bill_date" value="{{date('d-m-Y',strtotime($mrn_details['mrn_bill_date']))}}" class="form-control date_with_time small_field_custom datefield" name="mrn_bill_date">
                      </div>
                    </div>
                <div class="row form-three-box" data-select2-id="578">
                  <div class="col-lg-3 col-md-3 col-sm-12 ">
                    <label>Box Received</label>
                    <input type="number" autocomplete="off" id="box_received" value="{{$mrn_details['box_received']}}" name="box_received" class="form-control">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-12 ">
                    <label>Box Pending</label>
                    <input type="number" autocomplete="off" id="box_pending" value="{{$mrn_details['box_pending']}}" name="box_pending" class="form-control">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-12 ">
                    <label>Box Dispatched</label>
                    <input type="number" autocomplete="off" id="box_dispatch" value="{{$mrn_details['box_dispatch']}}" name="box_dispatch" class="form-control">
                  </div>
                  <div class="col-lg-3 col-md-3 col-sm-12 ">
                    <label>Receiver's Name</label>
                    <select name="receiver_id" id="receiver_id" class="form-control" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
                      <option value="">Select</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-12 ">
                <div class="row form-three-box">
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Bill No.</label>
                    <input type="text" autocomplete="off" value="{{$mrn_details['mrn_bill_no']}}" id="mrn_bill_no" name="mrn_bill_no" required class="form-control" aria-required="true">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>Challan No.</label>
                    <input type="text" autocomplete="off" value="{{$mrn_details['mrn_challan_no']}}" id="mrn_challan_no" name="mrn_challan_no" required class="form-control">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 ">
                    <label>E-Way Bill</label>
                    <input type="text" autocomplete="off" value="{{$mrn_details['mrn_eway_bill']}}" id="mrn_eway_bill" name="mrn_eway_bill" class="form-control">
                  </div>
                </div>
                <div class="row form-three-box">
<div class="col-lg-4 col-md-4 col-sm-12 ">
  <label>Remarks</label>
  <textarea name="mrn_remarks" class="form-control">{{$mrn_details['mrn_remarks']}}</textarea>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 ">
  <label>Transport</label>
  <select name="transport" class="form-control" data-plugin="select2" data-placeholder="Type name" data-minimum-input-length="1">
    <option value="">-- Select --</option>
                          
          @foreach($transport as $transport_list)
                      
    <option @if($transport_list['id']==$mrn_details['transport_id']) selected @endif value="{{$transport_list['id']}}">{{$transport_list['transport']}}</option>
    
          @endforeach
                    
  </select>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 ">
  <label>GRN Upload</label>
  <input type="file" id="grn_upload" name="grn_upload" class="form-control">
  <div class="form-box delete-img mrn-delete-img" id="upload_picture-{{$mrn_details['id']}}"> @if(!empty($mrn_details['grn_upload']))
    <input type="hidden" value="{{$mrn_details['grn_upload']}}" name="grn_file_hidden">
    <div class="remove_row"> <a href="javascript:void(0)" title="Delete Image" field_name="upload_picture" cus="{{$mrn_details['id']}}" class="delete-image"><i class="icon md-delete delete-tr" aria-hidden="true"></i></a></div>
    <a target="_blank" href="{{url('public/uploads/mrn/'.$mrn_details['grn_upload'])}}"> Upload File </a> @endif </div>
</div>
</div>

              </div>
            </div>
            <div class="purchaseorder-box quotationcreate-box-wrap">             
              <label for="contact"><strong>Item Details</strong> <a data-toggle="modal" class="search-quotation-pop-up small-btn  btn-sm btn-primary" href="javascript:void(0);" data-target="#myModal_purchase">Search</a></label>
              <br/>
              <span id="ajax-cust"></span>
              <div class="table-wrap big-table scrollfix-wrap">
                <table class="editable-table table table-striped quotationcreate-table left-fix-table" id="editableTable1">
                  <thead>
                    <tr>
                      <th width="10" class="sl-th task_left_fix">Sl</th>
                      <th width="50" class="item-th task_left_fix">Item</th>
                      <th class="tax-th task_left_fix tax-fix-width">Tax</th>
                      <th width="80" class="brand-th">Rate</th>
                      <th width="50" class="mrp-th">MRP</th>
                      <th width="80" class="disc-th">Disc %</th>
                      <th width="90" class="discount-th">Discount</th>
                      <th width="90" class="qty-th">Qty</th>
                      <th width="40" class="net-rate-th">Net Rate</th>
                      <th width="40" class="tax-amount-th">Tax Amount</th>
                      <th width="40" class="amount-th">Amount</th>
                      <th width="40" class="action-th">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody id="results">
                  
                  @php
                  $i=1;
                  @endphp
                  @foreach($mrn_item_details as $details)
                  <?php $details= (array)$details; ?>
                  <tr class="pur-san" id="data-row-{{ $i }}">
                    <td data-text="Sl" class="task_left_fix">{{ $i }}</td>
                    <td data-text="Item" class="task_left_fix"><input type="text" readonly name="row[{{$i}}][item_name]" value="{{$details['item_name']}}" class="autocomplete-dynamic form-control" id="itemrel_{{$i}}" required />
                      <input type="hidden" name="row[{{$i}}][item_id]" id="tmp_id_item_{{$i}}" cus="{{$i}}" rel="item" value="{{$details['item_id']}}" /></td>
                    <td data-text="Tax" class="task_left_fix tax-fix-width"><input type="text" name="row[{{$i}}][tax_rate]" value="{{$details['tax_rate']}}" cus="{{$i}}" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_{{$i}}" required /></td>
                    <td data-text="Rate"><input type="text" name="row[{{$i}}][rate]" value="{{$details['rate']}}" cus="{{$i}}" class="autocomplete-dynamic item-rate form-control" id="rate_rel_{{$i}}" /></td>
                    <td data-text="MRP"><input type="text" readonly name="row[{{$i}}][mrp]" value="{{$details['mrp']}}" cus="{{$i}}" class="autocomplete-dynamic form-control mrp" id="mrp_rel_{{$i}}" /></td>
                    <td data-text="Dis %"><input type="number" name="row[{{$i}}][discount_per]" value="{{$details['discount_per']}}" cus="{{$i}}" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_{{$i}}" /></td>
                    <td data-text="Discount"><input type="number" readonly name="row[{{$i}}][discount]" value="{{$details['discount']}}" class="autocomplete-dynamic form-control" id="discount_rel_{{$i}}" /></td>
                    <td data-text="Quantity"><input type="number" name="row[{{$i}}][quantity]" cus="{{$i}}" value="{{$details['quantity']}}" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_{{$i}}" /></td>
                    <td data-text="Net Rate" id="data-row-net-rate-{{ $i }}" class="netrate" style="font-weight:bold;" align="right">{{$details['net_rate']}}</td>
                    <td data-text="Tax Amount" id="data-row-tax-amount-{{ $i }}" class="taxamount" style="font-weight:bold;" align="right">{{$details['tax_amount']}}</td>
                    <td data-text="Amount" id="data-row-amount-{{ $i }}" class="amount" style="font-weight:bold;" align="right">{{$details['amount']}}</td>
                    <input type="hidden" class="form-control width-full amount_hidden" value="{{$details['net_rate']}}" rel="netrate" id="tmp_id_net_rate_{{$i}}" name="row[{{$i}}][net_rate]" />
                    <input type="hidden" class="form-control width-full amount_hidden" value="{{$details['tax_amount']}}" rel="taxamount" id="tmp_id_tax_amount_{{$i}}" name="row[{{$i}}][tax_amount]" />
                    <input type="hidden" class="form-control width-full amount_hidden" value="{{$details['amount']}}" rel="amount" id="tmp_id_amount_{{$i}}" name="row[{{$i}}][amount]" />
                    <td><a href="javascript:void(0);" onclick="return removeRow({{ $i }});" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td>
                  </tr>
                  @php
                  $i++;
                  @endphp
                  @endforeach
                    </tbody>
                  
                </table>
              </div>
              <div class="add-row" style="display:none;">
                <div class="add-left">
                  <div class="dropdown">
                    <button type="button" class="btn btn-primary add-new-line" cus="1">+ Add a new line</button>
                    <div class="dropdown-menu"> <a class="dropdown-item add-new-line" cus="5" href="javascript:void(0);">Add 5</a> <a class="dropdown-item add-new-line" cus="10" href="javascript:void(0);">Add 10</a> <a class="dropdown-item add-new-line" cus="20" href="javascript:void(0);">Add 20</a> </div>
                  </div>
                </div>
              </div>
              <div class="row quotationcreate-wrap justify-content-between">
                <div class="col-lg-6 col-md-6">
                  <h2 style="display:none;">Other Charges</h2>
                  <div class="select-form-box" style="display:none;">
                    <div class="input-form-box">
                      <select class="form-control charge-other">
                        <option value="">--Select--</option>
                        
                  @foreach($other_charges as $charges_other)
				  
                        <?php $charges_other= (array)$charges_other; ?>
                        <option value="{{$charges_other['other_charges']}}">{{$charges_other['other_charges']}}</option>
                        
                  @endforeach
                
                      </select>
                    </div>
                    <div class="input-form-box input-form-box-input">
                      <input type="number" min="0" class="form-control charges-val"/>
                    </div>
                    <div class="input-form-box input-form-box-buttob"><a href="javascript:void(0);" class="btn btn-primary add-other waves-effect waves-classic">+</a></div>
                  </div>
                  <div class="table-box" style="display:none;">
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
                      @foreach($mrn_details['other_charges_name'] as $key=>$charges)
                      <tr id='san-{{$key}}'>
                        <td>{{$charges}}</td>
                        <td class='ch-val'>{{$mrn_details['other_charges_val'][$key]}}</td>
                        <td><a href='javascript:void(0);' onclick='return removeOther({{$key}})' class='btn btn-danger waves-effect waves-classic'><i class='icon md-minus' style='margin:0;'></i></a></td>
                        <input type='hidden' name='other_charges_name[]' value='{{$charges}}'>
                        <input type='hidden' name='other_charges_val[]' value="{{$mrn_details['other_charges_val'][$key]}}" >
                      </tr>
                      @php
                      $sum = $sum+$mrn_details['other_charges_val'][$key];
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
                  <h2>Totals</h2>
                  <div class="form-new-box">
                    <label>Sub Total</label>
                    <input type="text" name="mrn_subtotal" value="{{$mrn_details['mrn_subtotal']}}" readonly id="sub-total" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Sale Tax</label>
                    <input type="text" autocomplete="off" readonly value="{{$mrn_details['mrn_tax_amount']}}" name="mrn_tax_amount" id="tax-amount" class="form-control">
                  </div>
                  <div class="form-new-box">
                    <label>Grand Total</label>
                    <input type="text" id="grand-total" readonly value="{{$mrn_details['mrn_grand_total']}}" name="mrn_grand_total" class="form-control">
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
  <input id="purchase-order-row" type="hidden" value="0">
  <input id="purchase-order-add-row" type="hidden" value="0">
  <input id="tbl_length" type="hidden" value="1">
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
          <table class="table table-bordered t1" id="data_table">
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
            <?php $user= (array)$user; ?>
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
<input type="hidden" class="data-count" value="0">
<div class="modal fade" id="myModal_purchase" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select PO</h4>
      </div>
      <div class="modal-body">
        <form method="POST" id="reloadform_qu" action="" class="custom-inline-form item-form-apply-qu">
          @csrf
          <div class="new-form-box">
            <div class="form-wrap small-box">
              <label>PO No</label>
              <input class="form-control" type="text" id="purchase_no" name="purchase_no" value="{{@$purchase_no}}" autocomplete="off" placeholder="">
            </div>
            <div class="form-wrap form-wrap-submit">
              <button class="btn btn-primary waves-effect waves-classic order_search_po" type="button" name="order_search_po">Search</button>
            </div>
            <div class="form-wrap form-wrap-submit"> <a id="reload_qu" href="javascript:void(0);" class="btn btn-primary reload_qu waves-effect waves-classic" title="Reload" name="reload"><i class="icon md-refresh" aria-hidden="true"></i></a> </div>
          </div>
        </form>
        <div class="table-box-popup">
          <table class="table table-bordered editable-table table-striped quotationcreate-table">
            <thead>
              <tr>
                <th></th>
                <th>PO No</th>
                <th>PO Date</th>
              </tr>
            </thead>
            <tbody id="results_data">
            
            @foreach($purchaselist as $podata)
            <?php $podata= (array)$podata; ?>
            <tr>
              <td><input type="checkbox" name="chk_search[]" class="chk_search" value="{{$podata['id']}}"></td>
              <td>{{$podata['purchaseorder_no']}}</td>
              <td>{{date('d-m-Y h:i:s',strtotime($podata['created_date']))}}</td>
            </tr>
            @endforeach
              </tbody>
            
          </table>
        </div>
        <div class="container" style="margin-top:10px;">
          <div class="box">
            <ul id="example-3" class="pagination">
            </ul>
            <div class="show"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success data-append" data-dismiss="modal">Ok</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
      $(function(){
          $(document).on('click','.data-append',function(){
            var purchase_id = $('input[type=checkbox]:checked').map(function(_, el) {
                return $(el).val();
                }).get();  
                if(purchase_id!='')
                {
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });   
                   var purchase_order_row = $('#purchase-order-row').val();
                   var purchase_order_add_row = $('#purchase-order-add-row').val();
                   //var rowCount_d_val = $('#tbl_length').val();
				   var rowCount_d_val = (($('#editableTable1 tr').length)-1);
                //   if(typeof rowCount !== "undefined")
                //   {
                //   var rowCount_d = rowCount.split('-'); rowCount_d_val
                //   var rowCount_d_val = rowCount_d[2];
                //   }
					//alert(rowCount_d_val); return false;
                    $.ajax({
                    url: "{{ url('admin/ajaxPurchaseItems') }}" ,
                    type: "POST",
                    data: {purchase_id:purchase_id,purchase_order_row:purchase_order_row,rowCount_d_val:rowCount_d_val},
                    success: function( response ) {
                    //console.log(response); return false;
                    if(response!='')
                    {
                    var returnedData = JSON.parse(response);
                    if(purchase_order_row==0 && purchase_order_add_row==0){
                    //$('#results').html(returnedData.html); 
					$('#results').append(returnedData.html);   
                    }else{
                    $('#results').append(returnedData.html);    
                    }
                    $('#sale-tax').val(returnedData.quotation_saletax);
                    $('#other-charges').html(returnedData.other_charges);
                    calculateAllUnitsWithoutId();
                    $('#purchase-order-row').val(1);
                    var tbl_length = $('#editableTable1 >tbody >tr').length;
                    $('#tbl_length').val(eval(tbl_length+1));
                    }else{
                    
                    }
                    }
                    });
                
                }
          });
      });
  </script> 
<script>
      function calculateAllUnitsWithoutId(){
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
            //alert(sal_tax);
            var total = parseFloat(grand_total_amount+subTotal_other+sal_tax);
            $('#grand-total').val(total.toFixed(2));
		}
  </script> 
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
	 var rowCount = $('#tbl_length').val();
	 var itemHtml='@foreach($items as $allitems)<option value="{{ $allitems->_id }}">{{ $allitems->article_no}}</option>@endforeach';
	 
	 for(var i = 1; i <= count_of_row; i++) {
	 rowCount++;
	    var html = '<tr id="data-row-'+rowCount+'"><td class="pur-san1" data-text="Sl"><span class="count-row"></span></td><td data-text="Item"><input type="text" name="row['+rowCount+'][item_name]" class="autocomplete-dynamic form-control" id="itemrel_'+rowCount+'" readonly required /><input type="hidden" name="row['+rowCount+'][item_id]" id="tmp_id_item_'+rowCount+'" cus="'+rowCount+'" rel="item" value="" /><small><a data-toggle="modal" cus="'+rowCount+'" class="search-pop-up small-btn" data-target="#myModal" href="javascript:void(0);">Search</a></small> </td><td data-text="Tax"> <input type="text" name="row['+rowCount+'][tax_rate]" cus="'+rowCount+'" class="autocomplete-dynamic tax_rate form-control" id="tax_rate_rel_'+rowCount+'" required /> </td><td data-text="Rate"> <input type="text" cus="'+rowCount+'" name="row['+rowCount+'][rate]" class="autocomplete-dynamic item-rate form-control" id="rate_rel_'+rowCount+'" /> </td><td data-text="MRP"> <input type="text" name="row['+rowCount+'][mrp]" cus="'+rowCount+'" class="autocomplete-dynamic form-control mrp" id="mrp_rel_'+rowCount+'" readonly /> </td> <td data-text="Dis %"> <input type="number" cus="'+rowCount+'" name="row['+rowCount+'][discount_per]" class="autocomplete-dynamic item-disc form-control" id="discount_per_rel_'+rowCount+'" /> </td> <td data-text="Discount"> <input type="number" name="row['+rowCount+'][discount]" class="autocomplete-dynamic form-control" id="discount_rel_'+rowCount+'" readonly /> </td> <td data-text="Quantity"> <input type="number" name="row['+rowCount+'][quantity]" cus="'+rowCount+'" class="autocomplete-dynamic ss-qty form-control" id="quantity_rel_'+rowCount+'" /> </td> <td data-text="Net Rate" id="data-row-net-rate-'+rowCount+'" class="netrate" style="font-weight:bold;" align="right"></td> <td data-text="Tax Amount" id="data-row-tax-amount-'+rowCount+'" class="taxamount" style="font-weight:bold;" align="right"></td><td data-text="Amount" id="data-row-amount-'+rowCount+'" class="amount" style="font-weight:bold;" align="right"></td> <input type="hidden" class="form-control width-full amount_hidden" rel="amount" id="tmp_id_amount_'+rowCount+'" name="row['+rowCount+'][amount]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="netrate" id="tmp_id_net_rate_'+rowCount+'" name="row['+rowCount+'][net_rate]" /> <input type="hidden" class="form-control width-full amount_hidden" rel="taxamount" id="tmp_id_tax_amount_'+rowCount+'" name="row['+rowCount+'][tax_amount]" /><td><a href="javascript:void(0);" onclick="return removeRow('+rowCount+');" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row waves-effect waves-classic" title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a></td> </tr>';
	     $('#results').append(html);
	     var tbl_length = $('#editableTable1 >tbody >tr').length;
         $('#tbl_length').val(eval(tbl_length+1));
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
				
					var description = ui.item.description+', '+ui.item.composition+', '+ui.item.count_construct+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, ''); 
						
					/* var description = ui.item.description+', '+ui.item.composition+', '+ui.item.gsm+', '+ui.item.width;
					    description= description.replace(/\\/g, '');
						
					var remark_count = ui.item.count_construct;
					    remark_count= remark_count.replace(/\\/g, ''); */
				 
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
							
							
							jQuery('#tmp_id_desc_'+rowNum).val(description);
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
    //if(row!=1){
    $('#data-row-'+row).remove();
    var rowCount = $('tr.pur-san:last').attr("id");
    //alert(rowCount);
    if(typeof rowCount !== "undefined")
    {
    $('#purchase-order-row').val(1); 
    }else{
    $('#purchase-order-row').val(0);    
    }
	calculateAllUnits(row);
	//}
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
            var rate = $('#rate_rel_'+id).val();
            var discount_per = $('#discount_per_rel_'+id).val();
            var tax = $('#tax_rate_rel_'+id).val();
            var total_disc = eval((rate*discount_per)/100);
            //alert((eval(discount_per));
            $('#discount_rel_'+id).val(eval(total_disc).toFixed(2));
            var discount = $('#discount_rel_'+id).val();
            
            var net_rate_amount = eval((rate-discount)*qty);
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
		var buyerArr = '<?php print_r(getAllVendor()); ?>';
		buyerArr = JSON.parse(buyerArr);
		//console.log(buyerArr);
		var buyerData = $.map(buyerArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var buyerHtml='<option value="">--Select--</option>';
		for(var i=0; i<buyerData.length; i++){
			buyerHtml+='<option value="'+buyerData[i].data+'">'+buyerData[i].value+'</option>';
		}
		$('#supplier_name').html(buyerHtml);
		//$('#buyer_name').html(buyerHtml);
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
                    url: "{{ url('admin/purchase_net_rate_search_mrn') }}" ,
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
                    url: "{{ url('admin/purchase_net_rate_search_mrn') }}" ,
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
            //alert('hiii'); return false;
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
            url: "{{ url('admin/purchase_net_rate_search_mrn') }}" ,
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
            var rowCount = $('#editableTable1 >tbody >tr').length;
            //alert(rowCount);
            var data_count = $('#purchase-order-row').val();
            var radioValue = $("input[name='apply-radio']:checked").attr('id');
            // var radioValue = $("input[name='apply-radio']:checked").map(function() {
            // return $(this).attr('id');
            // }).get();
            var customer_name = $('#customer_name').val();
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
           if(radioValue!='')
           {
            $.ajax({
            url: "{{ url('admin/item_search_data_mrn') }}" ,
            type: "POST",
            data: {customer_name:customer_name,id:radioValue,rowCount:rowCount,data_count:data_count},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
                 var data = jQuery.parseJSON(response);
            $('#tmp_id_item_'+id_data).val(data.id);
            $('#itemrel_'+id_data).val(data.name+'-'+data.brand);
            $('#description_rel_'+id_data).val(data.description);
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
            
            if(data_count==0){
            //$('#results').html(response);  
            }else{
            //$('#results').append(response);    
            }
            $('.data-count').val(1);
            $('#purchase-order-add-row').val(2);
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
       //$(":input:not([name=supplier_name])").prop("disabled", true);
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
          var quotation_type = $('#quotation_type').val();
          var quotation_srno = $('#quotation_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#quotation_no').val(quotation_type+quotation_srno);
       }); 
    });
    $(function(){
       $(document).on('change','#quotation_type',function(){
          var quotation_type = $('#quotation_type').val();
          var quotation_srno = $('#quotation_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#quotation_no').val(quotation_type+quotation_srno);
       }); 
    });
    $(function(){
       $(document).on('keyup','#quotation_srno',function(){
          var quotation_type = $('#quotation_type').val();
          var quotation_srno = $('#quotation_srno').val();
          //var quotation_no = $('#quotation_no').val();
          $('#quotation_no').val(quotation_type+quotation_srno);
       }); 
    });
</script> 
<script>
    $(function(){
        $(document).on('change','#quotation_status',function(){
           var status = $(this).val();
           if(status=='Approved')
           {
               $('#quotation_approved').prop('checked',true);
           }else{
               $('#quotation_approved').prop('checked',false);
           }
        });
    });
</script> 

<!--<script>--> 
<!--    $(function(){--> 
<!--       $(document).on('keyup','.cust_ref_no',function(){--> 
<!--           var vals = new Array();--> 
<!--            $(".cust_ref_no").each(function() {--> 
<!--            if($.inArray($(this).val(), vals) == -1) {--> 
<!--            vals.push($(this).val());--> 
<!--            $('.btn-data-save').prop('disabled',false);--> 
<!--            } else {--> 
<!--            $('.btn-data-save').prop('disabled',true);--> 
<!--            alert("Duplicate Cust Ref No.: " + $(this).val()); return false;--> 
<!--            }      --> 
<!--            });--> 
<!--       }); --> 
<!--    });--> 
<!--</script>--> 

<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/scripts/jquery.mockjax.js"></script> 
<script src="{{asset(PUBLIC_FOLDER.'theme')}}/global/autocomplete/src/jquery.autocomplete.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="https://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" media="all" type="text/css" href="https://dev-demo.info/antyafms/theme/require/jquery-ui-timepicker-addon.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script> 
<script>
$('.date_with_time').datepicker({
dateFormat: 'dd-mm-yy',
changeMonth: true,
changeYear: true,
});
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
						//alert(msg); return false;
					data = JSON.parse(msg);
					console.log(msg); //return false;
						var availableTags = data;
    //alert(availableTags);
    $( "#quotation_contact_name" ).autocomplete({
      source: availableTags,
      minLength: 1,
      select: function(event, ui) {
            $("#quotation_contact_phone").val(ui.item.mobile);
            $("#quotation_contact_email").val(ui.item.email);
            $("#quotation_department_name").val(ui.item.department);
        }
    });
					}
					});
			  }
		  });
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
            url: "{{ url('admin/purchase_net_rate_search_mrn') }}" ,
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
$('#quotation_ref_no').keypress(function (e) {
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
</script> 
<script>
    $(function(){
       $('#quotation_ref_no').blur(function(){
           var quotation_ref_no = $(this).val();
           var quotation_id = '';
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                
                $.ajax({
                url: "{{ url('admin/quotation_ref_no_search') }}" ,
                type: "POST",
                data: {quotation_ref_no:quotation_ref_no,quotation_id:quotation_id},
                success: function( response ) {
                //alert(response); return false;
                if(response==1)
                {
                $('#quotation_ref_error').show();
                $('#quotation_ref_no').focus(); 
                return false;
                }else{
                $('#quotation_ref_error').hide();    
                }
                }
                });
       }); 
    });
</script> 
<script>
     $(function(){
       $(document).on('change','#supplier_name',function(){
          var id = $(this).val();
          if(id!='')
          {
          $(":input:not([name=contact])").prop("disabled", false);   
          $(".search-purchase-pop-up").attr("data-target","#myModal_purchase");
          }else{
          $(":input:not([name=supplier_name])").prop("disabled", true); 
          $(".search-purchase-pop-up").removeAttr("data-target");
          }
       }); 
    });
</script> 
<script>
        $(function(){
           $(document).on('click','.search-purchase-pop-up',function(){
              var supplier_name = $('#supplier_name').val();
              if(supplier_name!='')
              {
              $('.chk_search').prop('checked', false);      
              }else{
                  alert('Please choose Supplier first.');
                  return false;
              }
           }); 
        });
    </script> 
<script>
            $(function(){
            $('#example-3').pagination({
            total: {{$purchaselist_count}}, 
            current: 1,
            length: 100,
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
            url: "{{ url('admin/ajaxPurchaseItems') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
            var returnedData = JSON.parse(response);
            $('#results_data').html(returnedData.html);  
            }
            }
            }); 
            }
            });
            });
            </script> 
<script>
                $(function(){
                
                $(document).on('click','.order_search_po',function(){
                $('#loader').show();
                var purchase_no = $("#purchase_no").val();
                $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });
                var formulario =  $(".item-form-apply-qu");
                $.ajax({
                url: "{{ url('admin/purchase_search_po') }}" ,
                type: "POST",
                data: {purchase_no:purchase_no},
                success: function( response ) {
                //alert(response); return false;
                
                if(response!='')
                {
                $('#loader').hide();
                var returnedData_qu = JSON.parse(response);
                $('#results_data').html(returnedData_qu.html);
                
                //alert(returnedData.record_count);
                $('#example-3').pagination({
                total: returnedData_qu.record_count, 
                current: 1,
                length: 100,
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
                url: "{{ url('admin/purchase_search_po') }}" ,
                type: "POST",
                data: {page:options.current,purchase_no:purchase_no},
                success: function( response ) {
                //alert(response); return false;
                if(response!='')
                {
                var returnedData = JSON.parse(response);
                $('#results_data').html(returnedData.html);  
                }
                }
                }); 
                }
                });    
                $('.data-append').show();
                }else{
                $('#results_data').html(''); 
                $('#loader').hide();
                $('.data-append').hide();
                }
                }
                }); 
                });
                });
                </script> 
<script>
    $(function(){
        $(document).on('click','#reload_qu',function(){
            //alert();
           $('#reloadform_qu')[0].reset();
           var supplier_name = $('#supplier_name').val();
              if(supplier_name!='')
              {
                    $.ajax({
                    type: "GET",
                    url: "{{ url('admin/ajaxSearchPurchase')}}/"+supplier_name,
                    data: {supplier_name:supplier_name},
                    success: function(msg){
                    //alert(msg); return false;
                    if(msg!='')
                    {
                        var returnedData_qu = JSON.parse(msg);
                        $('#results_data').html(returnedData_qu.html);
                        $('#example-3').pagination({
                    total: returnedData_qu.record_count, 
                    current: 1,
                    length: 100,
                    size: 2,
                    prev: 'Previous',
                    next: 'Next',
                    click: function(options, refresh, $target){
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var purchase_no = $("#purchase_no").val();
                    $.ajax({
                    url: "{{ url('admin/purchase_search_po') }}" ,
                    type: "POST",
                    data: {page:options.current,purchase_no:purchase_no},
                    success: function( response ) {
                    //alert(response); return false;
                    if(response!='')
                    {
                    var returnedData = JSON.parse(response);
                    $('#results_data').html(returnedData.html);  
                    }
                    }
                    }); 
                    }
                    });  
                    }
                    }
                    });
              }
           $('.order_search_po').trigger('click');
        });
    });
</script> 
<script>
  $(function(){
      $(document).on('click','.delete-image',function(){
            var id = $(this).attr('cus');
            if(id!='')
            {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
              var verify = confirm("Are you sure to delete the File?");
              if(verify)
              {
              $.ajax({
              url: "{{ url('admin/mrn_image_remove') }}" ,
              type: "POST",
              data: {id:id},
              success: function( response ) {
              // alert(response); return false;
              if(response!='')
              {
              $('#upload_picture-'+id).remove();
              }
              }
              }); 
            }
            }
      });
  });
</script> 
<script>
	$(function () { 
		var receiverArr = '<?php print_r(getAllusersData()); ?>';
		receiverArr = JSON.parse(receiverArr);
		//console.log(buyerArr);
		var receiverData = $.map(receiverArr, function (value, key) { return { value: value, data: key }; });
		//console.log(buyerData);
		var receiverHtml='<option value="">--Select--</option>';
		for(var i=0; i<receiverData.length; i++){
			receiverHtml+='<option value="'+receiverData[i].data+'">'+receiverData[i].value+'</option>';
		}
		$('#receiver_id').html(receiverHtml);
		$('#receiver_id').val({{$mrn_details['receiver_id']}});
		//console.log(buyerHtml);
	});
</script> 
@endsection 