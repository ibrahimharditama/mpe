<h1 class="my-header">Laporan Hutang</h1>

<div class="row m-0">
	<div class="col-5">
        
		<table class="table table-sm table-bordered" id="datatable">
			<thead style="background-color: rgba(0,0,0,.05)">	
				<tr class="font-weight-bold">
                    <td>No. Transaksi</td>
                    <td width="100px">Tanggal</td>
                    <td class="text-center" width="120px">Nominal</td>
                    <td class="text-center" width="120px">Sisa</td>
				</tr>
			</thead>

            <?php $total = 0; $sisa = 0; ?>

			<tbody>
                

                <?php foreach ($data as $key => $value): ?>

                    <?php 
                        $total_tagihan = 0;
                        $sisa_tagihan = 0;
                    ?>

<tr>
                        <td class="font-weight-bold" colspan="4"><?= $key; ?></td>
                    </tr>

                    <?php foreach ($data[$key] as $index => $row): ?>

                        <?php 
                            $total_tagihan += $data[$key][$index]['total'];
                            $sisa_tagihan += $data[$key][$index]['sisa'];
                        ?>

                        <tr>
                            <td><?= $data[$key][$index]['no_transaksi']; ?></td>
                            <td><?= $data[$key][$index]['tgl']; ?></td>
                            <td class="text-right text-number"><?= $data[$key][$index]['total']; ?></td>
                            <td class="text-right text-number"><?= $data[$key][$index]['sisa']; ?></td>
                        </tr>

                    <?php endforeach; ?>

                    <tr class="semi-bold">                        
                        <td class="text-right" colspan="2">Sub Total:</td>
                        <td class="text-right text-number"><?= $total_tagihan; ?></td>
                        <td class="text-right text-number"><?= $sisa_tagihan; ?></td>
                    </tr>

                    <?php $total += $total_tagihan; $sisa += $sisa_tagihan; ?>


                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                <td colspan="2">Total</td>
                    <td class="text-right text-number"><?= $total; ?></td>
                    <td class="text-right text-number"><?= $sisa; ?></td>
                </tr>
            </tfoot>
		</table>
	</div>
</div>

<script>



    $().ready(function() {
        $('td.text-number').number(true, 0, ',', '.');
        
    });

</script>



