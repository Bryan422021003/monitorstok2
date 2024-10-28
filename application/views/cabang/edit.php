<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Cabang
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('cabang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ''); ?>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="nama_cabang">Nama Cabang</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('nama_cabang', $cabang['nama_cabang']); ?>" name="nama_cabang" id="nama_cabang" type="text" class="form-control" placeholder="Nama Cabang...">
                        <?= form_error('nama_cabang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="alamat_cabang">Alamat Cabang</label>
                    <div class="col-md-9">
                        <input value="<?= set_value('alamat_cabang', $cabang['alamat_cabang']); ?>" name="alamat_cabang" id="alamat_cabang" type="text" class="form-control" placeholder="Alamat Cabang...">
                        <?= form_error('alamat_cabang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 text-md-right" for="id_user">User Admin</label>
                    <div class="col-md-9">
                        <select type="text" class="form-control" id="id_user" name="id_user" title="Pilih Nama Barang" required>
                            <?php foreach ($user_admin as $ua) : ?>
                                <?php $selected = in_array($cabang['id_user'], $ua) ? " selected " : null;?>
                                <option value="<?= $ua['id_user']; ?>" <?=$selected ?>><?= $ua['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('alamat_cabang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

               
                <div class="row form-group">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</bu>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>