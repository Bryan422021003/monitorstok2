<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
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
        $data['title'] = "Customer";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

        $this->db->where('cabang_id',$dt_user['cabang_id']);
        $data['customer'] = $this->admin->get('customer');
        $this->template->load('templates/dashboard', 'customer/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('nama_customer', 'Nama customer', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim|numeric');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Customer";
            $data['dt_user'] = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

            $this->template->load('templates/dashboard', 'customer/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $save = $this->admin->insert('customer', $input);
            if ($save) {
                set_pesan('data berhasil disimpan.');
                redirect('customer');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('customer/add');
            }
        }
    }


    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();

        if ($this->form_validation->run() == false) {
            $data['title'] = "Customer";
            
            $data['customer'] = $this->admin->get('customer', ['id_customer' => $id]);
            $this->template->load('templates/dashboard', 'customer/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('customer', 'id_customer', $id, $input);

            if ($update) {
                set_pesan('data berhasil diedit.');
                redirect('customer');
            } else {
                set_pesan('data gagal diedit.');
                redirect('customer/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);

        $barang =$this->admin->get('barang_masuk', ['customer_id' => $id]);
        if(is_null($barang)) {
            $delete = $this->admin->delete('customer', 'id_customer', $id);
            if ($this->db->error()) {
                set_pesan('data gagal dihapus.', false);
            } else {
                echo $this->db->error();
            }
        } else {
            set_pesan('customer sudah digunakan!', false);
        }
        redirect('customer');
    }
}
