<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Nota Penjualan</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-12">
		<div class="row">
			<div class="col-5">
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pr-0">No. Transaksi</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="no_transaksi" placeholder="Dibuat otomatis" value="<?php if ($data != null) echo $data['no_transaksi']; ?>" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pr-0">Tgl. Nota</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" name="tgl" value="<?php echo $data != null ? $data['tgl'] : date('Y-m-d'); ?>" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pr-0"><span class="text-danger">*</span> Pelanggan</label>
					<div class="col-sm-9">
						<select class="select2 w-75" name="id_pelanggan"  id="id_pelanggan" data-placeholder="Pilih Pelanggan">
						</select>
						<a class="btn btn-sm btn-warning btn-lookup" href="#"><i class="ti ti-new-window"></i></a>
						<?php if (isset($errors)) echo $errors['id_pelanggan']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pr-0">No. Pesanan</label>
					<div class="col-sm-9">
						<input type="hidden" name="id_penjualan_sebelumnya" value="<?php echo $data == null ? '' : $data['id_penjualan'] ?>">
						<select class="select2 w-75" name="id_penjualan" data-placeholder="Pilih Pesanan Penjualan">
							<option value=""></option>
						</select>
						<a class="btn btn-sm btn-warning btn-lookup" href="#"><i class="ti ti-new-window"></i></a>
					</div>
				</div>
			</div>
			<div class="col-5">
				<div class="form-group row">
					<label class="col-sm-3 col-form-label pr-0">Keterangan</label>
					<div class="col-sm-9">
						<textarea class="form-control" name="keterangan"><?php echo $data == null ? '' : $data['keterangan']; ?></textarea>
					</div>
				</div>
				<div class="form-group row button-action" style="display: none;">
					<label class="col-sm-3 col-form-label pr-0"></label>
					<div class="col-sm-9">
						<a class="btn btn-outline-info" id="do-bayar" href="#" data-toggle="modal" data-target="#exampleModal">
							<i class="ti ti-wallet"></i>
							Pembayaran
						</a>
						&nbsp;
						<a class="btn btn-outline-info" id="cetak" href="#" data-toggle="modal" data-target="#modalCetak">
							<i class="ti ti-layout-tab"></i>
							Cetak
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 mt-3">
		<table class="table-cell table-item">
			<thead>
				<tr>
					<th width="320px">Produk</th>
					<th>Uraian</th>
					<th>Qty</th>
					<th>Satuan</th>
					<th>Hrg Satuan (Rp)</th>					
					<th>Diskon (Rp)</th>
					<th>Sub-Total (Rp)</th>
					<th colspan="2"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select class="select2 w-100 select-item" name="produk[0][id]" data-placeholder="">
							<option value=""></option>
							<?php echo modules::run('options/produk', ''); ?>
						</select>
					</td>
					<td><input type="text" name="produk[0][uraian]" class="input-box input-nama" style="width:320px"></td>
					<td><input type="text" name="produk[0][qty]" class="input-box control-number input-count input-qty" style="width:50px" value="0"></td>
					<td>
						<input type="hidden" name="produk[0][id_satuan]" class="input-id-satuan">
						<input type="text" name="produk[0][satuan]" class="input-box input-satuan" style="width:100px">
					</td>
					<td><input type="text" name="produk[0][harga_jual]" class="input-box control-number input-count input-harga-jual" style="width:110px" value="0"></td>					
					<td><input type="text" name="produk[0][diskon]" class="input-box control-number input-count input-diskon" style="width:110px" value="0"></td>
					<td><input type="text" class="input-box control-number input-count input-sub-total" style="width:110px" value="0" readonly tabindex="-1"></td>
					<td align="center"><a href="#" class="btn btn-info btn-ico btn-row-add">+</a></td>
					<td align="center"><a href="#" class="btn btn-danger btn-ico btn-row-del" tabindex="-1">x</a></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Total</td>
					<td><input type="text" class="input-box control-number input-total" style="width:110px" value="0" readonly></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Diskon Faktur</td>
					<td><input type="text" name="diskon_faktur" class="input-box control-number input-count input-diskon-faktur" value="<?php echo $data == null ? 0 : $data['diskon_faktur']; ?>" style="width:110px"></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Biaya Lain-lain</td>
					<td><input type="text" name="biaya_lain" class="input-box control-number input-count input-biaya-lain" value="<?php echo $data == null ? 0 : $data['biaya_lain']; ?>" style="width:110px"></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2 semi-bold" align="right">GRAND TOTAL</td>
					<td><input type="text" class="input-box control-number input-grand-total" value="0" style="width:110px" readonly></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Uang Muka</td>
					<td><input type="text" name="uang_muka" class="input-box control-number input-count-dp input-dp" value="0" style="width:110px"></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Rek. Pembayaran DP</td>
					<td colspan="4">
						<select name="rek_pembayaran_dp" data-placeholder="Pilih Rekening" class="select2 w-100 select-item">
							<option value="">- Pilih Rekening -</option>
							<?php echo modules::run('options/rekening', ''); ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="border-bottom-none border-left-none"></td>
					<td colspan="3" class="pr-2" align="right">Sisa Pembayaran</td>
					<td><input type="text" id="sisa_tagihan" class="input-box control-number input-count input-biaya-lain" value="0" style="width:110px" readonly></td>
					<td colspan="2"></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('penjualan/faktur') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog mw-100 w-75" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Pembayaran</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-7">
						<div id="alert-pembayaran"></div>
						<table class="table table-sm table-bordered table-striped">
							<thead>
								<tr>
									<th>No.</th>
									<th>No. Transaksi</th>
									<th>Tgl. Bayar</th>
									<th>Rek. Bayar</th>
									<th>Nominal</th>
									<th colspan="2" align="center">Aksi</th>
								</tr>
							</thead>
							<tbody id="list-pembayaran">
							</tbody>
						</table>
					</div>
					<div class="col-4">
						<form method="post" id="frm-pembayaran" action="<?php echo site_url("penjualan/faktur/pembayaran"); ?>">
							<div class="form-group">
								<label>No. Transaksi</label>
								<input type="hidden" name="id_pembayaran" id="id_pembayaran" value="">
								<input type="hidden" name="id_faktur" id="id_faktur" value="<?php if ($data != null) echo $data['id']; ?>">
								<input type="text" class="form-control" placeholder="Dibuat otomatis" id="no_pembayaran" value="" readonly>
							</div>
							<div class="form-group">
								<label>Tgl. Pembayaran</label>
								<input type="text" class="form-control input-bayar datepicker" name="tgl_pembayaran" id="tgl_pembayaran" value="<?=date("Y-m-d");?>" readonly>
							</div>
							<div class="form-group row">
								<div class="col-sm-12"><label>Rek. Pembayaran</label></div>
								<div class="col-sm-12">
									<select name="rek_pembayaran" class="input-bayar" data-placeholder="Pilih Rekening" id="rek_pembayaran" data-message="<b>Rekening</b> harus diisi.">
										<option></option>
										<?php echo modules::run('options/rekening', ''); ?>
									</select>
									<div id="err-rek_pembayaran" class="err"></div>
								</div>
							</div>
							<div class="form-group">
								<label>Nominal</label>
								<input type="text" class="form-control input-bayar control-number" name="nominal_pembayaran" id="nominal_pembayaran" data-message="<b>Nominal</b> harus diisi." value="0">
								<div id="err-nominal_pembayaran" class="err"></div>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Simpan Pembayaran</button>
								<button type="button" class="btn btn-outline-secondary btn-block btn-cancel-upd-pay" style="display: none;" onclick="resetModalPayment()">Batalkan Perubahan</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalCetak" tabindex="-1" role="dialog" aria-labelledby="modalCetakLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalCetakLabel">Cetak</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<table class="table table-sm table-bordered table-striped">
						<thead>
							<tr>
								<th>Nama File</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Faktur</td>
								<td class="text-center">
									<?= buttonPrint(base_url('penjualan/faktur/cetak/'. $id )); ?>
								</td>
							</tr>
							<tr>
								<td>Faktur tanpa header</td>
								<td class="text-center">
									<?= buttonPrint(base_url('penjualan/faktur/cetak/'. $id .'/faktur/true' )); ?>
								</td>
							</tr>
							<tr>
								<td>Surat jalan</td>
								<td class="text-center">
									<?= buttonPrint(base_url('penjualan/faktur/cetak/'. $id .'/sj' )); ?>
								</td>
							</tr>
							<tr>
								<td>Surat jalan tanpa header</td>
								<td class="text-center">
									<?= buttonPrint(base_url('penjualan/faktur/cetak/'. $id .'/sj/true' )); ?>
								</td>
							</tr>
						</tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo site_url('penjualan/faktur/js-detail/'.($data == null ? '0' : $data['id'])); ?>"></script>
