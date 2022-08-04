@extends('layouts.fms_admin_layouts')
@section('pageTitle', 'Customer List')
@section('pagecontent')

<div class="page">
    <div class="new-width-design new-new-width-design">
        <div class="new-design-inner">
            <div class="new-header-div-fot-btn search-design-data">
                <div class="page-header">
                    <h1 class="page-title">Customer List</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Customer List</li>
                    </ol>
                </div>
                <div class="payment-heading-box1">
                    <form method="POST" action="{{url('admin/customer-list')}}" class="custom-inline-form">
                        @csrf
                        <div class="form-box">
                            <select name="tbl_column" required="" class="form-control" id="tbl_column">
                                <option value="">-- Search By --</option>
                                <option @if($tbl_column=='company_name') selected @endif value="company_name">Company Name</option>
                                <option @if($tbl_column=='email') selected @endif value="email">Email Id</option>
                                <option @if($tbl_column=='contact_number') selected @endif value="contact_number">Contact Number</option>
                            </select>
                        </div>
                        <div class="form-box">
                            <input class="form-control" required="" type="text" name="i_name" value="{{@$i_name}}"
                                autocomplete="off" placeholder="">
                        </div>
                        <div class="form-box form-box-submit"><button class="btn btn-primary waves-effect waves-classic"
                                type="submit" name="order_search">Search</button></div>
                        <div class="form-box form-box-submit"><a href="{{route('customerlist')}}"
                                class="btn btn-primary waves-effect waves-classic" title="Reload" name="reload"><i
                                    class="icon md-refresh" aria-hidden="true"></i></a></div>
                        <div class="form-box  form-box-submit">
                            <a class="btn btn-primary waves-effect waves-classic" style="float:right;"
                                href="{{ route('add_customer') }}"> <i class="icon md-plus" aria-hidden="true"></i>
                                Add</a>
                        </div>
                        <div class="form-box form-box-submit"><a class="btn btn-success waves-effect waves-classic small-buton waves-effect waves-classic" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" title="Import Data"><i class="icon md-upload" aria-hidden="true"></i></a></div>
                        <div class="form-box form-box-submit"><a class="btn btn-success waves-effect waves-classic small-buton waves-effect waves-classic" href="{{url('admin/export_data_master_customer/0')}}" title="Export Data"><i class="icon md-download" aria-hidden="true"></i></a></div>
                </div>
                </form>
            </div>
            <div class="page-content">
                <div class="row"> @if (\Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            <p>{{ \Session::get('success') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="panel">
                    <div class="panel-body"> @if(!empty($customer_list))
                        <table class="table table-bordered t1 custom-lineheight-table">
                            <thead>
                                <th class="bg-info sort ta" id="sl_no" style="color:#fff !important;">Sl. No</th>
                                <!-- <th class="bg-info sort ta" id="block_unblock" style="color:#fff !important;">Block
                                    Unblock</th> -->
                                <th class="bg-info sort ta" id="register_type" style="color:#fff !important;">Register
                                    Type</th>
                                <th class="bg-info sort ta" id="company_name" style="color:#fff !important;">Company
                                    Name</th>
                                <th class="bg-info sort ta" id="email_id" style="color:#fff !important;">Email Id</th>
                                <th class="bg-info sort ta" id="contact_number" style="color:#fff !important;">Contact
                                    Number</th>
                                <th class="bg-info" style="color:#fff !important;">Action</th>
                            </thead>
                            <tbody id="fb_table_body">
                                @php
                                $i=($customer_list->currentpage()-1)* $customer_list->perpage();
                                @endphp
                                @foreach($customer_list as $data)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <!-- <td>@if($data->block_unblock==1) Unblock @else Block @endif</td> -->
                                    <td>@if($data->register_type==1) Register @else Unregister @endif</td>
                                    <td class="white-space-normal">{{$data->company_name}}</td>
                                    <td class="email-td"><span title="{{$data->email}}">{{$data->email}}</span></td>
                                    <td>{{$data->contact_number}}</td>
                                    <td style="text-align:center;" class="action">
                                        <div class="action-wrap">
                                        <span class="btn btn-pure btn-success icon  waves-effect waves-classic">
                                        <i style="font-size:16px; cursor:pointer;" id="{{$data->id}}" title=" @if($data->block_unblock==1) Unblock @else Block @endif" cus="@if($data->block_unblock==1) 2 @else 1 @endif" class="fas fa-check-circle @if($data->block_unblock==1) bluetext @else blacktext @endif status-update" aria-hidden="true"></i></span>
                                            <a title='Edit' href="{{url('admin/edit-customer'.'/'.$data->id)}}"
                                                class="btn btn-pure btn-success icon  waves-effect waves-classic"><i
                                                    class="icon md-edit" aria-hidden="true"></i></a>
                                            <a title='Delete'
                                                onclick="return confirm('Are you sure you want to delete?');"
                                                href="{{url('admin/delete-customer'.'/'.$data->id)}}"
                                                class="btn btn-pure btn-danger icon"><i class="icon md-delete"
                                                    aria-hidden="true"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @php $i++ @endphp

                                @endforeach

                                @else
                                <tr>
                                    <td colspan="8">Result not found.</td>
                                </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                    
                </div>
                
            </div>
            <div class="mt-10">{!! $customer_list->onEachSide(0)->links('pagination::bootstrap-5') !!}</div>
        </div>
        
    </div>
    
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Data</h4>
      </div>
      <form method="post" action="{{url('admin/import_data_master_customer')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$forms[0]['id']}}">
        <input type="hidden" name="table_name" value="{{$forms[0]['table_name']}}">
        <div class="modal-body col-md-12">
          <input type="file" required accept=".csv" name="result_file" class="form-control">
          <small style="float: right;"><a href="{{url('admin/export_data_master_customer/1')}}">Download Sample File</a></small>
        </div>
        <div class="modal-footer">
          <button type="submit" name="importSubmit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('custom_validation_script')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://rawgit.com/padolsey/jQuery-Plugins/master/sortElements/jquery.sortElements.js"></script>
<script>
$(document).ready(function() {
    $("input[type='radio']").click(function() {
        var radioValue = $("input[name='action_data']:checked").val();
        if (radioValue != '') {
            $('.action-data-show').hide();
            $('#action-' + radioValue).show();
        } else {
            $('.action-data-show').hide();
        }
    });
});
</script>
<script>
$(function() {

    var table = $('.t1');

    $('#ticket_number,#department,#customer,#subject,#date_time,#location')

        .wrapInner('<span title=""/>')

        .each(function() {





            var th = $(this),

                thIndex = th.index(),

                inverse = false;



            th.click(function() {

                //alert();

                table.find('td').filter(function() {



                    return $(this).index() === thIndex;



                }).sortElements(function(a, b) {



                    return $.text([a]) > $.text([b]) ?

                        inverse ? -1 : 1

                        :
                        inverse ? 1 : -1;



                }, function() {



                    // parentNode is the element we want to move

                    return this.parentNode;



                });



                inverse = !inverse;

                if (inverse == true) {

                    $('.ta').addClass('sort');

                    $('.ta').removeClass('dsc');

                    $('.ta').removeClass('asc');

                    $(this).addClass('asc');

                    $(this).removeClass('sort');

                    $(this).removeClass('dsc');

                } else if (inverse == false) {

                    $('.ta').addClass('sort');

                    $('.ta').removeClass('dsc');

                    $('.ta').removeClass('asc');

                    $(this).addClass('dsc');

                    $(this).removeClass('sort');

                    $(this).removeClass('asc');

                }



            });



        });

});
</script>
<link href="https://themesofwp.com/hverp/public/css/jquery.datetimepicker.css" rel="stylesheet">
<script type="text/javascript" src="https://themesofwp.com/hverp/public/js/jquery.datetimepicker.full.js"></script>
<script>
jQuery(document).ready(function() {

    jQuery('#quotation_date').datetimepicker({

        timepicker: false,

        format: 'd-m-Y',

        autoclose: true,

        minView: 2

    });

});
</script>
<script>
function insertIntoFms(Id) {
    var cf = confirm('Are you sure you want to insert this TASK into FMS?');
    if (!cf) {
        return false;
    } else {
        $('#loader').show();
        var formData = 'id=' + Id;
        var ajax_url = "{{ url('admin/ajax_insert_task_into_fms')}}";

        $.ajax({

            type: 'POST',

            url: ajax_url,

            data: formData,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(res) {
                //alert(res); return false;
                console.log(res); //return false;

                /* check if data alredy inserted */
                if (res == 'yes') {
                    alert('Data already inserted please check.');
                    location.reload();
                    return false;
                }
                var data = JSON.parse(res);
                $('#loader').hide();
                if (data.status) {
                    $('#del_' + Id).html('');

                    $('#pi_' + Id).html('');

                    $('#pi_' + Id).html(
                        '<a href="javascript:void(0)" class="btn btn-pure btn-success icon  waves-effect waves-classic" title="Data inserted"><i class="icon md-check-circle" aria-hidden="true"></i></a>'
                    );
                }

                console.log(res);

            }

        });

    }
}
</script>
<script>
$(function() {
    $(document).on('click', '.update-net-rate', function() {
        var id = $(this).attr('id');
        var date_rate = $('.date_rate-' + id).val();
        var net_rate = $('.net_rate-' + id).val();
        var date_update = "{{date('d-m-Y')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (id != '' && date_rate != '' && net_rate != '') {
            $.ajax({
                url: "{{ url('admin/netrate_data_update') }}",
                type: "POST",
                data: {
                    id: id,
                    date_rate: date_rate,
                    net_rate: net_rate
                },
                success: function(response) {
                    // alert(response); return false;
                    if (response != '') {
                        $('#date-rate-' + id).html(date_rate);
                        $('#net-rate-' + id).html(net_rate);
                        $('.close-data').trigger('click');
                        $('#updated_on-' + id).html(date_update);
                    }
                }
            });
        }


    });
});
</script>

<script>
    $(function(){
                $(document).on('click','.status-update',function(){
                  var id = $(this).attr('id');
                  var status = $(this).attr('cus');
                  if(status==1){var type="Unblock";}else{var type = "Block";}
                  if(id!='' && status!='')
                  {
                    $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });
                    var verify = confirm("Are you sure you want to "+type+" this customer?");
                    if(verify)
                    {
                        $.ajax({
                        url: "{{ url('admin/ajax_customer_status_update') }}",
                        type: "POST",
                        data: {id:id,status:status},
                        success: function(response) {
                        //alert(response); return false;
                        if (response != '') {
                        window.location.reload(true);
                        }
                        }
                        });
                    }
                  }
                });
    });
</script>

@endsection