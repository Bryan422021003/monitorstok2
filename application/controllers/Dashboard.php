<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

        $data['barang'] = $this->admin->count('sa_barang');
        $data['barang_masuk'] = $this->admin->count('barang_masuk');
        $data['barang_keluar'] = $this->admin->count('barang_keluar');
        $data['supplier'] = $this->admin->count('supplier');
        $data['user'] = $this->admin->count('user');
        $data['stok'] = 0;
        $data['barang_min'] = 0;
        $data['transaksi'] = [
            'barang_masuk' => $this->admin->getBarangMasuk($dt_user['cabang_id'], 5),
            'barang_keluar' => $this->admin->getBarangKeluar($dt_user['cabang_id'], 5)
        ];

        // Line Chart
        $bln = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $data['cbm'] = [];
        $data['cbk'] = [];

        foreach ($bln as $b) {
            $data['cbm'][] = $this->admin->chartBarangMasuk($b);
            $data['cbk'][] = $this->admin->chartBarangKeluar($b);
        }

        $this->template->load('templates/dashboard', 'dashboard', $data);
    }
}
