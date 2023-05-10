<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Pengiriman</h1>

<form method="post" action="<?php echo $action_url; ?>">
    <input type="hidden" name="id" value="<?php if ($data != null) {echo $data['id'];} ?>">
    <div class="row m-0">
        <div class="col-12">
            
            <?php if ($this->session->flashdata('post_status') == 'approve'): ?>
            <div class="alert alert-success">Stok berhasil di kurangi.</div>
            <?php endif; ?>

            <div class="row">
                <div class="col-5">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Transaksi</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="no_transaksi" placeholder="Dibuat otomatis"
                                value="<?php if ($data != null) {
                                    echo $data['no_transaksi'];
                                } ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Tgl. Pengiriman</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="tgl"
                                value="<?php echo $data != null ? $data['tgl'] : date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0"><span class="text-danger">*</span> Pelanggan</label>
                        <div class="col-sm-9">
						<select class="select2 w-75" name="id_pelanggan"  id="id_pelanggan" data-placeholder="Pilih Pelanggan">
                            </select>
                            <a class="btn btn-sm btn-warning btn-lookup" href="#"><i
                                    class="ti ti-new-window"></i></a>
                            <?php if (isset($errors)) {
                                echo $errors['id_pelanggan'];
                            } ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Nota</label>
                        <div class="col-sm-9">
                            <input type="hidden" name="id_faktur_sebelumnya" value="<?php echo $data == null ? '' : $data['id_faktur']; ?>">
                            <select class="select2 w-75" name="id_faktur" data-placeholder="Pilih Nota Penjualan">
                                <option value=""></option>
                            </select>
                            <a class="btn btn-sm btn-warning btn-lookup" href="#"><i
                                    class="ti ti-new-window"></i></a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Supir</label>
                        <div class="col-sm-9">
                            <select class="select2 w-75" name="person_supir[]" data-placeholder="Pilih Supir" multiple="multiple">
                                <option value=""></option>
                                <?php echo modules::run('options/pengguna', $data == null ? '' : $data['person']['supir']);?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Kenek</label>
                        <div class="col-sm-9">
                            <select class="select2 w-75" name="person_kenek[]" data-placeholder="Pilih Kenek" multiple="multiple">
                                <option value=""></option>
                                <?php echo modules::run('options/pengguna', $data == null ? '' : $data['person']['kenek']); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Teknisi</label>
                        <div class="col-sm-9">
                            <select class="select2 w-75" name="person_teknisi[]" data-placeholder="Pilih Teknisi" multiple="multiple">
                                <option value=""></option>
                                <?php echo modules::run('options/pengguna', $data == null ? '' : $data['person']['teknisi']); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <!-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Alamat Pengiriman</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="alamat"><?php echo $data == null ? '' : $data['alamat']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keterangan"><?php echo $data == null ? '' : $data['keterangan']; ?></textarea>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
		
		<div class="col-12 mt-3">
			<h1 class="my-header">Produk Nota</h1>
            <table class="table-cell table-item-nota">
                <thead>
                    <tr>
                        <th width="320px">Produk</th>
                        <th>Uraian</th>
                        <th>Satuan</th>
                        <th>Qty</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="select2 w-100 select-item-nota" name="nota[0][id]" data-placeholder="">
                                <option value=""></option>
                            </select>
                        </td>
                        <td><input type="text" name="nota[0][uraian]" class="input-box input-nama"
                                style="width:320px" readonly></td>
                        <td>
                            <input type="hidden" name="nota[0][id_satuan]" class="input-id-satuan">
                            <input type="text" name="nota[0][satuan]" class="input-box input-satuan"
                                style="width:100px" readonly>
                        </td>
                        <td><input type="text" name="nota[0][qty]"
                                class="input-box control-number input-count input-qty" style="width:50px"
                                value="0"></td>
                        <td align="center"><a href="#" class="btn btn-info btn-ico btn-row-add-nota">+</a></td>
                        <td align="center"><a href="#" class="btn btn-danger btn-ico btn-row-del-nota"
                                tabindex="-1">x</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-12 mt-3">
			<h1 class="my-header">Produk Pipa</h1>
            <table class="table-cell table-item">
                <thead>
                    <tr>
                        <th width="320px">Produk</th>
                        <th>Uraian</th>
                        <th>Satuan</th>
                        <th>Qty</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="select2 w-100 select-item" name="produk[0][id]" data-placeholder="">
                                <option value=""></option>
                                <?php echo modules::run('options/produk_qty', ''); ?>
                            </select>
                        </td>
                        <td><input type="text" name="produk[0][uraian]" class="input-box input-nama"
                                style="width:320px"></td>
                        <td>
                            <input type="hidden" name="produk[0][id_satuan]" class="input-id-satuan">
                            <input type="text" name="produk[0][satuan]" class="input-box input-satuan"
                                style="width:100px">
                        </td>
                        <td><input type="text" name="produk[0][qty]"
                                class="input-box control-number input-count input-qty" style="width:50px"
                                value="0"></td>
                        <td align="center"><a href="#" class="btn btn-info btn-ico btn-row-add">+</a></td>
                        <td align="center"><a href="#" class="btn btn-danger btn-ico btn-row-del"
                                tabindex="-1">x</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="actionbar fixed-bottom">
        <button type="submit" class="btn btn-primary" id="simpan">
            <i class="ti ti-save"></i> Simpan
        </button>

        <a class="btn btn-outline-info ml-1" id="do-bayar" style="display:none"
            data-toggle="modal" href="#modal-approve" data-id="<?= $data != null ? $data['id'] : ''; ?>">
            <i class="ti-thumb-up"></i>
            Approve
        </a>

        <a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('penjualan/pengiriman'); ?>">
            <i class="ti ti-na"></i> Batalkan
        </a>
    </div>

