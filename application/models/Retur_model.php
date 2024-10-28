<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Retur_model extends CI_Model
{
    public function getDataRetur()
    {
        $query = "SELECT a.*,u.nama, b.nama_cabang AS asal, c.nama_cabang AS tujuan FROM retur a, cabang b, cabang c , user u WHERE u.id_user = a.id_user AND a.asal_cabang = b.id_cabang AND a.tujuan_cabang = c.id_cabang";
        return $this->db->query($query)->result_array();
    }

    public function getDataReturById($id)
    {
        $query = "SELECT u.nama,c.*,cb.nama_cabang as nama_asal, ct.nama_cabang as nama_tujuan FROM  retur c,  cabang cb, cabang ct, user u where u.id_user = c.id_user AND cb.id_cabang = c.asal_cabang AND ct.id_cabang = c.tujuan_cabang and c.id_retur = '$id'";
        return $this->db->query($query)->row_array();
    }

    public function getDetailReturById($id)
    {
        $query = "SELECT a.*,b.nama_barang,s.nama_satuan,cb.nama_cabang as nama_asal, ct.nama_cabang as nama_tujuan,b.stok as stok_real FROM detail_retur a, barang b, retur c, satuan s, cabang cb, cabang ct where cb.id_cabang = c.asal_cabang AND ct.id_cabang = c.tujuan_cabang AND b.satuan_id = s.id_satuan AND a.id_barang = b.id_barang and c.id_retur = a.id_retur and a.id_retur = '$id'";
        return $this->db->query($query)->result_array();
    }

    public function insertRetur()
    {
        $tanggal = $this->input->post('tanggal');
        $keterangan = $this->input->post('keterangan');
        $kode_transfer = $this->input->post('kode_transfer');
        $asal_cabang = $this->input->post('asal_cabang');
        $tujuan_cabang = $this->input->post('tujuan_cabang');

        $data = [
            'id_retur' => $kode_transfer,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'asal_cabang' => $asal_cabang,
            'tujuan_cabang' => $tujuan_cabang,
            'id_user' => $this->input->post('user_id')
        ];

        $this->db->insert('retur', $data);
      

        $barang = $this->input->post('id_barang');
        $jumlah = $this->input->post('jumlah');

        $index = 0;
        $data_detail = array();
        foreach ($barang as $id) {
            array_push($data_detail, array(
                'id_retur' => $kode_transfer,
                'id_barang' => $id,
                'jumlah' => $jumlah[$index],
            ));

            // Update Stok
            // mutasi_stok($id, 'cabang_detail', '-', $jumlah[$index], $asal_cabang);

            // Input data ke cabang jika data barang belum masuk di cabang
            // $query = "INSERT INTO cabang_detail (id_cabang, id_barang)
            // SELECT * FROM (SELECT '$tujuan_cabang', '$id') AS tmp
            // WHERE NOT EXISTS (
            //     SELECT * FROM cabang_detail WHERE id_cabang = '$tujuan_cabang' AND id_barang = '$id'
            // ) LIMIT 1";
            // $this->db->query($query);
            // end input

            // mutasi_stok($id, 'cabang_detail', '+', $jumlah[$index],  $tujuan_cabang);

            mutasi_stok($id, 'barang', '+', $jumlah[$index]);
            // End Update Stok
            $index++;
        }

        return $this->db->insert_batch('detail_retur', $data_detail);
    }


    public function editRetur($id)
    {

        $data_detail = $this->getDetailReturById($id);
        foreach ($data_detail as $row) {
            // Update Stok
            mutasi_stok($row['id_barang'], 'barang', '-', $row['jumlah']);

            // mutasi_stok($row['id_barang'], 'cabang_detail', '+', $row['jumlah_detail'], $row['asal_cabang']);
            // mutasi_stok($row['id_barang'], 'cabang_detail', '-', $row['jumlah_detail'], $row['tujuan_cabang']);
            // End Update
        }

        $this->db->delete('detail_retur', ['id_retur' => $id]);

        $tanggal = $this->input->post('tanggal');
        $keterangan = $this->input->post('keterangan');
        $asal_cabang = $this->input->post('asal_cabang');
        $tujuan_cabang = $this->input->post('tujuan_cabang');

        $data = [
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'asal_cabang' => $asal_cabang,
            'tujuan_cabang' => $tujuan_cabang
        ];

        $this->db->where('id_retur', $id);
        $this->db->update('retur', $data);

        $barang = $this->input->post('id_barang');
        $jumlah = $this->input->post('jumlah');

        $index = 0;
        $data_detail = array();
        foreach ($barang as $id_br) {
            array_push($data_detail, array(
                'id_retur' => $id,
                'id_barang' => $id_br,
                'jumlah' => $jumlah[$index],
            ));

            // // Update Stok
            // mutasi_stok($id_br, 'cabang_detail', '-', $jumlah[$index], $asal_cabang);

            // // Input data ke cabang jika data barang belum masuk di cabang
            // $query = "INSERT INTO cabang_detail (id_cabang, id_barang)
            // SELECT * FROM (SELECT '$tujuan_cabang', '$id_br') AS tmp
            // WHERE NOT EXISTS (
            //     SELECT * FROM cabang_detail WHERE id_cabang = '$tujuan_cabang' AND id_barang = '$id_br'
            // ) LIMIT 1";
            // $this->db->query($query);
            // // end input

            // mutasi_stok($id_br, 'cabang_detail', '+', $jumlah[$index],  $tujuan_cabang);
            // End Update Stok
            // Update Stok
            mutasi_stok($id, 'barang', '+', $jumlah[$index]);
            $index++;
        }

        return $this->db->insert_batch('detail_retur', $data_detail);
    }


    public function delete($table, $pk, $id)
    {

        $data_detail = $this->getDetailReturById($id);
        foreach ($data_detail as $row) {
         
            // Update Stok
            mutasi_stok($row['id_barang'], 'barang', '-', $row['jumlah']);
           
        }
        $this->db->delete('detail_retur', [$pk => $id]);

        return $this->db->delete($table, [$pk => $id]);
    }
}
