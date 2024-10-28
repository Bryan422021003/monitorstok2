<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transfer_model extends CI_Model
{
    public function getDataTransfer($tgl_awal, $tgl_akhir)
    {
        $where = "AND a.tgl_transfer BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";

        $query = "SELECT a.*, b.nama_cabang AS asal, c.nama_cabang AS tujuan FROM transfer_barang a, cabang b, cabang c WHERE a.asal_cabang = b.id_cabang AND a.tujuan_cabang = c.id_cabang $where";
        return $this->db->query($query)->result_array();
    }

    public function getTransferById($id)
    {
        $query = "SELECT u.nama,c.*,cb.nama_cabang as nama_asal, ct.nama_cabang as nama_tujuan FROM  transfer_barang c,  cabang cb, cabang ct, user u where u.id_user = c.user_id AND cb.id_cabang = c.asal_cabang AND ct.id_cabang = c.tujuan_cabang and c.id_transfer = '$id'";
        return $this->db->query($query)->row_array();
    }

    public function getDetailTransferById($id)
    {
        $query = "SELECT a.*,b.nama_barang,s.nama_satuan,cb.nama_cabang as nama_asal, ct.nama_cabang as nama_tujuan FROM detail_transfer_barang a, barang b, transfer_barang c, satuan s, cabang cb, cabang ct where cb.id_cabang = c.asal_cabang AND ct.id_cabang = c.tujuan_cabang AND b.satuan_id = s.id_satuan AND a.id_barang = b.id_barang and c.id_transfer = a.id_transfer and a.id_transfer = '$id'";
        return $this->db->query($query)->result_array();
    }

    public function insertTransfer()
    {
        $tgl_transfer = $this->input->post('tgl_transfer');
        $keterangan = $this->input->post('keterangan');
        $kode_transfer = $this->input->post('kode_transfer');
        $asal_cabang = $this->input->post('asal_cabang');
        $tujuan_cabang = $this->input->post('tujuan_cabang');

        $data = [
            'kode_transfer' => $kode_transfer,
            'tgl_transfer' => $tgl_transfer,
            'keterangan' => $keterangan,
            'asal_cabang' => $asal_cabang,
            'tujuan_cabang' => $tujuan_cabang,
            'user_id' => $this->input->post('user_id')
        ];

        $this->db->insert('transfer_barang', $data);
        $id_transfer =  $this->db->insert_id();

        $barang = $this->input->post('id_barang');
        $jumlah = $this->input->post('jumlah');

        $index = 0;
        $data_detail = array();
        foreach ($barang as $id) {
            array_push($data_detail, array(
                'id_transfer' => $id_transfer,
                'id_barang' => $id,
                'jumlah_detail' => $jumlah[$index],
            ));
            // End Update Stok
            $index++;
        }

        return $this->db->insert_batch('detail_transfer_barang', $data_detail);
    }


    public function editTransfer($id)
    {

        $this->db->delete('detail_transfer_barang', ['id_transfer' => $id]);

        $tgl_transfer = $this->input->post('tgl_transfer');
        $keterangan = $this->input->post('keterangan');
        $kode_transfer = $this->input->post('kode_transfer');
        $asal_cabang = $this->input->post('asal_cabang');
        $tujuan_cabang = $this->input->post('tujuan_cabang');

        $data = [
            'kode_transfer' => $kode_transfer,
            'tgl_transfer' => $tgl_transfer,
            'keterangan' => $keterangan,
            'asal_cabang' => $asal_cabang,
            'tujuan_cabang' => $tujuan_cabang
        ];


        $this->db->where('id_transfer', $id);
        $this->db->update('transfer_barang', $data);

        $barang = $this->input->post('id_barang');
        $jumlah = $this->input->post('jumlah');

        $index = 0;
        $data_detail = array();
        foreach ($barang as $id_br) {
            array_push($data_detail, array(
                'id_transfer' => $id,
                'id_barang' => $id_br,
                'jumlah_detail' => $jumlah[$index],
            ));
            $index++;
        }

        return $this->db->insert_batch('detail_transfer_barang', $data_detail);
    }


    public function delete($table, $pk, $id)
    {

        $this->db->delete('detail_transfer_barang', [$pk => $id]);

        return $this->db->delete($table, [$pk => $id]);
    }
}
