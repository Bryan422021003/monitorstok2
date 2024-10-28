<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Barang Masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['id_barang_masuk' => $id_barang_masuk, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                        <!-- <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Detail Barang</a> -->

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="id_barang_masuk">ID Transaksi</label>
                            <div class="col-md-5">
                                <input value="<?= $id_barang_masuk; ?>" type="text" readonly="readonly" class="form-control">
                                <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-2 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                            <div class="col-md-5">
                                <input value="<?= set_value('tanggal_masuk', date('Y-m-d')); ?>" name="tanggal_masuk" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                                <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-2 text-md-right" for="supplier_id">Supplier</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="supplier_id" id="supplier_id" class="custom-select">
                                        <option value="" selected disabled>Pilih Supplier</option>
                                        <?php foreach ($supplier as $s) : ?>
                                            <option <?= set_select('supplier_id', $s['id_supplier']) ?> value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>" target="__BLANK"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>

                        <div class="row form-group mt-4">
                            <label class="col-lg-2 text-md-right" for="supplier_id">Gudang</label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <select name="cabang_id" id="cabang_id" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : 
                                            if($cabang_id == $s['id_cabang']) {
                                            ?>
                                            <option <?= ($cabang_id == $s['id_cabang']) ? 'selected' : ''; ?> <?= set_select('cabang_id', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php }
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
                                    <th>Sisa Stok</th>
                                    <th>Jumlah Masuk</th>
                                    <th>Total</th>
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                    <th colspan="5" class="text-center"><button type="button" onclick="tambah_barang('tambah-pembelian')" class="btn btn-success"><i class="fas fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right">Jumlah</td>
                                    <td><input type="text" readonly class="form-control" name="total_pembelian" id="total_pembelian" value="0"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="row form-group">
                        <div class="col offset-md-5">
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