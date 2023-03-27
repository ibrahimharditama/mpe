<h1 class="my-header">Daftar Supplier</h1>

<div class="row m-0">
    <div class="col-12">
        <table class="cell-border stripe order-column hover" id="datatable">
            <thead>
                <tr>
                    <th width="5px">No.</th>
                    <th width="5px"></th>
                    <th>Kode</th>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>No. Telp.</th>
                    <th>No. HP</th>
                    <th>Email</th>
                    <th>Yg Buat</th>
                    <th>Yg Ubah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="actionbar fixed-bottom">
    <a class="btn btn-primary" href="<?php echo site_url('master/supplier/tambah'); ?>">
        + Tambah Data
    </a>
</div>

<script type="text/javascript">
function init_datatable() {
    datatable = $('#datatable').DataTable({
        'bInfo': true,
        'pageLength': 25,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': '<?php echo site_url('/master/supplier/datatable'); ?>',
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
                    return '<a onclick="return confirm(\'Yakin untuk menghapus?\');" href="' + site_url + 'master/supplier/hapus/' + row.id +
                        '"><img src="<?php echo base_url(); ?>assets/img/del.png"></a>';
                }
            },
            {
                data: 'kode'
            },
            {
                data: 'nama',
                render: function(data, type, row, meta) {
                    return '<a href="' + site_url + 'master/supplier/ubah/' + row.id + '">' + data +
                        '</a>';
                }
            },
            {
                data: 'alamat_tidy'
            },
            {
                data: 'no_telp'
            },
            {
                data: 'no_hp'
            },
            {
                data: 'email'
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

    init_datatable();

});
</script>