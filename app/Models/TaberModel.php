<?php

namespace App\Models;

use CodeIgniter\Model;

class TaberModel extends Model
{
    protected $table = 'grups';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama_grup',
        'tujuan',
        'target_tabungan',
        'jumlah_setoran',
        'periode_setoran',
        'awal_menabung'
    ];

    public function getgrname($id_grup)
    {
        $db = \Config\Database::connect();
        $sql = "SELECT nama_grup FROM groups WHERE id = ?";
        $query = $db->query($sql, [$id_grup]);
        if ($query->getNumRows() > 0) {
            return $query->getRow('nama_grup');
        } else {
            return null;
        }
    }

    public function getgrcount($id_user)
    {
        // cek sisa kuota grup
        $db = \Config\Database::connect();
        $sql = "SELECT grup1, grup2, grup3 FROM users WHERE id = ?";
        $query = $db->query($sql, [$id_user]);
        $kuota_grup = 3;
        if ($query->getRow('grup1') != null) {
            $kuota_grup = $kuota_grup - 1;
        }
        if ($query->getRow('grup2') != null) {
            $kuota_grup = $kuota_grup - 1;
        }
        if ($query->getRow('grup3') != null) {
            $kuota_grup = $kuota_grup - 1;
        }
        return $kuota_grup;
    }

    public function getprogresgrup($id_grup)
    {
        $db = \Config\Database::connect();
        // cek target tabungan grup
        $quertrg = "SELECT target_tabungan FROM groups WHERE id = ?";
        $trgtab = $db->query($quertrg, [$id_grup]);
        $targettabungan = $trgtab->getRow('target_tabungan');

        // Ambil semua data anggota dan cek total saldo di grup ini
        $sql = "SELECT id,username, CASE WHEN grup1 = ? THEN (SELECT saldo_grup1) WHEN grup2 = ? THEN (SELECT saldo_grup2) WHEN grup3 = ? THEN (SELECT saldo_grup3) ELSE 'salah' END AS saldoingrup FROM users WHERE ? IN (grup1, grup2, grup3) ORDER BY saldoingrup ASC";
        $saldoanggota = $db->query($sql, [$id_grup, $id_grup, $id_grup, $id_grup])->getResultArray();
        $saldototalgrup = 0;
        foreach ($saldoanggota as $k) {
            $saldototalgrup += $k['saldoingrup'];
        }
        $progresgrup = $saldototalgrup / ($targettabungan * count($saldoanggota));
        return $progresgrup;
    }

    public function getid_grup($order_id)
    {
        // dd($order_id);
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->select('id_grup');
        $query = $builder->getWhere(['order_id' => $order_id]);
        $query = $query->getRow('id_grup');
        // dd($query);
        return $query;
    }

    public function getstatus_code($order_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->select('status_code');
        $query = $builder->getWhere(['order_id' => $order_id]);
        $query = $query->getRow('status_code');
        // dd($query);
        return $query;
    }

    public function updatesaldo($id_user, $id_grup, $nominal)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('grup1, grup2, grup3, saldo_grup1, saldo_grup2, saldo_grup3');
        $query = $builder->getWhere(['id' => $id_user]);
        // dd($id_grup);
        // dd($query->getRow('grup1'));
        if ($query->getRow('grup1') == $id_grup) {
            // dd('grup1');
            $sql = 'UPDATE users SET saldo_grup1 = saldo_grup1 + ? where id = ?';
            $db->query($sql, [$nominal, $id_user]);
        } else if ($query->getRow('grup2') == $id_grup) {
            // dd('grup2');
            $sql = 'UPDATE users SET saldo_grup2 = saldo_grup2 + ? where id = ?';
            $db->query($sql, [$nominal, $id_user]);
        } else if ($query->getRow('grup3') == $id_grup) {
            // dd('grup3');
            $sql = 'UPDATE users SET saldo_grup3 = saldo_grup3 + ? where id = ?';
            $db->query($sql, [$nominal, $id_user]);
        }
    }
}
