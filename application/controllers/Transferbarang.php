<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transferbarang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->model('Transfer_model', 'transfer');
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
    }

    public function index()
    {
        $data['title'] = "Transfer Barang";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

        $tgl_akhir = date("Y-m-d");
        $tgl_awal = date('Y-m-d', strtotime('-30 days', strtotime($tgl_akhir)));

        $data['tglAwal'] = $tgl_awal;
        $data['tglAkhir'] = $tgl_akhir;
        $data['transfer_barang'] = $this->transfer->getDataTransfer($tgl_awal, $tgl_akhir);
        $this->form_validation->set_rules('tgl_awal', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tgl_akhir', 'Tanggal Akhir', 'required');
        if($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'transfer_barang/data', $data);
        } else {
            $tglAwal = $this->input->post('tgl_awal', true);
            $tglAkhir = $this->input->post('tgl_akhir', true);

            $data['tglAwal'] = $tglAwal;
            $data['tglAkhir'] = $tglAkhir;
            $data['transfer_barang'] = $this->transfer->getDataTransfer($tglAwal, $tglAkhir);
            
            $this->template->load('templates/dashboard', 'transfer_barang/data', $data);
        }
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tgl_transfer', 'Tanggal Transfer', 'required');
        $this->form_validation->set_rules('asal_cabang', 'Asal Cabang', 'required');
        $this->form_validation->set_rules('tujuan_cabang', 'Tujuan Cabang', 'required');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Transfer Barang";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);

            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOptTf();

            // Mendapatkan dan men-generate kode transaksi transfer barang
            $kode = 'TR-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('transfer_barang', 'kode_transfer', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['kode_transfer'] = $kode . $number;

            $this->template->load('templates/dashboard', 'transfer_barang/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->transfer->insertTransfer();

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('transferbarang');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('transferbarang/add');
            }
        }
    }

    public function edit($id)
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Transfer Barang";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);
            
            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOptTf();
            $data['transfer'] = $this->admin->get('transfer_barang', ['id_transfer' => $id]);
            $data['detail_transfer'] = $this->transfer->getDetailTransferById($id);

            $this->template->load('templates/dashboard', 'transfer_barang/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->transfer->editTransfer($id);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('transferbarang');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('transferbarang/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->transfer->delete('transfer_barang', 'id_transfer', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('transferbarang');
    }

    public function show_detailtransfer()
    {
        $id = $this->input->post('id');
        $data['transfer'] = $this->transfer->getTransferById($id);
        $data['detail_transfer'] = $this->transfer->getDetailTransferById($id);

        $this->load->view('transfer_barang/detail_transferbarang', $data);
    }

    public function cetak_transfer($id)
    {
        $transfer = $this->transfer->getTransferById($id);
        $detail_transfer = $this->transfer->getDetailTransferById($id);

        $this->load->library('PDF_MC_Table');
        $pdf = new PDF_MC_Table();

        $pdf->AddPage();

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Invoice Transfer Barang', 0, 1, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(45, 2, ' Kode Transfer', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $transfer['kode_transfer'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tanggal', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $transfer['tgl_transfer'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Asal Cabang', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $transfer['nama_asal'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tujuan Cabang', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $transfer['nama_tujuan'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Admin', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2,  $transfer['nama'], 0, 0, 'L');
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
        foreach ($detail_transfer as $d) {
            $pdf->Row(array($no++,  $d['id_barang'], $d['nama_barang'], $d['jumlah_detail'] . ' ' . $d['nama_satuan']));
        }

        $pdf->Output();
    }
}
