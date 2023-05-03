<h1 class="my-header">Pengiriman Penjualan</h1>

<div class="row m-0">
    <div class="col-12">
        <?php if ($this->session->flashdata('post_status') == 'approve'): ?>
        <div class="alert alert-success">Stok berhasil di kurangi.</div>
        <?php endif; ?>
        <table class="cell-border stripe order-column hover" id="datatable">
            <thead>
                <tr>
                    <th width="5px">No.</th>
                    <th width="5px"></th>
                    <th>No. Transaksi</th>
                    <th>Tgl.</th>
                    <th>Pelanggan</th>
                    <th>Qty<br>Kirim</th>
                    <th>Yg Buat</th>
                    <th>Yg Ubah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="actionbar fixed-bottom">
    <a class="btn btn-primary" href="<?php echo site_url('penjualan/pengiriman/tambah'); ?>">
        + Tambah Data
    </a>
</div>
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

<script type="text/javascript">
function init_datatable() {
    datatable = $('#datatable').DataTable({
        'bInfo': true,
        'pageLength': 25,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': '<?php echo site_url('/penjualan/pengiriman/datatable'); ?>',
        'stateSave': true,
        'order': [
            [1, 'desc']
        ],
        'fixedHeader': true,
        'columns': [{
                data: 'nomor',
                orderable: false
            },

            {
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a href="' + site_url + 'penjualan/pengiriman/cetak/' + row.id +
                        '" target="_blank"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
            {
                data: 'no_transaksi',
                render: function(data, type, row, meta) {
                    return '<a href="' + site_url + 'penjualan/pengiriman/ubah/' + row.id + '">' +
                        data+ '</a>';
                }
            },
            {
                data: 'tgl'
            },
            {
                data: 'pelanggan'
            },
            {
                data: 'qty_semua',
                className: 'dt-center'
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
$('#modal-approve').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    $('#approve_id').val(button.data('id'));
});

function ajaxApprove(filename) {
    $.ajax({
        type: 'POST',
        data: {
            id: $('#approve_id').val()
        },
        url: filename,
        success: function(data) {
            $('#modal-approve').modal('hide');
            window.location.reload();
        },
        error: function(xhr, status, error) {
            alert(xhr.responseText);
        }
    });
}

$().ready(function() {

    init_datatable();

});
</script>