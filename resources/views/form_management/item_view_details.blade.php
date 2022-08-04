@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Item View Details')
@section('pagecontent')
@php
//pr($datalist); die;
@endphp
<style>
.modal-open .select2-container {
    z-index: 9999999999;
}
</style>

<div class="page">
    <div class="new-width-design new-new-width-design">
        <div class="new-design-inner">
		<?php //pr(getAllRoom()); ?>
            <div class="new-header-div-fot-btn">
                <div class="page-header">
                    <h1 class="page-title">Item View Details</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/item_details?i_id='.$datalist['item_id'].'&type='.$datalist['type']) }}">Item Details : List</a></li>
                    </ol>
                </div>
				<div class="inlineform-wrap">
  <div class="custom-inline-form">
   
    
  </div>
</div>
            </div>
			
            <div class="page-content">
                <div class="row"> @if (\Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <p>{{ \Session::get('success') }}</p>
                        </div>
                    </div>
                    @endif <a href="javascript:void(0)" data-toggle="modal" data-target="#invoice_model"
                        id="invoice_btn"></a>
                    <!-- Modal -->
                    
                </div>
                <div class="panel">
                    <div class="panel-body">
                    <table class="table table-bordered t1 list-data-table item-details-table item-view-details table-striped">
                      
                        <tr>
                            <td><strong>Item</strong></td>
                            <td>{{ getAllitemsById([$datalist['item_id']]) }}</td>
                        </tr>
                       
                        <tr>
                            <td><strong>Location</strong></td>
                            <td>{{ $datalist['loc_cen'] }}</td>
                        </tr>
                        <tr>
                        <td><strong>Room No.</strong></td>
                        <td><?php 
                        $room_data = getAllRoom($datalist['room_no']); 
                        if(!empty($room_data)){ echo $room_data->room_no; }
                        ?></td>
                        </tr>
                        <tr>
                        <td><strong>Rack No.</strong></td>
						<td>{{getRackName($datalist['rack_no'])}}</td>
                        </tr>
                        <tr>
                        <td><strong>Batch. No</strong></td>
						<td>{{$datalist['batch_no']}}</td>
                        </tr>
                        <tr>
                        <td><strong>Mfg Date</strong></td>
						<td>{{globalDateformatItem($datalist['mfg_date'])}}</td>
                        </tr>
                        <tr>
                        <td><strong>Expiry Date</strong></td>
						<td>{{globalDateformatItem($datalist['expiry_date'])}}</td>
                        </tr>
                        <tr>
                        <td><strong>Retest Date</strong></td>
						<td>{{globalDateformatItem($datalist['retest_date'])}}</td>
                        </tr>
                        <tr>
                        <td><strong>Quantity</strong></td>
						<td>{{$datalist['quantity']}}</td>
                        </tr>
                        <tr>
                        <td><strong>MRP</strong></td>
						<td>{{$datalist['mrp']}}</td>
                        </tr>
                        <tr>
                        <td><strong>Rate</strong></td>
						<td>{{$datalist['rate']}}</td>
                        </tr>
                        <tr>
                        <td><strong>Amount</strong></td>
						<td>{{$datalist['amount']}}</td>
                        </tr>  
                        <tr>
                        <td><strong>COA</strong></td>
						<td>{{$datalist['coa']}}</td>
                        </tr>  
                        <tr>
                        <td><strong>COA PDF Upload</strong></td>
                        <td>@if(!empty($datalist['coa_file']))
                        <a target="_blank" href="{{url('public/uploads/stock/'.$datalist['coa_file'])}}"> 
                        Upload File
                        </a> 
                        @endif </td>
                        </tr>
                                       
                    </table>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
@endsection

@section('custom_validation_script')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>

@endsection