<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Pegawai & Pengguna</h1>

<form method="post" action="<?php echo $action_url; ?>" id="form">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-12">
		
		<?php if ($this->session->flashdata('post_status') == 'ok'): ?>
			<div class="alert alert-success">Data berhasil disimpan.</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-4">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Nama Lengkap</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="nama" value="<?php if ($data != null) echo $data['nama']; ?>">
						<span class="err_nama"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Jabatan</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_jabatan" data-placeholder="Pilih Jabatan">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'jabatan', $data == null ? '' : $data['id_jabatan']); ?>
						</select>
						<span class="err_id_jabatan"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Email</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="email" value="<?php if ($data != null) echo $data['email']; ?>">
						<span class="err_email"></span>
					</div>					
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Username</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="username" value="<?php if ($data != null) echo $data['username']; ?>">
						<span class="err_username"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Password</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="password">
						<span class="err_password"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Grup Pengguna</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_pengguna_grup" data-placeholder="Pilih Grup Pengguna">
							<option value=""></option>
							<?php echo modules::run('options/pengguna_grup', $data == null ? '' : $data['id_pengguna_grup']); ?>
						</select>
						<span class="err_id_pengguna_grup"></span>
					</div>
				</div>
			</div>
			<?php if($aksi == 'update' && !is_null($asset)): ?>
				<div class="col-8">
					<table class="table table-sm table-bordered table-striped" id="table-asset">
						<thead>
							<tr>
								<th>Nama Unit</th>
								<th>Model</th>
								<th>Tgl. Perolehan</th>
								<th>Usia Aset</th>
								<th>Periode Maintenance</th>
								<th>Tgl. Perawatan Terakhir</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($asset as $a): ?>
								<tr>
									<td><?= $a['nama']; ?></td>
									<td><?= $a['model']; ?></td>
									<td><?= $a['tgl_pembelian']; ?></td>
									<td><?= umur_bulan($a['tgl_pembelian']); ?></td>
									<td><?= $a['waktu_maintenance'].' '.strtolower($a['periode_maintenance']); ?></td>
									<td><?= $a['tgl_maintenance']; ?></td>
								</tr>
							<?php endforeach; ?>
							
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">
									<a href="javascript:void(0)" class="buttonAddAsset">[+] Tambah Unit Baru</a>
								</td>
							</tr>
						</tfoot>
						
					</table>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button type="submit" class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('pengaturan/pengguna') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>

<div class="modal fade" id="modalAsset" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form method="post" action="<?= base_url('pengaturan/pengguna/insert_asset'); ?>" id="form-asset">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Form Unit Aset</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Nama Unit</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" name="nama">
						<span class="err_asset_nama"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Model</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" name="model">
						<span class="err_asset_model"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Tgl. Perolehan</label>
					<div class="col-sm-7">
						<input type="text" class="form-control tgl_pembelian" name="tgl_pembelian" value="<?=date('Y-m-d'); ?>" readonly>
						<span class="err_asset_tgl_pembelian"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Usia Aset</label>
					<div class="col-sm-7">
						<input type="text" class="form-control usia_asset" value="0 bulan"  readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Periode Maintenance</label>
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-4">
								<input type="text" class="form-control" name="waktu_maintenance">
							</div>
							<div class="col-sm-8">
								<select class="select2-no-clear w-100" name="periode_maintenance" style="width: 100%;">
									<?= option_periode(); ?>
								</select>
							</div>
						</div>
						<span class="err_asset_waktu_maintenance"></span>
						
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Tgl. Terakhir Perawatan</label>
					<div class="col-sm-7">
						<input type="text" class="form-control datepicker" name="tgl_maintenance" value="<?=date('Y-m-d'); ?>" readonly>
						<span class="err_asset_tgl_maintenance"></span>
					</div>
				</div>

				<input type="hidden" name="id_pegawai" value="<?php if ($data != null) echo $data['id']; ?>">
				<input type="hidden" name="id_asset" value="">

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

	$().ready(function() {
		
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

					$(".err_nama").html(error['nama']);
					$(".err_email").html(error['email']);
					$(".err_username").html(error['username']);
					$(".err_id_pengguna_grup").html(error['id_pengguna_grup']);
					$(".err_id_jabatan").html(error['id_jabatan']);
					$(".err_password").html(error['password']);
				}

				$('button[type=submit]').attr('disabled', false);
				
			}
		});
    })

	$(".buttonAddAsset").on('click', function () {
		$('#modalAsset').modal('show');
	})

	$("#form-asset").submit(function(e) {
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
					var asset = response['data'];

					var htmlAsset = `<tr>
										<td>`+ asset['nama'] +`</td>
										<td>`+ asset['model'] +`</td>
										<td>`+ asset['tgl_pembelian'] +`</td>
										<td>`+ asset['usia'] +`</td>
										<td>`+ asset['periode_maintenance'] +`</td>
										<td>`+ asset['tgl_maintenance'] +`</td>
									</tr>`;

					$('#table-asset tbody').append(htmlAsset);
					$('#modalAsset').modal('hide');
				}

				if(response['code'] == 400) { 
					var error = response['data'];

					$('#modalAsset').find(".err_asset_nama").html(error['nama']);
					$('#modalAsset').find(".err_asset_model").html(error['model']);
					$('#modalAsset').find(".err_asset_tgl_pembelian").html(error['tgl_pembelian']);
					$('#modalAsset').find(".err_asset_waktu_maintenance").html(error['waktu_maintenance']);
					$('#modalAsset').find(".err_asset_tgl_maintenance").html(error['tgl_maintenance']);
				}

				$('button[type=submit]').attr('disabled', false);
				
			}
		});
    })

	$('#modalAsset').on('shown.bs.modal', function () {
		$('.tgl_pembelian').Zebra_DatePicker({
			offset: [-203, 280],
			onSelect: function() {
				console.log(monthDiff($(this).val()));
				$('#modalAsset').find('.usia_asset').val(monthDiff($(this).val()) + ' bulan');

			}
		});
	})

	$('#modalAsset').on('hidden.bs.modal', function () {
		console.log('hidden')
		$("#modalAsset").find("input[name=nama]").val('');
        $("#modalAsset").find("input[name=model]").val('');
        $("#modalAsset").find("input[name=tgl_pembelian]").val(moment().format("YYYY-MM-DD"));
        $("#modalAsset").find(".usia_asset").val('0 bulan');
		$("#modalAsset").find("input[name=waktu_maintenance]").val('');
		$("#modalAsset").find("input[name=tgl_maintenance]").val(moment().format("YYYY-MM-DD"));
	})


</script>