<script>
var i = 0;
var first_load = 1;
var _obj = [];
var modalPayment = $("#exampleModal");

$("#exampleModal").on('hidden.bs.modal', function () {
	resetModalPayment()
})

function resetModalPayment() {
	modalPayment.find('#id_pembayaran').val('');
	modalPayment.find('#no_pembayaran').val('');
	modalPayment.find('#tgl_pembayaran').val(moment().format("YYYY-MM-DD"));
	modalPayment.find('#rek_pembayaran').val('').trigger('change');
	modalPayment.find('#nominal_pembayaran').val('');
	modalPayment.find('.btn-cancel-upd-pay').hide();
}

function add_row(el, data)
{
	i++;
	
	var $row = $(el).closest('tr');
	
	$row.find('select.select2').select2('destroy');
	
	var $new_row = $row.clone().insertAfter($row);
	
	$('.select2').select2({ allowClear: true });
	$('input.control-number').number(true, 0, ',', '.');
	
	$new_row.find('.select2').val('').trigger('change');
	$new_row.find('input[type=text]').val('');
	
	$new_row.find('.select-item').attr('name', 'produk['+i+'][id]');
	$new_row.find('.input-nama').attr('name', 'produk['+i+'][uraian]');
	$new_row.find('.input-id-satuan').attr('name', 'produk['+i+'][id_satuan]');
	$new_row.find('.input-satuan').attr('name', 'produk['+i+'][satuan]');
	$new_row.find('.input-harga-jual').attr('name', 'produk['+i+'][harga_jual]');
	$new_row.find('.input-qty').attr('name', 'produk['+i+'][qty]');
	$new_row.find('.input-diskon').attr('name', 'produk['+i+'][diskon]');
	
	if (data != null) {
		$new_row.find('.select-item').val(data.id_produk).trigger('change');
		$new_row.find('.input-nama').val(data.uraian);
		$new_row.find('.input-id-satuan').val(data.id_satuan);
		$new_row.find('.input-satuan').val(data.satuan);
		$new_row.find('.input-harga-jual').val(data.harga_satuan);
		$new_row.find('.input-qty').val(data.qty);
		$new_row.find('.input-diskon').val(data.diskon);
	}
}

