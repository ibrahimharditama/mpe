<h1 class="my-header">Pengembalian Pipa</h1>

<div class="row m-0">
	<div class="col-12">
        <?php if ($this->session->flashdata('post_status') == 'ok'): ?>
            <div class="alert alert-success">Data berhasil disimpan.</div>
        <?php endif; ?>

        <div class="togle-datatable-inv mb-3">
			Tampilkan data selama: 
			<select id="data-hari">
				<option value="3">3</option>
				<option value="7">7</option>
				<option value="30">30</option>
				<option value="60">60</option>
				<option value="all">Semua</option>
			</select>
			hari terakhir
		</div>

		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr class="text-center">
                    <th width="40px"></th>
					<th width="5px">No</th>
                    <th width="150px">No Transaksi</th>
                    <th width="100px">Tanggal</th>
                    <th width="150px">No Pengiriman</th>
                    <th width="50px">Jumlah</th>
                    <th>Status</th>
                    <th>Supir</th>
                    <th>Kenek</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th>User Buat</th>
                    <th>User Ubah</th>
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

<div class="modal" id="modal-approve" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Konfirmasi Approve
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apa anda yakin untuk approve?
                <input type="hidden" id="approve_id" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    onclick="ajaxApprove('<?=base_url()?>penjualan/pengembalian-pipa/approve')">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>


<script>


    datatable = $('#datatable').DataTable({
        ajax: {
            url: site_url + 'penjualan/pengembalian-pipa/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
				d.datahari = $('#data-hari').val()
            }
        },
        pageLength: 50,
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
                    return buttonDelete(site_url + 'penjualan/pengembalian-pipa/delete/' + data)+'&nbsp;<a target="_blank" href="' + site_url + 'penjualan/pengembalian-pipa/cetak/'+data+'"><img src="<?php echo base_url(); ?>assets/img/printer.png"></a>';
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
                    return buttonUpdate(site_url + 'penjualan/pengembalian-pipa/ubah/' + row.id, data);
                }
            },
            {
                "data": "tgl",
                render: function (data, type, row, meta) {
                   return moment(data).format("DD-MM-YYYY");
                } 
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
                "data": "is_approve" ,
				"render": function (data, type, row, meta) {
					return data == 1 ? "APPROVED" : "";
				}
            },
            { "data": "supir" },
            { "data": "kenek" },
            { "data": "teknisi" },
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

    $('#modal-approve').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#approve_id').val(button.data('id'));
    });

    function ajaxApprove(filename) {
        $.ajax({
            type: 'POST',
            data: {id: $('#approve_id').val()},
            url: filename,
            success: function (data) {
                $('#modal-approve').modal('hide');
                window.location.reload();
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    }

    $('#data-hari').on('change', function() {
		datatable.clear().draw();
	})


</script>