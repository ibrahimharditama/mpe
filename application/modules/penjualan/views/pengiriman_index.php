<h1 class="my-header">Pengiriman Penjualan</h1>

<div class="row m-0">
    <div class="col-12">
        <?php if ($this->session->flashdata('post_status') == 'approve'): ?>
        <div class="alert alert-success">Stok berhasil di kurangi.</div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('delete_status') == 'ok'): ?>
        <div class="alert alert-success">Data berhasil dihapus.</div>
        <?php elseif ($this->session->flashdata('delete_status') == 'err'): ?>
        <div class="alert alert-danger">Data tidak dapat dihapus karena masih digunakan!</div>
        <?php endif; ?>
        
        <div class="table-responsive">
        <table class="cell-border stripe order-column hover nowrap" id="datatable">
            <thead>
                <tr class="text-center">
                    <th width="5px">No.</th>
                    <th width="5px"></th>
                    <th>No Transaksi</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Jumlah<br>Kirim</th>
                    <th>Status</th>
                    <th>Supir</th>
                    <th>Kenek</th>
                    <th>Teknisi</th>
                    <th>User Buat</th>
                    <th>User Ubah</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        </div>
    </div>
</div>

<div class="actionbar fixed-bottom">
    <a class="btn btn-primary" href="<?php echo site_url('penjualan/pengiriman/tambah'); ?>">
        + Tambah Data
    </a>
</div>

<script>
    $('#datatable').DataTable({
        ajax: {
            url: site_url + 'penjualan/pengiriman/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
            }
        },
        pageLength: 50,
        serverSide: true,
        order: [[2, 'desc']],
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        },
        columns: [
            {
				"data": null,
                "sortable": false, 
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
			},
			{
				"data": "id",
                "sortable": false, 
                "searchable": false,
                "render": function(data, type, row, meta) {
                    return buttonDelete(site_url + 'penjualan/pengiriman/delete/' + row.id + '/' + row.id_faktur)+'&nbsp;<a target="_blank" href="' + site_url + 'penjualan/pengiriman/cetak/' + row.id+'"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				"data": "no_transaksi",
				"render": function (data, type, row, meta) {
					return buttonUpdate(site_url + 'penjualan/pengiriman/ubah/' + row.id, data);
				}
			},
			{ 
                "data": "tgl", 
                render: function (data, type, row, meta) {
                   return moment(data).format("DD-MM-YYYY");
                } 
            },
			{ "data": "pelanggan" },
			{ 
				"data": "qty_semua", 
				"className": "dt-body-right", 
				"render": function (data, type, row, meta) {
					return angka(data);
				}
			},
            { 
                "data": "is_approve" ,
				"render": function (data, type, row, meta) {
					return data == 1 ? "APPROVED" : "";
				}
            },
            { "data": "supir" },
            { "data": "kenek" },
            { "data": "teknisi" },
			{ 
				"data": "yg_buat", 
            },
			{ 
				"data": "yg_ubah",
			},
        ],
        
    });
</script>