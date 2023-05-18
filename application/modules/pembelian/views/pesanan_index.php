<h1 class="my-header">Pesanan Pembelian</h1>

<div class="row m-0">
	<div class="col-12">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
					<th width="5px">No.</th>
					<th width="5px"></th>
					<th>No. Transaksi</th>
					<th>Tgl.</th>
					<th>Tgl. Kirim</th>
					<th>Supplier</th>
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
	<a class="btn btn-primary" href="<?php echo site_url('pembelian/pesanan/tambah'); ?>">
		+ Tambah Data
	</a>
</div>

<script>

	$('#datatable').DataTable({
        ajax: {
            url: site_url + 'pembelian/pesanan/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
            }
        },
        serverSide: true,
        order: [[2, 'desc']],
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        },
        columns: [
            {
				"data": null,
                "sortable": false, 
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
			},
			{
				"data": "id",
                "sortable": false, 
                "searchable": false,
                "render": function(data, type, row, meta) {
                    return '<a target="_blank" href="' + site_url + 'pembelian/pesanan/cetak/' + row.id+'"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				"data": "no_transaksi",
				"render": function (data, type, row, meta) {
					return buttonUpdate(site_url + 'pembelian/pesanan/ubah/' + row.id, data);
				}
			},
			{ "data": "tgl" },
			{ "data": "tgl_kirim" },
			{ "data": "supplier" },
			{ 
				"data": "qty_pesan", 
				"className": "dt-center", 
				"render": function (data, type, row, meta) {
					return angka(data);
				}
			},
			{ 
				"data": "qty_kirim", 
				"className": "dt-center",
				"render": function (data, type, row, meta) {
					return angka(data);
				}
			},
			{ 
				"data": "grand_total", 
				"className": "dt-body-right",
				"render": function (data, type, row, meta) {
					return rupiah(data);
				} 
			},
			{ "data": "yg_buat" },
			{ "data": "yg_ubah" },
        ],
        
    });


</script>