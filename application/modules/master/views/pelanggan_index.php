<h1 class="my-header">Daftar Pelanggan</h1>

<div class="row m-0">
	<div class="col-12">

		<?php if ($this->session->flashdata('delete_status') == 'ok'): ?>
		<div class="alert alert-success">Data berhasil dihapus.</div>
		<?php elseif ($this->session->flashdata('delete_status') == 'err'): ?>
		<div class="alert alert-danger">Data tidak dapat dihapus karena masih digunakan!</div>
		<?php endif; ?>

		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>Kode</th>
					<th>Nama Pelanggan</th>
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
	<a class="btn btn-primary" href="<?php echo site_url('master/pelanggan/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/master/pelanggan/datatable'); ?>',
		'stateSave': true,
		'order': [[ 3, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },  
			{
                orderable: false,
                render: function(data, type, row, meta) {
					return buttonDelete(site_url + 'master/pelanggan/hapus/' + row.id);
                }
            },
			{ data: 'kode' },
			{
				data: 'nama',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'master/pelanggan/ubah/' + row.id, data);
				}
			},
			{ data: 'alamat_tidy' },
			{ data: 'no_telp' },
			{ data: 'no_hp' },
			{ data: 'email' },
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>