function count_total()
{
	var $list_harga_jual = $('.input-harga-jual');
	var $list_qty = $('.input-qty');
	var $list_diskon = $('.input-diskon');
	var $list_subtotal = $('.input-sub-total');
	
	var total = 0;
	var diskon_faktur = parseInt($('.input-diskon-faktur').val());
	var biaya_lain = parseInt($('.input-biaya-lain').val());
	
	if (isNaN(diskon_faktur)) diskon_faktur = 0;
	if (isNaN(biaya_lain)) biaya_lain = 0;
	
	$.each ($list_harga_jual, function(i, o) {
		var harga_jual = parseInt($($list_harga_jual[i]).val());
		var qty = parseInt($($list_qty[i]).val());
		var diskon = parseInt($($list_diskon[i]).val());
		
		if (isNaN(harga_jual)) {
			harga_jual = 0;
		}
		
		if (isNaN(qty)) {
			qty = 0;
		}
		
		if (isNaN(diskon)) {
			diskon = 0;
		} 
		
		var subtotal = qty * (harga_jual - diskon);
		$($list_subtotal[i]).val(subtotal);
		
		total = total + subtotal;
	});
	
	var grand_total = total - diskon_faktur + biaya_lain;
	
	$('.input-total').val(total);
	$('.input-grand-total').val(grand_total);
}

function count_dp() {
    var gtt = $('.input-grand-total').val();
    var dp = $('.input-dp').val();
    var sisa_bayar = gtt - dp;
    $('#sisa_tagihan').val(sisa_bayar);
}

function init_details()
{
	var $body = $('.table-item tbody');
	
	$.each (details, function(i, o) {
		let last = $body.children().last();
		add_row(last, o);
	});
	
	if (details.length > 0) {
		$body.children().first().remove();
	}
	
	count_total();
}

