<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_model extends CI_Model
{
    public function getBarangMasukById($id)
    {
        $this->db->select('*');
        $this->db->join('user u', 'a.user_id = u.id_user');
        $this->db->join('supplier sp', 'a.supplier_id = sp.id_supplier');
        $this->db->join('cabang cb', 'a.cabang_id = cb.id_cabang');
        $this->db->from('barang_masuk a');
        $this->db->where('a.id_barang_masuk', $id);
        return $this->db->get()->row_array();
    }

    public function getDetailBarangMasukById($id)
    {
        $this->db->select('b.*, d.barang_id, c.nama_barang, d.id_sa, d.saldo, d.hpp ,s.nama_satuan, b.harga as harga_beli');
        $this->db->from('barang_masuk a');
        $this->db->join('detail_barang_masuk b', 'a.id_barang_masuk = b.id_barang_masuk');
        $this->db->join('sa_barang d', 'b.id_barang = d.id_sa');
        $this->db->join('barang c', 'd.barang_id = c.id_barang');
        $this->db->join('satuan s', 'c.satuan_id = s.id_satuan');
        $this->db->where('a.id_barang_masuk', $id);
        return $this->db->get()->result_array();
    }

    public function getBarangKeluarById($id)
    {
        $this->db->select('*');
        $this->db->join('user u', 'a.user_id = u.id_user');
        $this->db->join('customer cs', 'a.customer_id = cs.id_customer');
        $this->db->join('cabang cb', 'a.cabang_id = cb.id_cabang');
        $this->db->from('barang_keluar a');
        $this->db->where('a.id_barang_keluar', $id);
        return $this->db->get()->row_array();
    }

    public function getDetailBarangKeluarById($id)
    {
        $this->db->select('b.*,d.barang_id,c.nama_barang,s.nama_satuan');
        $this->db->from('barang_keluar a');
        $this->db->join('detail_barang_keluar b', 'a.id_barang_keluar = b.id_barang_keluar');
        $this->db->join('sa_barang d', 'b.id_barang = d.id_sa');
        $this->db->join('barang c', 'd.barang_id = c.id_barang');
        $this->db->join('satuan s', 'c.satuan_id = s.id_satuan');
        $this->db->where('a.id_barang_keluar', $id);
        return $this->db->get()->result_array();
    }


    public function insertSaldoAwal()
    {
        $data = [
            "tgl" => $this->input->post('tgl', true),
            "barang_id" => $this->input->post('barang_id', true),
            "cabang_id" => $this->input->post('cabang_id', true),
            "user_id" => $this->input->post('user_id', true),
            "saldo" => $this->input->post('stok_awal', true),
            "hpp" => $this->input->post('hpp', true),
        ];

        $save = $this->db->insert('sa_barang', $data);

        return $save;
    }

    public function updateSaldoAwal()
    {
        $data = [
            "tgl" => $this->input->post('tgl', true),
            "barang_id" => $this->input->post('barang_id', true),
            "cabang_id" => $this->input->post('cabang_id', true),
            "user_id" => $this->input->post('user_id', true),
            "saldo" => $this->input->post('stok_awal', true),
            "hpp" => $this->input->post('hpp', true),
        ];

        $this->db->where('id_sa', $this->input->post('id_sa', true));
        $save = $this->db->update('sa_barang', $data);

        return $save;
    }


    public function insertDataBarang()
    {
        $id_barang = $this->input->post('id_barang', true);
        $nama_barang = $this->input->post('nama_barang', true);
        $batas = $this->input->post('batas', true);
        $id_jenis = $this->input->post('jenis_id', true);
        $id_satuan = $this->input->post('satuan_id', true);

        $data = [
            "id_barang" => $id_barang,
            "nama_barang" => $nama_barang,
            "batas" => $batas,
            "satuan_id" => $id_satuan,
            "jenis_id" => $id_jenis
        ];

        $save = $this->db->insert('barang', $data);
        return $save;
    }

    public function insertBarang($table, $data, $batch = false)
    {
        $barang_id = $this->input->post('barang_id');
        $id_cabang = $this->input->post('cabang_id');

        // Input data ke cabang jika data barang belum masuk di cabang
        $query = "INSERT INTO cabang_detail (id_cabang, id_barang)
        SELECT * FROM (SELECT '$id_cabang', '$barang_id') AS tmp
        WHERE NOT EXISTS (
            SELECT * FROM cabang_detail WHERE id_cabang = '$id_cabang' AND id_barang = '$barang_id'
        ) LIMIT 1";
        // end input

        $this->db->query($query);
        return $this->db->insert($table, $data);
    }

    public function inputBarangCabang($barang_id, $id_cabang)
    {
        // Input data ke cabang jika data barang belum masuk di cabang
        $query = "INSERT INTO cabang_detail (id_cabang, id_barang)
        SELECT * FROM (SELECT '$id_cabang', '$barang_id') AS tmp
        WHERE NOT EXISTS (
            SELECT * FROM cabang_detail WHERE id_cabang = '$id_cabang' AND id_barang = '$barang_id'
        ) LIMIT 1";
        // $this->db->query($query);
        // end input
    }

    public function insertBarangMasuk()
    {
        $id_barang_masuk = $this->input->post('id_barang_masuk', true);
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_masuk', true);
        $supplier = $this->input->post('supplier_id', true);

        $data = [
            "id_barang_masuk" => $id_barang_masuk,
            "supplier_id" => $supplier,
            "user_id" => $this->input->post('user_id'),
            "tanggal_masuk" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->insert('barang_masuk', $data);

        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $harga = $this->input->post('harga', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {

            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_barang_masuk' => $id_barang_masuk,
                'jumlah' => $jumlah[$i],
                'harga' => $harga[$i],
                'id_barang' => $id_br
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_barang_masuk', $data_detail);
    }

    public function editBarangMasuk($id)
    {
        $this->db->delete('detail_barang_masuk', ['id_barang_masuk' => $id]);

        $id_barang_masuk = $id;
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_masuk', true);
        $supplier = $this->input->post('supplier_id', true);

        $data = [
            "supplier_id" => $supplier,
            "user_id" => $this->input->post('user_id'),
            "tanggal_masuk" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->where('id_barang_masuk', $id);
        $this->db->update('barang_masuk', $data);

        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $harga = $this->input->post('harga', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {
            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_barang_masuk' => $id_barang_masuk,
                'jumlah' => $jumlah[$i],
                'harga' => $harga[$i],
                'id_barang' => $id_br
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_barang_masuk', $data_detail);
    }

    public function insertBarangKeluar()
    {
        $id_barang_keluar = $this->input->post('id_barang_keluar', true);
        $id_customer = $this->input->post('customer_id', true);
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_keluar', true);

        $data = [
            "id_barang_keluar" => $id_barang_keluar,
            "customer_id" => $id_customer,
            "user_id" => $this->input->post('user_id'),
            "tanggal_keluar" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->insert('barang_keluar', $data);

        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {
            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_barang_keluar' => $id_barang_keluar,
                'jumlah' => $jumlah[$i],
                'id_barang' => $id_br
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_barang_keluar', $data_detail);
    }

    public function editBarangKeluar($id)
    {
        $this->db->delete('detail_barang_keluar', ['id_barang_keluar' => $id]);

        $id_barang_keluar = $id;
        $id_customer = $this->input->post('customer_id', true);
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_keluar', true);

        $data = [
            "customer_id" => $id_customer,
            "user_id" => $this->input->post('user_id'),
            "tanggal_keluar" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->where('id_barang_keluar', $id);
        $this->db->update('barang_keluar', $data);


        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {
            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_barang_keluar' => $id_barang_keluar,
                'jumlah' => $jumlah[$i],
                'id_barang' => $id_br
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_barang_keluar', $data_detail);
    }

    public function getBarangOpt($id_cabang)
    {
        $this->load->model('Admin_model', 'admin');
        $query = $this->admin->getBarangByCabang($id_cabang);
        $option = '';
        foreach ($query as $row) {
            $option .= "<option value=" . $row['id_sa'] . ">" . $row['nama_barang'] . "</option>";
        }

        return $option;
    }

    public function getBarangOptTf()
    {
        $this->load->model('Admin_model', 'admin');
        $query = $this->admin->getBarang();
        $option = '';
        foreach ($query as $row) {
            $option .= "<option value=" . $row['id_barang'] . ">" . $row['nama_barang'] . "</option>";
        }

        return $option;
    }

    public function delete($table, $pk, $id)
    {
        $this->load->model('Admin_model', 'admin');
        $db =  $this->admin->get($table, [$pk => $id]);
        $id_cabang = $db['cabang_id'];

        return $this->db->delete($table, [$pk => $id]);
    }

    public function mutasi_stok($barang_id, $table, $operasi, $jumlah, $id_cabang = '')
    {
        $query = "UPDATE $table SET stok = stok $operasi $jumlah WHERE id_barang  = '$barang_id'";
        if ($id_cabang != '') {
            $query = "UPDATE $table SET stok = stok $operasi $jumlah WHERE id_barang  = '$barang_id' AND id_cabang = '$id_cabang'";
        }
        $this->db->query($query);
    }

    public function getMutasiCabang($id)
    {
        $query = "SELECT mt.*,s.nama_satuan, a.nama_barang, c.nama_cabang, d.nama_cabang AS trs_cbg FROM (
            SELECT b.id_barang AS brg, a.tanggal_masuk AS tgl, a.cabang_id AS cbg, 'in' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t FROM barang_masuk a,detail_barang_masuk b
            WHERE a.id_barang_masuk = b.id_barang_masuk

            UNION

            SELECT b.id_barang AS brg, a.tanggal_keluar AS tgl, a.cabang_id AS cbg, 'out' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t FROM barang_keluar a, detail_barang_keluar b 
            WHERE a.id_barang_keluar = b.id_barang_keluar 

            UNION

            SELECT b.id_barang AS brg, a.tgl_transfer AS tgl, a.asal_cabang AS cbg, 'out_tr' jenis, b.jumlah_detail AS jml, a.tujuan_cabang AS cbg_t  FROM transfer_barang a, detail_transfer_barang b
            WHERE a.id_transfer = b.id_transfer

            UNION

            SELECT b.id_barang AS brg, a.tanggal AS tgl, a.asal_cabang AS cbg, 'in_retur' jenis,b.jumlah AS jml,a.tujuan_cabang AS cbg_t FROM retur a, detail_retur b

            WHERE a.id_retur = b.id_retur
            ) mt, barang a, cabang c, cabang d,satuan s WHERE mt.brg = a.id_barang AND c.id_cabang = mt.cbg AND d.id_cabang = mt.cbg_t AND a.satuan_id = s.id_satuan AND mt.cbg = '$id' ORDER BY mt.tgl DESC";

        return $this->db->query($query)->result_array();
    }

    public function getMutasiBarang($id = '', $id_cabang = '')
    {
        if ($id == '') {
            $where = '';
        } else {
            $where = 'AND mt.brg = "' . $id . '" AND mt.cbg = "'.$id_cabang.'"';
        }

        $query = "SELECT mt.*,s.nama_satuan, a.nama_barang, c.nama_cabang, d.nama_cabang AS trs_cbg FROM (
            SELECT c.barang_id AS brg, a.id_barang_masuk AS no, a.tanggal_masuk AS tgl, a.cabang_id AS cbg, 'in' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t 
            FROM barang_masuk a
            JOIN detail_barang_masuk b ON a.id_barang_masuk = b.id_barang_masuk
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT c.barang_id AS brg, a.id_barang_keluar AS no, a.tanggal_keluar AS tgl, a.cabang_id AS cbg, 'out' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t 
            FROM barang_keluar a
            JOIN detail_barang_keluar b ON a.id_barang_keluar = b.id_barang_keluar 
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT b.id_barang AS brg, a.kode_transfer AS no, a.tgl_transfer AS tgl, a.asal_cabang AS cbg, 'out_tr' jenis, b.jumlah_detail AS jml, a.tujuan_cabang AS cbg_t  
            FROM transfer_barang a
            JOIN detail_transfer_barang b ON a.id_transfer = b.id_transfer
                UNION
            SELECT b.id_barang AS brg, a.kode_transfer AS no, a.tgl_transfer AS tgl, a.tujuan_cabang AS cbg, 'in_tr' jenis, b.jumlah_detail AS jml, a.asal_cabang AS cbg_t  
            FROM transfer_barang a 
            JOIN detail_transfer_barang b ON a.id_transfer = b.id_transfer
                UNION
            SELECT c.barang_id AS brg, a.id_stock_op_name AS no, a.tanggal_stock_op_name AS tgl, a.cabang_id AS cbg, 'stkpn' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t 
            FROM stock_op_name a
            JOIN detail_stock_op_name b ON a.id_stock_op_name = b.id_stock_op_name 
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT b.id_barang AS brg, a.id_retur AS no, a.tanggal AS tgl, a.asal_cabang AS cbg, 'in_retur' jenis,b.jumlah AS jml,a.tujuan_cabang AS cbg_t FROM retur a, detail_retur b
            WHERE a.id_retur = b.id_retur
            ) mt, barang a, cabang c, cabang d,satuan s 
            WHERE mt.brg = a.id_barang AND c.id_cabang = mt.cbg AND d.id_cabang = mt.cbg_t AND a.satuan_id = s.id_satuan $where ORDER BY a.nama_barang desc, mt.tgl asc";

        return $this->db->query($query)->result_array();
    }

    public function getMutasiBarangByDate($id = '', $id_cabang = '', $tgl_awal = null, $tgl_akhir = null)
    {
        if ($id == '') {
            $where = '';
        } else { 
            $where = "AND mt.brg = '" . $id . "' AND mt.cbg = '".$id_cabang."' AND mt.tgl BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'";
        }

        $query = "SELECT mt.*,s.nama_satuan, a.nama_barang, c.nama_cabang, d.nama_cabang AS trs_cbg FROM (
        
            SELECT c.barang_id AS brg, '' as nama_customer, a.id_barang_masuk AS no, a.tanggal_masuk AS tgl, a.cabang_id AS cbg, 'in' jenis, b.jumlah AS jml, b.harga, a.cabang_id AS cbg_t 
            FROM barang_masuk a
            JOIN detail_barang_masuk b ON a.id_barang_masuk = b.id_barang_masuk
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT c.barang_id AS brg, d.nama_customer as nama_customer, a.id_barang_keluar AS no, a.tanggal_keluar AS tgl, a.cabang_id AS cbg, 'out' jenis, b.jumlah AS jml, 0, a.cabang_id AS cbg_t 
            FROM barang_keluar a
            JOIN detail_barang_keluar b ON a.id_barang_keluar = b.id_barang_keluar 
            JOIN customer d ON a.customer_id = d.id_customer 
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT b.id_barang AS brg, '' as nama_customer, a.kode_transfer AS no, a.tgl_transfer AS tgl, a.asal_cabang AS cbg, 'out_tr' jenis, b.jumlah_detail AS jml, 0, a.tujuan_cabang AS cbg_t  
            FROM transfer_barang a
            JOIN detail_transfer_barang b ON a.id_transfer = b.id_transfer
                UNION
            SELECT b.id_barang AS brg, '' as nama_customer, a.kode_transfer AS no, a.tgl_transfer AS tgl, a.tujuan_cabang AS cbg, 'in_tr' jenis, b.jumlah_detail AS jml, 0, a.asal_cabang AS cbg_t  
            FROM transfer_barang a 
            JOIN detail_transfer_barang b ON a.id_transfer = b.id_transfer
                UNION
            SELECT c.barang_id AS brg, '' as nama_customer, a.id_stock_op_name AS no, a.tanggal_stock_op_name AS tgl, a.cabang_id AS cbg, 'stkpn' jenis, b.jumlah AS jml, 0, a.cabang_id AS cbg_t 
            FROM stock_op_name a
            JOIN detail_stock_op_name b ON a.id_stock_op_name = b.id_stock_op_name 
            JOIN sa_barang c ON b.id_barang = c.id_sa 
                UNION
            SELECT b.id_barang AS brg, '' as nama_customer, a.id_retur AS no, a.tanggal AS tgl, a.asal_cabang AS cbg, 'in_retur' jenis,b.jumlah AS jml, 0, a.tujuan_cabang AS cbg_t 
            FROM retur a, detail_retur b
            WHERE a.id_retur = b.id_retur
            
            ) mt, barang a, cabang c, cabang d,satuan s 
            WHERE mt.brg = a.id_barang AND c.id_cabang = mt.cbg AND d.id_cabang = mt.cbg_t AND a.satuan_id = s.id_satuan $where ORDER BY a.nama_barang desc, mt.tgl asc";

        return $this->db->query($query)->result_array();
    }

    public function getMutasiBarangLaporan($limit = null, $id_barang = null, $range = null)
    {
        if ($range != null) {
            $where = 'AND mt.tgl BETWEEN "' . $range['mulai'] . '" AND "' . $range['akhir'] . '"';
        } else {
            $where = '';
        }

        $query = "SELECT mt.*,s.nama_satuan, a.nama_barang, c.nama_cabang, d.nama_cabang AS trs_cbg FROM (
            SELECT b.id_barang AS brg, a.tanggal_masuk AS tgl, a.cabang_id AS cbg, 'in' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t FROM barang_masuk a,detail_barang_masuk b
            WHERE a.id_barang_masuk = b.id_barang_masuk
            UNION
            SELECT b.id_barang AS brg, a.tanggal_keluar AS tgl, a.cabang_id AS cbg, 'out' jenis, b.jumlah AS jml, a.cabang_id AS cbg_t FROM barang_keluar a, detail_barang_keluar b 
            WHERE a.id_barang_keluar = b.id_barang_keluar 
            UNION
            SELECT b.id_barang AS brg, a.tgl_transfer AS tgl, a.asal_cabang AS cbg, 'out_tr' jenis, b.jumlah_detail AS jml, a.tujuan_cabang AS cbg_t  FROM transfer_barang a, detail_transfer_barang b
            WHERE a.id_transfer = b.id_transfer
            UNION
            SELECT b.id_barang AS brg, a.tanggal AS tgl, a.asal_cabang AS cbg, 'in_retur' jenis,b.jumlah AS jml,a.tujuan_cabang AS cbg_t FROM retur a, detail_retur b
            WHERE a.id_retur = b.id_retur
            ) mt, barang a, cabang c, cabang d,satuan s WHERE mt.brg = a.id_barang AND c.id_cabang = mt.cbg AND d.id_cabang = mt.cbg_t AND a.satuan_id = s.id_satuan $where ORDER BY a.nama_barang desc, mt.tgl asc";

        return $this->db->query($query)->result_array();
    }

    public function getStokDataBarang($id)
    {
        $query = 'SELECT * FROM barang a, satuan b where a.satuan_id = b.id_satuan and a.id_barang = "' . $id . '"';
        return $this->db->query($query)->row_array();
    }

    public function _saldo_awal_barang($id_sa_barang, $tgl_awal, $tgl_akhir)
    {
        $this->load->model('Admin_model', 'admin');
        $sa = $this->admin->get('sa_barang', ['id_sa' => $id_sa_barang]);
        $mutasi_barang = $this->getMutasiBarangByDate($sa['barang_id'], $sa['cabang_id'], $tgl_awal, $tgl_akhir);

        $sisa_stok = $sa['saldo'];
        foreach ($mutasi_barang as $b) :

            switch ($b['jenis']) {
                case "in_tr":
                    $jenis = 'Mutasi Barang Masuk ke ' . $b['trs_cbg'];
                    break;
                case "out_tr":
                    $jenis = 'Mutasi Barang Keluar ke ' . $b['trs_cbg'];
                    break;
                case "in":
                    $jenis = 'Barang Masuk';
                    break;
                case "in_retur":
                    $jenis = 'Barang Retur dari ' . $b['nama_cabang'];
                    break;
                default:
                    $jenis = 'Barang Keluar';
            }

            if($b['jenis'] == 'in' || $b['jenis'] == 'in_retur') {
                 $sisa_stok += $b['jml']; 
            } else if($b['jenis'] == 'in_tr' ) { 
                 $sisa_stok += $b['jml']; 
            } else { 
                $sisa_stok -= $b['jml']; 
            } 
        endforeach;

        return $sisa_stok;
    }

    // stock op name
    

    public function getStockOpNameById($id)
    {
        $this->db->select('*');
        $this->db->join('user u', 'a.user_id = u.id_user');
        $this->db->join('cabang cb', 'a.cabang_id = cb.id_cabang');
        $this->db->from('stock_op_name a');
        $this->db->where('a.id_stock_op_name', $id);
        return $this->db->get()->row_array();
    }

    public function getDetailStockOpNameById($id)
    {
        $this->db->select('b.*,d.barang_id,c.nama_barang,s.nama_satuan');
        $this->db->from('stock_op_name a');
        $this->db->join('detail_stock_op_name b', 'a.id_stock_op_name = b.id_stock_op_name');
        $this->db->join('sa_barang d', 'b.id_barang = d.id_sa');
        $this->db->join('barang c', 'd.barang_id = c.id_barang');
        $this->db->join('satuan s', 'c.satuan_id = s.id_satuan');
        $this->db->where('a.id_stock_op_name', $id);
        return $this->db->get()->result_array();
    }
    
    public function insertStockOpName()
    {
        $id_stock_op_name = $this->input->post('id_stock_op_name', true);
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_keluar', true);

        $data = [
            "id_stock_op_name" => $id_stock_op_name,
            "user_id" => $this->input->post('user_id'),
            "tanggal_stock_op_name" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->insert('stock_op_name', $data);

        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {
            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_stock_op_name' => $id_stock_op_name,
                'jumlah' => $jumlah[$i],
                'id_barang' => $id_br,
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_stock_op_name', $data_detail);
    }

    public function editStockOpName($id)
    {

        $this->db->delete('detail_stock_op_name', ['id_stock_op_name' => $id]);

        $id_stock_op_name = $id;
        $id_cabang = $this->input->post('cabang_id', true);
        $tanggal = $this->input->post('tanggal_keluar', true);

        $data = [
            "user_id" => $this->input->post('user_id'),
            "tanggal_stock_op_name" => $tanggal,
            "cabang_id" => $id_cabang
        ];

        $this->db->where('id_stock_op_name', $id);
        $this->db->update('stock_op_name', $data);


        $id_barang = $this->input->post('id_barang', true);
        $jumlah = $this->input->post('jumlah', true);
        $data_detail = array();
        $i = 0;
        foreach ($id_barang as $id_br) {
            $this->inputBarangCabang($id_br, $id_cabang);

            array_push($data_detail, array(
                'id_stock_op_name' => $id_stock_op_name,
                'jumlah' => $jumlah[$i],
                'id_barang' => $id_br
            ));
            $i++;
        }

        return $this->db->insert_batch('detail_stock_op_name', $data_detail);
    }

}
