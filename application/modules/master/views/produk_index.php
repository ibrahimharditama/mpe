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
                <tr>
                    <th width="5px">No.</th>
                    <th width="5px"></th>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Nama Item</th>
                    <th>Satuan</th>
                    <th>Jenis</th>
                    <th>Merek</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Yg Buat</th>
                    <th>Yg Ubah</th>
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
                    return buttonDelete(site_url + 'master/produk/hapus/' + row.id);
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
                data: 'stok',
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