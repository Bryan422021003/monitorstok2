<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Stock Op Name
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('stockopname') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('', [], ['id_stock_op_name' => $id_stock_op_name, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab ">
                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="id_stock_op_name">ID Transaksi</label>
                            <div class="col-md-5">
                                <input value="<?= $id_stock_op_name; ?>" type="text" readonly="readonly" class="form-control">
                                <?= form_error('id_stock_op_name', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-2 text-md-right" for="tanggal_keluar">Tanggal Keluar</label>
                            <div class="col-md-5">
                                <input value="<?= set_value('tanggal_keluar', date('Y-m-d')); ?>" name="tanggal_keluar" id="tanggal_keluar" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                                <?= form_error('tanggal_keluar', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="supplier_id">Gudang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="cabang_id" id="cabang_id" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : 
                                            if($cabang_id == $s['id_cabang']) {
                                            ?>
                                            <option <?= set_select('cabang_id', $s['id_cabang']) ?> <?= ($cabang_id == $s['id_cabang']) ? 'selected' : ''; ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php 
                                            }
                                        endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>" target="__BLANK"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('cabang_id', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <table class="table w-100 dt-responsive nowrap  " id="">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Saat Ini</th>
                                    <th>Stock Op Name</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-center"><button type="button" onclick="tambah_barang('tambah')" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row form-group">
                        <div class="col offset-md-12 text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                    <?= form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>