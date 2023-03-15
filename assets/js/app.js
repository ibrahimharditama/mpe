function bool_icon(val)
{
	var ya = '<i class="ti ti-check text-success"></i>';
	var tidak = '<i class="ti ti-close text-danger"></i>';
	return val == 1 ? ya : tidak;
}

function monthDiff(date) {
	var today = moment(new Date());
	var tgl   = moment(new Date(date));

	return today.diff(tgl, 'months');
}


$().ready(function() {
	$('input').attr('autocomplete', 'off');
	$('input.control-number').number(true, 0, ',', '.');
	$('input.control-decimal').number(true, 2, ',', '.');
	$('.select2').select2({ allowClear: true });
	$('.select2-no-clear').select2({ allowClear: false });
	$('.select2-tags').select2({ tags: true });
	$('.select2-modal').select2({ allowClear: true, dropdownParent: $('.modal') });
    $('.select2-modal-noclear').select2({ allowClear: false, dropdownParent: $('.modal') });

	
	$('.custom-file-input').on('change', function() {
		var file_name = $(this).val().split("\\").pop();
		$(this).siblings('.custom-file-label').addClass('selected').html(file_name);
	});
	
	$('#datatable').on('click', '.btn-delete', function(e) {
		return confirm('Apakah Anda yakin menghapus data ini?');
	});

	$(".alert").fadeTo(3000, 300).slideUp(300, function(){
		$(".alert").slideUp(300);
	});

	$('.modal').on('shown.bs.modal', function () {
		$('.datepicker').Zebra_DatePicker({
			offset: [-203, 280]
		});
	})
});


