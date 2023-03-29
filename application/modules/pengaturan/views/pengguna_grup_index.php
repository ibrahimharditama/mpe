<h1 class="my-header">Pengaturan Grup Pengguna</h1>

<div class="row m-0">
	<div class="col-6">

		<?php if ($this->session->flashdata('post_status') == 'inserted'): ?>
			<div class="alert alert-success">Data berhasil ditambahkan.</div>
		<?php elseif ($this->session->flashdata('post_status') == 'updated'): ?>
			<div class="alert alert-success">Perubahan data berhasil disimpan.</div>
		<?php endif; ?>

		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
                    <th width="5px"></th>
					<th>Nama Grup Pengguna</th>
					<th width="50px">Urutan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('pengaturan/pengguna-grup/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/pengaturan/pengguna-grup/datatable'); ?>',
		'order': [[ 3, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a onclick="return confirm(\'Yakin untuk menghapus?\');" href="' +
                        site_url + 'pengaturan/pengguna-grup/hapus/' + row.id +
                        '"><img src="<?php echo base_url(); ?>assets/img/del.png"></a>';
                }
            },
			{ data: 'nama', render: function(data, type, row, meta) {
                    return '<a href="' + site_url + 'pengaturan/pengguna-grup/ubah/' + row.id + '">' + data +
                        '</a>';
                } },
			{ data: 'urutan' }
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>