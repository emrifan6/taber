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

    public function getsaldo()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('grup1, grup2, grup3, saldo_grup1, saldo_grup2, saldo_grup3, saldo');
        $query = $builder->getWhere(['id' => user_id()]);
        // dd($query->getResult());
        return $query->getFirstRow();
    }

    public function getgrmember($grup_id)
    {
        // mendapatkan semua id dari member grup
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('id');
        $where = "grup1 = $grup_id OR grup2 = $grup_id OR grup3 = $grup_id ";
        $query = $builder->where($where);
        $query = $builder->get();
        return $query->getResult('array');
    }

    public function setgrinactive($grup_id)
    {
        $db = \Config\Database::connect();
        $sql = 'UPDATE groups SET status = "inactive" where id = ?';
        $db->query($sql, [$grup_id]);
    }

    public function setpindahsaldo($grup_id)
    {
        $db = \Config\Database::connect();
        $sql = 'UPDATE users SET saldo = 
        IF(grup1 = ?, saldo+saldo_grup1, 
           IF(grup2 = ?, saldo+saldo_grup2, 
              IF(grup3 = ?, saldo+saldo_grup3, saldo))),
        saldo_grup1 = IF(grup1 = ?, 0, saldo_grup1),
        saldo_grup2 = IF(grup2 = ?, 0, saldo_grup2),
        saldo_grup3 = IF(grup3 = ?, 0, saldo_grup3),
        grup1 = IF(grup1 = ?, null, grup1),
        grup2 = IF(grup2 = ?, null, grup2),
        grup3 = IF(grup3 = ?, null, grup3)
        WHERE id = ?';
        $db->query($sql, [$grup_id, $grup_id, $grup_id, $grup_id, $grup_id, $grup_id, $grup_id, $grup_id, $grup_id, user_id()]);
    }

    public function getcheckketua($grup_id)
    {
        $db = \Config\Database::connect();
        $sql = 'SELECT id_ketua FROM groups WHERE id = ?';
        $ketua = $db->query($sql, [$grup_id]);
        $ketua = $ketua->getRow('id_ketua');
        if ($ketua == user_id()) {
            return true;
        } else {
            return false;
        }
    }

    public function setgrketua($grup_id)
    {
        $db = \Config\Database::connect();
        $sql = 'UPDATE groups SET id_ketua = ? WHERE id = ?';
        $anggota = $this->getgrmember($grup_id);
        foreach ($anggota as $ag) {
            if ($ag['id'] != user_id()) {
                $db->query($sql, [$ag['id'], $grup_id]);
                break;
            }
        }
    }

    public function getbanklist()
    {
        // mendapatkan semua id dari member grup
        $db = \Config\Database::connect();
        $builder = $db->table('bank_list');
        $builder->select('id, bank_code, bank_name');
        $builder->orderBy('bank_code', 'ASC');
        $query = $builder->get();
        // dd($query->getResult('array'));
        return $query->getResult('array');
    }

    public function getpayoutnominalpending()
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $sql = 'SELECT SUM(nominal) AS nominal_pending FROM payout_transactions WHERE id_user = ? AND status = "pending"';
        $nominal_pending = $db->query($sql, [$user_id]);
        $nominal_pending = $nominal_pending->getRow('nominal_pending');
        return $nominal_pending;
        // dd($nominal_pending);
    }

    public function getchecknomialpayout($nominal)
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('saldo');
        // $where = "id = $user_id";
        $query = $builder->where('id', $user_id);
        $query = $builder->get();
        $saldo = $query->getRow('saldo');
        $nominal_pending = $this->getpayoutnominalpending();
        $saldo_pending = $saldo - $nominal_pending;
        if ((int)$nominal <= (int)$saldo_pending) {
            return true;
        } else {
            return false;
        }
    }

    public function getbankcode($bank_name)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('bank_list');
        $builder->select('bank_code, bank_name');
        $query = $builder->where('bank_name', $bank_name);
        $query = $builder->get();
        $query = $query->getResult();
        dd($query);
        return $query;
    }

    public function setpayouttransactions($nominal, $bank_name, $rekening, $nama)
    {
        // $bank_code = $this->getbankcode($bank_name);
        // dd($bank_code);
        $user_id = user_id();
        $db = \Config\Database::connect();
        $builder = $db->table('payout_transactions');
        $data = [
            'id_user' => $user_id,
            'bank_code'  => $bank_name,
            'rekening'  => $rekening,
            'nominal' => $nominal,
            'nama' => strtoupper($nama),
            'status' => 'pending',
            'transaction_time' => date("Y-m-d H:i:s")
        ];
        $builder->insert($data);
    }

    public function getpayouttransactions()
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $sql = 'SELECT * FROM payout_transactions WHERE id_user = ? ORDER BY transaction_time DESC LIMIT 10';
        $query = $db->query($sql, [$user_id]);
        $query = $query->getResult('array');
        // dd($query);
        return $query;
    }

    public function getcheckadmin()
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $sql = 'SELECT hakakses FROM users WHERE id = ?';
        $query = $db->query($sql, [$user_id]);
        $query = $query->getRow('hakakses');
        if ($query = 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function getadminpayoutrequet()
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $sql = 'SELECT * FROM payout_transactions WHERE status = "pending"  ORDER BY transaction_time';
        $query = $db->query($sql);
        $query = $query->getResult('array');
        // dd($query);
        return $query;
    }

    public function setsaldo($payout_nominal)
    {
        $user_id = user_id();
        $db = \Config\Database::connect();
        $sql = 'UPDATE users SET saldo = saldo - ? where id = ?';
        $query = $db->query($sql, [$payout_nominal, $user_id]);
    }

    public function setadminbayar($payout_id)
    {
        $time_now = date("Y-m-d H:i:s");
        $db = \Config\Database::connect();
        $sql = 'UPDATE payout_transactions SET status = "settlement", payout_time = ? where id = ?';
        $db->query($sql, [$time_now, $payout_id]);
    }
    public function setadmintolak($payout_id)
    {
        $time_now = date("Y-m-d H:i:s");
        $db = \Config\Database::connect();
        $sql = 'UPDATE payout_transactions SET status = "failed", payout_time = ? where id = ?';
        $db->query($sql, [$time_now, $payout_id]);
    }
    public function getpayoutnominal($payout_id)
    {
        $db = \Config\Database::connect();
        $sql = 'SELECT nominal FROM payout_transactions WHERE id = ?';
        $query = $db->query($sql, [$payout_id]);
        $query = $query->getRow('nominal');
        return $query;
    }
}
