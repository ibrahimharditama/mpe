<h1 class="my-header">Tagihan Pembelian</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>No. Transaksi</th>
					<th>Tgl. Terima</th>
					<th>Supplier</th>
					<th>No. Pembelian</th>
					<th>Tgl. Pembelian</th>
					<th>Qty<br>Pesan</th>
					<th>Qty<br>Terima</th>
					<th>Yg Buat</th>
					<th>Yg Ubah</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('pembelian/penerimaan/tambah'); ?>">
		+ Tambah Data
	</a>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Input Serial No.</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table-cell table-item">
					<thead>
						<tr>
							<th class="px-2" width="150px">Produk</th>
							<th class="px-2">Satuan</th>
							<th class="px-2">Qty</th>
							<th class="px-2">Serial No.</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="px-2">Pipa 3*1.5</td>
							<td class="px-2">meter</td>
							<td class="px-2" align="center">1</td>
							<td><input type="text" class="input-box" style="width:150px"></td>
						</tr>
						<tr>
							<td class="px-2">Pipa 3*1.5</td>
							<td class="px-2">meter</td>
							<td class="px-2" align="center">1</td>
							<td><input type="text" class="input-box" style="width:150px"></td>
						</tr>
						<tr>
							<td class="px-2">Pipa 3*1.5</td>
							<td class="px-2">meter</td>
							<td class="px-2" align="center">1</td>
							<td><input type="text" class="input-box" style="width:150px"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
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
		'ajax': '<?php echo site_url('/pembelian/penerimaan/datatable'); ?>',
		'stateSave': true,
		'order': [[ 1, 'asc' ]],
		'fixedHeader': true,
		'columns': [
			{ data: 'nomor', orderable: false },
			{
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a target="_blank" href="' + site_url + 'pembelian/penerimaan/cetak/' + row.id+'"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				data: 'no_transaksi',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'pembelian/penerimaan/ubah/' + row.id, data);
				}
			},
			{ data: 'tgl' },
			{ data: 'supplier' },
			{ 
				data: 'no_pembelian', 
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'pembelian/pesanan/ubah/' + row.id_pembelian, data);
				}
			},
			{ data: 'tgl_pembelian' },
			{ data: 'qty_pesan', className: 'dt-center' },
			{ data: 'qty_terima', className: 'dt-center' },
			{ data: 'yg_buat' },
			{ data: 'yg_ubah' },
		]
	});
}

$().ready(function() {
	
	init_datatable();
	
});
</script>