<h1 class="my-header">Maintenance Asset</h1>

<div class="row m-0">
	<div class="col-8">
		<table class="cell-border stripe order-column hover" id="datatable">
			<thead>	
				<tr class="text-center">
                    <th width="5px"></th>
					<th width="5px">No.</th>
					<th>Nama Unit</th>
					<th>Model</th>
                    <th>Pegawai</th>
                    <th>Tanggal Perawatan</th>
                    <th>Keterangan</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal" data-target="#modalMaintenance">
		+ Tambah Data
	</a>
    <a class="btn btn-primary" href="<?php echo site_url('aset/maintenanceaset/excel'); ?>">
		<i class="ti ti-file"> Excel</i>
	</a>
</div>

<div class="modal fade" id="modalMaintenance" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form method="post" action="<?= base_url('aset/maintenanceaset/insert'); ?>" id="form">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Form Maintenance Aset</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Nama Unit</label>
					<div class="col-sm-7">
                        <select class="select2 w-100 id_asset" name="id_asset"  data-placeholder="Pilih Nama Unit" style="width: 100%;">
                            <option value=""></option>
							<?= modules::run('options/aset'); ?>
                        </select>
						<span class="err_modal err_id_asset"></span>
					</div>
				</div>
                <div class="form-group row div-tgl-terakhir-perawatan">
					<label class="col-sm-4 col-form-label pr-0">Tgl. Perawatan</label>
					<div class="col-sm-7">
						<input type="text" class="form-control datepicker" name="tgl_maintenance" value="<?= date('Y-m-d'); ?>" readonly>
						<span class="err_modal err_tgl_maintenance"></span>
					</div>
				</div>
                <div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Keterangan</label>
					<div class="col-sm-7">
						<textarea class="form-control" name="keterangan"></textarea>
					</div>
				</div>

				<input type="hidden" name="id" value="">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</div>
		</div>
		</form>
	</div>
</div>

<script>

    var modal = $("#modalMaintenance");

    $('#datatable').DataTable({
        ajax: {
            url: site_url + 'aset/maintenanceaset/datatable',
            dataSrc: 'datatable.data',
            data: function(d) {
            }
        },
        serverSide: true,
        order: [5, 'desc'],
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
                    return buttonDelete(site_url + 'aset/maintenanceaset/delete/' + data);
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
                "data": "nama",
                "render": function (data, type, row, meta) {
                    var permissions = $("#permissionmenu").html();
                    if (permissions.indexOf("u") < 0) {
                        return data + " - " + row.pegawai;
                    } else {
                        return `<a href="javascript:void(0)" 
                            data-id="`+ row.id +`" 
                            data-idasset="`+ row.id_asset +`" 
                            data-tglmaintenance="`+ row.tgl_maintenance +`" 
                            data-keterangan="`+ row.keterangan +`" 
                            onclick="edit(this)">
                            `+ data +` - `+ row.pegawai +`
                        </a>`;
                }
                    }

                    
            },
            {
                "data": "model"
            },
            {
                "data": "pegawai"
            },
            {
                "data": "tgl_maintenance"
            },
            {
                "data": "keterangan"
            },
        ],
        
    });

    $("#form").submit(function(e) {
        e.preventDefault();

        var me = $(this);	
		var data_form = me.serialize();

		$.ajax({
			url: me.attr('action'),
			type: 'post',
			data: data_form,
			dataType: 'json',
			beforeSend:function(){
				$('button[type=submit]').attr('disabled', 'disabled');
			},
			success: function(response) {
				console.log(response);
				if(response['code'] == 200) {
					window.location.href = response['url'];
				}

				if(response['code'] == 400) { 
					var error = response['data'];

					modal.find(".err_id_asset").html(error['id_asset']);
					modal.find(".err_tgl_maintenance").html(error['tgl_maintenance']);
				}

				$('button[type=submit]').attr('disabled', false);
				
			}
		});
    })

    $('#modalMaintenance').on('hidden.bs.modal', function () {
		modal.find(".err_modal").html('');
        modal.find("input[name=id]").val('');
		modal.find(".id_asset").val('').trigger('change');
        modal.find("input[name=tgl_maintenance]").val(moment().format("YYYY-MM-DD"));
		modal.find("textarea[name=keterangan]").val('');
		modal.find("#form").attr("action", site_url + "aset/maintenanceaset/insert");

        modal.find(".id_asset").attr('readonly', false);
	})

    function edit(ini) {

        var id = $(ini).data('id');
        var idasset = $(ini).data('idasset');
        var tglmaintenance = $(ini).data('tglmaintenance');
        var keterangan = $(ini).data('keterangan');

        modal.find(".id_asset").attr('readonly', true);

        modal.find("input[name=id]").val(id);
		modal.find(".id_asset").val(idasset).trigger('change');
        modal.find("input[name=tgl_maintenance]").val(tglmaintenance);
		modal.find("textarea[name=keterangan]").val(keterangan);
		modal.find("#form").attr("action", site_url + "aset/maintenanceaset/update");

        modal.modal('show');

        
    }

</script>