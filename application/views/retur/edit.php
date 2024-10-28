<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Retur Barang
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('retur') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['retur' => $retur['id_retur']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Detail Barang</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row form-group mt-3">
                            <label class="col-md-4 text-md-right" for="id_retur">Id Retur</label>
                            <div class="col-md-4">
                                <input value="<?= $retur['id_retur']; ?>" name="id_retur" type="text" readonly="readonly" class="form-control">
                                <?= form_error('id_retur', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="tgl_retur">Tanggal Retur</label>
                            <div class="col-md-4">
                                <input value="<?= set_value('tanggal', $retur['tanggal']); ?>" name="tanggal" id="tanggal_retur" type="text" class="form-control date" placeholder="Tanggal retur...">
                                <?= form_error('tanggal', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group mt-3">
                            <label class="col-md-4 text-md-right" for="keterangan">Keterangan</label>
                            <div class="col-md-4">
                                <textarea value="" name="keterangan" type="text" class="form-control"><?= set_value('keterangan', $retur['keterangan']) ?></textarea>
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

                                            <option <?= $retur['asal_cabang'] == $s['id_cabang'] ? 'selected' : ''; ?> <?= set_select('asal_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
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
                                    <select name="tujuan_cabang" id="tujuan_cabang" class="custom-select">
                                        <option value="" selected readonly>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : ?>
                                            <option <?= $retur['tujuan_cabang'] == $s['id_cabang'] ? 'selected' : ''; ?> <?= set_select('tujuan_cabang', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
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
                                    <th>Stok</th>
                                    <th>Jumlah retur</th>
                                    <th>Total</th>
                                    <th width='10%'>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">
                                <?php
                                $i = 1;
                                foreach ($detail_retur as $row) :

                                    // $ci = get_instance();
                                    // $ci->load->model('Admin_model', 'admin');
                                    // $data_stok = $ci->admin->cekStokretur($row['id_barang'], $row['asal_cabang'], $row['tujuan_cabang']);

                                ?>
                                    <tr id="data<?= $i ?>">
                                        <td>
                                            <select name='id_barang[]' id='id_barang<?= $i ?>' class='form-control selectpicker' data-live-search='true' required onchange='total_barang(<?= $i ?>)'>
                                                <option value='' selected>-- Pilih Nama Barang --</option>
                                                <?php foreach ($barang as $s) : ?>
                                                    <option <?= $row['id_barang'] == $s['id_barang'] ? 'selected' : ''; ?> <?= set_select('id_barang', $s['id_barang']) ?> value="<?= $s['id_barang'] ?>"><?= $s['nama_barang'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_asal[]' readonly class='form-control stok<?= $i ?> jumlah' value='<?= $row['stok_real'] + $row['jumlah'] ?>' required onblur='total_barang(<?= $i ?>)'>
                                        </td>
                                        <td>
                                            <input type='text' name='jumlah[]' required class='form-control jumlah<?= $i ?> jumlah' value='<?= set_value('jumlah[]', $row['jumlah']); ?>' required onblur='total_barang(<?= $i ?>,1)'>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_tujuan[]' readonly class='form-control total_stok<?= $i ?> jumlah' value='<?= $row['stok_real'] ?>' required onblur='total_barang(<?= $i ?>)'>
                                        </td>
                                        <input type="hidden" id="jumlah_transedit<?= $i ?>" value="<?= set_value('jumlah_transedit[]', $row['jumlah'] + $row['stok_real']); ?>">
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