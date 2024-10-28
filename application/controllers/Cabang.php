<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cabang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->model('Barang_model', 'barang');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Cabang";
        $data['barang'] = $this->admin->getDataCabang();

        $this->template->load('templates/dashboard', 'cabang/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required|trim');
        $this->form_validation->set_rules('alamat_cabang', 'Alamat Cabang', 'required');
    }

    public function add()
    {
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Cabang";
            $data['user_admin'] = $this->admin->getUsers(1);
            $this->template->load('templates/dashboard', 'cabang/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->admin->insert('cabang', $input);

            if ($insert) {
                set_pesan('data berhasil disimpan');
                redirect('cabang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('cabang/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Cabang";
            $data['user_admin'] = $this->admin->getUsers(1);
            $data['cabang'] = $this->admin->get('cabang', ['id_cabang' => $id]);
            $this->template->load('templates/dashboard', 'cabang/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('cabang', 'id_cabang', $id, $input);

            if ($update) {
                set_pesan('data berhasil disimpan');
                redirect('cabang');
            } else {
                set_pesan('gagal menyimpan data');
                redirect('cabang/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        // cek ada di tabel
        $id = encode_php_tags($getId);
        $barang_masuk =$this->admin->get('barang_masuk', ['cabang_id' => $id]);
        $retur =$this->admin->get('retur', ['asal_cabang' => $id]);

        if(is_null($barang_masuk) && is_null($retur)) {
            if ($this->admin->delete('cabang', 'id_cabang', $id)) {
                set_pesan('data berhasil dihapus.');
            } else {
                set_pesan('data gagal dihapus.', false);
            }
        } else {
            set_pesan('Cabang ini sudah digunakan transaksi!', false);
        }

        redirect('cabang');
    }

    public function mutasi_barang($id)
    {
        $data['title'] = "Mutasi Barang";
        $data['cabang'] = $this->admin->get('cabang', ['id_cabang' => $id]);
        $data['mutasi_barang'] = $this->barang->getMutasiCabang($id);
        $this->template->load('templates/dashboard', 'cabang/mutasi_barang', $data);
    }
}
