<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
    }

    public function index()
    {
        $data['title'] = "Barang keluar";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

        $tgl_akhir = date("Y-m-d");
        $tgl_awal = date('Y-m-d', strtotime('-30 days', strtotime($tgl_akhir)));
        $range = array(
            'mulai' => $tgl_awal,
            'akhir' => $tgl_akhir,
        );
        $data['tglAwal'] = $tgl_awal;
        $data['tglAkhir'] = $tgl_akhir;
        $data['barangkeluar'] = $this->admin->getBarangKeluarByCabang($dt_user['cabang_id'], $range);
        
        // echo json_encode($data['barangkeluar']);

        $this->form_validation->set_rules('tgl_awal', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tgl_akhir', 'Tanggal Akhir', 'required');
        if($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
        } else {
            $tglAwal = $this->input->post('tgl_awal', true);
            $tglAkhir = $this->input->post('tgl_akhir', true);

            // memperoleh data by tanggal
            $range = array(
                'mulai' => $tglAwal,
                'akhir' => $tglAkhir,
            );
            $data['tglAwal'] = $tglAwal;
            $data['tglAkhir'] = $tglAkhir;
            $data['barangkeluar'] = $this->admin->getBarangKeluarByCabang($dt_user['cabang_id'], $range);

            $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
        }
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('customer_id', 'Customer', 'required');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Barang Keluar";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);

            $this->db->where('cabang_id',$dt_user['cabang_id']);
            $data['customer'] = $this->admin->get('customer');
            $data['cabang'] = $this->admin->get('cabang');

            $data['barang_opt'] = $this->barang->getBarangOpt($dt_user['cabang_id']);
            // Mendapatkan dan men-generate kode transaksi barang keluar
            $kode = 'T-BK-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {

            $insert = $this->barang->insertBarangKeluar();

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangkeluar');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangkeluar/add');
            }
        }
    }

    public function edit($id)
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Barang Keluar";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);

            $this->db->where('cabang_id',$dt_user['cabang_id']);
            $data['customer'] = $this->admin->get('customer');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOpt($dt_user['cabang_id']);
         
            $data['brg'] = $this->barang->getBarangKeluarById($id);

            $d_brg = $this->barang->getDetailBarangKeluarById($id);

            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-d');

            // memperoleh data stok terbaru
            $data['d_brg'] = array();
            foreach($d_brg as $row) {
                $stok_awal = $this->barang->_saldo_awal_barang($row['id_barang'], $tgl_awal, $tgl_akhir);

                $data['d_brg'][] = array(
                    'id_sa' => $row['id_barang'],
                    'jumlah' => $row['jumlah'],
                    'stok_real' => $stok_awal+$row['jumlah'],
                );
            }

            $this->template->load('templates/dashboard', 'barang_keluar/edit', $data);
        } else {
            $insert = $this->barang->editBarangKeluar($id);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangkeluar');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangkeluar/edit');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->barang->delete('barang_keluar', 'id_barang_keluar', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangkeluar');
    }

    public function show_barangkeluar()
    {
        $id_barangkeluar = $this->input->post('id');
        $data['brg'] = $this->barang->getBarangKeluarById($id_barangkeluar);
        $data['d_brg'] = $this->barang->getDetailBarangKeluarById($id_barangkeluar);

        $this->load->view('barang_keluar/detail_barangkeluar', $data);
    }

    public function cetak_bkluar($id_barangkeluar)
    {
        $brg = $this->barang->getBarangKeluarById($id_barangkeluar);
        $d_brg = $this->barang->getDetailBarangKeluarById($id_barangkeluar);

        $this->load->library('PDF_MC_Table');
        $pdf = new PDF_MC_Table();

        $pdf->AddPage();

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Invoice Barang Keluar', 0, 1, 'C');


        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(45, 2, 'Id Barang Keluar', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['id_barang_keluar'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tanggal', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['tanggal_keluar'], 0, 0, 'L');
        $pdf->Ln(10);
        
        $pdf->Cell(45, 2, 'Nama Cabang', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['nama_cabang'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Admin', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['nama'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Id Barang', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Nama Barang', 1, 0, 'C');
        $pdf->Cell(60, 7, 'Stok', 1, 0, 'C');
        $pdf->SetWidths(array(10, 60, 60, 60));
        $pdf->Ln();
        //Table with 20 rows and 4 columns
        $pdf->SetLineHeight(7);
        $no = 1;
        foreach ($d_brg as $d) {
            $pdf->Row(array($no++,  $d['id_barang'], $d['nama_barang'], $d['jumlah'] . ' ' . $d['nama_satuan']));
        }

        $pdf->Output();
    }
}
