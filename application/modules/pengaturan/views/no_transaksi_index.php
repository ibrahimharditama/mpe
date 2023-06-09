<h1 class="my-header">Pengaturan No. Transaksi</h1>

<div class="row m-0">
	<div class="col-10">

		<?php if ($this->session->flashdata('post_status') == 'inserted'): ?>
			<div class="alert alert-success">Data berhasil ditambahkan.</div>
		<?php elseif ($this->session->flashdata('post_status') == 'updated'): ?>
			<div class="alert alert-success">Perubahan data berhasil disimpan.</div>
		<?php endif; ?>

		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr class="text-center">
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
		'order': [ 1, 'asc' ],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{ 
				data: 'nama' , 
				"render": function (row, type, val, meta) {
					return buttonUpdate(site_url + 'pengaturan/no-transaksi/ubah/' + val.id, row);
                },
			},
			{ data: 'format' },
			{ data: 'digit_serial',className: 'dt-body-right', },
			{ data: 'is_reset_serial',className: 'dt-body-right', },
			{ data: 'reset_serial',className: 'dt-body-right', },
			{ data: 'tahun_sekarang',className: 'dt-body-right', },
			{ data: 'bulan_sekarang',className: 'dt-body-right', },
			{ 
				data: 'serial_berikutnya',
				className: 'dt-body-right',
				render: function (data, type, row, meta) {
					return angka(data);
				} 
			}
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>