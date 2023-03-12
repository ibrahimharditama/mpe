<div class="col-md-8 offset-md-2 p-0 mb-4">
	<div class="card">
		<div class="card-header">
			Pengaturan Menu
			<a href="<?php echo site_url('pengaturan/menu/tambah'); ?>" class="btn btn-primary btn-sm btn-header">
				<i class="ti ti-write"></i> Tambah Data
			</a>
		</div>
		<div class="card-body">
			<table class="cell-border stripe order-column hover" id="datatable">
				<thead>	
					<tr>
						<th width="50px">Aksi</th>
						<th width="10px">No.</th>
						<th>Teks</th>
						<th>URI</th>
						<th>Urutan</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="card-footer credit"><?php echo $this->config->item('credit'); ?></div>
	</div>
</div>

<script type="text/javascript">
function init_datatable()
{
	datatable = $('#datatable').DataTable ({
		'bInfo': true,
		'paging': false,
		'ordering': false,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': '<?php echo site_url('/pengaturan/menu/datatable'); ?>',
		'fixedHeader': true,
		'columns': [
			{
				data: function (row, type, val, meta) {
                    return '' +
                        '<a class="btn btn-action btn-primary" href="'+site_url+'pengaturan/menu/ubah/'+row.id+'">'+
                            '<i class="ti ti-pencil-alt"></i>'+
                        '</a>&nbsp;'+
						'<a class="btn btn-action btn-danger btn-delete" href="#">'+
                            '<i class="ti ti-trash"></i>'+
                        '</a>';
                },
				orderable: false,
				className: 'dt-body-center'
			},
			{ data: 'nomor' },
			{
				data: 'teks',
				render: function(data, type, row, meta) {
					if (row.id_induk != null) {
						return '<span class="ml-4"></span> '+row.teks;
					}
					else {
						return '<b>'+row.teks+'</b>';
					}
				}
			},
			{ data: 'uri' },
			{ data: 'urutan' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>