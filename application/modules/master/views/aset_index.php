<h1 class="my-header">Daftar Asset</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th>Nama Pegawai</th>
					<th>Nama Unit</th>
					<th>Model</th>
					<th>Tgl. Perolehan</th>
                    <th>Usia Aset</th>
                    <th>Periode Maintenance</th>
                    <th>Tgl. Perawatan Terakhir</th>
                    <th>Waktu Maintenance</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<!-- <a class="btn btn-primary" href="<?php echo site_url('master/'.$kategori.'/tambah'); ?>">
		+ Tambah Data
	</a> -->
</div>

<script>

    $('#datatable').DataTable({
        ajax: {
            url: base_url + 'master/aset/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
            }
        },
        serverSide: true,
        order: [8, 'asc'],
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
                }
            },
            {
                "data": "pegawai"
            },
            {
                "data": "nama"
            },
            {
                "data": "model"
            },
            {
                "data": "tgl_pembelian"
            },
            {
                "data": null,
                "sortable": false, 
                "searchable": false,
                "render": function(data, type, row, meta) {
                    return monthDiff(row.tgl_pembelian) + ' bulan';
                }
            },
            {
                "data": "periode_maintenance"
            },
            {
                "data": "tgl_maintenance"
            },
            {
                "data": "count_day_maintenance",
                "render": function(data, type, row, meta) {
                    return waktuMaintenance(data, row.tgl_harus_maintenance);
                }
            },
        ],
        
    });

</script>