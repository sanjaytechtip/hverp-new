<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Item Details Barcode</title>
<link rel="shortcut icon" href="{{asset(PUBLIC_FOLDER.'theme')}}/mmenu/assets/images/favicon.ico">     

<style>
body{ margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; font-size:11px; }
.barcode-wrapper{display:flex; flex-wrap:wrap; justify-content:center; align-items:center; min-height:100vh;}
.barcode-wrapper .barcode-wrap{}
.barcode-wrapper .barcode-wrap .barcode-box{ padding:10px 0; display:flex; justify-content:center; flex-wrap:wrap; width:235px; }
.barcode-wrapper .barcode-wrap .barcode-box > div{ width:100%; }
.barcode-wrapper .barcode-wrap .barcode-box .barcode-name{ display:flex; justify-content:space-between}
.barcode-wrapper .barcode-wrap .barcode-box .barcode-name .name,
.barcode-wrapper .barcode-wrap .barcode-box .barcode-name .price{ /*padding:0 6px;*/ }
.barcode-wrapper .barcode-wrap .pid{ text-align:center; }
p.inline {display: inline-block; margin:2mm 0 0 0; padding:0;}
span { font-size: 12px;}
div.b128{ border-left:1px black solid; height:40px; width:5px !impotant; } 
.barcode-section{ display:flex; justify-content:space-between; }
.center-section{ display:flex; width:100%;justify-content:center;  }
@media  print {
  #printPageButton, .no-print { display:none !important; }
}

@page {
        size: 3in .85in;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
		padding:0;
    }
html, body { margin: 0; padding:0; }
*{ margin:0; padding:0;}
a{ text-decoration:none; color:#3949ab; }
a:hover{ text-decoration:underline; }
</style>

</head>

<body>
<div class="barcode-wrapper">
<div class="barcode-wrap">
<?php //echo '<pre>';print_r($data);die;?>
    @foreach($data as $row)
    <?php //echo $data; DNS1D::getBarcodeHTML($data, 'CODABAR' )  'PHARMA2T',3,33,'green' 
	?>
	<div class="barcode-box">
    <div class="barcode-name"><div class="name">{{ $row->vendor_sku}}</div> <div class="price">{{ $row->batch_no}}</div></div>
		 {!! DNS1D::getBarcodeHTML($row->id.'-'.$row->item_id, 'CODABAR',2,33) !!}
		<div class="pid">&nbsp;{{ $row->name}}&nbsp;</div>
	</div>
    @endforeach
    <div style="display:flex; justify-content:space-between;" class="no-print"><a href="javascript:void(0)" onclick="history.back()">&#8592 Go Back</a> <a href="javascript:void(0)" id="printPageButton" onClick="window.print();">Print</a></div>
    </div>
</div>
</body>
</html>