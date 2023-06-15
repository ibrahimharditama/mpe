<h1 class="my-header">Manajemen Pengguna</h1>

<div class="row m-0">
	<div class="col-8">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr class="text-center">
					<th></th>
					<th width="5px">No.</th>
					<th>Nama Pengguna</th>
					<th>Email</th>
					<th>Username</th>
					<th>Grup Pengguna</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('pengaturan/pengguna/tambah'); ?>">
		+ Tambah Data
	</a>
</div>

<script type="text/javascript">
function init_datatable()
{
	datatable = $('#datatable').DataTable ({
		'bInfo': true,
		'pageLength': 25,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': '<?php echo site_url('/pengaturan/pengguna/datatable'); ?>',
		'stateSave': true,
		'order': [[ 2, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{
                data: "id",
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return buttonDelete(site_url + 'pengaturan/pengguna/delete/' + data);
                }
            },
			{ data: 'nomor', orderable: false },
			{
				data: 'nama',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'pengaturan/pengguna/ubah/' + row.id, data);
				}
			},
			{ data: 'email' },
			{ data: 'username' },
			{ data: 'grup_pengguna' }
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>