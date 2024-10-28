<?= $this->session->flashdata('pesan'); ?>

<div class="row mt-3 mb-4">
    <div class="col-sm">
        <div class="card border-bottom-primary shadow">
            <div class="col-sm">
                <div class="mt-2 mb-2">
                    <div class="card-body">
                        <form action="" method="post" class="form-inline">
                            <div class="form-group col-sm-4">
                                <label for="bln" class="col-sm-5 col-form-label">Tanggal Awal</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control" id="tgl_awal" name="tgl_awal" value="<?= $tglAwal; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="tahun" class="col-sm-5 col-form-label">Tanggal Akhir</label>
                                <div class="col-sm-7">
                                  <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= $tglAkhir; ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-4 justify-content-center">
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
                    Riwayat Stock Opname
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('stockopname/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Input Stock Opname
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>No Transaksi</th>
                    <th>Gudang</th>
                    <th>Tanggal Keluar</th>
                    <th>Nama Barang</th>

                    <th>User</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barangkeluar) :
                    foreach ($barangkeluar as $bk) :
                        $popup_detail = 'data-toggle="modal" data-target="#modal_show_bj" data-id="' . $bk['id_stock_op_name'] . '" class="data-stok-on" data-backdrop="static" data-keyboard="false" style="cursor:pointer;"';
                ?>
                        <tr>
                            <td <?= $popup_detail ?>><?= $no++; ?></td>
                            <td <?= $popup_detail ?>><?= $bk['id_stock_op_name']; ?></td>
                            <td <?= $popup_detail ?>><?= $bk['nama_cabang']; ?></td>
                            <td <?= $popup_detail ?>><?= $bk['tanggal_stock_op_name']; ?></td>
                            <td <?= $popup_detail ?>><?= $bk['nama_barang']; ?></td>

                            <td><?= $bk['nama']; ?></td>
                            <td>
                                <a  href="<?= base_url('stockopname/edit/') . $bk['id_stock_op_name'] ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-pen"></i></a>
                                <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('stockopname/delete/') . $bk['id_stock_op_name'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal_show_bj" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title text-white">Detail Data</h6>

                <button type="button" class="close close_modal">&times;</button>
            </div>
            <div class="body-detail">

            </div>

        </div>
    </div>
</div>