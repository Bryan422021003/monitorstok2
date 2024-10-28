<?= $this->session->flashdata('pesan'); ?>



<div class="row mt-3 mb-4">
    <div class="col-sm">
        <div class="card border-bottom-primary shadow">
            <div class="col-sm">
                <div class="mt-2 mb-2">
                    <div class="card-body">
                        <form action="" method="post" class="form-inline">
                            <div class="form-group col-sm-3">
                                <label for="bln" class="col-sm-5 col-form-label">Pilih Barang</label>
                                <div class="col-sm-7">
                                    <select name="id_barang" id="id_barang" class="custom-select">
                                        <option value="" selected disabled>Pilih Barang</option>
                                        <?php foreach ($barang as $row_b) : ?>
                                            <?php $selected = (in_array($sa_brg['id_sa'], $row_b)) ? "selected" : null;?>
                                            <option value="<?= $row_b['id_sa'] ?>" <?= $selected; ?> ><?= $row_b['nama_barang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="bln" class="col-sm-5 col-form-label">Tanggal Awal</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $tglAwal; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="tahun" class="col-sm-5 col-form-label">Tanggal Akhir</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $tglAkhir; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-3 justify-content-center">
                                <button type="submit" class="btn btn-primary mr-3">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-fw fa-filter"></i>
                                    </span>
                                    <span class="text">Filter</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    <?php if($status == 1) { ?>
                        Mutasi Barang - <?= $barang_id['nama_barang']?>
                    <?php } ?>
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
                    <th>Team Teknisi</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if($status == 1) { ?>
                <?php $no = 1; ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= !empty($barang['no']) ? $barang['no'] : ""; ?></td>
                    <td>Saldo Awal Stok</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $stok_awal['qty'].' '.$satuan['nama_satuan']; ?></td>
                </tr>
                <?php
                $stok_awal = $stok_awal['qty'];

                $total_masuk = 0;
                $total_keluar = 0;
                $sisa_stok = 0;

                if ($mutasi_barang) :
                    $sisa_stok += $stok_awal;
                    $no_mutasi = $no + 1;
                    foreach ($mutasi_barang as $b) :

                        switch ($b['jenis']) {
                            case "in_tr":
                                $jenis = 'Mutasi Barang Masuk ke ' . $b['trs_cbg'];
                                break;
                            case "out_tr":
                                $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                                break;
                            case "in":
                                $jenis = 'Barang Masuk';
                                break;
                            case "in_retur":
                                $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                                break;
                            case "stkpn":
                                $jenis = 'Stok Op Name dari ' . $b['nama_cabang'];
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
                            <td><?= $b['nama_customer']; ?></td>
                        <?php if($b['jenis'] == 'in' || $b['jenis'] == 'in_retur') { 
                                    $total_masuk += $b['jml'];?>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                            <td></td>
                            <?php $sisa_stok += $b['jml']; ?>
                        <?php } else if($b['jenis'] == 'in_tr' ) { 
                                    $total_masuk += $b['jml']; ?>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                            <td></td>
                            <?php $sisa_stok += $b['jml']; ?>
                        <?php } else if($b['jenis'] == 'stkpn' ) { 
                                if($b['jml'] > 0) { 
                                    $total_masuk += $b['jml'];
                                    ?>
                                    <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                                    <td></td>
                                    <?php $sisa_stok += intval($b['jml']); 
                                } else { 
                                    $total_keluar += $b['jml'];
                                    ?>
                                    <td></td>
                                    <td><?= abs($b['jml']) . ' ' . $b['nama_satuan'] ?></td>
                                    <?php $sisa_stok += intval($b['jml']); 
                                } ?>
                        <?php } else {
                                $total_keluar += $b['jml']; ?>
                            <td></td>
                            <td><?= $b['jml'] . ' ' . $b['nama_satuan'] ?></td>
                            <?php $sisa_stok -= intval($b['jml']); ?>
                        <?php } ?>
                            <td><?= $sisa_stok . ' ' . $b['nama_satuan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            Tidak ada mutasi barang!
                        </td>
                    </tr>
                <?php endif; ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="8" class="text-center">data tidak ada!</td>
                    </tr>

                <?php } ?>
            </tbody>
            <?php if($status == 1) { ?>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right">Jumlah</td>
                    <td><?= $total_masuk.' '.$satuan['nama_satuan']; ?></td>
                    <td><?= $total_keluar.' '.$satuan['nama_satuan']; ?></td>
                    <td><?= $stok_awal+$total_masuk-$total_keluar.' '.$satuan['nama_satuan']; ?></td>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>
</div>