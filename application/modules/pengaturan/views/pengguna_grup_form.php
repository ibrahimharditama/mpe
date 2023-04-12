<?php if ($this->session->flashdata('post_status') == 'err') : ?>
	<?php $errors = $this->session->flashdata('errors'); ?>
	<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Grup Pengguna</h1>

<form method="post" action="<?php echo $action_url; ?>">
	<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

	<div class="row m-0">
		<div class="col-10">

			<?php if ($this->session->flashdata('post_status') == 'ok') : ?>
				<div class="alert alert-success">Data berhasil disimpan.</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label>Nama Grup Pengguna</label>
						<input type="text" class="form-control" name="nama" value="<?php if ($data != null) echo $data['nama']; ?>">
						<?php if (isset($errors)) echo $errors['nama']; ?>
					</div>
					<div class="form-group">
						<label>Urutan</label>
						<input type="hidden" name="urutan_sekarang" value="<?php if ($data != null) echo $data['urutan']; ?>">
						<select class="select2-no-clear" name="urutan" style="width:100%">
							<?php echo modules::run('options/urutan', 'pengguna_grup', $data == null ? '' : $data['urutan']); ?>
						</select>
					</div>
				</div>
				<div class="col-sm-9">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th rowspan="2">Nama Menu</th>
								<th colspan="5">Akses</th>
							</tr>
							<tr>
								<th width="60px">Lihat</th>
								<th width="60px">Buat</th>
								<th width="60px">Ubah</th>
								<th width="60px">Hapus</th>
								<th width="60px">Appr.</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($menu as $id_menu => $m) : ?>
								<tr class="tr-parent" id_menu="<?php echo $id_menu; ?>" id_induk="0">
									<td><b><?php echo $m['teks']; ?></b></td>
									<td align="center"><?php if (isset($m['actions']['r'])) : ?><input type="checkbox" class="cb-r" name="permissions[<?php echo $id_menu; ?>][]" <?php if (isset($m['permissions']['r'])) echo 'checked'; ?> value="r"><?php endif; ?></td>
									<td align="center"><?php if (isset($m['actions']['c'])) : ?><input type="checkbox" class="cb-c" name="permissions[<?php echo $id_menu; ?>][]" <?php if (isset($m['permissions']['c'])) echo 'checked'; ?> value="c"><?php endif; ?></td>
									<td align="center"><?php if (isset($m['actions']['u'])) : ?><input type="checkbox" class="cb-u" name="permissions[<?php echo $id_menu; ?>][]" <?php if (isset($m['permissions']['u'])) echo 'checked'; ?> value="u"><?php endif; ?></td>
									<td align="center"><?php if (isset($m['actions']['d'])) : ?><input type="checkbox" class="cb-d" name="permissions[<?php echo $id_menu; ?>][]" <?php if (isset($m['permissions']['d'])) echo 'checked'; ?> value="d"><?php endif; ?></td>
									<td align="center"><?php if (isset($m['actions']['a'])) : ?><input type="checkbox" class="cb-a" name="permissions[<?php echo $id_menu; ?>][]" <?php if (isset($m['permissions']['a'])) echo 'checked'; ?> value="a"><?php endif; ?></td>
								</tr>
								<?php foreach ($m['submenu'] as $sm) : ?>
									<tr class="tr-child" id_induk="<?php echo $id_menu; ?>">
										<td class="pl-4"><?php echo $sm['teks']; ?></td>
										<td align="center"><?php if (isset($sm['actions']['r'])) : ?><input type="checkbox" class="cb-r" name="permissions[<?php echo $sm['id_menu']; ?>][]" <?php if (isset($sm['permissions']['r'])) echo 'checked'; ?> value="r"><?php endif; ?></td>
										<td align="center"><?php if (isset($sm['actions']['c'])) : ?><input type="checkbox" class="cb-o cb-c" name="permissions[<?php echo $sm['id_menu']; ?>][]" <?php if (isset($sm['permissions']['c'])) echo 'checked'; ?> value="c"><?php endif; ?></td>
										<td align="center"><?php if (isset($sm['actions']['u'])) : ?><input type="checkbox" class="cb-o cb-u" name="permissions[<?php echo $sm['id_menu']; ?>][]" <?php if (isset($sm['permissions']['u'])) echo 'checked'; ?> value="u"><?php endif; ?></td>
										<td align="center"><?php if (isset($sm['actions']['d'])) : ?><input type="checkbox" class="cb-o cb-d" name="permissions[<?php echo $sm['id_menu']; ?>][]" <?php if (isset($sm['permissions']['d'])) echo 'checked'; ?> value="d"><?php endif; ?></td>
										<td align="center"><?php if (isset($sm['actions']['a'])) : ?><input type="checkbox" class="cb-o cb-a" name="permissions[<?php echo $sm['id_menu']; ?>][]" <?php if (isset($sm['permissions']['a'])) echo 'checked'; ?> value="a"><?php endif; ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<div class="actionbar fixed-bottom">
		<button type="submit" class="btn btn-primary">
			<i class="ti ti-save"></i> Simpan
		</button>

		<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('pengaturan/pengguna-grup') ?>">
			<i class="ti ti-na"></i> Batalkan
		</a>
	</div>
</form>


<script>
	$().ready(function() {

		function cek_submenu(id_induk) {
			var num_checkhed_submenu = 0;

			$.each($('.tr-child[id_induk="' + id_induk + '"]'), function(key, obj) {
				if ($(obj).find('.cb-r').is(':checked')) {
					num_checkhed_submenu = num_checkhed_submenu + 1;
				}
			});

			if (num_checkhed_submenu == 0) {
				$('.tr-parent[id_menu="' + id_induk + '"]').find('.cb-r').prop('checked', false);
			} else {
				$('.tr-parent[id_menu="' + id_induk + '"]').find('.cb-r').prop('checked', true);
			}
		}

		$('.cb-r').click(function() {
			if ($(this).is(':checked') == false) {
				$(this).closest('tr').find('.cb-o').prop('checked', false);
			}

			var id_induk = $(this).closest('tr').attr('id_induk');

			if (id_induk != '0') {
				cek_submenu(id_induk);
			}
		});

		$('.cb-o').click(function() {
			if ($(this).is(':checked')) {
				$(this).closest('tr').find('.cb-r').prop('checked', true);
			}
		});
	});
</script>