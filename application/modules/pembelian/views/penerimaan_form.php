<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Tagihan Pembelian</h1>

<form method="post" action="<?php echo $action_url; ?>">
    <input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

    <div class="row m-0">
        <div class="col-12">
            <div class="row">
                <div class="col-5">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Transaksi</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="no_transaksi" placeholder="Dibuat otomatis"
                                value="<?php if ($data != null) echo $data['no_transaksi']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Tgl. Tagihan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="tgl"
                                value="<?php echo $data != null ? $data['tgl'] : date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0"><span class="text-danger">*</span> Supplier</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control w-75" name="supplier" value="<?php echo $data != null ? $data['supplier'] : ''; ?>" readonly style="display: inline;">
                            <input type="hidden" class="form-control" name="id_supplier" value="<?php echo $data != null ? $data['id_supplier'] : ''; ?>">
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-lookup" id="btn-add-supplier">
                                <i class="ti ti-new-window"></i>
                            </a>

                            <?php if (isset($errors)) echo $errors['id_supplier']; ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Pembelian</label>
                        <div class="col-sm-9">

                            <input type="text" class="form-control w-75" name="pembelian" value="<?php echo $data != null ? $data['pembelian'] : ''; ?>" readonly style="display: inline;">
                            <input type="hidden" class="form-control" name="id_pembelian" value="<?php echo $data != null ? $data['id_pembelian'] : ''; ?>">
                            <input type="hidden" name="id_pembelian_sebelumnya"
                                value="<?php echo $data == null ? '' : $data['id_pembelian'] ?>">
                            <a href="javascript:void(0);" class="btn btn-sm btn-warning btn-lookup" id="btn-add-pembelian">
                                <i class="ti ti-new-window"></i>
                            </a>

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
                        <td><input type="text" name="produk[0][harga_beli]"
                                class="input-box control-number input-count input-harga-beli" style="width:110px"
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
                        <td>
                            <input type="text" class="input-box control-number input-total" style="width:110px" value="0" readonly>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2" align="right">Diskon Faktur</td>
                        <td>
                            <input type="text" name="diskon_faktur" class="input-box control-number input-count input-diskon-faktur"
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
                        <td colspan="3" class="pr-2" align="right" valign="top">Keterangan</td>
                        <td colspan="4">
                            <textarea class="form-control" rows="1" style="min-height: 40px !important;" name="keterangan_biaya_lain"><?php echo $data == null ? '' : $data['keterangan_biaya_lain']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2 semi-bold" align="right">GRAND TOTAL</td>
                        <td><input type="text" id="gtt" class="input-box control-number input-grand-total" value="0"
                                style="width:110px" readonly></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2 semi-bold" align="right">Total Pembayaran</td>
                        <td>
                            <input type="text" name="total_bayar" class="input-box control-number input-total-bayar" value="<?php echo $data == null ? 0 : $data['total_bayar']; ?>" style="width:110px" readonly>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    
                    <tr>
                        <td colspan="3" class="border-bottom-none border-left-none"></td>
                        <td colspan="3" class="pr-2 semi-bold" align="right">Sisa Pembayaran</td>
                        <td>
                            <input type="text" id="sisa_tagihan" class="input-box control-number input-sisa-tagihan" value="0" style="width:110px" readonly>
                        </td>
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

        <a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('pembelian/penerimaan') ?>">
            <i class="ti ti-na"></i> Batalkan
        </a>

        <?php if($data['id'] != "" && $data['id'] != null): ?>
            <a class="btn btn-outline-info  ml-1" target="_blank" href="<?= site_url('pembelian/penerimaan/cetak/' . $data['id']); ?>" >
                <i class="ti ti-printer"></i> Cetak
            </a>

            <a class="btn btn-outline-info ml-1" href="javascript:void(0);" data-toggle="modal" data-target="#modal-pembayaran">
                <i class="ti ti-wallet"></i> Pembayaran
            </a>
        <?php endif; ?>
    </div>

</form>

