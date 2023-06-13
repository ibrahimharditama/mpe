<h1 class="my-header">Form Pengembalian Pipa</h1>

<form method="post" action="<?= $action_url; ?>" id="form">
    <input type="hidden" name="id" value="<?= $data != null ? $data['id'] : ''; ?>">

    <div class="row m-0">
        <div class="col-8">
            <?php if ($this->session->flashdata('post_status') == 'ok'): ?>
            <div class="alert alert-success">Data berhasil disimpan.</div>
            <?php elseif ($this->session->flashdata('post_status') == 'approve'): ?>
            <div class="alert alert-success">Stok berhasil dikembalikan.</div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="col-4">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Transaksi</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="no_transaksi" placeholder="Dibuat otomatis"
                                value="<?php if ($data != null) echo $data['no_transaksi']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Tgl. Pengembalian</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control datepicker" name="tgl"
                                value="<?php echo $data != null ? $data['tgl'] : date('Y-m-d'); ?>" readonly>
                            <span class="err_tgl"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">No. Pengiriman</label>
                        <div class="col-sm-8">
                            <select class="select2 w-100 id_pengiriman" name="id_pengiriman"
                                data-placeholder="Pilih No. Pengiriman">
                                <option value=""></option>
                                <?php echo modules::run('options/pengiriman_penjualan', !is_null($data) ? $data['id_pengiriman'] : ''); ?>
                            </select>
                            <span class="err_id_pengiriman"></span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pr-0">Keterangan</label>
                        <div class="col-sm-8">
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
                        <th width="430px">Produk</th>
                        <th width="100px">Satuan</th>
                        <th width="180px">Qty Dibawa</th>
                        <th width="180px">Qty Dikembalikan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['detail'])): ?>

                    <?php foreach ($data['detail'] as $row): ?>

                    <tr>
                        <td><span class="ml-2 mr-2"><?= $row['nama_produk']; ?></span></td>
                        <td><span class="ml-2 mr-2"><?= $row['satuan']; ?></span></td>
                        <td align="right"><span class="ml-2 mr-2 control-number"><?= $row['qty_bawa']; ?></span></td>
                        <td>
                            <input type="hidden" name="produk[id_produk][]" value="<?= $row['id_produk']; ?>">
                            <input type="hidden" name="produk[id_satuan][]" value="<?= $row['id_satuan']; ?>">
                            <input type="hidden" name="produk[satuan][]" value="<?= $row['satuan']; ?>">
                            <input type="hidden" name="produk[qty_bawa][]" class="input-qty-bawa"
                                value="<?= $row['qty_bawa']; ?>">
                            <input type="text" name="produk[qty_kembali][]" class="control-number w-100 input-qty"
                                value="<?= $row['qty_kembali']; ?>">
                            <span class="err_qty_kembali"></span>
                        </td>
                    </tr>

                    <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="border-bottom-none border-left-none"></td>
                        <td class="pr-2 semi-bold" align="right">Total</td>
                        <td>
                            <input type="text" name="qty" class="control-number input-total w-100 semi-bold"
                                value="<?= $data != null ? $data['qty'] : 0; ?>" readonly>
                        </td>
                        <td colspan="2"></td>
                    </tr>

                </tfoot>
            </table>
        </div>
    </div>

    <div class="actionbar fixed-bottom">
        <button type="submit" class="btn btn-primary" id="simpan">
            <i class="ti ti-save"></i> Simpan
        </button>

        <a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('penjualan/pengembalian-pipa') ?>">
            <i class="ti ti-na"></i> Batalkan
        </a>

        <?php if($data['id'] != "" && $data['id'] != null): ?>
            <a class="btn btn-outline-info ml-1" target="_blank" href="<?php echo site_url('penjualan/pengembalian-pipa/cetak/'. $data['id']) ?>" >
                <i class="ti ti-printer"></i> Cetak
            </a>
        <?php endif; ?>

        <a class="btn btn-outline-info ml-1" id="do-bayar" style="display:none"
            data-toggle="modal" href="#modal-approve" data-id="<?= $data != null ? $data['id'] : ''; ?>">
            <i class="ti-thumb-up"></i>
            Approve
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
                    onclick="ajaxApprove('<?=base_url()?>penjualan/pengembalian-pipa/approve')">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>
