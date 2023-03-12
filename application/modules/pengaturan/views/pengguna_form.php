<?php if ($this->session->flashdata('post_status') == 'err'): ?>
<?php $errors = $this->session->flashdata('errors'); ?>
<?php $data = $this->session->flashdata('data'); ?>
<?php endif; ?>

<h1 class="my-header">Form Pegawai & Pengguna</h1>

<form method="post" action="<?php echo $action_url; ?>">
<input type="hidden" name="id" value="<?php if ($data != null) echo $data['id']; ?>">

<div class="row m-0">
	<div class="col-12">
		<div class="row">
			<div class="col-4">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Nama Lengkap</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="nama" value="<?php if ($data != null) echo $data['nama']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Jabatan</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_jabatan" data-placeholder="Pilih Jabatan">
							<option value=""></option>
							<?php echo modules::run('options/lookup', 'jabatan', $data == null ? '' : $data['id_jabatan']); ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Email</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="email" value="<?php if ($data != null) echo $data['email']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Username</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="username" value="<?php if ($data != null) echo $data['username']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Password</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="password">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Grup Pengguna</label>
					<div class="col-sm-8">
						<select class="select2 w-100" name="id_pengguna_grup" data-placeholder="Pilih Grup Pengguna">
							<option value=""></option>
							<?php echo modules::run('options/pengguna_grup', $data == null ? '' : $data['id_pengguna_grup']); ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-8">
				<table class="table table-sm table-bordered table-striped">
					<tr>
						<th>Nama Unit</th>
						<th>Model</th>
						<th>Tgl. Perolehan</th>
						<th>Usia Aset</th>
						<th>Periode Maintenance</th>
						<th>Tgl. Perawatan Terakhir</th>
					</tr>
					<tr>
						<td>Unit 001</td>
						<td>ABC-123</td>
						<td>2023-02-01</td>
						<td>0 bulan</td>
						<td>1 bulan</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Unit 002</td>
						<td>ABC-123</td>
						<td>2023-02-01</td>
						<td>0 bulan</td>
						<td>1 bulan</td>
						<td>-</td>
					</tr>
					<tr>
						<td>Unit 003</td>
						<td>ABC-123</td>
						<td>2023-02-01</td>
						<td>0 bulan</td>
						<td>1 bulan</td>
						<td>-</td>
					</tr>
					<tr>
						<td colspan="6"><a href="#" data-toggle="modal" data-target="#exampleModal">[+] Tambah Unit Baru</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="actionbar fixed-bottom">
	<button class="btn btn-primary">
		<i class="ti ti-save"></i> Simpan
	</button>
	
	<a class="btn btn-outline-secondary ml-1" href="<?php echo site_url('pengaturan/pengguna') ?>">
		<i class="ti ti-na"></i> Batalkan
	</a>
</div>

</form>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
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
						<input type="text" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Model</label>
					<div class="col-sm-7">
						<input type="text" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Tgl. Perolehan</label>
					<div class="col-sm-7">
						<input type="text" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Usia Aset</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Periode Maintenance</label>
					<div class="col-sm-7">
						<input type="text" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label pr-0">Tgl. Terakhir Perawatan</label>
					<div class="col-sm-7">
						<input type="text" class="form-control" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>