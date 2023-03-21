<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ms" lang="ms">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            font-family: Verdana, Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
            margin: 0px;
            padding-top: 0.18cm;
            padding-right: 0.88cm;
            padding-bottom: 0.49cm;
            padding-left: 0.46cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .fw-bold {
            font-weight: bold;
        }

        .fw-400 {
            font-weight: 400;
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

        table>tbody>tr.table-header>td {
            border-bottom: 4px double black;
            border-top: 1px solid black;
            font-size: 9pt;
            font-weight: bold;
        }

        .table-body>td {
            font-size: 9pt;
            font-weight: 400;
        }

    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th class="t-left" colspan="2" rowspan="2">
                    <br><span class="fw-bold f-size-15-s">Makmur Permai</span>
                    <br><span class="fw-400 f-trebuchet f-size-8-s">Boulevard Timur Blok NE1 No. 40
                    <br>Telp:453.0095 4584.2138 4584.2139
                    <br>Fax: 453.0093
                </th>
                <th colspan="2">
                    <span class="fw-bold f-arial f-size-16">Faktur Penjualan</span>
                    <br><span class="fw-bold f-arial f-size-12">Nota: <?=$header->no_transaksi?></span>
                </th>
                <th class="t-right" colspan="4">
                    <br><span class="fw-bold f-arial f-size-10">Jakarta,</span> <span class="fw-400 f-size-9"><?=date("d-m-Y",strtotime($header->tgl))?></span>
                </th>
            </tr>
            <tr>
                <th colspan="6"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3"></td>
                <td class="fw-bold f-arial f-size-9" style="vertical-align: top;">Kepada YTH :</td>
                <td class="fw-400 f-verdana f-size-9" colspan="3">
                    <span class="fw-400 f-verdana f-size-9"><?=$header->nama?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td class="fw-400 f-verdana f-size-9" colspan="4"><?=$header->alamat?> <br> <?=$header->no_telp?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="8"></td>
            </tr>
            <tr class="table-header">
                <td width="30px">No.</td>
                <td colspan="2" width="390px">Nama Item</td>
                <td class="t-center">Jml Satuan</td>
                <td class="t-right">Harga</td>
                <td class="t-right">Pot</td>
                <td class="t-center" colspan="2">Total</td>
            </tr>
            <?php $no=1;foreach($detail as $dtDetail):?>
            <tr class="table-body">
                <td><?= $no++;?></td>
                <td colspan="2"><?=$dtDetail->nama?></td>
                <td class="t-center"><?=$dtDetail->qty?> <?=$dtDetail->satuan?></td>
                <td class="t-right"><?=number_format($dtDetail->harga_satuan)?></td>
                <td class="t-right"><?=$dtDetail->diskon?></td>
                <td class="t-right" colspan="2"><?=number_format($dtDetail->qty*$dtDetail->harga_satuan)?></td>
            </tr>
            <?php endforeach;?>
            <tr style="border-bottom: 1px solid black;">
                <td colspan="8">
                    <?php 
                        $totalData = count($detail); 
                        if($totalData < 5) echo '<br>';
                        if($totalData <= 3) echo '<br>';
                        if($totalData <= 1) echo '<br>';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="f-size-10 fw-bold">Jatuh Tempo :</span> <span class="f-size-10 fw-bold f-arial"><?=date("d-m-Y",strtotime($header->tgl))?></span>
                </td>
                <td class="t-right f-size-9 fw-400">Biaya Lain :</td>
                <td class="t-right f-size-9 fw-400" style="padding-right: 10px;">0</td>
                <td class="f-size-9 fw-400">Sub Total </td>
                <td class="f-size-9 fw-400">:</td>
                <td class="t-right f-size-9 fw-400"><?=number_format($header->total)?></td>
            </tr>
            <tr>
                <td class="f-size-8 fw-400" colspan="5"><?=terbilang($header->grand_total)?></td>
                <td class="f-size-9 fw-400">Potongan</td>
                <td class="f-size-9 fw-400">:</td>
                <td class="t-right f-size-9 fw-400"><?=number_format($header->diskon_faktur)?></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td class="f-size-9 fw-400">Total Akhir</td>
                <td class="f-size-9 fw-400">:</td>
                <td class="t-right f-size-9 fw-400"><?=number_format($header->grand_total)?></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td class="f-size-9 fw-400">DP</td>
                <td class="f-size-9 fw-400">:</td>
                <td class="t-right f-size-9 fw-400"><?=number_format($header->uang_muka)?></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="t-center f-size-10 fw-400" rowspan="2" style="border: 1px solid black;">NO REK BCA: <?=$bank->bank?><br>ATN: <?=$bank->nama?></td>
                <td colspan="2"></td>
                <td class="f-size-9 fw-400">Tunai</td>
                <td class="f-size-9 fw-400">:</td>
                <td class="t-right f-size-9 fw-400">0</td>
            </tr>
            <tr>
                <td></td>
                <td class="t-center fw-bold f-verdana f-size-10" >Penerima</td>
                <td class="t-center fw-bold f-verdana f-size-10" colspan="2">Hormat Kami</td>
                <td class="f-size-9 fw-400 f-verdana">Kredit</td>
                <td class="f-size-9 fw-400 f-verdana">:</td>
                <td class="t-right f-size-9 fw-400 f-verdana">0</td>
            </tr>
            <tr>
                <td class="tg-0pky" colspan="8"><br></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="t-center f-size-8 fw-400"  style="border: 1px solid black !important;">Barang telah diterima dengan baik.<br>Barang yang sudah dibeli tidak bisa<br>ditukar atau dikembalikan.</td>
                <td colspan="5"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>