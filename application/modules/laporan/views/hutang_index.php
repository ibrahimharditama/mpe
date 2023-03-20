<h1 class="my-header">Laporan Hutang</h1>

<div class="row m-0">
	<div class="col-8">
        
		<table class="table table-sm table-bordered" id="datatable">
			<thead style="background-color: rgba(0,0,0,.05)">	
				<tr class="font-weight-bold">
                    <td>Pelanggan</td>
                    <td width="150px">No. Transaksi</td>
                    <td class="text-center" width="120px">Total</td>
                    <td class="text-center" width="120px">Sudah dibayar</td>
                    <td class="text-center" width="120px">Sisa</td>
				</tr>
			</thead>

            <?php $total = 0; $bayar = 0; $sisa = 0; ?>

			<tbody>
                

                <?php foreach ($data as $key => $value): ?>

                    <?php 
                        $total_tagihan = $data[$key][0]['total'];
                        $bayar_tagihan = $data[$key][0]['bayar'];
                        $sisa_tagihan = $data[$key][0]['sisa'];
                    ?>

                    <tr>
                        <td class="align-top" rowspan="<?= count($value) + 1; ?>">
                            <?= $data[$key][0]['pelanggan']; ?>
                        </td>
                        <td><?= $data[$key][0]['no_transaksi']; ?></td>
                        <td class="text-right"><?= $data[$key][0]['total']; ?></td>
                        <td class="text-right"><?= $data[$key][0]['bayar']; ?></td>
                        <td class="text-right"><?= $data[$key][0]['sisa']; ?></td>
                    </tr>
                    
                    
                    <?php foreach ($data[$key] as $index => $row): ?>

                        <?php if($index != 0): ?>

                            <?php 
                                 $total_tagihan += $data[$key][$index]['total'];
                                 $bayar_tagihan += $data[$key][$index]['bayar'];
                                 $sisa_tagihan += $data[$key][$index]['sisa'];
                            ?>

                            <tr>
                                <td><?= $data[$key][$index]['no_transaksi']; ?></td>
                                <td class="text-right"><?= $data[$key][$index]['total']; ?></td>
                                <td class="text-right"><?= $data[$key][$index]['bayar']; ?></td>
                                <td class="text-right"><?= $data[$key][$index]['sisa']; ?></td>
                            </tr>

                        <?php endif;?>
                        

                    <?php endforeach; ?>

                    <tr class="semi-bold">
                        <td>Total</td>
                        <td class="text-right"><?= $total_tagihan; ?></td>
                        <td class="text-right"><?= $bayar_tagihan; ?></td>
                        <td class="text-right"><?= $sisa_tagihan; ?></td>
                    </tr>

                    <?php $total += $total_tagihan; $bayar += $bayar_tagihan; $sisa += $sisa_tagihan; ?>


                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2">Total</td>
                    <td class="text-right"><?= $total; ?></td>
                    <td class="text-right"><?= $bayar; ?></td>
                    <td class="text-right"><?= $sisa; ?></td>
                </tr>
            </tfoot>
		</table>
	</div>
</div>

<script>



    $().ready(function() {
        $('td.text-right').number(true, 0, ',', '.');
        
    });

</script>



