/*
 * =========================
 * Fungsi untuk menghitung sub-total dan total
 * =========================
 */
function count_price()
{
	var $list_unit_price = $('.tb-cell-unit-price');
	var $list_qty = $('.tb-cell-qty');
	var $list_subtotal = $('.tb-cell-subtotal-price');
	
	var total = 0;
	
	$.each ($list_unit_price, function(i, o) {
		var unit_price = parseInt($($list_unit_price[i]).val());
		var qty = parseInt($($list_qty[i]).val());
		
		if (isNaN(unit_price)) {
			harga = 0;
		}
		
		if (isNaN(qty)) {
			qty = 0;
		}
		
		var subtotal = qty * unit_price;
		$($list_subtotal[i]).val(subtotal);
		
		total = total + subtotal;
	});
	
	$('.tb-cell-total-price').val(total);
}


/*
 * =========================
 * ON READY!
 * =========================
 */
$().ready(function() {
	
	/*
	 * =========================
	 * Tambah item baru
	 * =========================
	 */
	$('.table-cell').on('click', '.tb-cell-btn-add', function(e) {
		e.preventDefault();
		
		var $row = $('.table-cell > tbody').find('tr').last();
		
		$row.find('select.select2').select2('destroy');
		
		var $new_row = $row.clone().appendTo('.table-cell > tbody');
		
		$('.select2').select2({ allowClear: true });
		$('input.control-number').number(true, 0, ',', '.');
		
		$new_row.find('.select2').val('');
		$new_row.find('input[type=text]').val('');
	});
	
	/*
	 * =========================
	 * Hapus item
	 * =========================
	 */
	$('.table-cell').on('click', '.tb-cell-btn-remove', function(e) {
		e.preventDefault();
		
		var num_rows = $('.table-cell > tbody').find('tr').length;
		
		var $row = $(this).closest('tr');
		
		if (num_rows == 1) {
			$row.find('.select2').val('').trigger('change');
			$row.find('input[type=text]').val('');
		}
		else {
			$row.remove();
		}
		
		count_price();
	});
	
	/*
	 * =========================
	 * Hitung sub-total dan total
	 * =========================
	 */
	$('.table-cell').on('keyup', '.tb-cell-keyup-count', count_price);
});