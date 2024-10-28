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
                            <th rowspan="3">No. </th>
                            <th rowspan="3">ID Barang</th>
                            <th rowspan="3">Nama Barang</th>
                            <th colspan="3">Saldo Awal</th>

                            <?php 
                            $jml_row = 0;
                            foreach($array_tgl as $row_tgl) {
                                $jml_row += 9;
                            } ?>

                            <th colspan="<?= $jml_row; ?>">Perhitungan HPP per Tanggal</th>

                            <th rowspan="3">Jumlah Stok Awal</th>
                            <th rowspan="3">Jumlah Stok In</th>
                            <th rowspan="3">Jumlah Stok Out</th>

                            <th colspan="3">Saldo Akhir</th>

                        </tr>
                        <tr>
                            <td rowspan="2" style="background-color: #FFFFE0;">Qty SA</td>
                            <td rowspan="2" style="background-color: #ADD8E6;">Harga SA</td>
                            <td rowspan="2" style="background-color: #98FB98;">Total SA</td>
                            <!-- looping per tanggal -->
                            <?php 
                            $tgl = 0;
                            foreach($array_tgl as $row_tgl) { ?>
                                <td colspan="9"><?= $row_tgl; ?></td>
                            <?php } ?>

                            <td rowspan="2" style="background-color: #FFFFE0;">Qty Akhir</td>
                            <td rowspan="2" style="background-color: #ADD8E6;">Hpp Akhir</td>
                            <td rowspan="2" style="background-color: #98FB98;">Total Akhir</td>

                        </tr>
                        <tr>
                            <!-- looping per tanggal -->
                            <?php 
                            $tgl = 0;
                            foreach($array_tgl as $row_tgl) { ?>
                                <td style="background-color: #FFFFE0;">Qty In</td>
                                <td style="background-color: #ADD8E6;">Harga In</td>
                                <td style="background-color: #98FB98;">Total In</td>

                                <td style="background-color: #FFFFE0;">Qty Out</td>
                                <td style="background-color: #ADD8E6;">HPP</td>
                                <td style="background-color: #98FB98;">Total Harga</td>

                                <td style="background-color: #FFFFE0;">Qty Saldo</td>
                                <td style="background-color: #ADD8E6;">Hpp</td>
                                <td style="background-color: #98FB98;">Total Harga</td>
                            <?php } ?>
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
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $dt['id_barang']; ?></td>
                                    <td><?= $dt['nm_barang']; ?></td>
                                    <td style="background-color: #FFFFE0;"><?= $dt['saldo_awal']['qty']; ?></td>
                                    <td style="background-color: #ADD8E6;"><?= $dt['saldo_awal']['harga']; ?></td>
                                    <td style="background-color: #98FB98;"><?= $dt['saldo_awal']['qty']*$dt['saldo_awal']['harga']; ?></td>


                                    <?php 
                                        $sum_qty_awal += $dt['saldo_awal']['qty'];
                                        $sum_harga_awal += $dt['saldo_awal']['qty']*$dt['saldo_awal']['harga'];

                                        $qty = $dt['saldo_awal']['qty'];
                                        $harga = $dt['saldo_awal']['harga'];

                                        $sum_in = 0;
                                        $sum_out = 0;

                                        $sum_transaksi = array();

                                        foreach($dt['transaksi'] as $trans) { 

                                            // echo json_encode($trans);
                                            // die;

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




                                            // echo $harga;
                                            // die;
                                            ?>

                                                <td style="background-color: #FFFFE0;"><?= $trans['masuk']['qty']; ?></td>
                                                <td style="background-color: #ADD8E6;"><?= $trans['masuk']['harga']; ?></td>
                                                <td style="background-color: #98FB98;"><?= $trans['masuk']['qty']*$trans['masuk']['harga']; ?></td>

                                                <td style="background-color: #FFFFE0;"><?= $trans['keluar']['qty']; ?></td>
                                                <td style="background-color: #ADD8E6;"><?= ($trans['keluar']['qty'] > 0) ? $harga_rata_rata : 0; ?></td>
                                                <td style="background-color: #98FB98;"><?= $trans['keluar']['qty']*$harga_rata_rata; ?></td>

                                                <td style="background-color: #FFFFE0;"><?= $qty; ?></td>
                                                <td style="background-color: #ADD8E6;"><?= $harga_rata_rata; ?></td>
                                                <td style="background-color: #98FB98;"><?= $qty*$harga_rata_rata; ?></td>

                                        <?php 

                                            // );
                                            $sum_transaksi[] = array(
                                                'tgl' => $trans['tgl'],
                                                'qty_in' => $trans['masuk']['qty'],
                                                'total_in' => $trans['masuk']['qty']*$trans['masuk']['harga'],
                                                'qty_out' => $trans['keluar']['qty'],
                                                'total_out' => $trans['keluar']['qty']*$harga_rata_rata,
                                                'qty_res' => $qty,
                                                'tot_res' => $qty*$harga_rata_rata,
                                            ); 


                                           


                                        } ?>

                                    <td><?= $dt['saldo_awal']['qty']; ?></td>
                                    <!-- stok in -->
                                    <td><?= $sum_in; ?></td>

                                    <!-- stok out -->
                                    <td><?= $sum_out; ?></td>

                                    <!-- saldo akhir -->
                                    <td style="background-color: #FFFFE0;"><?= $qty; ?></td>
                                    <td style="background-color: #ADD8E6;"><?= $harga_rata_rata; ?></td>
                                    <td style="background-color: #98FB98;"><?= $qty*$harga_rata_rata; ?></td>
                                </tr>
                            <?php 
                                $sum_array[] = array(
                                    'nm_barang' => $dt['nm_barang'],
                                    'transaksi' => $sum_transaksi
                                );

                                $sum_stok_in += $sum_in;
                                $sum_stok_out += $sum_out; 

                                $sum_tot += $qty*$harga_rata_rata;

                            endforeach; 

                            ?>


                            <tr>
                                <td colspan="3">Jumlah</td>
                                <td><?= $sum_qty_awal; ?></td>
                                <td></td>
                                <td><?= $sum_harga_awal; ?></td>

                               <?php 
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
                                ?>

                                <?php foreach($res_result as $row_res) { ?>
                                    <td><?= $row_res['qty_in']; ?></td>
                                    <td></td>
                                    <td><?= $row_res['total_in']; ?></td>

                                    <td><?= $row_res['qty_out']; ?></td>
                                    <td></td>
                                    <td><?= $row_res['total_out']; ?></td>

                                    <td><?= $row_res['qty_res']; ?></td>
                                    <td></td>
                                    <td><?= $row_res['tot_res']; ?></td>
                                <?php } ?>  

                                <td><?= $sum_qty_awal; ?></td>

                                <td><?= $sum_stok_in; ?></td>
                                <td><?= $sum_stok_out; ?></td>
                                <?php $sum_akhir = $sum_qty_awal+$sum_stok_in-$sum_stok_out; ?>
                                <td><?= $sum_akhir; ?></td>

                                <td></td>
                                <td><?= $sum_tot; ?></td>

                            </tr>


                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    Data Kosong
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
