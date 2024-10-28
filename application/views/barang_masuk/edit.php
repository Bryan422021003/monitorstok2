<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Barang Masuk
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
                <?= form_open('', [], ['id_barang_masuk' => $brg['id_barang_masuk'], 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">General</a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab ">
                        <div class="row form-group mt-4">
                            <label class="col-md-4 text-md-right" for="id_barang_masuk">ID Transaksi</label>
                            <div class="col-md-4">
                                <input value="<?= $brg['id_barang_masuk']; ?>" type="text" readonly="readonly" class="form-control">
                                <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                            <div class="col-md-4">
                                <input value="<?= set_value('tanggal_masuk', $brg['tanggal_masuk']); ?>" name="tanggal_masuk" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                                <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="supplier_id" id="supplier_id" class="custom-select">
                                        <option value="" selected disabled>Pilih Supplier</option>
                                        <?php foreach ($supplier as $s) : ?>
                                            <option <?= $brg['supplier_id'] == $s['id_supplier'] ? 'selected' : ''; ?> <?= set_select('supplier_id', $s['id_supplier']) ?> value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>"  target="__BLANK"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                            </div>
                        </div>
             <!--        </div>



                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"> -->
                        <div class="row form-group mt-4">
                            <label class="col-md-4 text-md-right" for="supplier_id">Gudang</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select name="cabang_id" id="cabang_id" class="custom-select">
                                        <option value="" selected disabled>Pilih Cabang</option>
                                        <?php foreach ($cabang as $s) : 
                                            if($brg['cabang_id'] == $s['id_cabang']) {
                                            ?>
                                            <option <?= ($brg['cabang_id'] == $s['id_cabang']) ? 'selected' : ''; ?> <?= set_select('cabang_id', $s['id_cabang']) ?> value="<?= $s['id_cabang'] ?>"><?= $s['nama_cabang'] ?></option>
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
                                    <th>Harga</th>
                                    <th>Total Harga</th>
                                    <th colspan="5" class="text-center"><button type="button" onclick="tambah_barang('tambah-pembelian')" class="btn btn-success"><i class="fas fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody id="detail_transfer">
                                <?php
                                $i = 1;
                                foreach ($detail_brg as $row) {

                                ?>
                                    <tr id="data<?= $i ?>">
                                        <td>
                                            <select name='id_barang[]' id='id_barang<?= $i ?>' class='form-control selectpicker' data-live-search='true' required onchange='total_masukpembelian(<?= $i ?>)'>
                                                <option value='' selected>-- Pilih Nama Barang --</option>
                                                <?php foreach ($barang as $s) : ?>
                                                    <option <?= $row['id_sa'] == $s['id_sa'] ? 'selected' : ''; ?> <?= set_select('id_barang', $s['id_sa']) ?> value="<?= $s['id_sa'] ?>"><?= $s['nama_barang'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_asal[]' readonly class='form-control stok<?= $i ?>' value='<?= $row['stok_real']  ?>' required>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type='text' name='jumlah[]' required class='form-control qty<?= $i ?> jumlah' value='<?= set_value('jumlah[]', $row['jumlah']); ?>' required onblur='total_masukpembelian(<?= $i ?>,1,"tambah"), total_beli()'>
                                                <div class="input-group-append">
                                                    <span class="input-group-text satuan<?= $i ?>"><?= $row['nama_satuan']; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type='text' name='stok_tujuan[]' readonly class='form-control total_stok<?= $i ?>' value='<?= $row['stok_real'] + $row['jumlah'] ?>' required>
                                        </td>
                                        <td>
                                            <input type='text' name='harga[]' class='form-control harga<?= $i ?> harga' value='<?= set_value('harga[]', $row['harga']); ?>' required onblur='total_masukpembelian(<?= $i ?>,1,"tambah"), total_beli()'>
                                        </td>
                                        <td>
                                            <input type='text' name='sub_harga[]' readonly class='form-control subtotal<?= $i ?>' value='<?= $row['harga'] * $row['jumlah'] ?>' required>
                                        </td>
                                        <td><button type="button" onclick="hapus_barang(<?= $i ?>)" class="btn btn-sm btn-danger"><i class="fas fa-minus"></i></button></td>
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
                                    <td colspan="5" class="text-right">Total Pembelian</td>
                                    <td><input type="text" readonly class="form-control" name="total_pembelian" id="total_pembelian" value="<?= $total_beli; ?>"></td>
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