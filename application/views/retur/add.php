

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Input Retur Barang
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('transferbarang') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['kode_transfer' => $kode_transfer, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Detail Barang</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row form-group mt-3">
                            <label class="col-md-4 text-md-right" for="kode_transfer">Kode Transfer</label>
                            <div class="col-md-4">
                                <input value="<?= $kode_transfer; ?>" type="text" readonly="readonly" class="form-control">
                                <?= form_error('kode_transfer', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="tanggal">Tanggal Retur</label>
                            <div class="col-md-4">
                                <input value="<?= set_value('tanggal', date('Y-m-d')); ?>" name="tanggal" id="tanggal_retur" type="text" class="form-control date" placeholder="Tanggal Transfer...">
                                <?= form_error('tanggal', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group mt-3">
                            <label class="col-md-4 text-md-right" for="keterangan">Keterangan</label>
                            <div class="col-md-4">
                                <textarea value="" name="keterangan" type="text" class="form-control"></textarea>
                                <?= form_error('keterangan', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>


                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="supplier_id">Asal Cabang</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select name="asal_cabang" id="asal_cabang" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : ?>
                                            <option <?= set_select('asal_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('asal_cabang', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            <label class="col-md-2 text-md-right" for="supplier_id">Tujuan Cabang</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <select readonly name="tujuan_cabang" id="tujuan_cabang" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : ?>
                                            <option <?= ($s['flag_pusat'] == '1') ? 'selected' : ''; ?> <?= set_select('tujuan_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('tujuan_cabang', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">

                        </div>
                        <table class="table w-100 dt-responsive nowrap" id="">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Jumlah Transfer</th>
                                    <th>Sisa Stok</th>
                                    <th width='10%'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">

                            </tbody>
                            <tr>
                                <td colspan="5" class="text-center"><button type="button" onclick="tambah_barang('tambah')" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang</button></td>
                            </tr>
                        </table>
                    </div>
                    <hr>

                    <div class="row form-group">
                        <div class="col offset-md-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </div>


                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>