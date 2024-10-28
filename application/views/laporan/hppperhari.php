<?= $this->session->flashdata('pesan'); ?>
<style type="text/css">
    /* Style untuk div di atas tabel */
    .top-scroll {
      max-height: 50vh; /* Atur tinggi div sesuai kebutuhan */
      overflow-y: auto; /* Hilangkan bilah gulir vertikal */
      overflow-x: auto; /* Aktifkan bilah gulir horizontal */
    }    
</style>


<div class="row mt-3 mb-4 search-filter">
    <div class="col-sm">
        <div class="card border-bottom-primary shadow">
            <div class="col-sm-12">
                <div class="mt-2 mb-2">
                    <div class="card-body">
                        <form action="" method="post" class="form-inline">
                            
                            <div class="form-group mb-2">
                                <div class="col-sm-12">
                                    <label for="tglAkhir" class="col-sm-6 col-form-label">Bulan</label>
                                    <div class="col-sm-6">
                                        <select class="form-control " id="bulan" name="bulan" required>
                                            <option value="">Pilih Bulan</option>
                                            <option value="1" <?= ($bulan_now == 1) ? "selected" : null; ?> >Januari</option>
                                            <option value="2" <?= ($bulan_now == 2) ? "selected" : null; ?> >Februari</option>
                                            <option value="3" <?= ($bulan_now == 3) ? "selected" : null; ?> >Maret</option>
                                            <option value="4" <?= ($bulan_now == 4) ? "selected" : null; ?> >April</option>
                                            <option value="5" <?= ($bulan_now == 5) ? "selected" : null; ?> >Mei</option>
                                            <option value="6" <?= ($bulan_now == 6) ? "selected" : null; ?> >Juni</option>
                                            <option value="7" <?= ($bulan_now == 7) ? "selected" : null; ?> >Juli</option>
                                            <option value="8" <?= ($bulan_now == 8) ? "selected" : null; ?> >Agustus</option>
                                            <option value="9" <?= ($bulan_now == 9) ? "selected" : null; ?> >September</option>
                                            <option value="10" <?= ($bulan_now == 10) ? "selected" : null; ?> >Oktober</option>
                                            <option value="11" <?= ($bulan_now == 11) ? "selected" : null; ?> >November</option>
                                            <option value="12" <?= ($bulan_now == 12) ? "selected" : null; ?> >Desember</option>
                                        </select>
                                        <?= form_error('bulan', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <div class="col-sm-12">
                                    <label for="tglAwal" class="col-sm-6 col-form-label">Tahun</label>
                                    <div class="col-sm-6">
                                        <select class="form-control " id="tahun" name="tahun" required>
                                            <option value="">Pilih Tahun</option>
                                            <?php foreach($tahun as $ta) : ?>
                                                  
                                                <option value="<?= $ta; ?>" <?= ($tahun_now == $ta) ? "selected" : null; ?>><?= $ta ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= form_error('tahun', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2 justify-content-end">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success btn-sm" name="cari">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-fw fa-filter"></i>
                                        </span>
                                        <span class="text">Filter</span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- menampilkan hpp per hari -->
<div class="row mt-3 mb-4">
    <div class="col-sm">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Tabel Perhitungan HPP
                        </h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive top-scroll">
                <table class="table table-striped w-100 dt-responsive nowrap">
                    <thead class="font-weight-bold">
                        <tr>
                            <td>Tanggal</td>
                            <td>Qty In</td>
                            <td>Harga In</td>
                            <td>Qty Out</td>
                            <td>Harga Out</td>
                            <td>Qty Akhir</td>
                            <td>Harga Akhir</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $no = 1;
                        if ($dt_transaksi) :

                            $sum_qty_awal = 0;
                            $sum_harga_awal = 0;
                            $sum_stok_in = 0;
                            $sum_stok_out = 0; 
                            $sum_tot = 0;

                            $sum_array = array();
                                        
                            foreach ($dt_transaksi as $dt) :

                                $sum_qty_awal += $dt['saldo_awal']['qty'];
                                $sum_harga_awal += $dt['saldo_awal']['qty']*$dt['saldo_awal']['harga'];

                                $qty = $dt['saldo_awal']['qty'];
                                $harga = $dt['saldo_awal']['harga'];

                                $sum_in = 0;
                                $sum_out = 0;

                                $sum_transaksi = array();

                                foreach($dt['transaksi'] as $trans) { 
                                    // perhitungan hpp
                                    if ($trans['masuk']['qty'] > 0) {
                                        $harga_rata_rata = (($qty * $harga) + ($trans['masuk']['qty'] * $trans['masuk']['harga'])) / ($qty + $trans['masuk']['qty']);
                                        $qty += $trans['masuk']['qty'];
                                        $sum_in += $trans['masuk']['qty'];
                                    } else {
                                        $harga_rata_rata = $harga;
                                    }

                                    if ($trans['keluar']['qty'] > 0) {
                                        $qty -= $trans['keluar']['qty'];
                                        $sum_out += $trans['keluar']['qty'];
                                        $harga_rata_rata = $harga;
                                    } 

                                    $harga = $harga_rata_rata;

                                    $sum_transaksi[] = array(
                                        'tgl' => $trans['tgl'],
                                        'qty_in' => $trans['masuk']['qty'],
                                        'total_in' => $trans['masuk']['qty']*$trans['masuk']['harga'],
                                        'qty_out' => $trans['keluar']['qty'],
                                        'total_out' => $trans['keluar']['qty']*$harga_rata_rata,
                                        'qty_res' => $qty,
                                        'tot_res' => $qty*$harga_rata_rata,
                                    ); 
                                        
                                }

                                $sum_array[] = array(
                                    'nm_barang' => $dt['nm_barang'],
                                    'transaksi' => $sum_transaksi
                                );

                                $sum_stok_in += $sum_in;
                                $sum_stok_out += $sum_out; 

                                $sum_tot += $qty*$harga_rata_rata;

                            endforeach; 


                            // menyusun disini
                            $res_result = array();            
                            foreach($array_tgl as $row_tgl) { 

                                $qty_in = 0;
                                $total_in = 0;
                                $qty_out = 0;
                                $total_out = 0;
                                $qty_res = 0;
                                $tot_res = 0;

                                foreach($sum_array as $row_sum) {
                                    foreach($row_sum['transaksi'] as $row_rs) {
                                        if($row_rs['tgl'] == $row_tgl) {
                                            $qty_in += $row_rs['qty_in'];
                                            $total_in += $row_rs['total_in'];
                                            $qty_out += $row_rs['qty_out'];
                                            $total_out += $row_rs['total_out'];
                                            $qty_res += $row_rs['qty_res'];
                                            $tot_res += $row_rs['tot_res'];
                                        }
                                    }
                                    
                                }

                                $res_result[] = array(
                                    'tgl' => $row_tgl,
                                    'qty_in' => $qty_in ,
                                    'total_in' => $total_in,
                                    'qty_out' => $qty_out,
                                    'total_out' => $total_out,
                                    'qty_res' => $qty_res,
                                    'tot_res' => $tot_res,
                                );
                            }

                            // menyusun disini
                            ?>

                            <tr>
                                <td>Saldo Awal</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?= $sum_qty_awal; ?></td>
                                <td><?= $sum_harga_awal; ?></td>
                            </tr>

                                <?php foreach($res_result as $row_res) { ?>
                                    <tr>
                                        <td><?= $row_res['tgl']; ?></td>
                                        <td><?= $row_res['qty_in']; ?></td>
                                        <td><?= $row_res['total_in']; ?></td>
                                        <td><?= $row_res['qty_out']; ?></td>
                                        <td><?= $row_res['total_out']; ?></td>
                                        <td><?= $row_res['qty_res']; ?></td>
                                        <td><?= $row_res['tot_res']; ?></td>
                                    </tr>
                                <?php } ?>  

                            </tr>


                    <?php endif; ?>

                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>