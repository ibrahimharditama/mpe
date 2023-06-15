<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Pesanan Penjualan</h1>

<form method="post" action="<?php echo $action_url; ?>">
    <input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

    <div class="row m-0">
        <div class="col-12">
            <div class="row">
                <div class="col-5">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No Transaksi</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="no_transaksi" placeholder="Dibuat otomatis"
                                value="<?php if ($data != null) echo $data['no_transaksi']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Tanggal Pesanan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="tgl"
                                value="<?php echo $data != null ? $data['tgl'] : date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Tanggal Kirim</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="tgl_kirim"
                                value="<?php echo $data != null ? $data['tgl_kirim'] : date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0"><span class="text-danger">*</span> Pelanggan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control w-75" name="pelanggan" value="<?php echo $data != null ? $data['pelanggan'] : ''; ?>" readonly style="display: inline;">
                            <input type="hidden" class="form-control" name="id_pelanggan" value="<?php echo $data != null ? $data['id_pelanggan'] : ''; ?>">
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-lookup" id="btn-add-pelanggan">
                                <i class="ti ti-new-window"></i>
                            </a>
                            <?php if (isset($errors)) echo $errors['id_pelanggan']; ?>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control"
                                name="keterangan"><?php echo $data == null ? '' : $data['keterangan']; ?></textarea>
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
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Harga Satuan (Rp)</th>
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
                                <?php echo modules::run('options/produk', '', 20); ?>
                            </select>
                        </td>
                        <td><input type="text" name="produk[0][uraian]" class="input-box input-nama"
                                style="width:320px"></td>
                        <td><input type="text" name="produk[0][qty]"
                                class="input-box control-number input-count input-qty" style="width:50px" value="0">
                        </td>
                        <td>
                            <input type="hidden" name="produk[0][id_satuan]" class="input-id-satuan">
                            <input type="text" name="produk[0][satuan]" class="input-box input-satuan"
                                style="width:100px">
                        </td>
                        <td><input type="text" name="produk[0][harga_jual]"
                                class="input-box control-number input-count input-harga-jual" style="width:110px"
                                value="0"></td>
                        <td><input type="text" name="produk[0][diskon]"
                                class="input-box control-number input-count input-diskon" style="width:110px" value="0">
                        </td>
                        <td><input type="text" class="input-box control-number input-count input-sub-total"
                                style="width:110px" value="0" readonly tabindex="-1"></td>
                        <td align="center"><a href="#" class="btn btn-info btn-ico btn-row-add">+</a></td>
                        <td align="center"><a href="#" class="btn btn-danger btn-ico btn-row-del" tabindex="-1">x</a>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2" align="right">Total</td>
                        <td><input type="text" class="input-box control-number input-total" style="width:110px"
                                value="0" readonly></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2" align="right">Diskon Faktur</td>
                        <td><input type="text" name="diskon_faktur"
                                class="input-box control-number input-count input-diskon-faktur"
                                value="<?php echo $data == null ? 0 : $data['diskon_faktur']; ?>" style="width:110px">
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2" align="right">Biaya Lain-lain</td>
                        <td><input type="text" name="biaya_lain"
                                class="input-box control-number input-count input-biaya-lain"
                                value="<?php echo $data == null ? 0 : $data['biaya_lain']; ?>" style="width:110px"></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2 semi-bold" align="right">GRAND TOTAL</td>
                        <td><input type="text" class="input-box control-number input-grand-total" value="0"
                                style="width:110px" readonly></td>
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

        <a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('penjualan/pesanan') ?>">
            <i class="ti ti-na"></i> Batalkan
        </a>

        <?php if($data['id'] != "" && $data['id'] != null): ?>
            <a class="btn btn-outline-info ml-1" id="cetak" href="javascript:void(0);" data-toggle="modal" data-target="#modalCetak">
                <i class="ti ti-printer"></i> Cetak
            </a>
        <?php endif; ?>
    </div>

</form>