function load_pesanan()
{
	var id_penjualan = '<?php echo $data == null ? 0 : $data['id_penjualan']; ?>';
	var id_faktur = '<?php echo $data == null ? 0 :  $data['id']; ?>'
	$('[name=id_penjualan]').data('placeholder', 'Memuat data...').select2({ allowClear: true });
	$('[name=id_penjualan]').find('[value!=""]').remove();
	
	var id_pelanggan = $('[name=id_pelanggan]').val();
	
	if(id_faktur != 0){
		$(".button-action").show();
	}

	$.post (
		site_url+'penjualan/faktur/ajax-open-pesanan'
		, { id_pelanggan: id_pelanggan, id_penjualan: id_penjualan }
		, function(response) {
			$.each (response, function(i, o) {
				_obj[parseInt(o.id)] = o;
				
				var option = new Option(o.no_transaksi+' - '+o.tgl, o.id, false, false);
				$('[name=id_penjualan]').append(option).trigger('change');
			});
			
			$('[name=id_penjualan]').data('placeholder', 'Pilih Pesanan').select2({ allowClear: true });
			
			$('[name=id_penjualan]').val(id_penjualan).trigger('change');
			id_penjualan = '0';
			first_load = 0;
		}
	);
}

function load_faktur(){
	var id_faktur = '<?php echo $data == null ? '0' : $data['id']; ?>';
	$.post (
		site_url+'penjualan/faktur/ajax-open-faktur'
		, { id_faktur: id_faktur }
		, function(response) {
			$('[name=uang_muka]').val(response.uang_muka);
			$('[name=rek_pembayaran_dp]').val(response.rek_pembayaran_dp).trigger('change');
			$('[id=sisa_tagihan]').val(response.sisa_tagihan);
		}
	);
}

$().ready(function() {
	var id_pelanggan = '<?php echo $data == null ? "0" :  $data['id_pelanggan']; ?>';
	var nama_pelanggan = '<?php echo $data == null ? "0" :  $data['kode_nama']; ?>';

	$('.table-item tbody').on( 'keypress', 'input', function(e){ 
		var val = $(this).val();
		if(e.which == 13) {
			e.preventDefault();
			add_row(this, null);
		}
	});

	init_details();
	load_pesanan();
	load_faktur();
	$('.datepicker').Zebra_DatePicker({
		offset: [-203, 280]
	});
	
	$(document).on('change', '[name=id_pelanggan]', function() {
		load_pesanan();
	});
	
	$(document).on('change', '[name=id_penjualan]', function() {
		var id_penjualan = $(this).val();
		var $body = $('.table-item tbody');
		
		if (id_penjualan != '' && first_load == 0) {
			$('[name=diskon_faktur]').val(_obj[parseInt(id_penjualan)].diskon_faktur);
			$('[name=biaya_lain]').val(_obj[parseInt(id_penjualan)].biaya_lain);
			
			$body.children().not(':first').remove();
			
			var $first = $body.children().first();
			
			$first.find('.select2').val('').trigger('change');
			$first.find('input[type=text]').val('');
			
			$.post (
				site_url+'penjualan/faktur/ajax-pesanan-detail'
				, { id_penjualan: id_penjualan }
				, function(response) {
					console.log(response);
					$.each(response, function(i, o) {
						let last = $body.children().last();
						add_row(last, o);
					});
					
					if (response.length > 0) {
						$body.children().first().remove();
					}
					
					count_total();
				}
			);
		}
	});
	
	$(document).on('click', '.btn-row-add', function(e) {
		e.preventDefault();
		add_row(this, null);
	});
	
	$(document).on('click', '.btn-row-del', function(e) {
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
		
		count_total();
	});
	
	$(document).on('change', '.select-item', function(e) {
		
		var $row = $(this).closest('tr');
		var nama = $('option:selected', this).data('nama');
		var id_satuan = $('option:selected', this).data('id-satuan');
		var satuan = $('option:selected', this).data('satuan');
		var harga_jual = $('option:selected', this).data('harga-jual');
		
		$row.find('.input-nama').val(nama);
		$row.find('.input-id-satuan').val(id_satuan);
		$row.find('.input-satuan').val(satuan);
		$row.find('.input-harga-jual').val(harga_jual);
	});
	
	$(document).on('keyup', '.input-count', function(e) {
		count_total();
	});
	
	$(document).on('keyup', '.input-count-dp', function(e) {
        count_dp();
    });

	$("#exampleModal").on('show.bs.modal', function () {
		$('#rek_pembayaran').select2({
			 width: '100%'
		});
		ajaxLoadPembayaran(site_url+'penjualan/faktur/ajax-load-pembayaran',$("#id_faktur").val());		
	});

	$('#id_pelanggan').select2({
        placeholder: "Pilih Pelangan",
        ajax: {
            url: site_url + "options/data-pelanggan",
            dataType: 'json',
            data: function (params) {
                var query = {
                    nama: params.term
                }
                return query;
            },
            processResults: function (data, page) {
                return {
                    results: data
                };
            },
        }
    });
	if($("input[name='id']").val() !== 0){
		$('#id_pelanggan').append('<option value='+id_pelanggan+'>'+nama_pelanggan+'</option>');	
	}
});

