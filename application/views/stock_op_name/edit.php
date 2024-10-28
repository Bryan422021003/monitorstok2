<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Stock Op Name
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
                <?= form_open('', [], ['id_stock_op_name' => $brg['id_stock_op_name'], 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab ">
                        <div class="row form-group mt-4">
                            <label class="col-md-2 text-md-right" for="id_barang_keluar">ID Transaksi</label>
                            <div class="col-md-5">
                                <input value="<?= $brg['id_stock_op_name']; ?>" type="text" readonly="readonly" class="form-control">
                                <?= form_error('id_barang_keluar', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-2 text-md-right" for="tanggal_keluar">Tanggal Keluar</label>
                            <div class="col-md-5">
                                <input value="<?= set_value('tanggal_keluar', $brg['tanggal_stock_op_name']); ?>" name="tanggal_keluar" id="tanggal_keluar" type="text" class="form-control date" placeholder="Tanggal Masuk...">
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
                                                if($brg['cabang_id'] == $s['id_cabang']) {
                                            ?>
                                            <option <?= ($brg['id_cabang'] == $s['id_cabang']) ? 'selected' : ''; ?> <?= set_select('cabang_id', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
                                        <?php 
                                                }
                                            endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('cabang/add'); ?>"  target="__BLANK"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('cabang_id', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <table class="table w-100 dt-responsive nowrap  " id="">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Stok</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">
                                <?php
                                $i = 1;
                                foreach ($d_brg as $row) {

                                ?>
                                    <tr id="data<?= $i ?>">
                                        <td>
                                            <select name='id_barang[]' id='id_barang<?= $i ?>' class='form-control selectpicker' data-live-search='true' required onchange='total_masukkeluar(<?= $i ?>)'>
                                                <option value='' selected>-- Pilih Nama Barang --</option>
                                                <?php foreach ($barang as $s) : ?>
                                                    <option <?= $row['id_sa'] == $s['id_sa'] ? 'selected' : ''; ?> <?= set_select('id_barang', $s['id_sa']) ?> value="<?= $s['id_sa'] ?>"><?= $s['nama_barang'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_asal[]' readonly class='form-control stok<?= $i ?> jumlah' value='<?= $row['stok_real']  ?>' required onblur='total_barang(<?= $i ?>)'>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type='text' name='jumlah[]' required class='form-control jumlah<?= $i ?> jumlah' value='<?= set_value('jumlah[]', $row['jumlah']); ?>' required onblur='total_masukkeluar(<?= $i ?>,1,"tambah")'>
                                                <div class="input-group-append">
                                                    <span class="input-group-text satuan<?= $i ?>">Satuan</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_tujuan[]' readonly class='form-control total_stok<?= $i ?> jumlah' value='<?= $row['stok_real'] + $row['jumlah'] ?>' required onblur='total_barang(<?= $i ?>)'>
                                        </td>
                                        <td><button type="button" onclick="hapus_barang(<?= $i ?>)" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button></td>'
                                        <td></td>
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
                                <input type="hidden" id="jumlah_edit" value="<?= $i - 1 ?>">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-center"><button type="button" onclick="tambah_barang('tambah')" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button></th>
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