<div class="modal fade" id="modalCetak" tabindex="-1" role="dialog" aria-labelledby="modalCetakLabel"
    aria-hidden="true">
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
                            <td>Surat Penawaran</td>
                            <td class="text-center">
                                <?= buttonPrint(base_url('penjualan/pesanan/cetak/'. $id.'/penawaran' )); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Faktur Penjualan</td>
                            <td class="text-center">
                                <?= buttonPrint(base_url('penjualan/pesanan/cetak/'. $id.'/faktur' )); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-pelanggan" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Data Pelanggan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="cell-border stripe order-column hover" id="datatable-pelanggan" style="width: 100%">
						<thead>
							<tr>
								<th>Pelanggan</th>
								<th>Alamat</th>
								<th>No. Telp / No. HP</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>    
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary" id="buttonSelectPelanggan">Pilih Pelanggan</button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo site_url('penjualan/pesanan/js-detail/'.($data == null ? '0' : $data['id'])); ?>"></script>
<script>
    var i = 0;

    function add_row(el, data) {
        i++;

        var $row = $(el).closest('tr');

        $row.find('select.select2').select2('destroy');

        var $new_row = $row.clone().insertAfter($row);

        $('.select2').select2({
            allowClear: true
        });
        $('input.control-number').number(true, 0, ',', '.');

        $new_row.find('.select2').val('').trigger('change');
        $new_row.find('input[type=text]').val('');

        $new_row.find('.select-item').attr('name', 'produk[' + i + '][id]');
        $new_row.find('.input-nama').attr('name', 'produk[' + i + '][uraian]');
        $new_row.find('.input-id-satuan').attr('name', 'produk[' + i + '][id_satuan]');
        $new_row.find('.input-satuan').attr('name', 'produk[' + i + '][satuan]');
        $new_row.find('.input-harga-jual').attr('name', 'produk[' + i + '][harga_jual]');
        $new_row.find('.input-qty').attr('name', 'produk[' + i + '][qty]');
        $new_row.find('.input-diskon').attr('name', 'produk[' + i + '][diskon]');

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

    function count_total() {
        var $list_harga_jual = $('.input-harga-jual');
        var $list_qty = $('.input-qty');
        var $list_diskon = $('.input-diskon');
        var $list_subtotal = $('.input-sub-total');

        var total = 0;
        var diskon_faktur = parseInt($('.input-diskon-faktur').val());
        var biaya_lain = parseInt($('.input-biaya-lain').val());

        if (isNaN(diskon_faktur)) diskon_faktur = 0;
        if (isNaN(biaya_lain)) biaya_lain = 0;

        $.each($list_harga_jual, function(i, o) {
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

    function init_details() {
        var $body = $('.table-item tbody');

        $.each(details, function(i, o) {
            let last = $body.children().last();
            add_row(last, o);
        });

        if (details.length > 0) {
            $body.children().first().remove();
        }

        count_total();
    }

    $().ready(function() {
        var id_pesanan = '<?php echo $data == null ? "0" :  $data['id']; ?>';
        var id_pelanggan = '<?php echo $data == null ? "0" :  $data['id_pelanggan']; ?>';

        $('.table-item tbody').on('keypress', 'input', function(e) {
            var val = $(this).val();
            if (e.which == 13) {
                e.preventDefault();
                add_row(this, null);
            }
        });

        init_details();

        $('.datepicker').Zebra_DatePicker({
            offset: [-203, 280]
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
            } else {
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

        
    });

    $('#btn-add-pelanggan').click(function(e) {
        e.preventDefault();   

		$('#modal-pelanggan').modal('show');

		datatable = $('#datatable-pelanggan').DataTable({
			ajax: {
				url: site_url + 'options/pelanggan_db',
				dataSrc: 'datatable.data',
				data: function (d) {},
			},
			serverSide: true,
			processing: true,
			order: [[0, 'asc']],
			language: {
				searchPlaceholder: 'Search...',
				sSearch: '',
				lengthMenu: '_MENU_ items/page',
			},
			select: true,
			columns: [
				{
					"data": "nama", 
					"render": function(data, type, row, meta) {
						return row.kode + ' - ' + data;
					}
				},
				{
					"data": "alamat"
				},
				{
					"data": "kontak"
				},
				
			],
		});

	    
    });

	$("#modal-pelanggan").on('hidden.bs.modal', function() {
        datatable.destroy();
    });

	function check_box(obj){
        if($(obj).is(":checked"))
            $(obj).parent().parent().addClass("selected");
        else
            $(obj).parent().parent().removeClass("selected");
    }

	$("#buttonSelectPelanggan").click(function() {
    	
        var selected = datatable.rows( { selected: true }).data()[0];
        
		$('[name=pelanggan]').val(selected.kode + ' - ' + selected.nama);
		$('[name=id_pelanggan]').val(selected.id);
        $("#modal-pelanggan").modal('hide');
    });
</script>