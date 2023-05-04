<div class="my-header pb-0 d-flex">
<p class="mr-3 mt-1">Kartu Stok</p> 
    <div style="width: 130px;">
        <input type="text" class="form-control datepicker-year-month" name="tgl" value="<?= $periode; ?>" readonly>
    </div>
    <p class="mr-3 mt-1 ml-3">Nama</p> 
    <div style="width: 250px;">
        <input type="text" class="form-control" name="nama" value="<?= $nama; ?>">
    </div>
    <form action="<?=site_url("laporan/kartustok")?>" method="post" id="frm-cari">
    <input type="hidden" name="periode" id="periode"/>
    <input type="hidden" name="nama_cari" id="nama_cari"/>
    </form>
</div>

<div class="row m-0">

	<div class="col-8 mt-2">
        
		<table class="table table-sm table-bordered" id="datatable">
			<thead style="background-color: rgba(0,0,0,.05)">	
                <tr class="font-weight-bold">
                    <td class="text-center" rowspan="2">Kode Produk</td>
                    <td class="text-center" rowspan="2">Nama Produk</td>
                    <td class="text-center" colspan="5">STOK</td>
                </tr>
                <tr class="font-weight-bold">
                    <td class="text-center">Satuan</td>
                    <td class="text-center">Awal</td>
                    <td class="text-center">Masuk</td>
                    <td class="text-center">Keluar</td>
                    <td class="text-center">Akhir</td>
                </tr>
			</thead>

            <tbody>
                <?php foreach ($data as $row): ?>

                    <tr>
                        <td class="semi-bold"><?= $row['kode']; ?></td>
                        <td class="semi-bold">
                            <a href="javascript:void(0)" data-id="<?= $row['id']; ?>" data-periode="<?= $periode; ?>" data-name="<?= $row['nama'].'  &middot; '.$row['jenis'].'  &middot; '.$row['merek'] ; ?>" onclick="detail(this)">
                            <?= $row['nama'].'  &middot; '.$row['jenis'].'  &middot; '.$row['merek'] ; ?>
                            </a>
                        </td>
                        <td class="text-center"><?= $row['satuan']; ?></td>
                        <td class="text-right"><?= number_format($row['stokawal'], 0, "," ,"."); ?></td>
                        <td class="text-right"><?= number_format($row['stokin'], 0, "," ,"."); ?></td>
                        <td class="text-right"><?= number_format($row['stokout'], 0, "," ,"."); ?></td>
                        <td class="text-right"><?= number_format($row['stokakhir'], 0, "," ,"."); ?></td>
                    </tr>

                <?php endforeach; ?>
                
            </tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
                    <table class="table table-sm table-bordered" id="table-detail">
                        <thead style="background-color: rgba(0,0,0,.05)">
                            <tr class="font-weight-bold">
                                <td>No Transaksi</td>
                                <td>Tanggal</td>
                                <td>Dept.</td>
                                <td>Keterangan</td>
                                <td>Masuk</td>
                                <td>Keluar</td>
                                <td>Saldo</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
    $().ready(function() {
        $('.datepicker-year-month').Zebra_DatePicker({
            offset: [-203, 280], 
            format: 'Y-m',
            show_clear_date: false, 
            view: 'months',
            onSelect: function() {
                $("#periode").val($(this).val());
                $("#nama_cari").val($("[name='nama']").val());
                $('form#frm-cari').submit()

            }

        });
        $("[name='nama']").on('keypress',function(e) {
            if(e.which == 13) {
                $("#periode").val($("[name='tgl']").val());
                $("#nama_cari").val($("[name='nama']").val());
                $('form#frm-cari').submit()
            }
        });
    });

    function detail(ini) {
        var id = $(ini).data('id');
        var periode = $(ini).data('periode');
        var name = $(ini).data('name');

        $.getJSON( site_url + 'laporan/kartustok/ajax_detail/' + id + '/' + periode, function(result) {
            var html = "";

            $.each(result, function(index, row) {
                html += `<tr>
                            <td>` + row.no_transaksi + `</td>
                            <td>` + row.tgl + `</td>
                            <td>` + row.dept + `</td>
                            <td>` + row.keterangan + `</td>
                            <td class="text-right">` + row.masuk + `</td>
                            <td class="text-right">` + row.keluar + `</td>
                            <td class="text-right semi-bold">` + row.saldo + `</td>
                        </tr>`
            });
            $("#modalDetail").find("table tbody").html(html);
            $("#modalDetail").find(".modal-title").html(name);
            $("#modalDetail").modal("show");


        });
        
    }

</script>



