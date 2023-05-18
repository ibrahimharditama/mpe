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

function waktuMaintenance(totalhari, tanggal) {
	var hari = parseInt(totalhari);
	var stringText = `<font color="Green">`+ tanggal + ` (` + totalhari + ` hari)</font>`;

	if(hari < 0) {
		stringText = `<font color="Crimson">`+ tanggal + ` (` + totalhari + ` hari)</font>`;
	}

	return stringText;
}

function buttonUpdate(url, name) {
	var permissions = $("#permissionmenu").html();
	if (permissions.indexOf("u") < 0) {
		return name;
	}

	return `<a href="` + url + `">` + name + `</a>`;
}

function buttonDelete(url) {
	var permissions = $("#permissionmenu").html();
	if (permissions.indexOf("d") < 0) {
		return '';
	}

	return `<a onclick="return confirm(\'Yakin untuk menghapus?\');" href="` + url + `"><img src="` + site_url + `assets/img/del.png"></a>`;
}

function angka(angka)
{
	return $.number(angka, 0, ',', '.')
}

function rupiah(angka, decimal = false, show_null = false)
{
	if (parseInt(angka) == 0) 
	{
		return show_null ? 'Rp0' : null;
	} else {
		if (parseInt(angka) >= 0) return !decimal ? 'Rp' + $.number(angka, 0, ',', '.') : 'Rp' + $.number(angka, 2, ',', '.');
		else if (parseInt(angka) < 0) return !decimal ? '( Rp' + $.number(angka, 0, ',', '.') + ' )' : '( Rp' + $.number(angka, 2, ',', '.') + ' )';
		else return null;
	}
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

	$(".alert").fadeTo(3000, 500).slideUp(500, function(){
		$(".alert").slideUp(500);
	});

	$('.modal').on('shown.bs.modal', function () {
		$('.datepicker').Zebra_DatePicker({
			offset: [-203, 280]
		});
	})

	var permissions = $("#permissionmenu").html();
	console.log(permissions);

	if (permissions.indexOf("c") < 0) {
		$('a:contains("Tambah Data")').remove();
	}

	if (permissions.indexOf("a") < 0) {
		$('a:contains("Approve")').remove();
	}
});