<div class="modal fade" id="modal-pembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog mw-100 w-75" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-9">
                        <div id="alert-pembayaran"></div>
                        <table class="table table-sm table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Transaksi</th>
                                    <th>Tgl. Bayar</th>
                                    <th>Rek. Bayar</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th colspan="2" align="center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="list-pembayaran">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-3">
                        <form method="post" id="frm-pembayaran"
                            action="<?php echo site_url("pembelian/penerimaan/pembayaran"); ?>">
                            <div class="form-group">
                                <label>No. Transaksi</label>
                                <input type="hidden" name="id_pembayaran" id="id_pembayaran" value="">
                                <input type="hidden" name="id_beli" id="id_beli"
                                    value="<?php if ($data != null) echo $data['id']; ?>">
                                <input type="text" class="form-control" placeholder="Dibuat otomatis" id="no_pembayaran"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label>Tgl. Pembayaran</label>
                                <input type="text" class="form-control input-bayar datepicker" name="tgl_pembayaran"
                                    id="tgl_pembayaran" value="<?=date("Y-m-d");?>" readonly
                                    data-message="<b>Tanggal</b> harus diisi.">
                                <div id="err-tgl_pembayaran" class="err"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12"><label>Rek. Pembayaran</label></div>
                                <div class="col-sm-12">
                                    <select name="rek_pembayaran" class="input-bayar" data-placeholder="Pilih Rekening"
                                        id="rek_pembayaran" data-message="<b>Rekening</b> harus diisi.">
                                        <option value="">- Pilih Rekening -</option>
                                        <?php echo modules::run('options/rekening', ''); ?>
                                    </select>
                                    <div id="err-rek_pembayaran" class="err"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="text" class="form-control input-bayar control-number"
                                    name="nominal_pembayaran" id="nominal_pembayaran" value="0"
                                    data-message="<b>Nominal</b> harus diisi.">
                                <div id="err-nominal_pembayaran" class="err"></div>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea  class="form-control" name="keterangan" id="keterangan_pembayaran"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Simpan Pembayaran</button>
                                <button type="button" class="btn btn-outline-secondary btn-block btn-cancel-upd-pay"
                                    style="display: none;" onclick="resetModalPayment()">Batalkan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Data Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="cell-border stripe order-column hover" id="datatable-supplier" style="width: 100%">
						<thead>
							<tr>
								<th>Supplier</th>
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
				<button type="button" class="btn btn-primary" id="buttonSelectSupplier">Pilih Supplier</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-pembelian" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Data Pembelian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="cell-border stripe order-column hover" id="datatable-pembelian" style="width: 100%">
						<thead>
							<tr>
								<th>No. Transaksi</th>
								<th>Tgl</th>
								<th>Qty Pesan</th>
                                <th>Qty Kirim</th>
                                <th>Total</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>    
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary" id="buttonSelectPembelian">Pilih Pembelian</button>
			</div>
		</div>
	</div>
</div>


