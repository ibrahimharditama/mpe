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
    <?php $start=0; $no=1; $total_item=0; for ($i=1; $i <= $table_count; $i++): ?>
        <div class="page-break">
        <!-- header -->
        <table>
            <tr>
                <td width="33.3%">
                    <table class="padding-top: 10px">
                        <tr>
                            <td>
                                <span class="fw-bold f-verdana f-size-15-s"><?= ucwords(strtolower(perusahaan('nama'))); ?></span><br>
                                <span class="fw-400 f-trebuchet f-size-8-s"><?= perusahaan('alamat'); ?></span>
                            </td>
                        </tr>
                    </table>
                </td>

                <td width="66.7%">
                    <table>
                        <tr>
                            <td class="t-center">
                                <span class="fw-bold f-arial f-size-16">SURAT JALAN</span><br>
                                <span class="fw-bold f-arial f-size-12">No. SJ : <?= $header->no_transaksi; ?></span>
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
                <td width="5%" class="t-right">Jml</td>
                <td width="10%">Satuan</td>
                <td width="80%">Nama Item</td>
            </tr>
            <?php foreach(array_slice($detail, $start, 10) as $dtDetail): ?>
                <tr class="table-body">
                    <td><?= $no++; ?></td>
                    <td class="t-right"><?= number_format($dtDetail->qty); ?></td>
                    <td><?= $dtDetail->satuan; ?></td>
                    <td><?= $dtDetail->nama; ?></td>
                </tr>
                <?php $total_item += $dtDetail->qty; ?>
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
                <td>
                    <table>
                        <tr>
                            <td width="65%" class="v-top">
                                <span class="table-footer">Keterangan : <?= $header->keterangan; ?></span>
                            </td>
                            <td width="35%" class="v-top">
                                <table>
                                    <tr class="table-footer">
                                        <td class="fw-bold" width="42%"><span class="fw-bold">Total Item</span></td>
                                        <td>:</td>
                                        <td class="fw-bold"><span class="fw-bold"><?= number_format($total_item); ?></span></td>
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
                                        <td width="40%"></td>
                                        <td width="30%" class="t-center fw-bold f-verdana f-size-10">Hormat Kami</td>
                                    </tr>
                                </table>
                            </td>
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