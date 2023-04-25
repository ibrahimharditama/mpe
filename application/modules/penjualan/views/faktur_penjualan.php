<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ms" lang="ms">

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Verdana:wght@400;700&family=Trebuchet%20MS:wght@400;700&family=Arimo:wght@400;700&display=swap" rel="stylesheet">
    <style type="text/css">
        @page {
            margin: 0px;
            size: 21.59cm 13.97cm;
        }

        body {
            margin: 0px;
            padding-top: 0.18cm;
            padding-right: 0.88cm;
            padding-bottom: 0.49cm;
            padding-left: 0.46cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table td {
            height: min-content;
        }

        .t-left {
            text-align: left;
        }

        .t-center {
            text-align: center;
        }

        .t-right {
            text-align: right;
        }

        .v-top {
            vertical-align: top;
        }

        .v-middle {
            vertical-align: middle;
        }

        .v-bottom {
            vertical-align: bottom;
        }

        .fw-bold {
            font-weight: 700;
        }

        .fw-400 {
            font-weight: 400;
        }

        .f-verdana {
            font-family: "Verdana";
        }

        .f-trebuchet {
            font-family: "Trebuchet MS";
        }

        .f-arial {
            font-family: "Arimo";
        }

        .f-size-16 {
            font-size: 16pt;
        }

        .f-size-15-s {
            font-size: 15.5pt;
        }

        .f-size-12 {
            font-size: 12pt;
        }

        .f-size-10 {
            font-size: 10pt;
        }

        .f-size-9 {
            font-size: 9pt;
        }

        .f-size-8-s {
            font-size: 8.5pt;
        }

        .f-size-8 {
            font-size: 8pt;
        }

        .table-header>td {
            border-bottom: 4px double black;
            border-top: 1px solid black;
            font-size: 9pt;
            font-family: "Verdana";
            font-weight: bold;
        }

        .table-body>td {
            font-size: 9pt;
            font-family: "Verdana";
            font-weight: 400;
        }

    </style>
</head>

<body>
    
    <table>
        <tr>
            <td width="250px" class="t-left v-top" rowspan="3" style="padding-top: 10px">
                    <span class="fw-bold f-verdana f-size-15-s"><?= ucwords(strtolower(perusahaan('nama'))); ?></span><br>
                    <span class="fw-400 f-trebuchet f-size-8-s">
                        <?= perusahaan('alamat'); ?>
                    </span>
            </td>
            <td width="450px" class="t-center v-top" colspan="3">
                <span class="fw-bold f-arial f-size-16">FAKTUR PENJUALAN</span><br>
                <span class="fw-bold f-arial f-size-12">Nota : <?= $header->no_transaksi; ?></span>
            </td>
            <td class="t-right v-bottom" colspan="2">
                <span class="fw-bold f-arial f-size-10">Jakarta,</span> <span class="fw-400 f-verdana f-size-9"><?=date("d/m/Y",strtotime($header->tgl))?></span>
            </td>
        </tr>
        <tr>
            <td width="75px"></td>
            <td width="75px"></td>
            <td width="75px" class="fw-bold f-arial f-size-9 v-top">Kepada Yth :</td>
            <td class="fw-400 f-verdana f-size-9 v-top"><?= $header->nama; ?></td>
            <td width="20px"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="fw-400 f-verdana f-size-9 v-top" colspan="2">
                <?= $header->alamat; ?> <br> <?= $header->no_telp; ?>
            </td>
            <td></td>
        </tr>
    </table>
    
    <table style="padding-top: 6px;">
        <tr class="table-header">
            <td width="30px">No.</td>
            <td colspan="2" width="250px">Nama Item</td>
            <td class="t-center">Jml Satuan</td>
            <td class="t-right">Harga</td>
            <td class="t-right">Pot</td>
            <td class="t-center" colspan="2" style="padding-left: 30px;">Total</td>
        </tr>
        <?php $no=1;foreach($detail as $dtDetail):?>
        <tr class="table-body">
            <td><?= $no++; ?></td>
            <td colspan="2"><?= $dtDetail->nama; ?></td>
            <td class="t-center"><?= number_format($dtDetail->qty); ?> <?= $dtDetail->satuan; ?></td>
            <td class="t-right"><?= number_format($dtDetail->harga_satuan); ?></td>
            <td class="t-right"><?= number_format($dtDetail->diskon); ?></td>
            <td class="t-right" colspan="2"><?= number_format($dtDetail->qty*$dtDetail->harga_satuan); ?></td>
        </tr>
        <?php endforeach;?>
        <tr style="border-bottom: 1px solid black;">
            <td colspan="8">
            </td>
        </tr>
        <tr>
            <td colspan="3">
               <span class="f-size-10 fw-bold f-arial"><?=terbilang($header->grand_total)?></span>
            </td>
            <td class="t-right f-verdana f-size-9 fw-400"></td>
            <td class="t-right f-verdana f-size-9 fw-400" style="padding-right: 10px;"></td>
            <td class="f-size-9 f-verdana fw-400">Sub Total </td>
            <td class="f-size-9 f-verdana fw-400">:</td>
            <td class="t-right f-verdana f-size-9 fw-400"><?=number_format($header->total)?></td>
        </tr>
        <tr>
            <td class="f-size-8 f-verdana fw-400" colspan="5"></td>
            <td class="f-size-9 f-verdana fw-400">Potongan</td>
            <td class="f-size-9 f-verdana fw-400">:</td>
            <td class="t-right f-verdana f-size-9 fw-400"><?=number_format($header->diskon_faktur)?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="f-size-9 f-verdana fw-400">Total Akhir</td>
            <td class="f-size-9 f-verdana fw-400">:</td>
            <td class="t-right f-verdana f-size-9 fw-400"><?=number_format($header->grand_total)?></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="f-size-9 f-verdana fw-400">Biaya Lain</td>
            <td class="f-size-9 f-verdana fw-400">:</td>
            <td class="t-right f-verdana f-size-9 fw-400"><?=number_format($header->biaya_lain)?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td class="t-center f-verdana f-size-10 fw-400" rowspan="2" style="border: 1px solid black;">
                NO REK  <?=$bank->bank?>: <?=$bank->no_rekening?><br>
                ATN: <?=$bank->nama?>
            </td>
            <td colspan="2"></td>
            <td class="f-size-9 f-verdana fw-400">Sisa</td>
            <td class="f-size-9 f-verdana fw-400">:</td>
            <td class="t-right f-verdana f-size-9 fw-400"><?=number_format($header->grand_total)?></td>
        </tr>
        <tr>
            <td></td>
            <td class="t-center fw-bold f-verdana f-size-10" >Penerima</td>
            <td class="t-center fw-bold f-verdana f-size-10" colspan="2">Hormat Kami</td>
        </tr>
        <tr>
            <td class="tg-0pky" colspan="8"><br></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td class="t-center f-verdana f-size-8 fw-400"  style="border: 1px solid black !important;">Barang telah diterima dengan baik.<br>Barang yang sudah dibeli tidak bisa<br>ditukar atau dikembalikan.</td>
            <td colspan="5"></td>
        </tr>
    </table>



</body>

</html>