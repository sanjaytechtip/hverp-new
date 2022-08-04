<table class="table table-striped table-dark table-bordered">
  <tr>
    <th>Sr No.</th>
    <th>Title</th>
  </tr>
  <tbody id="results">
  @foreach($products as $pro)
  <tr>
    <td>{{$pro['vendor_sku']}}</td>
    <td>{{$pro['name']}}</td>
  </tr>
  @endforeach
  </tbody>
</table>
<div id="pagination">
   
</div>
<div class="container">
	
	<div class="box">
	    <ul id="example-2" class="pagination"></ul>
	    <div class="show"></div>
	</div>
	
</div>
<script>
    $('#example-2').pagination({
    total: 5532, 
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
            url: "{{ url('admin/pagination-ajax2') }}" ,
            type: "POST",
            data: {page:options.current},
            success: function( response ) {
            //alert(response); return false;
            if(response!='')
            {
              $('#results').html(response);  
            }
            }
            }); 
    }
});
</script>