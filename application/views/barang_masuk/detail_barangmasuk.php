<div class="modal-detail">
    <div class="card shadow-sm p-3">
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col">
                    <table class="table w-100 dt-responsive nowrap">
                        <tbody>
                            <tr>
                                <td>
                                    Id Barang Masuk
                                </td>
                                <td>
                                    <?= $brg['id_barang_masuk'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tanggal
                                </td>
                                <td>
                                    <?= $brg['tanggal_masuk'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nama Supplier
                                </td>
                                <td>
                                    <?= $brg['nama_supplier'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Nama Cabang
                                </td>
                                <td>
                                    <?= $brg['nama_cabang'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Admin
                                </td>
                                <td>
                                    <?= $brg['nama'] ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Total Harga</th>


                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_pembelian = 0;
                    if ($d_brg) :
                        foreach ($d_brg as $b) :
                    ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $b['barang_id']; ?></td>
                                <td><?= $b['nama_barang']; ?></td>
                                <td><?= $b['jumlah'] . ' ' . $b['nama_satuan']; ?></td>
                                <td><?= $b['harga_beli']; ?></td>
                                <td><?= $b['jumlah']*$b['harga_beli']; ?></td>
                                


                            </tr>
                        <?php 
                                $total_pembelian += $b['jumlah']*$b['harga_beli'];
                            endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                Data Kosong
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right">Total Pembelian</td>
                        <td><?= $total_pembelian; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a href="<?= base_url('barangmasuk/cetak_bmasuk/'.$brg['id_barang_masuk'])?>" type="button" target="_blank" class="btn btn-primary">Cetak</a>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>