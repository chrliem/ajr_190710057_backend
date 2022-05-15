<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    <link rel="stylesheet" href="css/app.css">
        <title>Nota Transaksi</title>
    </head>
    <body>
  <table style="border:1px black solid; width: 100%;">
    <tbody>
    <tr>
    <td colspan="4"><center><strong>Atma Jogja Rental</strong></center></td>
    </tr>
    <tr class="border-bottom">
        <td width="25%">{{$transaksi[0]->no_transaksi}}</td>
        <td width="25%"></td>
        <td width="25%"></td>
        <td width="25%">{{$transaksi[0]->tgl_transaksi}}</td>
    </tr>
    <tr>
        <td>Customer</td>
        <td>{{$transaksi[0]->nama_customer}}</td>
        <td>Promo</td>
        <td>{{$transaksi[0]->kode_promo}}</td>
    </tr>
    <tr>
        <td>Customer Service</td>
        <td>{{$transaksi[0]->nama_pegawai}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr >
        <td>Driver</td>
        <td>{{$transaksi[0]->nama_driver}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr style="background-color:black">
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr class="border-bottom">
        <td colspan="4"><strong><center>Nota Transaksi</center></strong></td>
    </tr>
    <tr class="border-bottom">
        <td>Tanggal Mulai Sewa</td>
        <td>{{$transaksi[0]->tgl_mulai_sewa}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="border-bottom">
        <td>Tanggal Selesai Sewa</td>
        <td>{{$transaksi[0]->tgl_selesai_sewa}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="border-bottom">
        <td>Tanggal Pengembalian</td>
        <td>{{$transaksi[0]->tgl_pengembalian}}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr class="border-bottom">
        <td><strong>Item</strong></td>
        <td><strong>Satuan</strong></td>
        <td><strong>Durasi</strong></td>
        <td><strong>Sub Total</strong></td>
    </tr>
    <tr>
        <td>[Mobil] {{$transaksi[0]->nama_mobil}}</td>
        <td>{{$transaksi[0]->tarif_mobil_harian}}</td>
        <td>{{$transaksi[0]->durasi_penyewaan}} Hari</td>
        <td>{{$transaksi[0]->total_biaya_mobil}}</td>
    </tr>
    <tr class="border-bottom">
        @if($transaksi[0]->nama_driver == null)
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        @else
            <td>[Driver] {{$transaksi[0]->nama_driver}}</td>
            <td>{{$transaksi[0]->tarif_driver_harian}}</td>
            <td>{{$transaksi[0]->durasi_penyewaan}} Hari</td>
            <td>{{$transaksi[0]->total_biaya_driver}}</td>
        @endif
    </tr>
    
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong>{{$transaksi[0]->total_biaya_driver+$transaksi[0]->total_biaya_mobil}}</strong></td>
    </tr>
    <tr style="background-color:black">
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td><center>Customer</center></td>
        <td><center>Customer Service</center></td>
        <td class="border-bottom-1">Diskon</td>
        <td class="border-bottom-1">{{($transaksi[0]->potongan_promo)*$transaksi[0]->grand_total_pembayaran}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="border-bottom-1">Denda</td>
        <td class="border-bottom-1">{{$transaksi[0]->total_biaya_ekstensi}}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="border-bottom-1">Total</td>
        <td class="border-bottom-1"><strong>{{$transaksi[0]->grand_total_pembayaran}}</strong></td>
    </tr>
    <tr>
        <td><center>{{$transaksi[0]->nama_customer}}</center></td>
        <td><center>{{$transaksi[0]->nama_pegawai}}</center></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
    </table>

</body>    
</html>

<style>
    tr.border-bottom td {
  border-bottom: 1px solid black;
}
td.border-bottom-1{
    border-bottom: 1px solid black;
}
</style>