<script src="<?php echo site_url('pembelian/penerimaan/js-detail/'.($data == null ? '0' : $data['id'])); ?>"></script>
<script>

    var i = 0;
    var modalPayment = $("#modal-pembayaran");

    $().ready(function() {

        init_details();

        $('.table-item tbody').on('keypress', 'input', function(e) {
            var val = $(this).val();
            if (e.which == 13) {
                e.preventDefault();
                add_row(this, null);
            }
        });

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
            var harga_beli = $('option:selected', this).data('harga-beli');

            $row.find('.input-nama').val(nama);
            $row.find('.input-id-satuan').val(id_satuan);
            $row.find('.input-satuan').val(satuan);
            $row.find('.input-harga-beli').val(harga_beli);
        });

        $(document).on('keyup', '.input-count', function(e) {
            count_total();
        });

        $("#modal-pembayaran").on('show.bs.modal', function() {
            $('#rek_pembayaran').select2({
                width: '100%'
            });
            ajaxLoadPembayaran(site_url + 'pembelian/penerimaan/ajax-load-pembayaran', $("#id_beli").val());
        });

    });

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

    $('#btn-add-supplier').click(function(e) {
        e.preventDefault();   

		$('#modal-supplier').modal('show');

		datatable = $('#datatable-supplier').DataTable({
			ajax: {
				url: site_url + 'options/supplier_db',
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

	$("#modal-supplier").on('hidden.bs.modal', function() {
        datatable.destroy();
    });

	function check_box(obj){
        if($(obj).is(":checked"))
            $(obj).parent().parent().addClass("selected");
        else
            $(obj).parent().parent().removeClass("selected");
    }

	$("#buttonSelectSupplier").click(function() {
    	
        var selected = datatable.rows( { selected: true }).data()[0];
        
		$('[name=supplier]').val(selected.kode + ' - ' + selected.nama);
		$('[name=id_supplier]').val(selected.id);
        $('[name=pembelian]').val('');
		$('[name=id_pembelian]').val('');
        $("#modal-supplier").modal('hide');
        
    });

    $('#btn-add-pembelian').click(function(e) {
        e.preventDefault();  
        var id_supplier =  $('[name=id_supplier]').val();
        var id_pembelian = "<?php echo $data == null ? '' : $data['id_pembelian']; ?>";

        if(id_supplier != "" && id_supplier != null) {

            $('#modal-pembelian').modal('show');

            datatable_pembelian = $('#datatable-pembelian').DataTable({
                ajax: {
                    url: site_url + 'options/pembelian_db',
                    dataSrc: 'datatable.data',
                    data: function (d) {
                        d.id_supplier = id_supplier;
                        d.id_pembelian = id_pembelian;
                    },
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
                        "data": "no_transaksi", 
                    },
                    {
                        "data": "tgl"
                    },
                    {
                        "data": "qty_pesan",
                        "className": "dt-center",
				"render": function (data, type, row, meta) {
					return angka(data);
				}
                    },
                    {
                        "data": "qty_kirim",
                        "className": "dt-center",
                        "render": function (data, type, row, meta) {
                            return angka(data);
                        }
                    },
                    {
                        "data": "grand_total",
                        "className": "dt-body-right",
                        "render": function (data, type, row, meta) {
                            return rupiah(data);
                        } 
                    },
                    
                ],
            });
        
        } else {
            alert('Pilih Supplier terlebih dahulu.');
        }

	    
    });

	$("#modal-pembelian").on('hidden.bs.modal', function() {
        datatable_pembelian.destroy();
    });

	$("#buttonSelectPembelian").click(function() {
    	
        var selected = datatable_pembelian.rows( { selected: true }).data()[0];
		$('[name=pembelian]').val(selected.no_transaksi);
		$('[name=id_pembelian]').val(selected.id);
        generate_detail_produk();
        $("#modal-pembelian").modal('hide');
    });

    function generate_detail_produk() {
        var id_pembelian = $('[name=id_pembelian]').val();
        var table = $('.table-item tbody');
        table.children().not(':first').remove();
        var table_first = table.children().first();
        table_first.find('.select2').val('').trigger('change');
        table_first.find('input[type=text]').val('');

        if(id_pembelian != null && id_pembelian != '') {
            $.post(
                site_url + 'pembelian/penerimaan/ajax-pembelian-detail', {
                    id_pembelian: id_pembelian
                },
                function(response) {

                    var data = response.pembelian;
                    var detail = response.detail;

                    $('[name=diskon_faktur]').val(parseInt(data.diskon_faktur));
                    $('[name=biaya_lain]').val(parseInt(data.biaya_lain));

                    $.each(detail, function(i, o) {
                        let last = table.children().last();
                        add_row(last, o);
                    });

                    if (detail.length > 0) {
                        table.children().first().remove();
                    }

                    count_total();
                }
            );
        }
    }

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
        $new_row.find('.input-harga-beli').attr('name', 'produk[' + i + '][harga_beli]');
        $new_row.find('.input-qty').attr('name', 'produk[' + i + '][qty]');
        $new_row.find('.input-diskon').attr('name', 'produk[' + i + '][diskon]');

        if (data != null) {
            $new_row.find('.select-item').val(data.id_produk).trigger('change');
            $new_row.find('.input-nama').val(data.uraian);
            $new_row.find('.input-id-satuan').val(data.id_satuan);
            $new_row.find('.input-satuan').val(data.satuan);
            $new_row.find('.input-harga-beli').val(data.harga_satuan);
            $new_row.find('.input-qty').val(data.qty);
            $new_row.find('.input-diskon').val(data.diskon);

        }
    }

    function count_total() {
        console.log('penjumlahan otomatis jalan');

        var $list_harga_beli = $('.input-harga-beli');
        var $list_qty = $('.input-qty');
        var $list_diskon = $('.input-diskon');
        var $list_subtotal = $('.input-sub-total');
        var total_bayar = $('.input-total-bayar').val();

        var total = 0;
        var diskon_faktur = parseInt($('.input-diskon-faktur').val());
        var biaya_lain = parseInt($('.input-biaya-lain').val());

        if (isNaN(diskon_faktur)) diskon_faktur = 0;
        if (isNaN(biaya_lain)) biaya_lain = 0;
        if (isNaN(total_bayar)) total_bayar = 0;

        $.each($list_harga_beli, function(i, o) {
            var harga_beli = parseInt($($list_harga_beli[i]).val());
            var qty = parseInt($($list_qty[i]).val());
            var diskon = parseInt($($list_diskon[i]).val());

            if (isNaN(harga_beli)) {
                harga_beli = 0;
            }

            if (isNaN(qty)) {
                qty = 0;
            }

            if (isNaN(diskon)) {
                diskon = 0;
            }

            var subtotal = qty * (harga_beli - diskon);
            $($list_subtotal[i]).val(subtotal);

            total += subtotal;
        });

       

        var grand_total = total - diskon_faktur + biaya_lain;
        var sisa_bayar = grand_total - total_bayar;

        $('.input-total').val(total);
        
        $('.input-grand-total').val(grand_total);
        $('#sisa_tagihan').val(sisa_bayar);
        console.log((grand_total-total_bayar));
    }

    $("#modal-pembayaran").on('hidden.bs.modal', function() {
        resetModalPayment();
    })

    function resetModalPayment() {
        modalPayment.find('#id_pembayaran').val('');
        modalPayment.find('#no_pembayaran').val('');
        modalPayment.find('#tgl_pembayaran').val(moment().format("YYYY-MM-DD"));
        modalPayment.find('#rek_pembayaran').val('').trigger('change');
        modalPayment.find('#nominal_pembayaran').val('');
        modalPayment.find('#nominal_pembayaran').val('');
        modalPayment.find('#keterangan_pembayaran').val('');
        modalPayment.find('.btn-cancel-upd-pay').hide();
        
        var lengthtable = $("#list-pembayaran tr").length;
        var totalbayar = 0;
        if(lengthtable > 0) {
            $('.nominal_byr').each( function(i, v) {
                totalbayar += parseInt(v.value.split('.').join());
            })
        }

        $('.input-total-bayar').val(totalbayar);
        count_total();
    }


    $(document).on('submit', 'form#frm-pembayaran', function(event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var err = validasi($(".input-bayar"));
        if (err == 0) {
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    showAlert({
                        message: data.message,
                        class: data.type
                    });
                    ajaxLoadPembayaran(site_url + 'pembelian/penerimaan/ajax-load-pembayaran', $(
                        "#id_beli").val());
                    resetModalPayment();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert("Error: " + errorThrown);
                }
            });
        }
        return false;
    });

    function showAlert(obj) {
        var html = '<div class="alert alert-' + obj.class + ' alert-dismissible" role="alert">' +
            '   <strong>' + obj.message + '</strong>' +
            '      <img class="float-right" data-dismiss="alert" aria-label="Close" src="<?php echo base_url(); ?>assets/img/del.png">' +
            '   </div>';
        $('#alert-pembayaran').html(html);
        $("#alert-pembayaran").fadeTo(3000, 300).slideUp(300, function() {
            $("#alert-pembayaran").slideUp(300);
        });
    }

    function ajaxLoadPembayaran(url, id) {
        $.get(
            url, {
                id: id
            },
            function(response) {
                if (response.length > 0) {
                    $("#list-pembayaran").empty();
                    $.each(response, function(i, o) {
                        add_row_bayar(o);
                    });
                }
            }
        );
    }

    function add_row_bayar(data) {
        $no = $("#list-pembayaran tr").length;
        $row = `<tr>
                    <td>
                        <input class="id_byr" type="hidden" value="` + data.id + `"/>
                        <input class="nominal_byr" type="hidden" value="` + data.nominal + `"/>` + ($no + 1) + `
                    </td>
                    <td><span class="no_byr">` + data.no_transaksi + `</span></td>
                    <td><span class="tgl_byr">` + data.tgl + `</span></td>
                    <td>
                        <input class="rek_byr" type="hidden" value="` + data.rek_pembayaran + `"/>
                        <span>` + data.no_rekening + ` - ` + data.bank + `</span>
                    </td>
                    <td align="right">Rp<span class="control-number">` + data.nominal + `</span></td>
                    <td><span id="ket_byr">` + data.keterangan + `</span></td>
                    <td width="5px">
                        <img onclick="delTr(this)" src="<?php echo base_url(); ?>assets/img/del.png"></td><td width="5px">
                        <img  onclick="editTr(this)" src="<?php echo base_url(); ?>assets/img/edit.png">
                    </td>
                </tr>`;
        $("#list-pembayaran").append($row);
        $('span.control-number').number(true, 0, ',', '.');
    }

    function delTr(obj) {
        $row = $(obj).parent().parent();
        $id = $row.find('input[class="id_byr"]').val();
        $.post(
            site_url + 'pembelian/penerimaan/hapus-pembayaran', {
                id: $id
            },
            function(response) {
                showAlert({
                    message: 'Pembayaran berhasil di hapus!',
                    class: "danger"
                });
                $row.remove();
            }
        );
    }

    function editTr(obj) {
        $row = $(obj).parent().parent();
        $("#id_pembayaran").val($row.find('input[class="id_byr"]').val());
        $("#no_pembayaran").val($row.find('span[class="no_byr"]').text());
        $("#tgl_pembayaran").val($row.find('span[class="tgl_byr"]').text());
        $("#rek_pembayaran").select2("val", $row.find('input[class="rek_byr"]').val());
        $("#nominal_pembayaran").val($row.find('input[class="nominal_byr"]').val());
        $("#keterangan_pembayaran").val($row.find('span[class="ket_byr"]').text());
        modalPayment.find('.btn-cancel-upd-pay').show();
    }

    function validasi(komponen) {
        $err = 0;
        komponen.each(function() {
            if (!$(this).val()) {
                $(this).addClass("is-invalid");
                $("#err-" + $(this).attr("id")).html($(this).data("message"))
                $err++;
            } else {
                $(this).removeClass("is-invalid");
                $("#err-" + $(this).attr("id")).html("")
            }
        });
        return $err
    }

    

</script>