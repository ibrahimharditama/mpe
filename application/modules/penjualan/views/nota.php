<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ms" lang="ms">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
    @page {
        margin: 0px;
    }
	
	body{
		margin: 0px;
		font-family: Verdana, Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; 
        font-size: 12px		
	}

    p {
        padding: 0px;
        margin-bottom: 0rem;
        margin-top: 0rem;
    }

    h1 {
        font-size: 24px;
        margin-top: 0rem;
        margin-bottom: 0rem;
    }

    h2 {
        font-size: 20px;
        margin-top: 0rem;
        margin-bottom: 0rem;
    }

    h3 {
        font-size: 16px;
        margin-top: 0rem;
        margin-bottom: 0rem;
    }
	
	.bold{
        font-weight: bold;
	}
    </style>
</head>

<body style="padding:10px">
    <table style="width:100%">
        <tr>
            <td valign="top" width="30%">
                <h2>Makmur Permai</h2>
                <p>Boulevard Timur Blok NE1 No. 40</p>
                <p>Telp:453.0095 4584.2138 4584.2139</p>
                <p>Fax: 453.0093</p>
            </td>
            <td  valign="top" align="center" width="40%">
                <h1>FAKTUR PENJUALAN</h1>
                <h3>Nota : <?=$header->no_transaksi?></h3>
            </td>
            <td valign="bottom"  width="30%">
                <span class="bold">Jakarta, </span><span><?=date("d-m-Y",strtotime($header->tgl))?></span>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td>
                <p class="bold">Kepada Yth:</p>
                <p><?=$header->nama?></p>
                <p><?=$header->alamat?></p>
                <p><?=$header->no_telp?></p>
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;width:100%;margin-top:10px;" cellspacing="0">
        <tr>
            <td style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">No.</p>
            </td>
            <td style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">Nama Item</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">Jml Satuan</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">Harga</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">Pot</p>
            </td>
            <td align="center"
                style="border-top-style:solid;border-top-width:1pt;border-bottom-style:solid;border-bottom-width:1pt">
                <p class="bold">Total</p>
            </td>
        </tr>
        <?php $no=1;foreach($detail as $dtDetail):?>
        <tr>
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
		<tr>
		<td style="height:20pt" colspan="6"></td>
		</tr>
        <?php endforeach;?>
        <tr style="height:12pt">
            <td colspan="3" valign="top">
                <p class="bold">Jatuh Tempo : </p>
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