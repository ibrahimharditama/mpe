<h1 class="my-header">Penjualan</h1>

<div class="row m-0">
	<div class="col-12">

		<div class="togle-datatable-inv mb-3">
			Toggle column: <a href="javascript:void(0);" class="toggle-vis" data-column="2" >No. Transaksi</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="3">Tgl. Nota</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="4">No. Pesanan</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="5">Tgl. Pesanan</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="6">Pelanggan</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="7">Keterangan</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="8">Total</a> - <a href="javascript:void(0);" class="toggle-vis text-dark" data-column="9">Qty Pesan</a> - <a href="javascript:void(0);" class="toggle-vis" data-column="10">Qty Kirim</a> - <a href="javascript:void(0);" class="toggle-vis text-dark" data-column="11">Yg Buat</a> - <a href="javascript:void(0);" class="toggle-vis text-dark" data-column="12">Yg Ubah</a>
		</div>

		<div class="table-responsive">
			<table class="cell-border stripe order-column hover" id="datatable">
				<thead>	
					<tr>
						<th width="5px">No.</th>
						<th width="5px"></th>
						<th>No. Transaksi</th>
						<th>Tgl. Nota</th>
						<th>No. Pesanan</th>
						<th>Tgl. Pesanan</th>
						<th>Pelanggan</th>
						<th>Keterangan</th>
						<th>Total</th>
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
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?php echo site_url('penjualan/faktur/tambah'); ?>">
		+ Tambah Data
	</a>
</div>

<script>

	datatable = $('#datatable').DataTable({
        ajax: {
            url: site_url + 'penjualan/faktur/datatable',
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
				data: null,
                sortable: false, 
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
			},
			{
                orderable: false,
                render: function(data, type, row, meta) {
                    return '<a target="_blank" href="' + site_url + 'penjualan/faktur/cetak/' + row.id+'"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
                }
            },
			{
				data: 'no_transaksi',
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'penjualan/faktur/ubah/' + row.id, data);
				}
			},
			{ data: 'tgl' },
			{ 
				data: 'no_pesanan', 
				render: function (data, type, row, meta) {
					return data != null && data != '' ? buttonUpdate(site_url + 'penjualan/pesanan/ubah/' + row.id_penjualan, data) : '';
				}
			},
			{ data: 'tgl_pesanan' },
			{ data: 'pelanggan' },
			{ data: 'keterangan_pay' },
			{ 
				data: 'grand_total',
				className: 'dt-center',
				render: function (data, type, row, meta) {
					return angka(data);
				} 
			},
			{ 
				data: 'qty_pesan', 
				className: 'dt-center',
				visible: false,
				render: function (data, type, row, meta) {
					return angka(data);
				},
			},
			{ 
				data: 'qty_kirim', 
				className: 'dt-center',
				render: function (data, type, row, meta) {
					return angka(data);
				}
			},
			{ 
				data: 'yg_buat',
				visible: false,
			},
			{ 
				data: 'yg_ubah',
				visible: false,
			},
        ],
        
    });

	$('a.toggle-vis').on('click', function (e) {
        e.preventDefault();

		if($(this).hasClass('text-dark')){
			$(this).removeClass('text-dark')
		} else {
			$(this).addClass('text-dark');
		}
 
        // Get the column API object
        var column = datatable.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
    });


</script>