</form>
<div class="modal" id="modal-approve" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Konfirmasi Approve
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apa anda yakin untuk approve?
                <input type="hidden" id="approve_id" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    onclick="ajaxApprove('<?=base_url()?>penjualan/pengiriman/approve')">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo site_url('penjualan/pengiriman/js-detail/' . ($data == null ? '0' : $data['id'])); ?>"></script>
<script>
    var i = 0;
    var first_load = 1;
    var _obj = [];
	let produk_nota_list = [];
	var details_nota_awal  = [];
	let is_ready = false;

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
        } else {
            $new_row.find('.input-qty').val(1);
        }
    }

	function add_row_nota(el, data) {
        i++;

        var $row = $(el).closest('tr');

        $row.find('select.select2').select2('destroy');

        var $new_row = $row.clone().insertAfter($row);

        $('.select2').select2({
            allowClear: true
        });
        $('input.control-number').number(true, 0, ',', '.');

        // $new_row.find('.select2').val('').trigger('change');
        $new_row.find('input[type=text]').val('');

        $new_row.find('.select-item-nota').attr('name', 'nota[' + i + '][id]');
        $new_row.find('.input-nama').attr('name', 'nota[' + i + '][uraian]');
        $new_row.find('.input-id-satuan').attr('name', 'nota[' + i + '][id_satuan]');
        $new_row.find('.input-satuan').attr('name', 'nota[' + i + '][satuan]');
        $new_row.find('.input-harga-jual').attr('name', 'nota[' + i + '][harga_jual]');
        $new_row.find('.input-qty').attr('name', 'nota[' + i + '][qty]');
        $new_row.find('.input-diskon').attr('name', 'nota[' + i + '][diskon]');
        if (data != null) {
			console.log($new_row.find('.select-item-nota').find('option')[0]);
            $new_row.find('.select-item-nota').val(data.id_produk)//.trigger('change');
            $new_row.find('.input-nama').val(data.uraian);
            $new_row.find('.input-id-satuan').val(data.id_satuan);
            $new_row.find('.input-satuan').val(data.satuan);
            $new_row.find('.input-harga-jual').val(data.harga_satuan);
            $new_row.find('.input-qty').val(data.qty);

			$row.find('select.select2').select2('destroy');
			$('.select2').select2({
				allowClear: true
			});
        }
    }

	function load_row_nota(el, data) {
        i++;

        var $row = $(el).closest('tr');

        $row.find('select.select2').select2('destroy');

        var $new_row = $row.clone().insertAfter($row);

        $('.select2').select2({
            allowClear: true
        });
        $('input.control-number').number(true, 0, ',', '.');

        // $new_row.find('.select2').val('').trigger('change');
        $new_row.find('input[type=text]').val('');

        $new_row.find('.select-item-nota').attr('name', 'nota[' + i + '][id]');
        $new_row.find('.input-nama').attr('name', 'nota[' + i + '][uraian]');
        $new_row.find('.input-id-satuan').attr('name', 'nota[' + i + '][id_satuan]');
        $new_row.find('.input-satuan').attr('name', 'nota[' + i + '][satuan]');
        $new_row.find('.input-harga-jual').attr('name', 'nota[' + i + '][harga_jual]');
        $new_row.find('.input-qty').attr('name', 'nota[' + i + '][qty]');
        $new_row.find('.input-diskon').attr('name', 'nota[' + i + '][diskon]');
        if (data != null) {
            $new_row.find('.select-item-nota').val(data.id)//.trigger('change');
            $new_row.find('.input-nama').val(data.nama);
            $new_row.find('.input-id-satuan').val(data.id_satuan);
            $new_row.find('.input-satuan').val(data.satuan);
            $new_row.find('.input-harga-jual').val(data.harga_satuan);
            $new_row.find('.input-qty').val(data.qty);

			$row.find('select.select2').select2('destroy');
			$('.select2').select2({
				allowClear: true
			});
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

		const nota = $('.table-item-nota tbody');
		$.each(details_nota, function(i, o) {
			console.log(o);
            let last = nota.children().last();
            add_row_nota(last, o);
        });

        if (details_nota.length > 0) {
            nota.children().first().remove();
        }
    }

    function init_awal_details() {
		const nota = $('.table-item-nota tbody');
		$.each(details_nota_awal, function(i, o) {
			console.log(o);
            let last = nota.children().last();
            load_row_nota(last, o);
        });

        if (details_nota_awal.length > 0) {
            nota.children().first().remove();
        }
    }

    function load_pesanan() {
        var id_faktur = '<?php echo $data == null ? 0 : $data['id_faktur']; ?>';
        var is_approve = '<?php echo $data == null ? "0" :  $data['is_approve']; ?>';
        $('[name=id_faktur]').select2({ allowClear: true });
        $('[name=id_faktur]').find('[value!=""]').remove();

        var id_pelanggan = $('[name=id_pelanggan]').val();

        if (id_faktur != 0) {
            $("#do-bayar").css("display", "inline");
        }

        if(is_approve == 1){
            $("#do-bayar").css("display", "none");
        }

        $.post(
            site_url + 'penjualan/pengiriman/ajax-open-faktur', {
                id_pelanggan: id_pelanggan,
                id_faktur: id_faktur
            },
            function(response) {
                $.each(response, function(i, o) {
                    _obj[parseInt(o.id)] = o;
                    var option = new Option(o.no_transaksi + ' -- ' + o.tgl, o.id, false, false);
                    console.log(option);
                    $('[name=id_faktur]').append(option).trigger('change');
                });

                // $('[name=id_faktur]').data('placeholder', 'Pilih Pesanan').select2({ allowClear: true });

                $('[name=id_faktur]').val(id_faktur).trigger('change');
				is_ready = true;
                first_load = 0;
				load_produk_nota(id_faktur,init_details);

				id_faktur = '0';
            }
        );
    }

    function load_faktur() {
        var id_faktur = '<?php echo $data == null ? '0' : $data['id']; ?>';
        $.post(
            site_url + 'penjualan/faktur/ajax-open-faktur', {
                id_faktur: id_faktur
            },
            function(response) {
                $('[name=uang_muka]').val(response.uang_muka);
                $('[name=rek_pembayaran_dp]').val(response.rek_pembayaran_dp).trigger('change');
                $('[id=sisa_tagihan]').val(response.sisa_tagihan);
            }
        );
    }

	async function load_produk_nota(id_nota,callback) {
		try {
			const targetUrl = site_url + "options/produk_nota/"+id_nota+"/1";
			const result = await fetch(targetUrl, {
				method: 'GET'
			});

			result.json()
				.then(
					(result) => {
						console.log(result);
						produk_nota_list = result;
                        details_nota_awal = result
						let options = [{
							text:"",
							id:""
						}];
						for (let index = 0; index < result.length; index++) {
							const element = result[index];
							
							options.push({
								text: element.nama,
								id: element.id,
							});
						}

						$(".select-item-nota").empty().select2({
							data: options
						});

						console.log("before");
						if(callback) {
                            callback()
                        };
						console.log("after");
					},
					(error) => {
						console.log(error);
						alert(error);
					}
				)

		} catch(e) {
			alert("Error on fetch :" + e);
			console.log("error",e);
		}
	}

    $().ready(function() {
        var id_pelanggan = '<?php echo $data == null ? "0" :  $data['id_pelanggan']; ?>';
	    var nama_pelanggan = '<?php echo $data == null ? "0" :  $data['kode_nama']; ?>';
	    var is_approve = '<?php echo $data == null ? "0" :  $data['is_approve']; ?>';
        if(is_approve == 1){
            $("#simpan").css("display", "none");
            $("#do-bayar").css("display", "none");
        }
        // load_faktur();
        $('.datepicker').Zebra_DatePicker({
            offset: [-203, 280]
        });

		$("[name='id_faktur']").change(function(){
			if(is_ready){
				const val = $(this).val();

				const body = $('.table-item-nota tbody');
				body.children().not(':first').remove();

				const first = body.children().first();
				first.find('.select2').empty();
				first.find('.select2').val('').trigger('change');
				first.find('input[type=text]').val('');
				if(val == "") return;
				load_produk_nota(val,init_awal_details);
			}
		});

        $(document).on('change', '[name=id_pelanggan]', function() {
            load_pesanan();
        });

        $(document).on('click', '.btn-row-add', function(e) {
            e.preventDefault();
            add_row(this, null);
        });

		$(document).on('click', '.btn-row-add-nota', function(e) {
            e.preventDefault();
            add_row_nota(this, null);
        });

        $(document).on('click', '.btn-row-del', function(e) {
            e.preventDefault();

            var num_rows = $('.table-item > tbody').find('tr').length;

            var $row = $(this).closest('tr');

            if (num_rows == 1) {
                $row.find('.select2').val('').trigger('change');
                $row.find('input[type=text]').val('');
            } else {
                $row.remove();
            }

            count_total();
        });

		$(document).on('click', '.btn-row-del-nota', function(e) {
            e.preventDefault();

            var num_rows = $('.table-item-nota > tbody').find('tr').length;

			console.log(num_rows);

            var $row = $(this).closest('tr');

            if (num_rows <= 1) {
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
            var qty = $('option:selected', this).data('qty');

            $row.find('.input-nama').val(nama);
            $row.find('.input-id-satuan').val(id_satuan);
            $row.find('.input-satuan').val(satuan);
            $row.find('.input-harga-jual').val(harga_jual);
            $row.find('.input-qty').val((qty ?? 0));
        });

		$(document).on('change', '.select-item-nota', function(e) {
			const t = $(this);

			let detail;
			for (let index = 0; index < produk_nota_list.length; index++) {
				const element = produk_nota_list[index];
				
				if(element.id == t.val()){
					detail = element
					break;
				}
			}

			if(detail){
				var $row = $(this).closest('tr');

				$row.find('.input-nama').val(detail.nama);
				$row.find('.input-id-satuan').val(detail.id_satuan);
				$row.find('.input-satuan').val(detail.satuan);
				$row.find('.input-harga-jual').val(detail.harga_jual);
				$row.find('.input-qty').val(detail.qty);
			}
		});

        $(document).on('keyup', '.input-count', function(e) {
            count_total();
        });

        $("#exampleModal").on('show.bs.modal', function() {
            $('#rek_pembayaran').select2({
                width: '100%'
            });
            ajaxLoadPembayaran(site_url + 'penjualan/faktur/ajax-load-pembayaran', $("#id_faktur")
            .val());
        });
        $('#id_pelanggan').select2({
            allowClear: true
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
        if($("input[name='id']").val() !== ""){
            $('#id_pelanggan').append('<option value='+id_pelanggan+'>'+nama_pelanggan+'</option>');
        }
        load_pesanan();
    });

    $(document).on('submit', 'form#frm-pembayaran', function(event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
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
                ajaxLoadPembayaran(site_url + 'penjualan/faktur/ajax-load-pembayaran', $(
                    "#id_faktur").val());
            },
            error: function(xhr, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            }
        });
        return false;
    });

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
        $row = '<tr>' +
            '<td><input id="id_byr" type="hidden" value="' + data.id +
            '"/><input id="nominal_byr" type="hidden" value="' + data.nominal + '"/>' + ($no + 1) + '</td>' +
            '<td><span id="no_byr">' + data.no_transaksi + '</span></td>' +
            '<td><span id="tgl_byr">' + data.tgl + '</span></td>' +
            '<td><input id="rek_byr" type="hidden" value="' + data.rek_pembayaran + '"/><span id="">' + data
            .no_rekening + " - " + data.bank + '</span></td>' +
            '<td align="right">Rp<span class="control-number">' + data.nominal + '</span></td>' +
            '<td width="5px"><img onclick="delTr(this)" src="<?php echo base_url(); ?>assets/img/del.png"></td><td width="5px"><img  onclick="editTr(this)" src="<?php echo base_url(); ?>assets/img/edit.png"></td>' +
            '</tr>';
        $("#list-pembayaran").append($row);
        $('span.control-number').number(true, 0, ',', '.');
    }

    function validasi(komponen) {
        $err = false;
        komponen.each(function() {
            if ($(this).val() == "") {
                $(this).addClass("is-invalid");
                $err = true;
            } else {
                $(this).removeClass("is-invalid");
                $err = false;
            }
        });
        return $err
    }

    function delTr(obj) {
        $row = $(obj).parent().parent();
        $id = $row.find('input[id="id_byr"]').val();
        $.get(
            site_url + 'penjualan/faktur/hapus-pembayaran', {
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
        $("#id_pembayaran").val($row.find('input[id="id_byr"]').val());
        $("#no_pembayaran").val($row.find('span[id="no_byr"]').text());
        $("#tgl_pembayaran").val($row.find('span[id="tgl_byr"]').text());
        $("#rek_pembayaran").select2("val", $row.find('input[id="rek_byr"]').val());
        $("#nominal_pembayaran").val($row.find('input[id="nominal_byr"]').val());
    }

    function showAlert(obj) {
        var html = '<div class="alert alert-' + obj.class + ' alert-dismissible" role="alert">' +
            '   <strong>' + obj.message + '</strong>' +
            '      <img class="float-right" data-dismiss="alert" aria-label="Close" src="<?php echo base_url(); ?>assets/img/del.png">' +
            '   </div>';
        $('#alert-pembayaran').append(html);
    }

    $('#modal-approve').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#approve_id').val(button.data('id'));
    });

    function ajaxApprove(filename) {
        $.ajax({
            type: 'POST',
            data: {id: $('#approve_id').val()},
            url: filename,
            success: function (data) {
                $('#modal-approve').modal('hide');
                window.location.reload();
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    }

</script>
