<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id = null)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }

    public function getBarang()
    {
        $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->order_by('id_barang');
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangByCabang($id_cabang = null)
    {
        $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('sa_barang sb', 'b.id_barang = sb.barang_id');
        $this->db->order_by('id_barang');

        $this->db->where('sb.cabang_id', $id_cabang);
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangMasuk($cabang = null, $limit = null)
    {
        $this->db->select('*');
        $this->db->join('barang_masuk bmsk', 'bmsk.id_barang_masuk = bm.id_barang_masuk');
        $this->db->join('user u', 'bmsk.user_id = u.id_user');
        $this->db->join('supplier sp', 'bmsk.supplier_id = sp.id_supplier');
        $this->db->join('sa_barang sa', 'bm.id_barang = sa.id_sa');
        $this->db->join('barang b', 'sa.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmsk.cabang_id = c.id_cabang');


        if ($cabang != null) {
            $this->db->where('bmsk.cabang_id', $cabang);
        }

        if ($limit != null) {
            $this->db->limit($limit);
        }

        // if ($id_barang != null) {
        //     $this->db->where('b.id_barang', $id_barang);
        // }

        // if ($range != null) {
        //     $this->db->where('bmsk.tanggal_masuk' . ' >=', $range['mulai']);
        //     $this->db->where('bmsk.tanggal_masuk' . ' <=', $range['akhir']);
        // }

        $this->db->order_by('bmsk.id_barang_masuk', 'DESC');
        $this->db->group_by('bmsk.id_barang_masuk');
        return $this->db->get('detail_barang_masuk bm')->result_array();
    }

    public function getBarangKeluar($cabang = null, $limit = null)
    {
        $this->db->select('*');
        $this->db->join('barang_keluar bmk', 'bmk.id_barang_keluar = bk.id_barang_keluar');
        $this->db->join('user u', 'bmk.user_id = u.id_user');
        $this->db->join('sa_barang sa', 'bk.id_barang = sa.id_sa');
        $this->db->join('barang b', 'sa.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmk.cabang_id = c.id_cabang');
        if ($cabang != null) {
            $this->db->where('bmk.cabang_id', $cabang);
        }
        if ($limit != null) {
            $this->db->limit($limit);
        }
        // if ($id_barang != null) {
        //     $this->db->where('id_barang', $id_barang);
        // }
        // if ($range != null) {
        //     $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
        //     $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        // }

        $this->db->order_by('bk.id_barang_keluar', 'DESC');
        $this->db->group_by('bk.id_barang_keluar');
        return $this->db->get('detail_barang_keluar bk')->result_array();
    }

    public function getRetur($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*,c.nama_cabang as asal_cabang, cc.nama_cabang as tujuan_cabang');
        $this->db->join('retur bmk', 'bmk.id_retur = bk.id_retur');
        $this->db->join('user u', 'bmk.id_user = u.id_user');
        $this->db->join('barang b', 'bk.id_barang = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmk.asal_cabang = c.id_cabang');
        $this->db->join('cabang cc', 'bmk.tujuan_cabang = cc.id_cabang');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null) {
            $this->db->where('tanggal' . ' >=', $range['mulai']);
            $this->db->where('tanggal' . ' <=', $range['akhir']);
        }

        $this->db->order_by('bk.id_retur', 'DESC');
        $this->db->group_by('bk.id_retur');
        return $this->db->get('detail_retur bk')->result_array();
    }

    public function getBarangMasukByCabang($cabang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('barang_masuk bmsk', 'bmsk.id_barang_masuk = bm.id_barang_masuk');
        $this->db->join('user u', 'bmsk.user_id = u.id_user');
        $this->db->join('sa_barang d', 'bm.id_barang = d.id_sa');
        $this->db->join('supplier sp', 'bmsk.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'd.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmsk.cabang_id = c.id_cabang');

        if ($cabang != null) {
            $this->db->where('bmsk.cabang_id', $cabang);
        }

        if ($range != null) {
            $this->db->where('bmsk.tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('bmsk.tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('bmsk.id_barang_masuk', 'DESC');
        $this->db->group_by('bmsk.id_barang_masuk');
        return $this->db->get('detail_barang_masuk bm')->result_array();
    }

    public function getBarangKeluarByCabang($cabang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('barang_keluar bmk', 'bmk.id_barang_keluar = bk.id_barang_keluar');
        $this->db->join('user u', 'bmk.user_id = u.id_user');
        $this->db->join('customer e', 'bmk.customer_id = e.id_customer');
        $this->db->join('sa_barang d', 'bk.id_barang = d.id_sa');
        $this->db->join('barang b', 'd.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmk.cabang_id = c.id_cabang');

        if ($cabang != null) {
            $this->db->where('bmk.cabang_id', $cabang);
        }

        if ($range != null) {
            $this->db->where('bmk.tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('bmk.tanggal_keluar' . ' <=', $range['akhir']);
        }
    
        $this->db->order_by('bk.id_barang_keluar', 'DESC');
        $this->db->group_by('bk.id_barang_keluar');
        return $this->db->get('detail_barang_keluar bk')->result_array();
    }

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null) {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $field)
    {
        $this->db->select_sum($field);
        return $this->db->get($table)->row_array()[$field];
    }

    public function min($table, $field, $min)
    {
        $field = $field . ' <=';
        $this->db->where($field, $min);
        return $this->db->get($table)->result_array();
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'T-BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'T-BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id, $id_cabang)
    {
        $this->db->join('cabang_detail a', 'b.id_barang=a.id_barang');
        $this->db->join('satuan s', 's.id_satuan=b.satuan_id');
        $data = $this->db->get_where('barang b', ['b.id_barang' => $id, 'a.id_cabang' => $id_cabang])->row_array();
        if ($data == null) {
            $this->db->select('s.nama_satuan,0 stok');
            $this->db->join('satuan s', 's.id_satuan=b.satuan_id');
            $data = $this->db->get_where('barang b', ['b.id_barang' => $id])->row_array();
        }
        return $data;
    }

    public function cekStokTransfer($id, $asal_cabang, $tujuan_cabang)
    {
        $query = "SELECT * FROM cabang_detail WHERE id_barang = '$id' AND id_cabang = '$asal_cabang'";
        $q1 = $this->db->query($query)->row_array();
        $query2 = "SELECT * FROM cabang_detail WHERE id_barang = '$id' AND id_cabang = '$tujuan_cabang'";
        $q2 = $this->db->query($query2)->row_array();

        if ($q1 == null) {
            $q1 = ['stok' => 0];
        }

        if ($q2 == null) {
            $q2 = ['stok' => 0];
        }

        $data = [
            'stok_awal' => $q1['stok'],
            'stok_tujuan' => $q2['stok']
        ];

        return $data;
    }

    public function getDataCabang()
    {
        $this->db->select('user.nama, cabang.*');
        $this->db->join('user', 'cabang.id_user = user.id_user');
        return $this->db->get('cabang')->result_array();
    }

    public function getSaBarangById($id_barang)
    {
        $this->db->where('a.barang_id', $id_barang);
        return $this->db->get('sa_barang a')->result_array();
    }

    public function getDataCabangByUser($id)
    {
        $this->db->select('user.nama, cabang.*');
        $this->db->join('user', 'cabang.id_cabang = user.cabang_id');
        $this->db->where_in('user.cabang_id', $id);
        return $this->db->get('cabang')->row_array();
    }

    public function getStockOpNameByCabang($cabang = null, $range = null)
    {
        $this->db->select('*');
        $this->db->join('stock_op_name bmk', 'bmk.id_stock_op_name = bk.id_stock_op_name');
        $this->db->join('user u', 'bmk.user_id = u.id_user');
        $this->db->join('sa_barang d', 'bk.id_barang = d.id_sa');
        $this->db->join('barang b', 'd.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->join('cabang c', 'bmk.cabang_id = c.id_cabang');

        if ($cabang != null) {
            $this->db->where('bmk.cabang_id', $cabang);
        }

        if ($range != null) {
            $this->db->where('bmk.tanggal_stock_op_name' . ' >=', $range['mulai']);
            $this->db->where('bmk.tanggal_stock_op_name' . ' <=', $range['akhir']);
        }
    
        $this->db->order_by('bk.id_stock_op_name', 'DESC');
        $this->db->group_by('bk.id_stock_op_name');
        return $this->db->get('detail_stock_op_name bk')->result_array();
    }
}
