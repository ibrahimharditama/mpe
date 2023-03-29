<h1 class="my-header">Master <?php echo ucfirst($kategori); ?></h1>

<div class="row m-0">
	<div class="col-sm-6">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
                    <th width="5px"></th>
					<th>Nama <?php echo ucfirst($kategori); ?></th>
					<th>Yg Buat</th>
					<th>Yg Ubah</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('master/'.$kategori.'/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/master/'.$kategori.'/datatable'); ?>',
		'stateSave': true,
		'order': [[ 2, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{
                orderable: false,
                render: function(data, type, row, meta) {
					return buttonDelete(site_url + 'master/<?php echo $kategori;?>/hapus/' + row.id);
                }
            },
			{
				data: 'nama',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'master/<?php echo $kategori; ?>/ubah/' + row.id, data);
				}
			},
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>