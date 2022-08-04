@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Challan View Listing')
@section('pagecontent')
<div class="page">
<div class="new-width-design new-new-width-design">
  <div class="new-design-inner">
    <div class="new-header-div-fot-btn">
      <div class="page-header">
        <h1 class="page-title">Challan View Listing</h1>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('challan_list') }}">Challan List</a></li>
          <li class="breadcrumb-item">Challan View Listing</li>
        </ol>
      </div>
      <div class="inlineform-wrap">
 
       
</div>
      </div>
     
      <div class="page-content">
        <div class="row"> @if (\Session::has('success'))
          <div class="col-md-12">
            <div class="alert alert-success">
              <p>{{ \Session::get('success') }}</p>
            </div>
          </div>
          @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model" id="invoice_btn"></a> </div>
        <div class="panel">
          <div class="panel-body">
            <table class="table table-bordered t1 custom-lineheight-table">
              <thead>
                <tr>
                  <th class="bg-info" id="ticket_number" style="color:#fff !important;">Challan No</th>
                  <th class="bg-info" id="location" style="color:#fff !important;">Customer</th>
                  <th class="bg-info" id="department" style="color:#fff !important;">SO No </th>
                  <th class="bg-info" id="dispatch" style="color:#fff !important;">Dispatch Qty</th>
                  <th class="bg-info" id="item" style="color:#fff !important;">Item Name</th>
                  <th class="bg-info" id="action" style="color:#fff !important;">Created Date</th>
                </tr>
              </thead>
              <tbody id="fb_table_body">
                @php
                $cus_data = getAllBuyerData();
                @endphp
                @if(!empty($challan_data_item))
                @foreach($challan_data_item as $list)
                @php
                $items = getSalesOrderItems($list['_id']);
                @endphp
                <tr>
                  <td>{{$challan_data['challan_no']}}</td>
                  <td>{{ $cus_data[$list['customer_id']] }}</td>
                  <td>{{ $list['sale_order_no'] }}</td>
                  <td>{{ $list['dispatch_qty'] }}</td>
                  <td>{{ getItemName($list['item_id']) }}</td>
                  <td>{{ date('d-m-Y H:i A',strtotime($list['created_date'])) }}</td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="6">No record found.</td></tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      

@endsection

@section('custom_validation_script')

<style>
.email-popup .row .form-wrap label { display: block; text-align: left; }

.email-popup .note-editable{ text-align:left;}
    
</style>

@endsection