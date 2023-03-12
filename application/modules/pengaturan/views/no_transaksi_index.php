<div class="col-md-10 offset-md-1 p-0 mb-4">
	<div class="card">
		<div class="card-header">
			Pengaturan No. Transaksi
		</div>
		<div class="card-body">
			<?php if ($this->session->flashdata('post_status') == 'inserted'): ?>
			<div class="alert alert-success">Data berhasil ditambahkan.</div>
			<?php elseif ($this->session->flashdata('post_status') == 'updated'): ?>
			<div class="alert alert-success">Perubahan data berhasil disimpan.</div>
			<?php endif; ?>
			
			<table class="cell-border stripe order-column hover" id="datatable">
				<thead>	
					<tr>
						<th width="25px">Ubah</th>
						<th width="10px">No.</th>
						<th>Nama Transaksi</th>
						<th>Format</th>
						<th>Digit<br>Serial</th>
						<th>Reset<br>Serial?</th>
						<th>Periode<br>Reset</th>
						<th>Tahun</th>
						<th>Bulan</th>
						<th>Serial<br>Berikutnya</th>
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
		'pageLength': 25,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': '<?php echo site_url('/pengaturan/no-transaksi/datatable'); ?>',
		'order': [[ 2, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{
				data: function (row, type, val, meta) {
                    return '' +
                        '<a class="btn btn-action btn-primary" href="'+site_url+'pengaturan/no-transaksi/ubah/'+row.id+'">'+
                            '<i class="ti ti-pencil-alt"></i>'+
                        '</a>';
                },
				orderable: false,
				className: 'dt-body-center'
			},
			{ data: 'nomor', orderable: false },
			{ data: 'nama' },
			{ data: 'format' },
			{ data: 'digit_serial' },
			{ data: 'is_reset_serial' },
			{ data: 'reset_serial' },
			{ data: 'tahun_sekarang' },
			{ data: 'bulan_sekarang' },
			{ data: 'serial_berikutnya' }
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>