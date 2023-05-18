<h1 class="my-header">Penjualan</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>No. Transaksi</th>
					<th>Tgl. Nota</th>
					<th>Pelanggan</th>
					<th>No. Pesanan</th>
					<th>Tgl. Pesanan</th>
					<th>Qty<br>Pesan</th>
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
	<a class="btn btn-primary" href="<?php echo site_url('penjualan/faktur/tambah'); ?>">
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
		'ajax': '<?php echo site_url('/penjualan/faktur/datatable'); ?>',
		'order': [[ 2, 'desc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a target="_blank" href="' + site_url + 'penjualan/faktur/cetak/' + row.id +
                        '/faktur/false/true"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				data: 'no_transaksi',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'penjualan/faktur/ubah/' + row.id, data);
				}
			},
			{ data: 'tgl' },
			{ data: 'pelanggan' },
			{ data: 'no_pesanan' },
			{ data: 'tgl_pesanan' },
			{ 
				data: 'qty_pesan', 
				className: 'dt-center',
				render: function (data, type, row, meta) {
					return angka(data);
				}
			},
			{ 
				data: 'qty_kirim', 
				className: 'dt-center',
				render: function (data, type, row, meta) {
					return angka(data);
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