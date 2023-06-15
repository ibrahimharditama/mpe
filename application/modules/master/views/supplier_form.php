<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Supplier</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-8">
		<div class="row">
			<div class="col-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Kode Supplier</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="kode" placeholder="Dibuat otomatis" value="<?php if ($data != null) echo $data['kode']; ?>" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0"><span class="text-danger">*</span> Nama Supplier</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="nama" value="<?php if ($data != null) echo $data['nama']; ?>">
						<?php if (isset($errors)) echo $errors['nama']; ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Alamat</label>
					<div class="col-sm-8">
						<textarea class="form-control" name="alamat"><?php if ($data != null) echo $data['alamat']; ?></textarea>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Kota</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="kota" value="<?php if ($data != null) echo $data['kota']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Provinsi</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="provinsi" value="<?php if ($data != null) echo $data['provinsi']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Kode Pos</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="kode_pos" value="<?php if ($data != null) echo $data['kode_pos']; ?>">
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">No. Telepon</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="no_telp" value="<?php if ($data != null) echo $data['no_telp']; ?>">
					</div>
				</div>
				<!-- <div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">No. HP</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="no_hp" value="<?//php if ($data != null) echo $data['no_hp']; ?>">
					</div>
				</div> -->
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Email</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="email" value="<?php if ($data != null) echo $data['email']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Keterangan</label>
					<div class="col-sm-8">
						<textarea class="form-control" name="keterangan"><?php if ($data != null) echo $data['keterangan']; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('master/supplier') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>