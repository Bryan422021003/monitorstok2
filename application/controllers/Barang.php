<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('Admin_model', 'admin');
        $this->load->model('Barang_model', 'barang');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Barang";
        $data['barang'] = $this->admin->getBarang();

        $this->template->load('templates/dashboard', 'barang/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');
        $this->form_validation->set_rules('batas', 'Batas', 'required');
        $this->form_validation->set_rules('jenis_id', 'Jenis Barang', 'required');
        $this->form_validation->set_rules('satuan_id', 'Satuan Barang', 'required');
    }

    private function _validasi_saldo()
    {
        $this->form_validation->set_rules('cabang_id', 'Gudang', 'required|trim');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Barang";
            $data['jenis'] = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');
          
            // Mengenerate ID Barang
            $kode_terakhir = $this->admin->getMax('barang', 'id_barang');
            $kode_tambah = substr($kode_terakhir, -6, 6);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 6, '0', STR_PAD_LEFT);
            $data['id_barang'] = 'B' . $number;

            $this->template->load('templates/dashboard', 'barang/add', $data);
        } else {
            // $input = $this->input->post(null, true);
            $insert = $this->barang->insertDataBarang();

            if ($insert) {
                set_pesan('data berhasil disimpan');
                redirect('barang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('barang/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Barang";
            $data['jenis'] = $this->admin->get('jenis');
            $data['satuan'] = $this->admin->get('satuan');
            $data['barang'] = $this->admin->get('barang', ['id_barang' => $id]);
            $this->template->load('templates/dashboard', 'barang/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('barang', 'id_barang', $id, $input);

            if ($update) {
                set_pesan('data berhasil disimpan');
                redirect('barang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('barang/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        // cek ada di tabel
        $id = encode_php_tags($getId);
        $barang_masuk =$this->admin->get('detail_barang_masuk', ['id_barang' => $id]);
        $transfer_barang =$this->admin->get('detail_transfer_barang', ['id_barang' => $id]);
        $retur_barang =$this->admin->get('detail_retur', ['id_barang' => $id]);

        if(is_null($barang_masuk) && is_null($transfer_barang) && is_null($retur_barang)) {
            if ($this->admin->delete('barang', 'id_barang', $id)) {
                set_pesan('data berhasil dihapus.');
            } else {
                set_pesan('data gagal dihapus.', false);
            }
        } else {
            set_pesan('Barang ini sudah digunakan transaksi!', false);
        }

        redirect('barang');
    }











    // saldo awal
    public function barang_by_cabang()
    {
        // mencari cabang by id user 
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
        $dt_cabang = $this->admin->getDataCabangByUser($dt_user['cabang_id']);

        $data['title'] = "Barang ".$dt_cabang['nama_cabang']; 
        $dt_barang = $this->admin->getBarangByCabang($dt_cabang['id_cabang']); 

        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-d');

        $data['barang'] = array(); 
        foreach($dt_barang as $row){
            $sa = $this->admin->get('sa_barang', ['id_sa' => $row['id_sa']]);
            $mutasi_barang = $this->barang->getMutasiBarang($row['id_barang'], $row['cabang_id']);

            $sisa_stok = $sa['saldo'];
            foreach ($mutasi_barang as $b) :

                switch ($b['jenis']) {
                    case "in_tr":
                        $jenis = 'Mutasi Barang Masuk ke ' . $b['trs_cbg'];
                        break;
                    case "out_tr":
                        $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                        break;
                    case "in":
                        $jenis = 'Barang Masuk';
                        break;
                    case "in_retur":
                        $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                        break;
                    case "stkpn":
                        $jenis = 'Stok Op Name dari ' . $b['nama_cabang'];
                        break;
                    default:
                        $jenis = 'Barang Keluar';
                }

                if($b['jenis'] == 'in' || $b['jenis'] == 'in_retur') {
                     $sisa_stok += $b['jml']; 
                } else if($b['jenis'] == 'in_tr' ) { 
                     $sisa_stok += $b['jml']; 
                } else if($b['jenis'] == 'stkpn' ) { 
                    $sisa_stok += $b['jml']; 
                } else { 
                    $sisa_stok -= $b['jml']; 
                } 
            endforeach;

            $data['barang'][] = array(
                'id_sa' => $row['id_sa'],
                'tgl' => $row['tgl'],
                'id_barang' => $row['id_barang'],
                'nama_barang' => $row['nama_barang'],
                'nama_jenis' => $row['nama_jenis'],
                'saldo' => $row['saldo'],
                'hpp' => $row['hpp'],
                'saldo_akhir' => $sisa_stok,
                'nama_satuan' => $row['nama_satuan'],
            );
        }

        $this->template->load('templates/dashboard', 'barang/saldoawal', $data);
    }

    public function mutasi_barang_cabang($id)
    {
        // id merupakan id_sa
        $data['title'] = "Mutasi Barang";
        $data['sa_brg'] = $this->admin->get('sa_barang', ['id_sa' => $id]);
        $data['barang'] = $this->admin->get('barang', ['id_barang' => $data['sa_brg']['barang_id']]);
        $data['satuan'] = $this->admin->get('satuan', ['id_satuan' => $data['barang']['satuan_id']]);

        // saldo akhir 30 hari terakhir

        $tgl_satu = date('Y-m-01', strtotime(date("Y-m-d")));
        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tgl_satu)));

        $data['stok_awal'] = $this->_saldo_awal_barang($id, $tgl_awal, $tgl_akhir);
        $data['tglAwal'] = $tgl_satu;
        $data['tglAkhir'] = date("Y-m-d");
        $data['mutasi_barang'] = $this->barang->getMutasiBarangByDate($data['sa_brg']['barang_id'], $data['sa_brg']['cabang_id'], $data['tglAwal'], $data['tglAkhir']);
        
        $this->form_validation->set_rules('tgl_awal', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tgl_akhir', 'Tanggal Akhir', 'required');
        if($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'barang/mutasi_barang_by_id', $data);
        } else {
            $id_sa = $id;
            $tglAwal = $this->input->post('tgl_awal', true);
            $tglAkhir = $this->input->post('tgl_akhir', true);

            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tglAwal)));
            $data['sa_brg'] = $this->admin->get('sa_barang', ['id_sa' => $id]);
            $data['barang'] = $this->admin->get('barang', ['id_barang' => $data['sa_brg']['barang_id']]);
            $data['satuan'] = $this->admin->get('satuan', ['id_satuan' => $data['barang']['satuan_id']]);

            $data['stok_awal'] = $this->_saldo_awal_barang($id, $tgl_awal, $tgl_akhir);
            $data['tglAwal'] = $tglAwal;
            $data['tglAkhir'] = $tglAkhir;
            $data['mutasi_barang'] = $this->barang->getMutasiBarangByDate($data['sa_brg']['barang_id'], $data['sa_brg']['cabang_id'], $data['tglAwal'], $data['tglAkhir']);

            $this->template->load('templates/dashboard', 'barang/mutasi_barang_by_id', $data);
        }
    }

    public function _saldo_awal_barang($id_sa_barang, $tgl_awal, $tgl_akhir)
    {
        $sa = $this->admin->get('sa_barang', ['id_sa' => $id_sa_barang]);
        $mutasi_barang = $this->barang->getMutasiBarangByDate($sa['barang_id'], $sa['cabang_id'], $tgl_awal, $tgl_akhir);

        $sisa_stok = $sa['saldo'];
        foreach ($mutasi_barang as $b) :

            switch ($b['jenis']) {
                case "in_tr":
                    $jenis = 'Mutasi Barang Masuk ke ' . $b['trs_cbg'];
                    break;
                case "out_tr":
                    $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                    break;
                case "in":
                    $jenis = 'Barang Masuk';
                    break;
                case "in_retur":
                    $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                    break;
                case "stkpn":
                    $jenis = 'Stok Op Name dari ' . $b['nama_cabang'];
                    break;
                default:
                    $jenis = 'Barang Keluar';
            }

            if($b['jenis'] == 'in' || $b['jenis'] == 'in_retur') {
                 $sisa_stok += $b['jml']; 
            } else if($b['jenis'] == 'in_tr' ) { 
                 $sisa_stok += $b['jml']; 
            } else if($b['jenis'] == 'stkpn' ) { 
                $sisa_stok += $b['jml']; 
            } else { 
                $sisa_stok -= $b['jml']; 
            } 
        endforeach;

        return $sisa_stok;
    }

    private function _validasi_saldo_awal()
    {
        $this->form_validation->set_rules('tgl', 'Tanggal', 'required');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');
        $this->form_validation->set_rules('stok_awal', 'Stok Awal', 'required');
    }

    public function add_saldoawal()
    {
        $this->_validasi_saldo_awal();

        if ($this->form_validation->run() == false) {
            $data['now'] = date("Y-m-d");

            $data['title'] = "Tambah Saldo Barang Barang";
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['dt_cabang'] = $this->admin->getDataCabangByUser($dt_user['cabang_id']);

            $data['cabang'] = $this->admin->get('cabang');
            $dt_barang_all = $this->admin->get('barang');

            $dt_barang = $this->admin->getBarangByCabang($dt_user['cabang_id']);
            $usedValues = array();
            foreach($dt_barang as $row_) {
                $usedValues[] = $row_['id_barang'];
            }

            $data['barang'] = array();
            foreach($dt_barang_all as $row) {
                if(in_array($row['id_barang'], $usedValues)) {
                    $data['barang'][] = array(
                        'id_barang' => $row['id_barang'],
                        'nama_barang' => $row['nama_barang'],
                        'stts1' => 'disabled',
                        'stts2' => '(Sudah diset)',
                    );
                } else {
                    $data['barang'][] = array(
                        'id_barang' => $row['id_barang'],
                        'nama_barang' => $row['nama_barang'],
                        'stts1' => '',
                        'stts2' => '',
                    );
                }
            }
            
            // Mengenerate ID Barang
            $this->template->load('templates/dashboard', 'barang/add_saldoawal', $data);
        } else {
            $insert = $this->barang->insertSaldoAwal();

            if ($insert) {
                set_pesan('data berhasil disimpan');
                redirect('barang/barang_by_cabang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('barang/add_saldoawal');
            }
        }
    }

    public function edit_saldoawal($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi_saldo_awal();

        if ($this->form_validation->run() == false) {
            // memperoleh data by id saldo awal
            $data['sa_brg'] = $this->admin->get('sa_barang', ['id_sa' => $id]);

            $data['title'] = "Update Saldo Awal Barang";
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang'] = $this->admin->get('barang');

            $this->template->load('templates/dashboard', 'barang/edit_saldoawal', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->barang->updateSaldoAwal();
            
            if ($update) {
                set_pesan('data berhasil disimpan');
                redirect('barang/barang_by_cabang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('barang/edit_saldoawal/' . $id);
            }
        }
    }

    public function delete_saldoawal($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('sa_barang', 'id_sa', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }

        redirect('barang/barang_by_cabang');
    }

    // tutup saldo awal


















    public function getstok($getId, $id_cabang)
    {
        $id = encode_php_tags($getId);
        $query = $this->admin->cekStok($id, $id_cabang);
        output_json($query);
    }

    public function getStokDataBarang($getId)
    {
        $id = encode_php_tags($getId);
        $sa_barang = $this->admin->get('sa_barang', ['id_sa' => $id]);
        $barang = $this->admin->get('barang', ['id_barang' => $sa_barang['barang_id']]);
        $satuan = $this->admin->get('satuan', ['id_satuan' => $barang['satuan_id']]);

        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-d');
        $stok_terakhir = $this->_saldo_awal_barang($id, $tgl_awal, $tgl_akhir);

        $result = array(
            'id_sa' => $id,
            'id_barang' => $sa_barang['barang_id'],
            'stok' => $stok_terakhir,
            'nama_satuan' => $satuan['nama_satuan'],
        );

        output_json($result);
    }

    public function getstoktransfer($getId, $awal_cabang, $akhir_cabang)
    {
        $id = encode_php_tags($getId);
        $query = $this->admin->cekStokTransfer($id, $awal_cabang, $akhir_cabang);
        output_json($query);
    }

    public function mutasi_barang($id)
    {
        $data['title'] = "Mutasi Barang";
        $data['barang'] = $this->admin->get('barang', ['id_barang' => $id]);

        $data['satuan'] = $this->admin->get('satuan', ['id_satuan' => $data['barang']['satuan_id']]);
        $data['mutasi_barang'] = $this->barang->getMutasiBarang($id);

        $this->template->load('templates/dashboard', 'barang/mutasi_barang', $data);
    }



   
}
