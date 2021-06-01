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
}
