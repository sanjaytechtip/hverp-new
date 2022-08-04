<style>
table {
	width: 100%;	
	font-family:Arial, Helvetica, sans-serif;
}
table, th, td {
	border: solid 1px #cdcdcd;
	border-collapse: collapse;
	padding:5px 5px;
	text-align: center;	
	font-family:Arial, Helvetica, sans-serif;
}

.panel-body{ max-width: 1200px; margin: 0 auto; padding: 20px 0; }

th{ background-color: #343a40; color: #ffffff; font-size:small; font-weight:normal; white-space:nowrap; letter-spacing:normal; }
td { font-size:small; }

@media  print {
#printWindowBtn {
	display:none;
}
}
</style>

<div class="panel-body">
  <table cellpadding="0" cellspacing="0" width="100%;" border="0">
  <tr>
    <td colspan="2"><table cellpadding="0" cellspacing="5" width="100%;" border="0">
   <tr>
    <td style="border:none; text-align:left;"><strong style="padding:5px; font-size:20px;">H.V.TECHNOLOGIES</strong></td>
    <td style="text-align:right; border:none; font-size:13px;" valign="middle"><strong>Quotation</strong></td>
  </tr>
</table>
</td>
  </tr>
    <tr>
      <td width="50%;"><table style="text-align:left;" cellpadding="5" cellspacing="0" width="100%;" border="0">
          <tr>
            <td style="text-align:left;"><strong>Name :</strong> {{ getCustomerNamefromId($quotation_details['customer_name'])}}</td>
          </tr>
          <tr>
            <td style="text-align:left;"><strong>Address :</strong> {{getCustomerAddress($quotation_details['customer_name'])['h_area']}} @if(getCustomerAddress($quotation_details['customer_name'])['h_landmark']!='') <br>
              {{getCustomerAddress($quotation_details['customer_name'])['h_landmark']}} @endif <br>
              {{getCustomerAddress($quotation_details['customer_name'])['h_city']}}</td>
          </tr>
          <tr>
            <td style="text-align:left;">{{getCustomerAddress($quotation_details['customer_name'])['h_state']}}, {{getCustomerAddress($quotation_details['customer_name'])['h_country']}} - {{getCustomerAddress($quotation_details['customer_name'])['h_pin_code']}} <span style="display:inline-block; float:right;"><strong>GSTIN No.</strong> {{getCustomerAddress($quotation_details['customer_name'])['GST_number']}}</span></td>
          </tr>
        </table></td>
      <td width="50%;"><table style="text-align:left;" cellpadding="5" cellspacing="0" width="100%;" border="0">
          <tr>
            <td style="text-align:left;"><strong>Quotation No.</strong> {{$quotation_details['quotation_no']}}</td>
          </tr>
          <tr>
            <td style="text-align:left;"><strong>Date</strong> {{date('d-M-Y',strtotime($quotation_details['quotation_date']))}}</td>
          </tr>
          <tr>
            <td style="text-align:left;"><strong>Customer Reference No</strong> {{$quotation_details['quotation_ref_no']}}</td>
          </tr>
          <tr>
            <td style="text-align:left;"><strong>Ref Date</strong> @if($quotation_details['quotation_ref_date']!='1970-01-01'){{ globalDateformatNet($quotation_details['quotation_ref_date'])}} @endif </td>
          </tr>
          <tr>
            <td style="text-align:left;"><strong>Due Date</strong> @if($quotation_details['quotation_due_date']!='1970-01-01'){{ globalDateformatNet($quotation_details['quotation_due_date'])}} @endif </td>
          </tr>
        </table></td>
    </tr>
    <?php
    if(!empty(getCustomerAddress($quotation_details['customer_name'])['h_state'])){
        $check_gst_number = getCustomerAddress($quotation_details['customer_name'])['h_state'];
        if($check_gst_number=='Uttarakhand')
        {
            $gst_num = 1;
        }else{
            $gst_num = 0;
        }
    }
    ?>
    <tr>
      <td style="padding: 0; border: none;" colspan="3"><table style="width:100%; border: none" cellspacing="0" cellpadding="2" border="0">
          <tr>
            <th rowspan="2" style="width:3%;"><strong>Sl.</strong></th>
            <th rowspan="2" style="width:3%;"><strong>Cust Ref No.</strong></th>
            <th rowspan="2" style="width:6%;"><strong>Code</strong></th>
            <th rowspan="2" style="width:10%;"><strong style="width:200px;">Item Name</strong></th>
            <th rowspan="2" style="width:8%;"><strong style="width:100px;">Comment</strong></th>
            <th rowspan="2" style="width:8%;"><strong style="width:100px;">Description</strong></th>
            <th rowspan="2" style="width:6%;"><strong>HSN Code</strong></th>
            <th rowspan="2" style="width:4%;"><strong>Qty.</strong></th>
            <th rowspan="2" style="width:5%;"><strong>Pack</strong></th>
            <th rowspan="2" style="width:5%;"><strong>Rate</strong></th>
            <th rowspan="2" style="width:5%;"><strong>Amt.</strong></th>
            <th colspan="2" style="width:9%;"><strong>Discount</strong></th>
            @if($gst_num==0)
            <th colspan="2" style="width:9%;"><strong>IGST</strong></th>
            <th colspan="2" style="width:10%;"><strong></strong></th>
            @else
            <th colspan="2" style="width:11%;"><strong>CGST</strong></th>
            <th colspan="2" style="width:11%;"><strong>SGST</strong></th>
            @endif
            <th rowspan="2" style="width:6%;"><strong>Amount</strong></th>
          </tr>
          <tr>
            <th><strong>%</strong></th>
            <th><strong>Amt</strong></th>
            <th><strong>%</strong></th>
            <th><strong>Amt</strong></th>
            @if($gst_num==0)
            <th><strong></strong></th>
            <th><strong></strong></th>
            @else
            <th><strong>%</strong></th>
            <th><strong>Amt</strong></th>
            @endif
          </tr>
          @php
          
          $i=1;
          
          @endphp
          
          @foreach($quotation_item_details as $item_details)
          <tr>
            <td style="border-style: solid solid solid solid; border-width: 1px 1px 1px 1px;">{{$i}}</td>
            <td style="border-style: solid solid solid solid; border-width: 1px 1px 1px 1px;">{{$item_details['cust_ref_no']}}</td>
            <td style=" border-style: solid solid solid solid; border-width: 1px 1px 1px 1px;">{{$item_details['vendor_sku']}}</td>
            <td style="text-align:left; border-style: solid solid solid solid;">{{$item_details['item_name']}}</td>
            <td style="text-align:left; border-style: solid solid solid solid;">{{$item_details['comment']}}</td>
            <td style="text-align:left; border-style: solid solid solid solid;">{{$item_details['description']}}</td>
            <td style="text-align:left border-style: solid solid solid solid; border-width: 1px 1px 1px 1px;">{{$item_details['hsn_code']}}</td>
            <td style="text-align:left;">{{$item_details['quantity']}}</td>
            <td style="text-align:left;">{{$item_details['packing_name']}}</td>
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
            <td style=" text-align:right;">{{number_format($item_details['amount'],2,'.', '')}}</td>
          </tr>
          @php
          
          $i++;
          
          @endphp
          
          @endforeach
          <tr>
            <td style="text-align: right; " colspan="7"><strong>Sale Tax</strong></td>
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
            <td style="text-align: right;"> {{number_format($quotation_details['quotation_saletax'],2,'.', '')}} </td>
          </tr>
          @foreach($other_charges_data as $key=>$charges)
          <tr>
            <td style="text-align: right; " colspan="7"><strong>{{$charges['other_charges_name']}}</strong></td>
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
            <td style="text-align: right; "> {{number_format($charges['other_charges_val'],2,'.', '')}} </td>
          </tr>
          @endforeach
          <tr>
            <td style="text-align: right; " colspan="7"><strong>GRAND TOTAL</strong></td>
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
            <td style="text-align: right; font-weight:bold;">{{number_format($quotation_details['quotation_grand_total'],2,'.', '')}}</td>
          </tr>
        </table></td>
    </tr>
    <tr>
    <td style="padding: 0; border: none;" colspan="2">
      <table style="width:100%; border: none" cellspacing="0" cellpadding="5" border="0">
<tr>
  <td style="border:none; padding:5px; text-align:left;" colspan="2"><strong>Terms and Conditions</strong></td>
</tr>
<tr>
  <td style="border:none; padding:5px; text-align:left;" valign="top"><strong>PAN No.</strong> : {{$company_details['pan_no']}} <br><strong>GSTIN No.</strong> : {{$company_details['gstin_no']}}</td>
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
	</td>
    </tr>
  </table>
</div>