$(document).on('submit', 'form#frm-pembayaran', function (event) {
	event.preventDefault();
	var form = $(this);
	var data = new FormData($(this)[0]);
	var url = form.attr("action");
	var err = validasi($(".input-bayar"));
	if(err == 0){
		$.ajax({
			type: form.attr('method'),
			url: url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function (data) {
				showAlert({message: data.message, class:data.type});
				ajaxLoadPembayaran(site_url+'penjualan/faktur/ajax-load-pembayaran',$("#id_faktur").val());
				resetModalPayment();
			},
			error: function (xhr, textStatus, errorThrown) {
				alert("Error: " + errorThrown);
			}
		});
	}
	return false;
});
function ajaxLoadPembayaran(url,id) {
	$.get (
			url
			, { id: id }
			, function(response) {
				if (response.length > 0){
					$("#list-pembayaran").empty();
					$.each (response, function(i, o) {
						add_row_bayar(o);
					});
				}
			}
		);
}

function add_row_bayar(data){
	$no = $("#list-pembayaran tr").length;
	$row='<tr>'+
			'<td><input id="id_byr" type="hidden" value="'+data.id+'"/><input id="nominal_byr" type="hidden" value="'+data.nominal+'"/>'+($no+1)+'</td>'+
			'<td><span id="no_byr">'+data.no_transaksi+'</span></td>'+
			'<td><span id="tgl_byr">'+data.tgl+'</span></td>'+
			'<td><input id="rek_byr" type="hidden" value="'+data.rek_pembayaran+'"/><span id="">'+data.no_rekening+" - "+data.bank+'</span></td>'+
			'<td align="right">Rp<span class="control-number">'+data.nominal+'</span></td>'+
			'<td width="5px"><img onclick="delTr(this)" src="<?php echo base_url(); ?>assets/img/del.png"></td><td width="5px"><img  onclick="editTr(this)" src="<?php echo base_url(); ?>assets/img/edit.png"></td>'+
		'</tr>';
	$("#list-pembayaran").append($row);
	$('span.control-number').number(true, 0, ',', '.');
}

function validasi(komponen){
	$err = 0;
	komponen.each(function(){
		if(!$(this).val()){
			$(this).addClass("is-invalid");
			$("#err-"+$(this).attr("id")).html($(this).data("message"))
			$err++;
		}
		else{
			$(this).removeClass("is-invalid");
			$("#err-"+$(this).attr("id")).html("")
		}
	});	
	return $err
}

function delTr(obj){
	$row = $(obj).parent().parent();
	$id = $row.find('input[id="id_byr"]').val();
	$.get (
			site_url+'penjualan/faktur/hapus-pembayaran'
			, { id: $id }
			, function(response) {
				showAlert({message: 'Pembayaran berhasil di hapus!', class:"danger"});
				$row.remove();
			}
		);
}

function editTr(obj){
	$row = $(obj).parent().parent();
	$("#id_pembayaran").val($row.find('input[id="id_byr"]').val());
	$("#no_pembayaran").val($row.find('span[id="no_byr"]').text());
	$("#tgl_pembayaran").val($row.find('span[id="tgl_byr"]').text());
	$("#rek_pembayaran").select2("val",$row.find('input[id="rek_byr"]').val());
	$("#nominal_pembayaran").val($row.find('input[id="nominal_byr"]').val());
	modalPayment.find('.btn-cancel-upd-pay').show();
}

function showAlert(obj){
    var html = '<div class="alert alert-' + obj.class + ' alert-dismissible" role="alert">'+
        '   <strong>' + obj.message + '</strong>'+
        '      <img class="float-right" data-dismiss="alert" aria-label="Close" src="<?php echo base_url(); ?>assets/img/del.png">'+
        '   </div>';
	$('#alert-pembayaran').html(html);
	$("#alert-pembayaran").fadeTo(3000, 300).slideUp(300, function(){
		$("#alert-pembayaran").slideUp(300);
	});
}

</script>