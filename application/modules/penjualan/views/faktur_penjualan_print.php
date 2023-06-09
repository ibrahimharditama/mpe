<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ms" lang="ms">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Verdana:wght@400;700&family=Trebuchet%20MS:wght@400;700&family=Arimo:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/print.css">
    
</head>

<body>
    <?php $start=0; $no=1; for ($i=1; $i <= $table_count; $i++): ?>
        <div class="page-break">
        <!-- header -->
        <table>
            <tr>
                <td width="33.3%">
                    <?php if(json_decode($no_header) == false): ?>
                        <table class="padding-top: 10px">
                            <tr>
                                <td>
                                    <span class="fw-bold f-verdana f-size-15-s"><?= ucwords(strtolower(perusahaan('nama'))); ?></span><br>
                                    <span class="fw-400 f-trebuchet f-size-8-s"><?= perusahaan('alamat'); ?></span>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </td>

                <td width="66.7%">
                    <table>
                        <tr>
                            <td class="t-center">
                                <span class="fw-bold f-arial f-size-16"><?= $tipe == 'faktur' ? 'FAKTUR PENJUALAN' : 'SURAT JALAN'; ?></span><br>
                                <span class="fw-bold f-arial f-size-12"><?= $tipe == 'faktur' ? 'Nota : '.$header->no_transaksi : 'No. SJ : '.$header->no_transaksi; ?></span>
                            </td>
                            <td width="40%" class="v-bottom t-right">
                                <span class="fw-bold f-arial f-size-10">Jakarta,</span> <span class="fw-400 f-verdana f-size-9"><?=date("d/m/Y",strtotime($header->tgl))?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold f-arial f-size-9"  colspan="2" style="padding-left: 38%;padding-right: 5%">
                                Kepada Yth : <span class="fw-400 f-verdana f-size-9"><?= $header->nama; ?></span><br>
                                <span class="fw-400 f-verdana f-size-9">
                                    <?= $header->alamat; ?> <br>
                                    <?= $header->no_telp; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        </table>
        <!-- end header -->
        
        <!-- body -->
        <table style="margin-top: 5px;">
            <tr class="table-header">
                <td width="5%">No.</td>
                <td width="43%">Nama Item</td>
                <td width="5%" class="t-right">Jml</td>
                <td width="10%">Satuan</td>
                <td width="15%" class="t-right">Harga</td>
                <td width="5%" class="t-right">Pot</td>
                <td width="17%" class="t-center">Total</td>
            </tr>
            <?php foreach(array_slice($detail, $start, 10) as $dtDetail): ?>
                <tr class="table-body">
                    <td><?= $no++; ?></td>
                    <td><?= $dtDetail->uraian; ?></td>
                    <td class="t-right"><?= number_format($dtDetail->qty); ?></td>
                    <td><?= $dtDetail->satuan; ?></td>
                    <td class="t-right"><?= number_format($dtDetail->harga_satuan); ?></td>
                    <td class="t-right"><?= number_format($dtDetail->diskon); ?></td>
                    <td class="t-right"><?= number_format($dtDetail->qty*$dtDetail->harga_satuan-$dtDetail->diskon); ?></td>
                </tr>
            <?php endforeach; ?>

            <tr style="border-bottom: 1px solid black;">
                <td colspan="7">
                    <?php 
                        $totalData = count(array_slice($detail, $start, 10)); 
                        if($totalData < 5) echo '<br>';
                        if($totalData <= 3) echo '<br>';
                        if($totalData <= 1) echo '<br>';
                    ?>
                </td>
            </tr>
        </table>
        <!-- end body -->
        
        <!-- footer -->
        <table>
            <tr>
                <td width="73%">
                    <table>
                        <tr class="table-footer">
                            <td width="65%" class="v-top">
                                <span class="table-footer"><?= terbilang($header->grand_total); ?></span> <br><br>
                                <span class="table-footer"><?= $header->keterangan_faktur; ?></span>
                            </td>
                            <td width="35%" class="v-top">
                                <table>
                                    <tr class="table-footer">
                                        <td width="42%">Biaya Lain : </td>
                                        <td class="t-right"><?= number_format($header->biaya_lain); ?> </td>
                                    </tr>
                                    <tr class="table-footer">
                                        <td colspan="2">Ket. Biaya Lain : </td>
                                    </tr>
                                    <tr class="table-footer">
                                        <td colspan="2" rowspan="1"><?= $header->keterangan_biaya_lain; ?></td>
                                    </tr>
                                </table>
                            </td> 
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td width="30%" class="t-center fw-bold f-verdana f-size-10">Penerima</td>
                                        
                                        <td width="40%" class="t-center f-verdana f-size-10 fw-400" style="<?= json_decode($is_rekening) == true ? 'border: 1px solid black' : ''; ?>">
                                            <?php if(json_decode($is_rekening) == true): ?>
                                                NO REK <?= $bank->bank; ?>: <?= $bank->no_rekening;?><br>
                                                ATN: <?= $bank->nama; ?>
                                            <?php endif; ?>
                                            
                                        </td>
                                        <td width="30%" class="t-center fw-bold f-verdana f-size-10">Hormat Kami</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="30%"></td>
                                        <td width="40%" class="t-center f-verdana f-size-8 fw-400" style="border: 1px solid black;">
                                            Barang telah diterima dengan baik.<br>
                                            Barang yang sudah dibeli tidak bisa<br>
                                            ditukar atau dikembalikan.
                                        </td>
                                        <td width="30%"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="27%" class="v-top">
                    <table>
                        <tr class="table-footer">
                            <td width="36%">Sub Total</td>
                            <td width="1%" class="t-center">:</td>
                            <td class="t-right"><?= number_format($header->total); ?> </td>
                        </tr>
                        <tr class="table-footer">
                            <td width="36%">Potongan</td>
                            <td width="1%" class="t-center">:</td>
                            <td class="t-right"><?= number_format($header->diskon_faktur); ?> </td>
                        </tr>
                        <tr class="table-footer">
                            <td width="36%">Total Akhir</td>
                            <td width="1%" class="t-center">:</td>
                            <td class="t-right"><?= number_format($header->grand_total); ?> </td>
                        </tr>
                        <tr class="table-footer">
                            <td width="36%">Total Bayar</td>
                            <td width="1%" class="t-center">:</td>
                            <td class="t-right"><?= number_format($header->total_bayar); ?> </td>
                        </tr>
                        <tr class="table-footer">
                            <td width="36%">Sisa</td>
                            <td width="1%" class="t-center">:</td>
                            <td class="t-right"><?= number_format($header->grand_total - $header->total_bayar); ?> </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- end footer -->
        </div>
        
    <?php  $start = $start + 10;endfor; ?>

    
    



</body>
<script>
    window.print();
</script>
</html>