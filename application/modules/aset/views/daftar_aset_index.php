<h1 class="my-header">Daftar Asset</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr class="text-center">
					<th width="5px">No.</th>
					<th>Nama Unit</th>
					<th>Model</th>
					<th>Tanggal Perolehan</th>
                    <th>Usia Aset</th>
                    <th>Periode Maintenance</th>
                    <th>Nama Pegawai</th>
                    <th>Tanggal Perawatan Terakhir</th>
                    <th>Waktu Maintenance</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('aset/daftaraset/excel'); ?>">
		<i class="ti ti-file"> Excel</i>
	</a>
</div>

<script>

    $('#datatable').DataTable({
        ajax: {
            url: site_url + 'aset/daftaraset/datatable',
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
                "data": "nama", 
                "render": function (data, type, row, meta) {
                    return data + " - " + row.pegawai;
                }
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
                "data": "pegawai"
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