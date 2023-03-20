<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ms" lang="ms">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
    @page {
        margin: 0px;
    }

    body {
        margin: 0px;
    }

    p {
        padding: 0px;
        margin-bottom: 0rem;
        margin-top: 0rem;
        font-size: 12px
    }

    h1 {
        font-size: 20px;
        font-weight: normal;
        margin-top: 0rem;
        margin-bottom: 0rem;
    }

    h2 {
        font-size: 18px;
        margin-top: 0rem;
        margin-bottom: 0rem;
        font-weight: bold;
    }

    h3 {
        display: block;
        font-size: 16px;
        margin-top: 0rem;
        margin-bottom: 0rem;
        font-weight: bold;
    }
    </style>
</head>

<body style="padding:10px">
    <table style="width:100%">
        <tr>
            <td>
                <h2>Makmur Permai</h2>
                <p>Boulevard Timur Blok NE1 No. 40</p>
                <p>Telp:453.0095 4584.2138 4584.2139</p>
                <p>Fax: 453.0093</p>
            </td>
            <td align="center">
                <h1>FAKTUR PENJUALAN</h1>
                <h3>Nota : <?=$header->no_transaksi?></h3>
            </td>
            <td valign="bottom">
                <p>Jakarta, 10/03/2023 12:47:20</p>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td>
                <p>Kepada Yth:</p>
                <p><?=$header->nama?></p>
                <p><?=$header->alamat?></p>
                <p><?=$header->no_telp?></p>
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;width:100%;margin-top:10px" cellspacing="0">
        <tr style="height:16pt">
            <td style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>No.</p>
            </td>
            <td style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>Nama Item
                </p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>Jml Satuan
                </p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>Harga</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>Pot</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p>Total</p>
            </td>
        </tr>
        <?php $no=1;foreach($detail as $dtDetail):?>
        <tr style="height:12pt">
            <td width="5%">
                <p><?= $no++;?></p>
            </td>
            <td width="35%">
                <p><?=$dtDetail->nama?></p>
            </td>
            <td align="center" width="10%">
                <p><?=$dtDetail->qty?> <?=$dtDetail->satuan?></p>
            </td>
            <td align="right" width="10%">
                <p><?=number_format($dtDetail->harga_satuan)?></p>
            </td>
            <td align="right" width="10%">
                <p><?=$dtDetail->diskon?></p>
            </td>
            <td align="right" width="10%">
                <p><?=number_format($dtDetail->qty*$dtDetail->harga_satuan)?></p>
            </td>
        </tr>
        <?php endforeach;?>
        <tr style="height:12pt">
            <td colspan="3" valign="top">
                <p>Jatuh Tempo : 10/03/2023</p>
            </td>
            <td width="10%" valign="top">
                <p>Biaya Lain :</p>
            </td>
            <td align="right" valign="top">
                <p>Sub Total : </p>
            </td>
            <td align="right" valign="top">
                <p><?=number_format($header->total)?></p>
            </td>
        </tr>
        <tr style="height:12pt">
            <td colspan="4" valign="top">
                <p>tujuh belas juta tujuh ratus ribu rupiah</p>
            </td>
            <td align="right" valign="top">
                <p>Potongan : </p>
            </td>
            <td align="right" valign="top">
                <p><?=number_format($header->diskon_faktur)?></p>
            </td>
        </tr>
        <tr style="height:12pt">
            <td colspan="4" valign="top">
            </td>
            <td align="right" valign="top">
                <p>Total Akhir : </p>
            </td>
            <td align="right" valign="top">
                <p><?=number_format($header->grand_total)?></p>
            </td>
        </tr>
        <tr style="height:12pt">
            <td colspan="4" valign="top">
            </td>
            <td align="right" valign="top">
                <p>DP : </p>
            </td>
            <td align="right" valign="top">
                <p><?=number_format($header->uang_muka)?></p>
            </td>
        </tr>
        <tr style="height:12pt">
            <td colspan="4" valign="top">
            </td>
            <td align="right" valign="top">
                <p>Tunai : </p>
            </td>
            <td align="right" valign="top">
            </td>
        </tr>
        <tr style="height:12pt">
            <td colspan="4" valign="top">
            </td>
            <td align="right" valign="top">
                <p>Kredit : </p>
            </td>
            <td align="right" valign="top">
            </td>
        </tr>
    </table>
</body>

</html>