<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Monitor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Barang_model', 'barang');
    }

    public function index()
    {
        $data['title'] = "Monitoring STOK";
        $barang = $this->admin->getBarang();
        $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
        $tgl_akhir = date('Y-m-d');

        $data['barang'] = array();
        foreach($barang as $row){
            // memperoleh jumlah stok per barang by cabang
            $stok_magetan = 0;
            $stok_caruban = 0;
            $dt_barang_by_id_barang = $this->admin->getSaBarangById($row['id_barang']);
            if($dt_barang_by_id_barang){
                foreach($dt_barang_by_id_barang as $dt_barang){
                    if($dt_barang['cabang_id'] == 1){
                        // cabang magetan
                        $stok_terkini = $this->barang->_saldo_awal_barang($dt_barang['id_sa'], $tgl_awal, $tgl_akhir);
                        $stok_magetan += $stok_terkini;
                    } else {
                        // cabang caruban
                        $stok_terkini = $this->barang->_saldo_awal_barang($dt_barang['id_sa'], $tgl_awal, $tgl_akhir);
                        $stok_caruban += $stok_terkini;
                    }
                }
            }

            $data['barang'][] = array(
                'id_barang' => $row['id_barang'],
                'nama_barang' => $row['nama_barang'],
                'batas' => $row['batas'],
                'stok_magetan' => $stok_magetan,
                'stok_caruban' => $stok_caruban,
                'total_stok' => $stok_magetan + $stok_caruban,
            ); 
        }
        
        echo json_encode($data['barang']);
        die;
        

        $this->template->load('templates/public', 'monitor/index', $data);
    }
}