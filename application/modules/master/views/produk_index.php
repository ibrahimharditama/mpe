<style>
.btn-circle {
    width: 16px;
    height: 16px;
    padding: 1px 0px;
    border-radius: 15px;
    font-size: 10px;
    text-align: center;
}
</style>
<h1 class="my-header">Daftar Item Inventory</h1>
<div class="row m-0">
    <div class="col-12">
    <?php if ($this->session->flashdata('delete_status') == 'ok'): ?>
    <div class="alert alert-success">Data berhasil dihapus.</div>
    <?php elseif ($this->session->flashdata('delete_status') == 'err'): ?>
    <div class="alert alert-danger">Data tidak dapat dihapus karena masih digunakan!</div>
    <?php endif; ?>
    </div>

    <div class="col-12 mb-2">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipe" value="semua" checked>
            <label class="form-check-label">Semua</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipe" value="inventory">
            <label class="form-check-label">Inventory</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipe" value="jasa">
            <label class="form-check-label">Jasa</label>
        </div>
    </div>
    <div class="col-12">
        <table class="cell-border stripe order-column hover" id="datatable">
            <thead>
                <tr class="text-center">
                    <th width="5px">No.</th>
                    <th width="40px"></th>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Nama Item</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Jenis</th>
                    <th>Merek</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>User Buat</th>
                    <th>User Ubah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="actionbar fixed-bottom">
    <a class="btn btn-primary" href="<?php echo site_url('master/produk/tambah'); ?>">
        + Tambah Data
    </a>
</div>
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="table-detail">
                        <thead style="background-color: rgba(0,0,0,.05)">
                            <tr class="font-weight-bold">
                                <td>No Transaksi</td>
                                <td>Tanggal</td>
                                <td>Dept.</td>
                                <td>Keterangan</td>
                                <td>Masuk</td>
                                <td>Keluar</td>
                                <td>Saldo</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function init_datatable(tipe) {
    datatable = $('#datatable').DataTable({
        'bInfo': true,
        'pageLength': 25,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo site_url('/master/produk/datatable'); ?>',
            'data': function(data) {
                data.tipe =tipe;
            }
        },
        'stateSave': true,
        'order': [
            [2, 'asc']
        ],
        'fixedHeader': true,
        'columns': [{
                data: 'nomor',
                orderable: false
            },
            {
                orderable: false,
                render: function(data, type, row, meta) {
                    return buttonDelete(site_url + 'master/produk/hapus/' + row.id)+ '&nbsp;' +
                        '<a href="javascript:void(0)" onclick="detail(this)" data-id="' + row.id +
                        '" data-name="' + row.nama + ' &middot; ' + row.jenis + '  &middot; ' + row
                        .merek +'" data-periode="<?php echo $periode; ?>" class="btn btn-primary btn-circle btn-circle"><i class="ti-credit-card"></i></a>';
                }
            },
            {
                data: 'kode'
            },
            {
                data: 'tipe'
            },
            {
                data: 'nama',
                render: function(data, type, row, meta) {
                    return buttonUpdate(site_url + 'master/produk/ubah/' + row.id, data);
                }
            },
            {
                data: 'stok',
                className: 'dt-body-right'
            },
            {
                data: 'satuan'
            },
            {
                data: 'jenis'
            },
            {
                data: 'merek'
            },
            {
                data: 'harga_beli',
                className: 'dt-body-right'
            },
            {
                data: 'harga_jual',
                className: 'dt-body-right'
            },
            {
                data: 'yg_buat'
            },
            {
                data: 'yg_ubah'
            },
        ]
    });
}
function detail(ini) {
    var id = $(ini).data('id');
    var periode = $(ini).data('periode');
    var name = $(ini).data('name');

    $.getJSON(site_url + 'laporan/kartustok/ajax_detail/' + id + '/' + periode, function(result) {
        var html = "";

        $.each(result, function(index, row) {
            html += `<tr>
                            <td>` + row.no_transaksi + `</td>
                            <td>` + row.tgl + `</td>
                            <td>` + row.dept + `</td>
                            <td>` + row.keterangan + `</td>
                            <td class="text-right">` + row.masuk + `</td>
                            <td class="text-right">` + row.keluar + `</td>
                            <td class="text-right semi-bold">` + row.saldo + `</td>
                        </tr>`
        });
        $("#modalDetail").find("table tbody").html(html);
        $("#modalDetail").find(".modal-title").html(name);
        $("#modalDetail").modal("show");
    });
}
$().ready(function() {
    var tipe = $('[name=tipe]').val();
    init_datatable(tipe);
});
$(document).on('change', '[name=tipe]', function(e) {
    var tipe = $(this).val();
    $("#datatable").dataTable().fnDestroy();
    init_datatable(tipe);
});
</script>