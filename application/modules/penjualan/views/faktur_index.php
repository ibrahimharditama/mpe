<h1 class="my-header">Penjualan</h1>

<div class="row m-0">
	<div class="col-12">

		<div class="togle-datatable-inv mb-2">
			Toggle column: 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="2" >No Transaksi</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="3">Tanggal Nota</a> 
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="4">No Pesanan</a> 
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="5">Tanggal Pesanan</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="6">Pelanggan</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="7">Keterangan</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="8">Total</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="9">Status</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="10">Status Pengiriman</a> 
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="11">Jumlah Pesan</a> 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="12">Jumlah Kirim</a> 
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="13">User Buat</a> 
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="14">User Ubah</a>
		</div>
		<div class="togle-datatable-inv mb-3">
			Tampilkan data selama: 
			<select id="data-hari">
				<option value="3">3</option>
				<option value="7" selected>7</option>
				<option value="30">30</option>
				<option value="all">Semua</option>
			</select>
			hari terakhir
		</div>

		<div class="table-responsive">
			<table class="cell-border stripe order-column hover" id="datatable">
				<thead>	
					<tr class="text-center">
						<th width="5px">No</th>
						<th width="5px"></th>
						<th>No Transaksi</th>
						<th>Tanggal Nota</th>
						<th>No Pesanan</th>
						<th>Tanggal Pesanan</th>
						<th>Pelanggan</th>
						<th>Keterangan</th>
						<th>Total</th>
						<th>Status</th>
						<th>Status Pengiriman</th>
						<th>Jumlah<br>Pesan</th>
						<th>Jumlah<br>Kirim</th>
						<th>User Buat</th>
						<th>User Ubah</th>
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
				d.datahari = $('#data-hari').val()
            }
        },
        serverSide: true,
        order: [[2, 'desc']],
		pageLength: 50,
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
				data: 'id',
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
			{ data: 'tgl',
				render: function (data, type, row, meta) {
                   return moment(data).format("DD-MM-YYYY");
                }
			},
			{ 
				data: 'no_pesanan', 
				visible: false,
				render: function (data, type, row, meta) {
					return data != null && data != '' ? buttonUpdate(site_url + 'penjualan/pesanan/ubah/' + row.id_penjualan, data) : '';
				}
			},
			{ 
				data: 'tgl_pesanan',
				visible: false, 
				render: function (data, type, row, meta) {
                   return data != '' ? moment(data).format("DD-MM-YYYY") : '';
                }
			},
			{ data: 'pelanggan' },
			{ data: 'keterangan_pay' },
			{ 
				data: 'grand_total',
				className: 'dt-body-right',
				render: function (data, type, row, meta) {
					return angka(data);
				} 
			},
			{ data: 'status' },
			{ data: 'status_pengiriman' },
			{ 
				data: 'qty_pesan', 
				className: 'dt-body-right',
				visible: false,
				render: function (data, type, row, meta) {
					return angka(data);
				},
			},
			{ 
				data: 'qty_kirim', 
				className: 'dt-body-right',
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

		if($(this).hasClass('btn-warning')){
			$(this).removeClass('btn-warning')
			$(this).addClass('btn-danger');
		} else {
			$(this).removeClass('btn-danger')
			$(this).addClass('btn-warning');
		}
 
        // Get the column API object
        var column = datatable.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
    });

	$('#data-hari').on('change', function() {
		datatable.clear().draw();
	})


</script>