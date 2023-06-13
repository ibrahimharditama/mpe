<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Item</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-8">
		<div class="row">
			<div class="col-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Kode Item</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="kode" value="<?php if ($data != null) echo $data['kode']; ?>">
						<?php if (isset($errors)) echo $errors['kode']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Tipe</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_tipe" data-placeholder="Pilih Tipe">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'tipe', $data == null ? '' : $data['id_tipe']); ?>
						</select>
						<?php if (isset($errors)) echo $errors['id_tipe']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Nama Item</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="nama" value="<?php if ($data != null) echo $data['nama']; ?>">
						<?php if (isset($errors)) echo $errors['nama']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Jenis</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_jenis" data-placeholder="Pilih Jenis">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'jenis', $data == null ? '' : $data['id_jenis']); ?>
						</select>
						<?php if (isset($errors)) echo $errors['id_jenis']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Satuan</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_satuan" data-placeholder="Pilih Satuan">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'satuan', $data == null ? '' : $data['id_satuan']); ?>
						</select>
						<?php if (isset($errors)) echo $errors['id_satuan']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Merek</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_merek" data-placeholder="Pilih Merek">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'merek', $data == null ? '' : $data['id_merek']); ?>
							<?php if (isset($errors)) echo $errors['id_merek']; ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Harga Beli (Rp)</label>
					<div class="col-sm-8">
						<input type="text" class="form-control control-number" name="harga_beli" value="<?php if ($data != null) echo $data['harga_beli']; ?>">
						<?php if (isset($errors)) echo $errors['harga_beli']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Harga Jual (Rp)</label>
					<div class="col-sm-8">
						<input type="text" class="form-control control-number" name="harga_jual" value="<?php if ($data != null) echo $data['harga_jual']; ?>">
						<?php if (isset($errors)) echo $errors['harga_jual']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Keterangan</label>
					<div class="col-sm-8">
						<textarea class="form-control" name="keterangan"><?php echo $data == null ? '' : $data['keterangan']; ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-6">
				
			</div>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('master/produk') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>