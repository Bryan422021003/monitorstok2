<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends PDF_MC_Table{
    // Page header
    function Content($query){
        // Kop Laporan
        $this->setFont('Arial','B',12);
        $this->setFillColor(255,255,255);
        $this->cell(0,6,'Laporan Data Barang',0,1,'C',1); 
        $this->cell(0,6,'Monitoring Stok [ '.date('d-M-Y').' ]',0,1,'C',1); 

        $this->Ln();
        $this->setFont('Arial','B',9);
        $this->setFillColor(255,255,255);
        $this->cell(10,6,'No.',1,0,'C',1);
        $this->cell(23,6,'ID Barang',1,0,'C',1);
        $this->cell(55,6,'Nama Barang',1,0,'C',1);
        $this->cell(30,6,'Jenis Barang',1,0,'C',1);
        $this->cell(40,6,'Stok Terbaru',1,0,'C',1);
        $this->cell(30,6,'Satuan',1,0,'C',1);

        $this->Ln();
        $this->setFont('Arial','',9);
        $this->SetWidths(Array(10,23,55,30,40,30));
        $this->SetAligns(Array('C','L','L','L','L','L'));
        $this->SetLineHeight(5);
        if($query) {
            $i = 1;
            foreach ($query as $data) {
                $this->Row(Array(
                    $i++,
                    $data['id_barang'],
                    $data['nama_barang'],
                    $data['nama_jenis'],
                    $data['stok'],
                    $data['nama_satuan'],
                ));
            } 
        } else {
            $this->setFont('Arial','',9);
            $this->setFillColor(255,255,255);   
            $this->cell(200,6,'Tidak ada data',1,0,'L',1);
        }

    }
}
 
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle('Data Barang',true);
$pdf->Content($query);
$pdf->Output('data-barang-'.date('d-M-Y').'.pdf', 'I');

?>