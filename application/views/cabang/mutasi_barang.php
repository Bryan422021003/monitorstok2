<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Cabang <?= $cabang['nama_cabang'] ?>
                </h4>
            </div>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nama Barang</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($mutasi_barang) :
                    $sisa_stok = 0;
                    foreach ($mutasi_barang as $b) :

                        switch ($b['jenis']) {
                            case "out_tr":
                                $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                                break;
                            case "in":
                                $jenis = 'Barang Masuk ke ' . $b['nama_cabang'];
                                break;
                            case "in_retur":
                                $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                                break;
                            default:
                                $jenis = 'Barang Keluar dari ' . $b['nama_cabang'];
                        }
                ?>

                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $b['nama_barang']; ?></td>
                            <td><?= $b['tgl']; ?></td>
                            <td><?= $jenis; ?></td>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
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