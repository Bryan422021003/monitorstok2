<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Transfer Barang
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
                <?= form_open('', [], ['id_transfer' => $transfer['id_transfer'], 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row form-group mt-3">
                            <label class="col-md-2 text-md-right" for="kode_transfer">Kode Transfer</label>
                            <div class="col-md-5">
                                <input value="<?= $transfer['kode_transfer']; ?>" name="kode_transfer" type="text" readonly="readonly" class="form-control">
                                <?= form_error('kode_transfer', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-2 text-md-right" for="tgl_transfer">Tanggal Transfer</label>
                            <div class="col-md-5">
                                <input value="<?= set_value('tgl_transfer', $transfer['tgl_transfer']); ?>" name="tgl_transfer" id="tgl_transfer" type="text" class="form-control date" placeholder="Tanggal Transfer...">
                                <?= form_error('tgl_transfer', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group mt-3">
                            <label class="col-md-2 text-md-right" for="keterangan">Keterangan</label>
                            <div class="col-md-5">
                                <textarea value="" name="keterangan" type="text" class="form-control"><?= set_value('keterangan', $transfer['keterangan']) ?></textarea>
                                <?= form_error('keterangan', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>


                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="supplier_id">Asal Cabang</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select name="asal_cabang" id="asal_cabang" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : ?>

                                            <option <?= $transfer['asal_cabang'] == $s['id_cabang'] ? 'selected' : ''; ?> <?= set_select('asal_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('asal_cabang', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            <label class="col-md-2 text-md-right" for="supplier_id">Tujuan Cabang</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <select name="tujuan_cabang" id="tujuan_cabang" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : ?>
                                            <option <?= $transfer['tujuan_cabang'] == $s['id_cabang'] ? 'selected' : ''; ?> <?= set_select('tujuan_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('tujuan_cabang', '<small class="text-danger">', '</small>'); ?>
                            </div>
                         
                        </div>
                    
                        <table class="table w-100 dt-responsive nowrap" id="">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Transfer</th>
                                    <th width='10%'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">
                                <?php
                                $i = 1;
                                foreach ($detail_transfer as $row) :
                                ?>
                                    <tr id="data<?= $i ?>">
                                        <td>
                                            <select name='id_barang[]' id='id_barang<?= $i ?>' class='form-control selectpicker' data-live-search='true' required>
                                                <option value='' selected>-- Pilih Nama Barang --</option>
                                                <?php foreach ($barang as $s) : ?>
                                                    <option <?= $row['id_barang'] == $s['id_barang'] ? 'selected' : ''; ?> <?= set_select('id_barang', $s['id_barang']) ?> value="<?= $s['id_barang'] ?>"><?= $s['nama_barang'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='jumlah[]' required class='form-control jumlah<?= $i ?> jumlah' value='<?= set_value('jumlah[]', $row['jumlah_detail']); ?>' required>
                                        </td>
                                        <td><button type="button" onclick="hapus_barang(<?= $i ?>)" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button></td>
                                        <td></td>
                                    </tr>
                                <?php $i++;
                                endforeach; ?>
                                <input type="hidden" id="jumlah_edit" value="<?= $i?>">
                            </tbody>
                            <tr>
                                <td colspan="5" class="text-center"><button type="button" onclick="tambah_barang()" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang</button></td>
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