<h1 class="my-header">Pengembalian Pipa</h1>

<div class="row m-0">
	<div class="col-9">
        <?php if ($this->session->flashdata('post_status') == 'ok'): ?>
            <div class="alert alert-success">Data berhasil disimpan.</div>
        <?php endif; ?>
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr>
                    <th width="5px"></th>
					<th width="5px">No.</th>
                    <th width="150px">No. Transaksi</th>
                    <th width="100px">Tgl.</th>
                    <th width="150px">No. Pengiriman</th>
                    <th width="50px">Qty</th>
                    <th>Keterangan</th>
                    <th>Yg Buat</th>
                    <th>Yg Ubah</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="<?= base_url('penjualan/pengembalian-pipa/tambah'); ?>">
		+ Tambah Data
	</a>
</div>



<script>


    $('#datatable').DataTable({
        ajax: {
            url: site_url + 'penjualan/pengembalian-pipa/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
            }
        },
        serverSide: true,
        order: [[3, 'desc'],[2, 'desc']],
        language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_ items/page',
        },
        columns: [
            {
                "data": "id",
                "sortable": false, 
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return buttonDelete(site_url + 'penjualan/pengembalian-pipa/delete/' + data);
                }
            },
            {
                "data": null,
                "sortable": false, 
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "no_transaksi",
                "render": function (data, type, row, meta) {
                    return `<a href="` + site_url + `penjualan/pengembalian-pipa/ubah/` + row.id + `">`+ data +`</a>`;
                }
            },
            {
                "data": "tgl"
            },
            {
                "data": "no_pengiriman",
                "render": function (data, type, row, meta) {
                    return `<a href="` + site_url + `penjualan/pengiriman/ubah/` + row.id_pengiriman + `" target="_blank">`+ data +`</a>`;
                }
            },
            {
                "data": "qty",
                "class": 'dt-body-right',
                "render": function (data, type, row, meta) {
                    return $.number(data, 0, ',', '.');
                }
            },
            {
                "data": "keterangan"
            },
            {
                "data": "created_by"
            },
            {
                "data": "updated_by"
            },
        ],
        
    });

    

</script>