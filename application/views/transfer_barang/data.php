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
                    Data Transfer
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('transferbarang/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Transfer
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table w-100 dt-responsive nowrap table-hover" id="dataTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Transfer</th>
                    <th>Tanggal</th>
                    <!-- <th>Jumlah</th> -->
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($transfer_barang) :
                    $no = 1;
                    foreach ($transfer_barang as $s) :
                        $popup_detail = 'data-toggle="modal" data-target="#modal_show_bj" data-id="' . $s['id_transfer'] . '" class="data-transfer" data-backdrop="static" data-keyboard="false" style="cursor:pointer;"';
                ?>
                        <tr>
                            <td <?= $popup_detail ?>><?= $no++; ?></td>
                            <td <?= $popup_detail ?>><?= $s['kode_transfer']; ?></td>
                            <td <?= $popup_detail ?>><?= $s['tgl_transfer']; ?></td>
                            <!-- <td><?= $s['jumlah']; ?></td> -->
                            <td <?= $popup_detail ?>><?= $s['asal']; ?></td>
                            <td <?= $popup_detail ?>><?= $s['tujuan']; ?></td>
                            <th>
                                <a href="<?= base_url('transferbarang/edit/') . $s['id_transfer'] ?>" class="btn btn-circle btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('transferbarang/delete/') . $s['id_transfer'] ?>" class="btn btn-circle btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </th>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">
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