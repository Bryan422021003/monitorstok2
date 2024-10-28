<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Tabel <?= $title; ?>
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover w-100 dt-responsive nowrap">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Batas</th>
                    <th>Jumlah Stok Magetan</th>
                    <th>Jumlah Stok Caruban</th>
                    <th>Total Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barang) :
                    foreach ($barang as $bm) :
                        // class magetan
                        $class = '';
                        if($bm['stok_magetan'] <= $bm['batas']) {
                            $class = 'bg-danger text-white font-weight-bold';
                        } else if($bm['stok_magetan'] > $bm['batas'] && $bm['stok_magetan'] < (intval($bm['batas'])+10)) {
                            $class = 'bg-warning text-white font-weight-bold';
                        } else if($bm['stok_magetan'] >= (intval($bm['batas'])+10)) {
                            $class = 'bg-success text-white font-weight-bold';
                        }

                        // class caruban
                        $class_crb = '';
                        if($bm['stok_caruban'] <= $bm['batas']) {
                            $class_crb = 'bg-danger text-white font-weight-bold';
                        } else if($bm['stok_caruban'] > $bm['batas'] && $bm['stok_caruban'] < (intval($bm['batas'])+10)) {
                            $class_crb = 'bg-warning text-white font-weight-bold';
                        } else if($bm['stok_caruban'] >= (intval($bm['batas'])+10)) {
                            $class_crb = 'bg-success text-white font-weight-bold';
                        }
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bm['id_barang']; ?></td>
                            <td><?= $bm['nama_barang']; ?></td>
                            <td class="text-dark font-weight-bold"><?= $bm['batas']; ?></td>
                            <td class="<?= $class; ?>"><?= $bm['stok_magetan']; ?></td>
                            <td class="<?= $class_crb; ?>"><?= $bm['stok_caruban']; ?></td>
                            <td><?= $bm['total_stok']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>