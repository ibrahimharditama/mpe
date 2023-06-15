<h1 class="my-header">Pembelian</h1>

<div class="row m-0">
	<div class="col-12">

		<div class="togle-datatable-inv mb-3">
			Toggle column: 
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="2" >No Transaksi</a>
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="3">Tanggal Terima</a>
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="4">No Pembelian</a>
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="5">Tanggal Pembelian</a>
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="6">Supplier</a>
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="7">Keterangan</a>
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="8">Total</a>
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="9">Jumlah Pesan</a>
			<a href="javascript:void(0);" class="btn btn-warning ml-1 toggle-vis" data-column="10">Jumlah Terima</a>
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="11">User Buat</a>
			<a href="javascript:void(0);" class="btn btn-danger ml-1 toggle-vis" data-column="12">User Ubah</a>
		</div>

		<div class="table-responsive">
			<table class="cell-border stripe order-column hover" id="datatable">
				<thead>	
					<tr class="text-center">
						<th width="5px">No.</th>
						<th width="5px"></th>
						<th>No Transaksi</th>
						<th>Tanggal Terima</th>
						<th>No Pembelian</th>
						<th>Tanggal Pembelian</th>
						<th>Supplier</th>
						<th>Keterangan</th>
						<th>Total</th>
						<th>Jumlah<br>Pesan</th>
						<th>Jumlah<br>Terima</th>
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
	<a class="btn btn-primary" href="<?php echo site_url('pembelian/penerimaan/tambah'); ?>">
		+ Tambah Data
	</a>
</div>

<script>
	datatable = $('#datatable').DataTable({
        ajax: {
            url: site_url + 'pembelian/penerimaan/datatable',
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
				data: 'id',
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
			{ 
				data: 'tgl', 
				render: function (data, type, row, meta) {
                   return moment(data).format("DD-MM-YYYY");
                }
			},
			{ 
				data: 'no_pembelian', 
				visible: false,
				render: function (data, type, row, meta) {
					return buttonUpdate(site_url + 'pembelian/pesanan/ubah/' + row.id_pembelian, data);
				}
			},
			{ 
				data: 'tgl_pembelian', 
				visible: false,
				render: function (data, type, row, meta) {
                   return moment(data).format("DD-MM-YYYY");
                }
			},
			{ data: 'supplier' },
			{ data: 'keterangan_pay' },
			{ 
				data: 'grand_total',
				className: 'dt-body-right',
				render: function (data, type, row, meta) {
					return angka(data);
				} 
			},
			{ 
				data: 'qty_pesan', 
				className: 'dt-body-right',
				visible: false,
				render: function (data, type, row, meta) {
					return angka(data);
				},
			},
			{ 
				data: 'qty_terima', 
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



</script>