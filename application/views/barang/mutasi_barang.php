<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Mutasi Barang <?= $barang['nama_barang']?>
                </h4>
            </div>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nomor Transaksi</th>
                    <th>Nama Barang</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= !empty($barang['no']) ? $barang['no'] : ""; ?></td>
                    <td>Saldo Awal Stok</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $barang['stok_awal'].' '.$satuan['nama_satuan']; ?></td>
                </tr>
                <?php
                $stok_awal = $barang['stok_awal'];

                if ($mutasi_barang) :
                    $sisa_stok = $stok_awal;
                    $no_mutasi = $no + 1;
                    foreach ($mutasi_barang as $b) :

                        switch ($b['jenis']) {
                            case "out_tr":
                                $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                                break;
                            case "in":
                                $jenis = 'Barang Masuk';
                                break;
                            case "in_retur":
                                $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                                break;
                            default:
                                $jenis = 'Barang Keluar';
                        }
                ?>

                        <tr>
                            <td><?= $no_mutasi++; ?></td>
                            <td align="right"><?= $b['no']; ?></td>
                            <td><?= $b['nama_barang']; ?></td>
                            <td><?= $b['tgl']; ?></td>
                            <td><?= $jenis; ?></td>
                        <?php if($b['jenis'] == 'in' || $b['jenis'] == 'in_retur') { ?>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                            <td></td>
                            <?php $sisa_stok += $b['jml']; ?>
                        <?php } else { ?>
                            <td></td>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                            <?php $sisa_stok -= $b['jml']; ?>
                        <?php } ?>
                            <td><?= $sisa_stok . ' ' . $b['nama_satuan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>