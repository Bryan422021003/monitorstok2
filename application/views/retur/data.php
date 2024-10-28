<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Retur
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('retur/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Retur
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
                    <th>Kode Retur</th>
                    <th>Tanggal</th>
                    <!-- <th>Jumlah</th> -->
                    <th>Asal</th>
                    <th>Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($retur_barang) :
                    $no = 1;
                    foreach ($retur_barang as $s) :
                        $popup_detail = 'data-toggle="modal" data-target="#modal_show_bj" data-id="' . $s['id_retur'] . '" class="data-rt" data-backdrop="static" data-keyboard="false" style="cursor:pointer;"';
                ?>
                        <tr>
                            <td <?= $popup_detail ?>><?= $no++; ?></td>
                            <td <?= $popup_detail ?>><?= $s['id_retur']; ?></td>
                            <td <?= $popup_detail ?>><?= $s['tanggal']; ?></td>
                            <!-- <td><?= $s['jumlah']; ?></td> -->
                            <td <?= $popup_detail ?>><?= $s['asal']; ?></td>
                            <td <?= $popup_detail ?>><?= $s['tujuan']; ?></td>
                            <th>
                                <a href="<?= base_url('retur/edit/') . $s['id_retur'] ?>" class="btn btn-circle btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('retur/delete/') . $s['id_retur'] ?>" class="btn btn-circle btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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