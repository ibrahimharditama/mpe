function bool_icon(val)
{
	var ya = '<i class="ti ti-check text-success"></i>';
	var tidak = '<i class="ti ti-close text-danger"></i>';
	return val == 1 ? ya : tidak;
}


$().ready(function() {
	$('input').attr('autocomplete', 'off');
	$('input.control-number').number(true, 0, ',', '.');
	$('input.control-decimal').number(true, 2, ',', '.');
	$('.select2').select2({ allowClear: true });
	$('.select2-no-clear').select2({ allowClear: false });
	$('.select2-tags').select2({ tags: true });
	
	$('.custom-file-input').on('change', function() {
		var file_name = $(this).val().split("\\").pop();
		$(this).siblings('.custom-file-label').addClass('selected').html(file_name);
	});
	
	$('#datatable').on('click', '.btn-delete', function(e) {
		return confirm('Apakah Anda yakin menghapus data ini?');
	});
});
