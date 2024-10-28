<?php
defined('BASEPATH') or exit('No direct script access allowed');

class retur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->model('Transfer_model', 'transfer');
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Retur_model', 'retur');
    }

    public function index()
    {
        $data['title'] = "Retur Barang";
        $data['retur_barang'] = $this->retur->getDataRetur();
        $this->template->load('templates/dashboard', 'retur/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal', 'Tanggal Retur', 'required');
        $this->form_validation->set_rules('asal_cabang', 'Asal Cabang', 'required');
        $this->form_validation->set_rules('tujuan_cabang', 'Tujuan Cabang', 'required');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Retur Barang";
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);
            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOpt();
            // Mendapatkan dan men-generate kode transaksi transfer barang
            $kode = 'RT-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('retur', 'id_retur', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['kode_transfer'] = $kode . $number;

            $this->template->load('templates/dashboard', 'retur/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->retur->insertRetur();

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('retur');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('retur/add');
            }
        }
    }

    public function edit($id)
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Transfer Barang";
            $data['barang'] = $this->admin->get('barang');
            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOpt($id);
            $data['retur'] = $this->retur->getDataReturById($id);
            $data['detail_retur'] = $this->retur->getDetailReturById($id);

            $this->template->load('templates/dashboard', 'retur/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->retur->editRetur($id);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('retur');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('retur/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->retur->delete('retur', 'id_retur', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('retur');
    }

    public function show_detailretur()
    {
        $id = $this->input->post('id');
        $data['retur'] = $this->retur->getDataReturById($id);
        $data['detail_retur'] = $this->retur->getDetailReturById($id);

        $this->load->view('retur/detail_returbarang', $data);
    }

    public function cetak_retur($id)
    {
        $retur = $this->retur->getDataReturById($id);
        $detail_retur = $this->retur->getDetailReturById($id);

        $this->load->library('PDF_MC_Table');
        $pdf = new PDF_MC_Table();

        $pdf->AddPage();

        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Invoice Retur Barang', 0, 1, 'C');

        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(45, 2, ' ID Retur', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $retur['id_retur'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tanggal', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $retur['tanggal'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Asal Cabang', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $retur['nama_asal'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tujuan Cabang', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $retur['nama_tujuan'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Admin', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2,  $retur['nama'], 0, 0, 'L');
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
        foreach ($detail_retur as $d) {
            $pdf->Row(array($no++,  $d['id_barang'], $d['nama_barang'], $d['jumlah'] . ' ' . $d['nama_satuan']));
        }

        $pdf->Output();
    }
}
