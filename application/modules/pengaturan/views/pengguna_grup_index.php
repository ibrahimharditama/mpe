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
					<th width="50px">Aksi</th>
					<th width="10px">No.</th>
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
			{
				data: function (row, type, val, meta) {
                    return '' +
                        '<a class="btn btn-action btn-primary" href="'+site_url+'pengaturan/pengguna-grup/ubah/'+row.id+'">'+
                            '<i class="ti ti-pencil-alt"></i>'+
                        '</a>&nbsp;'+
						'<a class="btn btn-action btn-danger btn-delete" href="#">'+
                            '<i class="ti ti-trash"></i>'+
                        '</a>';
                },
				orderable: false,
				className: 'dt-body-center'
			},
			{ data: 'nomor', orderable: false },
			{ data: 'nama' },
			{ data: 'urutan' }
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>