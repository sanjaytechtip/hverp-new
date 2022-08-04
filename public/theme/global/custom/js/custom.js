$(document).ready(function(){
		$('#fmsdataTable tr').on('click', function(){
		$('#fmsdataTable tr').removeClass('active_row');
		$(this).addClass('active_row');
	});
})