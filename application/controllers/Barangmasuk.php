<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller
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
        $data['title'] = "Barang Masuk";
        $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);

        $tgl_akhir = date("Y-m-d");
        $tgl_awal = date('Y-m-d', strtotime('-30 days', strtotime($tgl_akhir)));
        $range = array(
            'mulai' => $tgl_awal,
            'akhir' => $tgl_akhir,
        );
        $data['tglAwal'] = $tgl_awal;
        $data['tglAkhir'] = $tgl_akhir;
        $data['barangmasuk'] = $this->admin->getBarangMasukByCabang($dt_user['cabang_id'], $range);
        $this->form_validation->set_rules('tgl_awal', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tgl_akhir', 'Tanggal Akhir', 'required');
        if($this->form_validation->run() == false) {
            $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
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
            $data['barangkeluar'] = $this->admin->getBarangMasukByCabang($dt_user['cabang_id'], $range);

            $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
        }
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Barang Masuk";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);

            $this->db->where('cabang_id',$dt_user['cabang_id']);
            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOpt($dt_user['cabang_id']);

            // Mendapatkan dan men-generate kode transaksi barang masuk
            $kode = 'T-BM-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_masuk', 'id_barang_masuk', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_masuk'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_masuk/add', $data);
        } else {
            // $input = $this->input->post(null, true);
            // $input = array_except($input, 'cabang_id');

            $insert = $this->barang->insertBarangMasuk();

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barang_masuk/add');
            }
        }
    }

    public function edit($id)
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $dt_user = $this->admin->get('user', ['id_user' => $this->session->userdata('login_session')['user']]);
            $data['cabang_id'] = $dt_user['cabang_id'];

            $data['title'] = "Barang Masuk";
            $data['barang'] = $this->admin->getBarangByCabang($dt_user['cabang_id']);

            $this->db->where('cabang_id',$dt_user['cabang_id']);
            $data['supplier'] = $this->admin->get('supplier');
            $data['cabang'] = $this->admin->get('cabang');
            $data['barang_opt'] = $this->barang->getBarangOpt($dt_user['cabang_id']);

            $data['brg'] = $this->barang->getBarangMasukById($id);
            $data['d_brg'] = $this->barang->getDetailBarangMasukById($id);

            $tgl_awal = date('Y-m-d', strtotime('1/1/1971'));
            $tgl_akhir = date('Y-m-d');

            // memperoleh data stok terbaru
            $data['detail_brg'] = array();
            $data['total_beli'] = 0;
            foreach($data['d_brg'] as $row) {
                $getBarangById = $this->barang->getMutasiBarang($row['id_barang']);

                $stok_terkini = 0;
                foreach($getBarangById as $row_){

                    if($row_['jenis'] == 'in'){
                        // barang masuk
                        $stok_terkini += $row_['jml']; 
                    } else if($row_['jenis'] == 'out'){ 
                        // barang keluar
                        $stok_terkini -= $row_['jml']; 
                    } 
                }


                $data['detail_brg'][] = array(
                    'id_barang' => $row['id_barang'],
                    'id_sa' => $row['id_sa'],
                    'stok_real' => $row['saldo'] + $stok_terkini,
                    'stok_terkini' => $stok_terkini,
                    'jumlah' => $row['jumlah'],
                    'nama_barang' => $row['nama_barang'],
                    'nama_satuan' => $row['nama_satuan'],
                    'hpp' => $row['hpp'],
                    'harga' => $row['harga_beli'],
                );

                $data['total_beli'] +=  $row['jumlah'] * $row['harga_beli'];
            }
            
            $this->template->load('templates/dashboard', 'barang_masuk/edit', $data);
        } else {
            // $input = $this->input->post(null, true);
            // $input = array_except($input, 'cabang_id');

            $insert = $this->barang->editBarangMasuk($id);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barang_masuk/edit');
            }
        }
    } 

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($result = $this->barang->delete('barang_masuk', 'id_barang_masuk', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangmasuk');
    }

    public function show_barangmasuk()
    {
        $id_barangmasuk = $this->input->post('id');
        $data['brg'] = $this->barang->getBarangMasukById($id_barangmasuk);
        $data['d_brg'] = $this->barang->getDetailBarangMasukById($id_barangmasuk);

        $this->load->view('barang_masuk/detail_barangmasuk', $data);
    }

    public function cetak_bmasuk($id)
    {
        $brg = $this->barang->getBarangMasukById($id);
        $d_brg = $this->barang->getDetailBarangMasukById($id);

        $this->load->library('PDF_MC_Table');
        $pdf = new PDF_MC_Table();

        $pdf->AddPage();
     
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Cell(190, 7, 'Invoice Barang Masuk', 0, 1, 'C');
     
 
        $pdf->Ln(10);
        $pdf->SetFont('Times', '', 12);
        $pdf->Cell(45, 2, 'Id Barang Masuk', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['id_barang_masuk'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Tanggal', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['tanggal_masuk'], 0, 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(45, 2, 'Nama Supplier', 0, 0, 'L');
        $pdf->Cell(5, 2, ':', 0, 0, 'L');
        $pdf->Cell(45, 2, $brg['nama_supplier'], 0, 0, 'L');
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
            $pdf->Row(array($no++,  $d['id_barang'], $d['nama_barang'], $d['jumlah'].' '.$d['nama_satuan']));
        }

        $pdf->Output();
    }
}
