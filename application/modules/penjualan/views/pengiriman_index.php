<h1 class="my-header">Pesanan Penjualan</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>No. Transaksi</th>
					<th>Tgl.</th>
					<th>Pelanggan</th>
					<th>Qty<br>Kirim</th>
					<th>Yg Buat</th>
					<th>Yg Ubah</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('penjualan/pengiriman/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/penjualan/pengiriman/datatable'); ?>',
		'stateSave': true,
		'order': [[ 1, 'desc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			
			{
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a href="' + site_url + 'penjualan/pengiriman/cetak/' + row.id +
                        '"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				data: 'no_transaksi',
				render: function (data, type, row, meta) {
					return '<a href="'+site_url+'penjualan/pengiriman/ubah/'+row.id+'">'+data+'</a>';
				}
			},
			{ data: 'tgl' },
			{ data: 'pelanggan' },
			{ data: 'qty_pesan', className: 'dt-center' },
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>