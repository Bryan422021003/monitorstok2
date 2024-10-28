<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Saldo Awal Barang
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['user_id' => $this->session->userdata('login_session')['user'], 'cabang_id' => $dt_cabang['id_cabang']]); ?>


                <div class="row form-group mt-4">
                    <label class="col-lg-2 text-md-left" for="tgl">Tanggal</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="date" class="form-control" id="tgl" name="tgl" value="<?= $now; ?>">
                        </div>
                        <?= form_error('tgl', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group mt-4">
                    <label class="col-lg-2 text-md-left" for="barang_id">Barang</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select">
                                <option value="" selected disabled>Pilih Barang</option>
                                <?php foreach ($barang as $row_b) : ?>
                                    <option <?= set_select('barang_id', $row_b['id_barang']) ?> value="<?= $row_b['id_barang'] ?>" <?= $row_b['stts1'] ?>><?= $row_b['nama_barang'] ?> <?= $row_b['stts2'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group mt-4">
                    <label class="col-lg-2 text-md-left" for="stok_awal">Stok Awal</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="number" class="form-control" id="stok_awal" name="stok_awal" placeholder="Jumlah Stok">
                        </div>
                        <?= form_error('stok_awal', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group mt-4">
                    <label class="col-lg-2 text-md-left" for="hpp">HPP</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="number" class="form-control" id="hpp" name="hpp" value="" step="any" placeholder="HPP Barang">
                        </div>
                        <?= form_error('hpp', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col offset-md-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>

        </div>
    </div>
</div>