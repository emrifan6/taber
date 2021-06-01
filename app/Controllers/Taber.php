<?php

namespace App\Controllers;

use App\Models\TaberModel;
use App\Models\Midtrans;
use CodeIgniter\Database\Query;
use CodeIgniter\HTTP\Request;

use function PHPUnit\Framework\isNull;

class Taber extends BaseController
{
	protected $tabermodel;
	public function __construct()
	{
		$this->tabermodel  = new TaberModel();
	}

	public function index()
	{
		// Awal Cek permintaan gabung grup
		$user_id = user_id();
		$db = \Config\Database::connect();
		$sql = "SELECT u.username, g.nama_grup, jg.id FROM users as u 
		JOIN joingrup as jg ON jg.id_user = u.id AND jg.status != 'rejected' AND jg.status != 'accepted' 
		JOIN groups as g ON jg.id_grup = g.id AND g.id_ketua = ?";
		$query = $db->query($sql, [$user_id]);
		if ($query->getNumRows() > 0) {
			$join_req = $query->getresult();
		} else {
			$join_req = null;
		}
		// Akhir Cek permintaan gabung grup

		// AWAL CEK TAGIAHAN
		// SELECT *, TIMESTAMPDIFF(day, DATE(awal_menabung), CURDATE()) AS DateDiff FROM groups WHERE id = ?
		$sql = "SELECT grup1,grup2,grup3 FROM users WHERE id = ?";
		$grupquery = $db->query($sql, [$user_id]);
		$grup1 = $grupquery->getRow('grup1');
		$grup2 = $grupquery->getRow('grup2');
		$grup3 = $grupquery->getRow('grup3');
		//dd($grup2);
		//query cek tagihan
		// SELECT IF (akhir_menabung <= CURRENT_DATE(), u.saldo_grup1 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), DATE(g.akhir_menabung)) div g.periode_setoran) * g.jumlah_setoran), u.saldo_grup1 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), CURDATE()) div g.periode_setoran) * g.jumlah_setoran) ) AS tagihan FROM groups AS g, users AS u WHERE g.id = 24 AND u.id = 2
		// $sqltagihan = "SELECT *, TIMESTAMPDIFF(day, DATE(awal_menabung), CURDATE()) AS DateDiff FROM groups WHERE id = ?";

		if ($grup1 != null) {
			$querytagihan = "SELECT g.nama_grup, IF (akhir_menabung <= CURRENT_DATE(), 
			u.saldo_grup1 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), DATE(g.akhir_menabung)) div g.periode_setoran) * g.jumlah_setoran), 
			u.saldo_grup1 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), CURDATE()) div g.periode_setoran) * g.jumlah_setoran) 
			) AS tagihan FROM groups AS g, users AS u WHERE g.id = ? AND u.id = ?";
			$tmp = $db->query($querytagihan, [$grup1, $user_id]);
			$namagrup1 = $tmp->getRow('nama_grup');
			$tmptagihan =  $tmp->getRow('tagihan');
			$tagihan1 = intval($tmptagihan);
			// cek tagihan minus atau plus
			if ($tagihan1 >= 0) {
				$tagihan1 = null;
			}
			// dd($namagrup1);
		} else {
			$tagihan1 = null;
			$namagrup1 = null;
		}
		if ($grup2 != null) {
			$querytagihan = "SELECT g.nama_grup, IF (akhir_menabung <= CURRENT_DATE(), 
			u.saldo_grup2 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), DATE(g.akhir_menabung)) div g.periode_setoran) * g.jumlah_setoran), 
			u.saldo_grup2 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), CURDATE()) div g.periode_setoran) * g.jumlah_setoran) 
			) AS tagihan FROM groups AS g, users AS u WHERE g.id = ? AND u.id = ?";
			$tmp = $db->query($querytagihan, [$grup2, $user_id]);
			$namagrup2 = $tmp->getRow('nama_grup');
			$tmptagihan =  $tmp->getRow('tagihan');
			$tagihan2 = intval($tmptagihan);
			// cek tagihan minus atau plus
			if ($tagihan2 >= 0) {
				$tagihan2 = null;
			}
		} else {
			$tagihan2 = null;
			$namagrup2 = null;
		}
		if ($grup3 != null) {
			$querytagihan = "SELECT g.nama_grup, IF (akhir_menabung <= CURRENT_DATE(), 
			u.saldo_grup3 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), DATE(g.akhir_menabung)) div g.periode_setoran) * g.jumlah_setoran), 
			u.saldo_grup3 - ((TIMESTAMPDIFF(day, DATE(g.awal_menabung), CURDATE()) div g.periode_setoran) * g.jumlah_setoran) 
			) AS tagihan FROM groups AS g, users AS u WHERE g.id = ? AND u.id = ?";
			$tmp = $db->query($querytagihan, [$grup3, $user_id]);
			$namagrup3 = $tmp->getRow('nama_grup');
			$tmptagihan =  $tmp->getRow('tagihan');
			$tagihan3 = intval($tmptagihan);
			// cek tagihan minus atau plus
			if ($tagihan3 >= 0) {
				$tagihan3 = null;
			}
		} else {
			$tagihan3 = null;
			$namagrup3 = null;
		}

		$datatagihan = array();

		if ($tagihan1 != null) {
			$tagihangrup = array('tagihan' => $tagihan1, 'nama_grup' => $namagrup1, 'id_grup' => $grup1);
			array_push($datatagihan, $tagihangrup);
		}
		if ($tagihan2 != null) {
			$tagihangrup = array('tagihan' => $tagihan2, 'nama_grup' => $namagrup2, 'id_grup' => $grup2);
			array_push($datatagihan, $tagihangrup);
		}
		if ($tagihan3 != null) {
			$tagihangrup = array('tagihan' => $tagihan3, 'nama_grup' => $namagrup3, 'id_grup' => $grup3);
			array_push($datatagihan, $tagihangrup);
		}

		// dd($datatagihan);
		// AKHIR CEK TAGIHAN
		$data = [
			'title' => 'Tabungan Bersama',
			'join_req' => $join_req,
			'tagihan' => $datatagihan
		];

		// dd($data);

		return view('taber/dashboard', $data);
	}
	public function grup()
	{

		$user_id = user_id();
		$db = \Config\Database::connect();
		$sql = "SELECT grup1,grup2,grup3 FROM users WHERE id = ?";
		$id_grupquery = $db->query($sql, [$user_id]);
		$id_grups = $id_grupquery->getRow();
		// dd($id_grup);
		foreach ($id_grupquery->getResultArray() as $row) {
			$grups = [
				$row['grup1'],
				$row['grup2'],
				$row['grup3']
			];
		}
		if ($grups[0] == null && $grups[1] == null && $grups[2] == null) {
			$datagrup = null;
			// dd($datagrup);
		} else {
			$x = 1;
			foreach ($grups as $k) {
				$sql = "SELECT * FROM groups WHERE id = ?";
				$grupsquery = $db->query($sql, [$k]);
				$tmp =  $grupsquery->getRow();
				if (!empty($tmp)) {
					$datagrup[$x] = $tmp;
					$x++;
				}
			}
		}

		$data = [
			'title' => 'Grup',
			'grups' => $datagrup
		];
		// dd($data);

		return view('taber/grup', $data);
	}
	public function create()
	{

		$data = [
			'title' => 'Buat Grup'
		];
		// cek sisa kuota grup menabung
		$user_id = user_id();
		$kuota = $this->tabermodel->getgrcount($user_id);
		if ($kuota <= 0) {
			session()->setFlashdata('pesan', 'Gagal buat grup,saat ini setiap user dibatasi maximal mengikuti 3 grup menabung');
			return redirect()->to('/taber/grup');
		} else {
			return view('taber/create', $data);
		}
	}
	public function savegroup()
	{
		$targettabungan = $this->request->getVar('targettabungan');
		// dd($targettabungan);
		$jumlahsetoran = $this->request->getVar('jmlsetoran');
		$periodesetoran = $this->request->getVar('periodesetoran');
		$jangka_waktu = ($targettabungan / $jumlahsetoran) * $periodesetoran;
		$awal_menabung = date_create($this->request->getVar('mulaimenabung'));
		$akhirmenabung = date_add($awal_menabung, date_interval_create_from_date_string($jangka_waktu . " days"));
		$kode_grup = bin2hex(random_bytes(3));
		$user_id = user_id();

		$data = [
			'id'  => null,
			'nama_grup'  => $this->request->getVar('namagrup'),
			'id_ketua'  => $user_id,
			'tujuan'  => $this->request->getVar('tujuangrup'),
			'target_tabungan'  => $this->request->getVar('targettabungan'),
			'jumlah_setoran'  => $this->request->getVar('jmlsetoran'),
			'periode_setoran'  => $this->request->getVar('periodesetoran'),
			'jangka_waktu'  => $jangka_waktu,
			'awal_menabung'  => $this->request->getVar('mulaimenabung'),
			'akhir_menabung'  => date_format($akhirmenabung, "Y-m-d"),
			'kode_grup'  => $kode_grup,
			'created'  => date("Y-m-d")

		];

		$db = \Config\Database::connect();
		$query = $db->table('groups')->insert($data);

		$sql = "SELECT id FROM groups WHERE kode_grup = ?";
		$id_grupquery = $db->query($sql, [$kode_grup]);
		$id_grup = $id_grupquery->getRow('id');

		for ($i = 1; $i <= 3; $i++) {
			$check = 'SELECT grup' . $i . ' FROM users WHERE id = ' . $user_id;
			$query = $db->query($check);
			$row   = $query->getRow();
			$select = 'grup' . $i;
			$hasil = $row->$select;
			if (is_null($hasil)) {
				$sql = 'UPDATE users SET grup' . $i . '=?' . 'WHERE id = ' . $user_id;
				$db->query($sql, [$id_grup]);
				session()->setFlashdata('pesan', 'Pembuatan grup menabung baru berhasil');
				break;
			}
		}
		return redirect()->to('/taber/grup');
	}

	public function detailgrup($kodegrup)
	{
		$db = \Config\Database::connect();
		$sql = 'SELECT * FROM groups WHERE kode_grup = ?';
		$datagrup = $db->query($sql, [$kodegrup])->getRow();
		$datagrup = json_decode(json_encode($datagrup), true);
		$id_grup = $datagrup['id'];


		$namaketua =  $db->query('SELECT username FROM users WHERE id = ?', [$datagrup['id_ketua']])->getRow();
		$namaketua = json_decode(json_encode($namaketua), true);
		// dd($namaketua);

		// Ambil data anggota
		$sql = "SELECT id,username, CASE WHEN grup1 = ? THEN (SELECT saldo_grup1) WHEN grup2 = ? THEN (SELECT saldo_grup2) WHEN grup3 = ? THEN (SELECT saldo_grup3) ELSE 'salah' END AS saldoingrup FROM users WHERE ? IN (grup1, grup2, grup3) ORDER BY saldoingrup ASC";
		$saldoanggota = $db->query($sql, [$id_grup, $id_grup, $id_grup, $id_grup])->getResultArray();
		$saldototalgrup = 0;
		foreach ($saldoanggota as $k) {
			$saldototalgrup += $k['saldoingrup'];
		}

		// $progresgrup = $saldototalgrup / ($datagrup['target_tabungan'] * count($saldoanggota));
		// dd($progresgrup); 
		// dd($saldoanggota);

		$progresgrup = $this->tabermodel->getprogresgrup($id_grup);

		$data = [
			'title' => 'Buat Grup',
			'datagrup' => $datagrup,
			'namaketua' => $namaketua,
			'saldoanggota' => $saldoanggota,
			'saldototalgrup' => $saldototalgrup,
			'progresgrup' => $progresgrup
		];

		// dd($data);

		return view('taber/detailgrup', $data);
	}

	public function join()
	{
		// cek sisa kuota grup menabung
		$user_id = user_id();
		$kuota = $this->tabermodel->getgrcount($user_id);
		if ($kuota <= 0) {
			session()->setFlashdata('pesan', 'Gagal buat grup,saat ini setiap user dibatasi maximal mengikuti 3 grup menabung');
			return redirect()->to('/taber/grup');
		} else {
			// cek kode grup apakah terdaftar di database
			$kodegrup = $this->request->getVar('kodegrup');
			$db = \Config\Database::connect();
			$sql = 'SELECT id,kode_grup FROM groups WHERE kode_grup = ?';
			$cekgrup = $db->query($sql, [$kodegrup]);
			$idtmp = $cekgrup->getRow('id');
			if ($cekgrup->getNumRows() <= 0) {
				return redirect()->to('/taber/grup');
			} else {
				$sql = 'SELECT * FROM joingrup 
				WHERE id_grup = ? 
				AND id_user = ?
				ORDER BY timestamp DESC LIMIT 1';
				$cekstatus = $db->query($sql, [$idtmp, user_id()]);
				$status = $cekstatus->getRow('status');
				if ($status != 'waiting') {
					$data = [
						'id' => null,
						'id_user' => user_id(),
						'id_grup' => $idtmp,
						'status' => 'waiting',
						'timestamp' => null
					];
					$query = $db->table('joingrup')->insert($data);
					session()->setFlashdata('pesan', 'Permintaan gabung GRUP telah terkirim');
				}
				if ($status == 'waiting') {
					session()->setFlashdata('pesan', 'Permintaan gabung GRUP menunggu persetujuan');
				}
			}
			return redirect()->to('/taber/grup');
		}
	}

	public function terima($id)
	{

		$db = \Config\Database::connect();
		$sql = "SELECT u.username, g.nama_grup, jg.id_user, jg.id_grup FROM users as u 
		JOIN  joingrup as jg ON jg.id = ?
		JOIN groups as g ON jg.id_grup = g.id  AND u.id = jg.id_user";
		$query = $db->query($sql, [$id]);
		if ($query->getNumRows() > 0) {
			$id_user = $query->getRow('id_user');
			$id_grup = $query->getRow('id_grup');
			$username = $query->getRow('username');
			$grupname = $query->getRow('grupname');
			for ($i = 1; $i <= 3; $i++) {
				$check = 'SELECT grup' . $i . ' FROM users WHERE id = ' . $id_user;
				$query = $db->query($check);
				$row   = $query->getRow();
				$select = 'grup' . $i;
				$hasil = $row->$select;
				if (is_null($hasil)) {
					$sql = 'UPDATE users SET grup' . $i . '=?' . 'WHERE id = ' . $id_user;
					$db->query($sql, [$id_grup]);
					break;
				}
			}
			$sql = "DELETE FROM joingrup WHERE id = ?";
			$query = $db->query($sql, [$id]);
			session()->setFlashdata('pesan', 'Member baru ' . $username . ' berhasil ditambahkan ke grup ' . $grupname);
		} else {
			session()->setFlashdata('pesan', 'Kuota grup member sudah penuh');
		}
		// Akhir Cek permintaan gabung grup
		$data = [
			'title' => 'Tabungan Bersama'
		];

		return redirect()->to('/taber');
		//Akhir fungsi penerimaan gabung grup
	}

	public function tolak($id)
	{
		$db = \Config\Database::connect();
		$sql = "UPDATE joingrup SET status = 'rejected' WHERE id = ?";
		$query = $db->query($sql, [$id]);
		session()->setFlashdata('pesan', 'Penolakan masuk ke grup berhasil diproses');
		return redirect()->to('/taber');
	}

	public function bayar($nominal, $id_grup)
	{
		// dd($nominal);
	}
}