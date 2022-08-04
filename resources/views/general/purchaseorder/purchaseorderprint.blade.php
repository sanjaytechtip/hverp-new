<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Purchase Order Print</title>
</head>
<body>
<div class="panel-body">
  <style>
table {
	width: 100%;
	
	font-family:Arial, Helvetica, sans-serif;
}
table, th, td {
	border: solid 1px #cdcdcd;
	border-collapse: collapse;
	padding:3px 5px;
	text-align: center;
	
	font-family:Arial, Helvetica, sans-serif;
}

.panel-body{ max-width: 1200px; margin: 0 auto; padding: 20px 0; }

th{ background-color: #343a40; color: #ffffff; font-size: 12px; }
td { font-size: 12px; }

@media  print {
#printWindowBtn {
	display:none;
}
.panel-body{ max-width: 100%; width:100%; padding:0; }

th{ background-color:#ffffff; color: #000000; font-size:8px; }
td { font-size: 8px; }

}
</style>
<?php $purchaseorder_details = (array)$purchaseorder_details; ?>
  <div id="printWindowBtn" style="text-align: center; background:#0c5265; width:130px; border-radius:3px;cursor:pointer; padding:5px 10px; color:#fff; margin:0 auto;">Click here to Print.</div>
  <br/>
  <table>
    <tr>
      <td style="border:none; margin-top:10px; padding:10px; text-align:left; font-size:20px"><strong>H.V.TECHNOLOGIES</strong></td>
      <td style="text-align:right; border:none; padding:10px; margin:10px; font-size:13px;"><strong>Purchase Order</strong></td>
    </tr>
    <tr>
      <td width="50%;"><table style="text-align:left; font-size:5px; border:0 none;" border="0">
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Name  :</strong> {{ getCustomerNamefromId($purchaseorder_details['customer_name'])}}</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Address :</strong> {{getCustomerAddress($purchaseorder_details['customer_name'])['h_area']}} @if(getCustomerAddress($purchaseorder_details['customer_name'])['h_landmark']!='') <br>
              {{getCustomerAddress($purchaseorder_details['customer_name'])['h_landmark']}} @endif <br>
              {{getCustomerAddress($purchaseorder_details['customer_name'])['h_city']}}</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;">{{getCustomerAddress($purchaseorder_details['customer_name'])['h_state']}}, {{getCustomerAddress($purchaseorder_details['customer_name'])['h_country']}} - {{getCustomerAddress($purchaseorder_details['customer_name'])['h_pin_code']}} <span style="display:inline-block; float:right;"><strong>GSTIN No.</strong> {{getCustomerAddress($purchaseorder_details['customer_name'])['GST_number']}}</span></td>
          </tr>
        </table></td>
      <td width="50%;"><table style="text-align:left; font-size:5px; border:0 none;" border="0">
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Purchase Order No.</strong> {{$purchaseorder_details['purchaseorder_no']}}</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;"><strong>PO. Date</strong> {{date('d-M-Y',strtotime($purchaseorder_details['purchaseorder_date']))}}</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Customer Reference No</strong> {{$purchaseorder_details['purchaseorder_ref_no']}}</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Ref Date</strong> @if($purchaseorder_details['purchaseorder_ref_date']!='1970-01-01'){{ globalDateformatNet($purchaseorder_details['purchaseorder_ref_date'])}} @endif</td>
          </tr>
          <tr>
            <td style="text-align:left; border:0 none;"><strong>Delivery Date</strong> @if($purchaseorder_details['purchaseorder_due_date']!='1970-01-01'){{ globalDateformatNet($purchaseorder_details['purchaseorder_due_date'])}} @endif</td>
          </tr>
        </table></td>
    </tr>
    <?php
    if(!empty(getCustomerAddress($purchaseorder_details['customer_name'])['h_state'])){
        $check_gst_number = getCustomerAddress($purchaseorder_details['customer_name'])['h_state'];
        if($check_gst_number=='Uttarakhand')
        {
            $gst_num = 1;
        }else{
            $gst_num = 0;
        }
    }
    ?>
    <tr>
      <td style="padding: 0; border: none;" colspan="2"><table style="width:100%; border: none" cellspacing="0" cellpadding="5" border="0">
          <tr>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:48px; border-left:transparent;"><strong>Sl.</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:48px; border-left:transparent;"><strong>Cust Ref No.</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:48px;"><strong>Code</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:200px; text-align:left;"><strong>Description</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:45px;"><strong>HSN Code</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:30px;"><strong>Qty.</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:50px;"><strong>Pack</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:38px;"><strong>Rate</strong></th>
            <th rowspan="2" style="font-weight: 600; border-top: none;  width:30px;"><strong>Amt.</strong></th>
            <th colspan="2" style="font-weight: 600; border-top: none;  width:23px;"><strong>Discount</strong></th>
            @if($gst_num==0)
            <th colspan="2" style="width:12%; border-top: none;"><strong>IGST</strong></th>
            <th colspan="2" style="width:12%; border-top: none;"><strong></strong></th>
            @else
            <th colspan="2" style="width:12%; border-top: none;"><strong>CGST</strong></th>
            <th colspan="2" style="width:12%; border-top: none;"><strong>SGST</strong></th>
            @endif
            <th rowspan="2"  style="font-weight: 600; border-top: none;  width:30px; border-right:transparent;"><strong>Amount</strong></th>
          </tr>
          <tr>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>%</strong></th>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>Amt</strong></th>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>%</strong></th>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>Amt</strong></th>
            @if($gst_num==0)
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong></strong></th>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong></strong></th>
            @else
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>%</strong></th>
            <th style="font-weight: 600; border-top: none;  width:30px;"><strong>Amt</strong></th>
            @endif
          </tr>
          @php
          $i=1;
          @endphp
          @foreach($purchaseorder_item_details as $item_details)
		  <?php $item_details = (array)$item_details; ?>
          <tr>
            <td>{{$i}}</td>
            <td>{{$item_details['cust_ref_no']}}</td>
            <td>{{$item_details['vendor_sku']}}</td>
            <td style="text-align:left;">{{$item_details['item_name']}}</td>
            <td>{{$item_details['hsn_code']}}</td>
            <td>{{$item_details['quantity']}}</td>
            <td>{{$item_details['packing_name']}}</td>
            <td style=" text-align:right;">{{number_format($item_details['rate'],2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['quantity']*$item_details['rate'],2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['discount_per'],2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['discount'],2,'.', '')}}</td>
            @if($gst_num==0)
            <td style=" text-align:right;">{{number_format($item_details['tax_rate'],2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['tax_amount'],2,'.', '')}}</td>
            <td style=" text-align:right;"></td>
            <td style=" text-align:right;"></td>
            @else
            <td style=" text-align:right;">{{number_format($item_details['tax_rate']/2,2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['tax_amount']/2,2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['tax_rate']/2,2,'.', '')}}</td>
            <td style=" text-align:right;">{{number_format($item_details['tax_amount']/2,2,'.', '')}}</td>
            @endif
            <td  style=" text-align:right; border-right:transparent;">{{number_format($item_details['amount'],2,'.', '')}}</td>
          </tr>
          @php
          $i++;
          @endphp
          @endforeach
          <tr>
            <td style="text-align: right;  border-left:transparent;" colspan="5"><strong>Sale Tax</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right;  border-right:transparent;"> {{number_format($purchaseorder_details['purchaseorder_saletax'],2,'.', '')}} </td>
          </tr>
          @foreach($purchaseorder_details['other_charges_name'] as $key=>$charges)
          <tr>
            <td style="text-align: right; " colspan="5"><strong>{{$charges}}</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; "> {{number_format($purchaseorder_details['other_charges_val'][$key],2,'.', '')}} </td>
          </tr>
          @endforeach
          <tr>
            <td style="text-align: right; " colspan="5"><strong>GRAND TOTAL</strong></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: right; ">{{number_format($purchaseorder_details['purchaseorder_grand_total'],2,'.', '')}}</td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td style="border:none; padding:5px; text-align:left;" colspan="2"><strong>Terms and Conditions</strong></td>
    </tr>
	<?php $company_details = (array)$company_details;?>
    <tr>
      <td style="border:none; padding:5px; text-align:left;" valign="top"><strong>PAN No.</strong> : {{$company_details['pan_no']}} <br>
        <strong>GSTIN No.</strong> : {{$company_details['gstin_no']}}</td>
      <td style="border:none; padding:5px; text-align:right;" valign="top"><strong>for H.V.Technologies</strong></td>
    </tr>
    <tr>
      <td style="border:none; padding:5px; text-align:left;" valign="top"><strong>Address</strong>: {{$company_details['address']}}</td>
      <td style="border:none; padding:5px; text-align:right;" valign="top"><strong>Authorised Signatory</strong></td>
    </tr>
    <tr>
      <td style="border:none; padding:5px; text-align:left;" valign="top"><strong>Bank Details</strong> : {{$company_details['bank_details']}}</td>
      <td style="border:none; padding:5px; text-align:right;" valign="top"><strong>E. & O.E.</strong></td>
    </tr>
  </table>
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function(){
$('#printWindowBtn').on('click',function(){
window.print();
});
});
</script>