<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Supplier";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
        
        $this->db->where('cabang_id',$dt_user['cabang_id']);
        $data['supplier'] = $this->admin->get('supplier');
        $this->template->load('templates/dashboard', 'supplier/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Supplier";
            $data['dt_user'] = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            
            $this->template->load('templates/dashboard', 'supplier/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $save = $this->admin->insert('supplier', $input);
            if ($save) {
                set_pesan('data berhasil disimpan.');
                redirect('supplier');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('supplier/add');
            }
        }
    }


    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Supplier";
            $data['supplier'] = $this->admin->get('supplier', ['id_supplier' => $id]);
            $this->template->load('templates/dashboard', 'supplier/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('supplier', 'id_supplier', $id, $input);

            if ($update) {
                set_pesan('data berhasil diedit.');
                redirect('supplier');
            } else {
                set_pesan('data gagal diedit.');
                redirect('supplier/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);

        $barang =$this->admin->get('barang_masuk', ['supplier_id' => $id]);
        if(is_null($barang)) {
            $delete = $this->admin->delete('supplier', 'id_supplier', $id);
            if ($this->db->error()) {
                set_pesan('data gagal dihapus.', false);
            } else {
                echo $this->db->error();
            }
        } else {
            set_pesan('Supplier sudah digunakan!', false);
        }
        redirect('supplier');
    }
}
