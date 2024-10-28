<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Retur_model', 'retur');
        $this->load->library('form_validation');
    }
    
    public function index()
    {
        // id merupakan id_sa
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
        $dt_cabang = $this->admin->getDataCabangByUser($dt_user['cabang_id']);

        $data['title'] = "Laporan Stok ".$dt_cabang['nama_cabang']; 
        $data['barang'] = $this->admin->getBarangByCabang($dt_cabang['id_cabang']); 

        // saldo akhir 30 hari terakhir
        $tgl_satu = date('Y-m-01', strtotime(date("Y-m-d")));
        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tgl_satu)));

        $data['tglAwal'] = $tgl_satu;
        $data['tglAkhir'] = date("Y-m-d");
        $data['status'] = 0;
        
        $this->form_validation->set_rules('id_barang', '', 'required');
        $this->form_validation->set_rules('tgl_awal', '', 'required');
        $this->form_validation->set_rules('tgl_akhir', '', 'required');
        if($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'laporan/index', $data);
        } else {
            $data['status'] = 1;

            $id_sa = $this->input->post('id_barang', true);
            $tglAwal = $this->input->post('tgl_awal', true);
            $tglAkhir = $this->input->post('tgl_akhir', true);

            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tglAwal)));
            $data['sa_brg'] = $this->admin->get('sa_barang', ['id_sa' => $id_sa]);
            $data['barang_id'] = $this->admin->get('barang', ['id_barang' => $data['sa_brg']['barang_id']]);
            $data['satuan'] = $this->admin->get('satuan', ['id_satuan' => $data['barang_id']['satuan_id']]);

            $data['barang'] = $this->admin->getBarangByCabang($dt_cabang['id_cabang']); 

            $data['stok_awal'] = $this->_saldo_awal_barang($id_sa, $tgl_awal, $tgl_akhir);
            $data['tglAwal'] = $tglAwal;
            $data['tglAkhir'] = $tglAkhir;
            $data['mutasi_barang'] = $this->barang->getMutasiBarangByDate($data['sa_brg']['barang_id'], $data['sa_brg']['cabang_id'], $data['tglAwal'], $data['tglAkhir']);
            
            
            
            $this->template->load('templates/dashboard', 'laporan/index', $data);
        }
    }

    function _saldo_awal_barang($id_sa_barang, $tgl_awal, $tgl_akhir)
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

        $saldo_awal = array(
            'qty' => $sisa_stok,
            'harga' => intval($sa['hpp']),
        );

        return $saldo_awal;
    }
    
    public function perhitungan_hpp()
    {
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
        $dt_cabang = $this->admin->getDataCabangByUser($dt_user['cabang_id']);

        $data['title'] = "Laporan Perhitungan HPP ".$dt_cabang['nama_cabang']; 

        // menampilkan semua barang
        $data['barang'] = $this->admin->getBarangByCabang($dt_cabang['id_cabang']); 

        date_default_timezone_set('Asia/Jakarta');
        
        $data['bulan_now'] = date("m");
        $data['tahun_now'] = date("Y");

        $data['tahun'] = array(2022,2023,2024,2025,2026);

        // memperoleh tgl pada bulan ini
        $tgl = date("d");
        $data['now'] = $data['tahun_now'].'-'.$data['bulan_now'].'-'.$tgl;
        $data['tglAkhir'] = date("Y-m-t", strtotime( $data['now'] ));
        $tgl_a = explode("-",$data['tglAkhir']);
        $array_tgl = array();   
        for($i=1;$i<=$tgl_a[2];$i++){
            $array_tgl[] = $tgl_a[0].'-'.sprintf("%02d", $tgl_a[1]).'-'.sprintf("%02d", $i);
        }

        $data['array_tgl'] = $array_tgl;
        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-01', strtotime($data['now']));

        // // perhitungan hpp
        $data['dt_transaksi'] = array();
        foreach($data['barang'] as $row){
            // mencari data transaksi terkait keluar masuk
            $saldo_awal = $this->_saldo_awal_barang($row['id_sa'], $tgl_awal, $tgl_akhir);

            $transaksi = array();
            foreach($array_tgl as $row_tgl) {
                // mutasi stok
                $tgl_set = date('Y-m-d', strtotime($row_tgl));
                $mutasi = $this->barang->getMutasiBarangByDate($row['id_barang'], $dt_user['cabang_id'], $tgl_set, $tgl_set); 

                $qty_in_baru = 0;
                $harga_in_baru = 0;

                $qty_out_baru = 0;
                foreach($mutasi as $row_mutasi) {
                    if($row_mutasi['jenis'] == 'in_tr' || $row_mutasi['jenis'] == 'in') {
                        $qty_in_baru = intval($row_mutasi['jml']);
                        $harga_in_baru = intval($row_mutasi['harga']);
                    } else {
                        $qty_out_baru += intval($row_mutasi['jml']);
                    }
                }
                // die;
                $stok_in = array(
                    'qty' => $qty_in_baru,
                    'harga' => $harga_in_baru,
                );

                $stok_out = array(
                    'qty' => $qty_out_baru,
                );
                $saldo_akhir = array();

                $transaksi[] = array(
                    'tgl' => $row_tgl,
                    'masuk' => $stok_in,
                    'keluar' => $stok_out,
                );
            }

            $data['dt_transaksi'][] = array(
                'id_barang' => $row['id_barang'],
                'nm_barang' => $row['nama_barang'],
                'saldo_awal' => $saldo_awal,
                'transaksi' => $transaksi,
            );
        }

        // hasil
        $this->form_validation->set_rules('bulan', '', 'required');
        $this->form_validation->set_rules('tahun', '', 'required');
        if($this->form_validation->run() == false) {
            $data['status'] = 0;
            $this->template->load('templates/dashboard', 'laporan/hpp', $data);
        } else {
            $data['status'] = 1;
            $data['bulan_now'] = $this->input->post('bulan', true);
            $data['tahun_now'] = $this->input->post('tahun', true);
    
            $data['tahun'] = array(2022,2023,2024,2025,2026);
    
            // memperoleh tgl pada bulan ini
            $tgl = date("d");
            $data['now'] = $data['tahun_now'].'-'.$data['bulan_now'].'-'.$tgl;
            $data['tglAkhir'] = date("Y-m-t", strtotime( $data['now'] ));
            $tgl_a = explode("-",$data['tglAkhir']);
            $array_tgl = array();   
            for($i=1;$i<=$tgl_a[2];$i++){
                $array_tgl[] = $tgl_a[0].'-'.sprintf("%02d", $tgl_a[1]).'-'.sprintf("%02d", $i);
            }
    
            $data['array_tgl'] = $array_tgl;
            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-01', strtotime($data['now']));
    
            // // perhitungan hpp
            $data['dt_transaksi'] = array();
            foreach($data['barang'] as $row){
                // mencari data transaksi terkait keluar masuk
                $saldo_awal = $this->_saldo_awal_barang($row['id_sa'], $tgl_awal, $tgl_akhir);
    
                $transaksi = array();
                foreach($array_tgl as $row_tgl) {
                    // mutasi stok
                    $tgl_set = date('Y-m-d', strtotime($row_tgl));
                    $mutasi = $this->barang->getMutasiBarangByDate($row['id_barang'], $dt_user['cabang_id'], $tgl_set, $tgl_set); 
    
                    $qty_in_baru = 0;
                    $harga_in_baru = 0;
    
                    $qty_out_baru = 0;
                    foreach($mutasi as $row_mutasi) {
                        if($row_mutasi['jenis'] == 'in_tr' || $row_mutasi['jenis'] == 'in') {
                            $qty_in_baru = intval($row_mutasi['jml']);
                            $harga_in_baru = intval($row_mutasi['harga']);
                        } else {
                            $qty_out_baru += intval($row_mutasi['jml']);
                        }
                    }
                    // die;
                    $stok_in = array(
                        'qty' => $qty_in_baru,
                        'harga' => $harga_in_baru,
                    );
    
                    $stok_out = array(
                        'qty' => $qty_out_baru,
                    );
                    $saldo_akhir = array();
    
                    $transaksi[] = array(
                        'tgl' => $row_tgl,
                        'masuk' => $stok_in,
                        'keluar' => $stok_out,
                    );
                }
    
                $data['dt_transaksi'][] = array(
                    'id_barang' => $row['id_barang'],
                    'nm_barang' => $row['nama_barang'],
                    'saldo_awal' => $saldo_awal,
                    'transaksi' => $transaksi,
                );
            }

            $this->template->load('templates/dashboard', 'laporan/hpp', $data);
        }
    }

    public function perhitungan_hpp_perhari()
    {
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
        $dt_cabang = $this->admin->getDataCabangByUser($dt_user['cabang_id']);

        $data['title'] = "Laporan Perhitungan HPP ".$dt_cabang['nama_cabang']; 

        // menampilkan semua barang
        $data['barang'] = $this->admin->getBarangByCabang($dt_cabang['id_cabang']); 

        date_default_timezone_set('Asia/Jakarta');
        
        $data['bulan_now'] = date("m");
        $data['tahun_now'] = date("Y");

        $data['tahun'] = array(2022,2023,2024,2025,2026);

        // memperoleh tgl pada bulan ini
        $tgl = date("d");
        $data['now'] = $data['tahun_now'].'-'.$data['bulan_now'].'-'.$tgl;
        $data['tglAkhir'] = date("Y-m-t", strtotime( $data['now'] ));
        $tgl_a = explode("-",$data['tglAkhir']);
        $array_tgl = array();   
        for($i=1;$i<=$tgl_a[2];$i++){
            $array_tgl[] = $tgl_a[0].'-'.sprintf("%02d", $tgl_a[1]).'-'.sprintf("%02d", $i);
        }

        $data['array_tgl'] = $array_tgl;
        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-01', strtotime($data['now']));

        // // perhitungan hpp
        $data['dt_transaksi'] = array();
        foreach($data['barang'] as $row){
            // mencari data transaksi terkait keluar masuk
            $saldo_awal = $this->_saldo_awal_barang($row['id_sa'], $tgl_awal, $tgl_akhir);

            $transaksi = array();
            foreach($array_tgl as $row_tgl) {
                // mutasi stok
                $tgl_set = date('Y-m-d', strtotime($row_tgl));
                $mutasi = $this->barang->getMutasiBarangByDate($row['id_barang'], $dt_user['cabang_id'], $tgl_set, $tgl_set); 

                $qty_in_baru = 0;
                $harga_in_baru = 0;

                $qty_out_baru = 0;
                foreach($mutasi as $row_mutasi) {
                    if($row_mutasi['jenis'] == 'in_tr' || $row_mutasi['jenis'] == 'in') {
                        $qty_in_baru = intval($row_mutasi['jml']);
                        $harga_in_baru = intval($row_mutasi['harga']);
                    } else {
                        $qty_out_baru += intval($row_mutasi['jml']);
                    }
                }
                // die;
                $stok_in = array(
                    'qty' => $qty_in_baru,
                    'harga' => $harga_in_baru,
                );

                $stok_out = array(
                    'qty' => $qty_out_baru,
                );
                $saldo_akhir = array();

                $transaksi[] = array(
                    'tgl' => $row_tgl,
                    'masuk' => $stok_in,
                    'keluar' => $stok_out,
                );
            }

            $data['dt_transaksi'][] = array(
                'id_barang' => $row['id_barang'],
                'nm_barang' => $row['nama_barang'],
                'saldo_awal' => $saldo_awal,
                'transaksi' => $transaksi,
            );
        }

        // hasil
        $this->form_validation->set_rules('bulan', '', 'required');
        $this->form_validation->set_rules('tahun', '', 'required');
        if($this->form_validation->run() == false) {
            $data['status'] = 0;
            $this->template->load('templates/dashboard', 'laporan/hppperhari', $data);
        } else {
            $data['status'] = 1;
            $data['bulan_now'] = $this->input->post('bulan', true);
            $data['tahun_now'] = $this->input->post('tahun', true);
    
            $data['tahun'] = array(2022,2023,2024,2025,2026);
    
            // memperoleh tgl pada bulan ini
            $tgl = date("d");
            $data['now'] = $data['tahun_now'].'-'.$data['bulan_now'].'-'.$tgl;
            $data['tglAkhir'] = date("Y-m-t", strtotime( $data['now'] ));
            $tgl_a = explode("-",$data['tglAkhir']);
            $array_tgl = array();   
            for($i=1;$i<=$tgl_a[2];$i++){
                $array_tgl[] = $tgl_a[0].'-'.sprintf("%02d", $tgl_a[1]).'-'.sprintf("%02d", $i);
            }
    
            $data['array_tgl'] = $array_tgl;
            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-01', strtotime($data['now']));
    
            // // perhitungan hpp
            $data['dt_transaksi'] = array();
            foreach($data['barang'] as $row){
                // mencari data transaksi terkait keluar masuk
                $saldo_awal = $this->_saldo_awal_barang($row['id_sa'], $tgl_awal, $tgl_akhir);
    
                $transaksi = array();
                foreach($array_tgl as $row_tgl) {
                    // mutasi stok
                    $tgl_set = date('Y-m-d', strtotime($row_tgl));
                    $mutasi = $this->barang->getMutasiBarangByDate($row['id_barang'], $dt_user['cabang_id'], $tgl_set, $tgl_set); 
    
                    $qty_in_baru = 0;
                    $harga_in_baru = 0;
    
                    $qty_out_baru = 0;
                    foreach($mutasi as $row_mutasi) {
                        if($row_mutasi['jenis'] == 'in_tr' || $row_mutasi['jenis'] == 'in') {
                            $qty_in_baru = intval($row_mutasi['jml']);
                            $harga_in_baru = intval($row_mutasi['harga']);
                        } else {
                            $qty_out_baru += intval($row_mutasi['jml']);
                        }
                    }
                    // die;
                    $stok_in = array(
                        'qty' => $qty_in_baru,
                        'harga' => $harga_in_baru,
                    );
    
                    $stok_out = array(
                        'qty' => $qty_out_baru,
                    );
                    $saldo_akhir = array();
    
                    $transaksi[] = array(
                        'tgl' => $row_tgl,
                        'masuk' => $stok_in,
                        'keluar' => $stok_out,
                    );
                }
    
                $data['dt_transaksi'][] = array(
                    'id_barang' => $row['id_barang'],
                    'nm_barang' => $row['nama_barang'],
                    'saldo_awal' => $saldo_awal,
                    'transaksi' => $transaksi,
                );
            }

            $this->template->load('templates/dashboard', 'laporan/hppperhari', $data);
        }
    }

    // perhitungan hpp
    private function saldo_awal_hpp()
    {
         $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
         $tgl_akhir = date('Y-m-d', strtotime('-1 days', strtotime($tglAwal)));
         $saldo_awal = $this->db->get_where('p_item', ['item_id' => $item_id])->row_array();
         $data_persediaan = $this->Stok_m->getData($item_id, $tgl_awal, $tgl_akhir);

         $qty = $saldo_awal['stok'];
         $harga = $saldo_awal['harga'];
         if ($data_persediaan != null) {
            foreach ($data_persediaan as $p) :
               if ($p['stts'] == 0) {
                  $qty -= $p['qty'];
                  $harga_rata_rata = $harga;
               } else {
                  $harga_rata_rata = (($qty * $harga) + ($p['qty'] * $p['harga'])) / ($qty + $p['qty']);
                  $qty += $p['qty'];
               }

               $harga = $harga_rata_rata;
            endforeach;
         } else {
            $harga_rata_rata = $harga;
         }
    }


}
