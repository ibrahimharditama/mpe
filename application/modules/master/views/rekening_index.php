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

<h1 class="my-header">Daftar Rekening</h1>

<div class="row m-0">
    <div class="col-8">
        <table class="cell-border stripe order-column hover" id="datatable">
            <thead>
                <tr>
                    <th width="5px">No.</th>
                    <th width="40px"></th>
                    <th>Kode</th>
                    <th>Bank</th>
                    <th>No Rekening</th> 
                    <th>Atas Nama</th>
                    <th>Rekening Faktur?</th>
                    <th>User Buat</th>
                    <th>User Ubah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="actionbar fixed-bottom">
    <a class="btn btn-primary" href="<?php echo site_url('master/rekening/tambah'); ?>">
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
        'ajax': '<?php echo site_url('/master/rekening/datatable'); ?>',
        'stateSave': true,
        'order': [
            [3, 'asc']
        ],
        'fixedHeader': true,
        'columns': [{
                data: 'nomor',
                orderable: false
            },
            {
                orderable: false,
                render: function(data, type, row, meta) {
                    return buttonDelete(site_url + 'master/rekening/hapus/' + row.id)+'&nbsp;'+
                        '<a href="' + site_url + 'master/rekening/is_rekening_faktur/' + row.id +
                        '" class="btn btn-primary btn-circle btn-circle"><i class="ti-thumb-up"></i></a>';
                }
            },
            {
                data: 'kode'
            },
            {
                data: 'bank'
            },
            {
                data: 'no_rekening',
                render: function(data, type, row, meta) {
                    return buttonUpdate(site_url + 'master/rekening/ubah/' + row.id, data);
                }
            },
            {
                data: 'nama'
            },
            {
                data: 'is_use',
                "class": 'dt-body-center',
                render: function(data, type, row, meta) {
                    return data == 1 ? '<i class="ti-check"></i>' : '';
                }
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