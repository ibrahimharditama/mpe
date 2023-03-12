<h1 class="my-header">Pesanan Penjualan</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th>No. Transaksi</th>
					<th>Tgl.</th>
					<th>Tgl. Kirim</th>
					<th>Pelanggan</th>
					<th>Qty<br>Pesan</th>
					<th>Qty<br>Kirim</th>
					<th>Total</th>
					<th>Yg Buat</th>
					<th>Yg Ubah</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('penjualan/pesanan/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/penjualan/pesanan/datatable'); ?>',
		'stateSave': true,
		'order': [[ 1, 'desc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{
				data: 'no_transaksi',
				render: function (data, type, row, meta) {
					return '<a href="'+site_url+'penjualan/pesanan/ubah/'+row.id+'">'+data+'</a>';
				}
			},
			{ data: 'tgl' },
			{ data: 'tgl_kirim' },
			{ data: 'pelanggan' },
			{ data: 'qty_pesan', className: 'dt-center' },
			{ data: 'qty_kirim', className: 'dt-center' },
			{ data: 'grand_total', className: 'dt-body-right' },
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>