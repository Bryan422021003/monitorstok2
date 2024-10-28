<div class="modal-detail">
    <div class="card shadow-sm p-3">
        <div class="card-header bg-white py-3">
            <div class="row">
                <div class="col">
                    <table class="table w-100 dt-responsive nowrap">
                        <tbody>
                            <tr>
                                <td>
                                    Kode Transfer
                                </td>
                                <td>
                                    <?= $transfer['kode_transfer'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tanggal
                                </td>
                                <td>
                                    <?= $transfer['tgl_transfer'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Asal Cabang
                                </td>
                                <td>
                                    <?= $transfer['nama_asal'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tujuan Cabang
                                </td>
                                <td>
                                    <?= $transfer['nama_tujuan'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Admin
                                </td>
                                <td>
                                    <?= $transfer['nama'] ?>
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


                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($detail_transfer) :
                        foreach ($detail_transfer as $b) :
                    ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $b['id_barang']; ?></td>
                                <td><?= $b['nama_barang']; ?></td>
                                <td><?= $b['jumlah_detail'] . ' ' . $b['nama_satuan']; ?></td>



                            </tr>
                        <?php endforeach; ?>
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
<div class="modal-footer">
    <a href="<?= base_url('transferbarang/cetak_transfer/' . $transfer['id_transfer']) ?>" type="button" target="_blank" class="btn btn-primary">Cetak</a>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>