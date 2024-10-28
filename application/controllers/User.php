<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        if (!is_pemilik()) {
            redirect('dashboard');
        }

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "User Management";
        $data['cabang'] = $this->admin->getDataCabang();
        $data['users'] = $this->admin->getUsers();
        $this->template->load('templates/dashboard', 'user/data', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($mode == 'add') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[user.username]|alpha_numeric');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]|trim');
            $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'matches[password]|trim');
        } else {

            $db = $this->admin->get('user', ['id_user' => $this->input->post('id_user', true)]);
            $username = $this->input->post('username', true);
            $email = $this->input->post('email', true);
            
            if(!empty($db)) {
                $uniq_username = ($db['username'] == $username) ? '' : '|is_unique[user.username]';
                $uniq_email = ($db['email'] == $email) ? '' : '|is_unique[user.email]';
            } else {
                $uniq_username = '';
                $uniq_email = '';
            }

            $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric' . $uniq_username);
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email' . $uniq_email);
        }
    }

    public function add()
    {
        $this->_validasi('add');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah User";
            $data['cabang'] = $this->admin->getDataCabang();
            $this->template->load('templates/dashboard', 'user/add', $data);
        } else {
            $input = $this->input->post(null, true);
            if ($input['role'] == 'pemilik') {
                $foto = 'user.png';
            } else {
                $foto = 'admin.png';
            }

            $input_data = [
                'cabang_id'     => $input['cabang_id'],
                'nama'          => $input['nama'],
                'username'      => $input['username'],
                'email'         => $input['email'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role'],
                'password'      => password_hash($input['password'], PASSWORD_DEFAULT),
                'created_at'    => time(),
                'foto'          => $foto
            ];

            if ($this->admin->insert('user', $input_data)) {
                set_pesan('data berhasil disimpan.');
                redirect('user');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('user/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi('edit');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit User";
            $data['cabang'] = $this->admin->getDataCabang();
            $data['user'] = $this->admin->get('user', ['id_user' => $id]);
            $this->template->load('templates/dashboard', 'user/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $input_data = [
                'cabang_id'     => $input['cabang_id'],
                'nama'          => $input['nama'],
                'username'      => $input['username'],
                'email'         => $input['email'],
                'no_telp'       => $input['no_telp'],
                'role'          => $input['role']
            ];

            if ($this->admin->update('user', 'id_user', $id, $input_data)) {
                set_pesan('data berhasil diubah.');
                redirect('user');
            } else {
                set_pesan('data gagal diubah.', false);
                redirect('user/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        $cabang = $this->admin->get('cabang', ['id_user' => $id]);
        $retur = $this->admin->get('retur', ['id_user' => $id]);
        $barang_masuk = $this->admin->get('barang_masuk', ['user_id' => $id]);
        $barang_keluar = $this->admin->get('barang_keluar', ['user_id' => $id]);
        $transfer_barang = $this->admin->get('transfer_barang', ['user_id' => $id]);

        if (!is_null($cabang) || !is_null($retur) || !is_null($barang_masuk) || !is_null($barang_keluar)  || !is_null($transfer_barang)) {
            set_pesan('User sudah digunakan!', false);
        } else {
            $this->admin->delete('user', 'id_user', $id);
            set_pesan('data berhasil dihapus.');
        }
        redirect('user');
    }

    public function toggle($getId)
    {
        $id = encode_php_tags($getId);
        $status = $this->admin->get('user', ['id_user' => $id])['is_active'];
        $toggle = $status ? 0 : 1; //Jika user aktif maka nonaktifkan, begitu pula sebaliknya
        $pesan = $toggle ? 'user diaktifkan.' : 'user dinonaktifkan.';

        if ($this->admin->update('user', 'id_user', $id, ['is_active' => $toggle])) {
            set_pesan($pesan);
        }
        redirect('user');
    }
}