<script>
$('.id_pengiriman').on('select2:select', function() {
    var id_pengiriman = $(this).val();
    $.getJSON(site_url + 'penjualan/pengembalian-pipa/ajax_get_detail_pengembalian/' + id_pengiriman, function(
        result) {
        console.log(result);

        var html = '';
        $.each(result, function(index, row) {
            html += `<tr>
                            <td><span class="ml-2 mr-2">` + row.nama_produk + `</span></td>
                            <td><span class="ml-2 mr-2">` + row.satuan + `</span></td>
                            <td align="right"><span class="ml-2 mr-2 control-number">` + row.qty + `</span></td>
                            <td>
                                <input type="hidden" name="produk[id_produk][]" value="` + row.id_produk + `">
                                <input type="hidden" name="produk[id_satuan][]" value="` + row.id_satuan + `">
                                <input type="hidden" name="produk[satuan][]" value="` + row.satuan + `">
                                <input type="hidden" name="produk[qty_bawa][]" class="input-qty-bawa" value="` + row
                .qty + `">
                                <input type="text" name="produk[qty_kembali][]" class="control-number w-100 input-qty" value="0">
                                <span class="err_qty_kembali"></span>
                            </td>
                        </tr>`;


        });

        $('.table-item tbody').html(html);
        $('input.control-number').number(true, 0, ',', '.');
        qty();

    })
})

$('.id_pengiriman').on('select2:clearing', function() {
    $('.table-item tbody tr').remove();
    qty();
});

function qty() {
    var total = 0;
    var $list_qty_bawa = $('.input-qty-bawa');
    var $list_qty = $('.input-qty');

    $.each($list_qty, function(i, o) {
        var qty_bawa = parseInt($($list_qty_bawa[i]).val());
        var qty = parseInt($($list_qty[i]).val());

        if (qty > qty_bawa) {
            qty = 0;
            $($list_qty[i]).val(0);
            alert("Qty dikembalikan <= Qty dibawa");
        }

        if (isNaN(qty)) {
            qty = 0;
            $($list_qty[i]).val(0);
        }

        total += qty;
    });

    $('.input-total').val(total);

}

$("#form").submit(function(e) {
    e.preventDefault();
    var me = $(this);

    $.ajax({
        url: me.attr('action'),
        type: 'post',
        data: me.serialize(),
        dataType: 'json',
        beforeSend: function() {
            $('button[type=submit]').attr('disabled', 'disabled');
        },
        success: function(response) {
            console.log(response);

            if (response['code'] == 200) {
                window.location.href = response['url'];
            }

            if (response['code'] == 400) {
                var error = response['data'];
                var $err_qty_kembali = $('.err_qty_kembali');

                $(".err_tgl").html(error['tgl']);
                $(".err_id_pengiriman").html(error['id_pengiriman']);

                $(".err_qty_kembali").each(function(i, o) {
                    $($err_qty_kembali[i]).html(error['produk[qty_kembali][' + i + ']'])
                });


            }

            $('button[type=submit]').attr('disabled', false);

        }
    })
})

$().ready(function() {
    var id_pengembalian = '<?php echo $data == null ? '0' : $data['id']; ?>';
    var is_approve = '<?php echo $data == null ? "0" :  $data['is_approve']; ?>';

    if (id_pengembalian != 0) {
        $("#do-bayar").css("display", "inline");
    }

    if(is_approve == 1){
        $("#simpan").css("display", "none");
        $("#do-bayar").css("display", "none");
    }

    $('.datepicker').Zebra_DatePicker({
        offset: [-203, 280]
    });

    $(document).on('keyup', '.input-qty', function(e) {
        qty();
    });
});
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