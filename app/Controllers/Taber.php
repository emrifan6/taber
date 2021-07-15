<?php

namespace App\Controllers;

use App\Models\TaberModel;
use App\Models\Midtrans;
use CodeIgniter\Database\Query;
use CodeIgniter\HTTP\Request;

use function PHPUnit\Framework\isNull;

class Taber extends BaseController
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var HTTP\IncomingRequest
	 */
	protected $request;

	protected $tabermodel;
	public function __construct()
	{
		$this->tabermodel  = new TaberModel();
		$this->email = \Config\Services::email();
	}

	// FUNGSI KIRIM EMAIL
	public function sendEmail($to, $title, $message)
	{
		$this->email->setFrom('emrifan20@gmail.com', 'Tabungan-Bersama');
		$this->email->setTo($to);
		$this->email->setSubject($title);
		$this->email->setMessage($message);
		if (!$this->email->send()) {
			return false;
		} else {
			return true;
		}
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
		// dd($grup3);
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
			// dd($tagihan2);
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
			// dd($tagihan3);
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



		// AWAL CEK TRANSAKSI

		$transsql = "SELECT * FROM transactions WHERE id_user = ? ORDER BY transaction_time DESC LIMIT 10";
		$transquery = $db->query($transsql, [$user_id]);
		if ($transquery->getNumRows() > 0) {
			$data_trans = $transquery->getresult();
			// MENGGANTI KOLOM id_grup MENJADI NAMA GRUP
			$arr_data_trans = array();
			foreach ($data_trans as $dt) {
				$tmp = json_decode(json_encode($dt), true);
				$tmp['id_grup'] = $this->tabermodel->getgrname($tmp['id_grup']);
				array_push($arr_data_trans, $tmp);
				// dd($tmp);
			}
			$data_trans = $arr_data_trans;
			// dd($arr_data_trans);
		} else {
			$data_trans = null;
		}
		// dd($data_trans);


		// AKHIR CEK TRANSAKSI


		// AWAL CEK DATA API MIDTRANS
		// cek apakah ada transaksi
		$sql = "SELECT * FROM transactions WHERE id_user = ? AND status_code != 200 LIMIT 10";
		$querytransc = $db->query($sql, [$user_id]);
		if ($querytransc->getNumRows() > 0) {
			$data_transc = $querytransc->getresult();
		} else {
			$data_transc = null;
		}
		if (!empty($data_transc)) {
			// mengambil semua order_id dari user
			$order_id = array();
			// $idgruptrans = array();
			foreach ($data_transc as $j) {
				$temp = json_decode(json_encode($j), true);
				array_push($order_id, $temp['order_id']);
				// array_push($idgruptrans, $temp['id_grup']);
			}
			// dd($idgruptrans);

			// dd($order_id);
			$api_response = array();
			foreach ($order_id as $k) {
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://api.sandbox.midtrans.com/v2/' . $k . '/status',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array(
						'Accept: application/json',
						'Content-Type: application/json',
						'Authorization: Basic U0ItTWlkLXNlcnZlci01X0pPX0FSTEpBUm13cnZBT0ZPVklGMXU6'
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$data_api = json_decode(json_encode($response), true);
				array_push($api_response, $data_api);
			}

			$builder = $db->table('transactions');
			foreach ($api_response as $ar) {
				$tmp = json_decode($ar, true);
				$order_ids = $tmp['order_id'];
				$data_api = [
					'status_code' => $tmp['status_code'],
					'status_message' => $tmp['status_message'],
					'transaction_status' => $tmp['transaction_status'],
					'fraud_status' => $tmp['fraud_status']
				];
				$status_code =  $this->tabermodel->getstatus_code($order_ids);
				// dd($order_ids, $status_code, $tmp['status_code']);
				if ($status_code == "201" and $tmp['status_code'] == "200") {
					$id_grup = $this->tabermodel->getid_grup($order_ids);
					$this->tabermodel->updatesaldo($user_id, $id_grup, $tmp['gross_amount']);
				}
				$builder->where('order_id', $order_ids);
				$builder->update($data_api);
			}
		}

		$data = [
			'title' => 'Tabungan Bersama',
			'join_req' => $join_req,
			'tagihan' => $datatagihan,
			'transaksi' => $data_trans
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
				$sql = "SELECT * FROM groups WHERE id = ? AND status = 'active'";
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
			$idtmp = $this->tabermodel->cek_kodegrup($kodegrup);
			$status = "";
			$ketuagrup = "";
			$emailketuagrup = "";
			if ($idtmp == null) {
				// dd("jalan");
				session()->setFlashdata('pesan', 'Kode grup tidak terdaftar');
				return redirect()->to('/taber/grup');
			} else {
				$status = $this->tabermodel->set_waiting_status($idtmp);
				$dataketuagrup =  $this->tabermodel->get_ketuagrup($idtmp);
				$ketuagrup = $dataketuagrup['username'];
				$emailketuagrup = $dataketuagrup['email'];
				$namagrup = $this->tabermodel->getgrname($idtmp);
				$username = $this->tabermodel->getusername();
				// KIRIM EMAIL KE KETUA GRUP
				// dd($emailketuagrup);
				$title = 'PERMINTAAN GABUNG GRUP MENABUNG';
				$message = 'TABER "TABUNGAN BERSAMA", ANDA MEMILIKI PERMINTAAN GABUNG DARI ' . $username . ' KE DALAM GRUP MENABUNG ' . $namagrup;
				// dd($message);
				$this->sendEmail($emailketuagrup, $title, $message);
				session()->setFlashdata('pesan', 'Permintaan gabung GRUP telah terkirim');
			}
			if ($status == 'waiting') {
				session()->setFlashdata('pesan', 'Permintaan gabung GRUP menunggu persetujuan');
			}
		}
		return redirect()->to('/taber/grup');
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
		// FUNGSI INI SUDAH DIGANTIKAN OLEH CONTROLLER SNAP
		// dd($nominal);
	}

	public function saldo()
	{
		$saldo = $this->tabermodel->getsaldo();
		$saldo = json_decode(json_encode($saldo), true);
		// dd($saldo);
		// MENGGANTI KOLOM id_grup MENJADI NAMA GRUP
		if ($saldo['grup1'] != null) {
			$saldo['grup1'] = $this->tabermodel->getgrname($saldo['grup1']);
		}
		if ($saldo['grup2'] != null) {
			$saldo['grup2'] = $this->tabermodel->getgrname($saldo['grup2']);
		}
		if ($saldo['grup3'] != null) {
			$saldo['grup3'] = $this->tabermodel->getgrname($saldo['grup3']);
		}

		// dd($saldo);

		$data = [
			'title' => 'Saldo',
			'saldo' => $saldo
		];
		return view('taber/saldo', $data);
	}

	public function keluargrup()
	{
		// dd();
		// $this->request->getPost();
		$grup_id = $this->request->getVar('id_grup');
		$granggota = $this->tabermodel->getgrmember($grup_id);
		// cek berapa banyak anggota grup
		if (count($granggota) == 1) {
			$this->tabermodel->setgrinactive($grup_id);
			$this->tabermodel->setpindahsaldo($grup_id);
		} else if (count($granggota) > 1) {
			if ($this->tabermodel->getcheckketua($grup_id)) {
				$this->tabermodel->setgrketua($grup_id);
			}
			$this->tabermodel->setpindahsaldo($grup_id);
		}
		return redirect()->to('/taber/grup');
	}

	public function payout()
	{
		// dd(user()->email);
		// dd(user());
		// $this->tabermodel->nominalpending();
		$bank = $this->tabermodel->getbanklist();
		$saldo = $this->tabermodel->getsaldo();
		$saldo = json_decode(json_encode($saldo), true);
		$payout_pending = $this->tabermodel->getpayoutnominalpending();
		$saldo_pending = $saldo['saldo'] - $payout_pending;
		$transaksi = $this->tabermodel->getpayouttransactions();
		// dd($saldo);
		$data = [
			'title' => 'Tarik uang tabungan',
			'bank' => $bank,
			'saldo' => $saldo_pending,
			'transaksi' => $transaksi
		];
		return view('taber/payout', $data);
	}

	public function payoutrequest()
	{
		$nominal = $this->request->getVar('payout_nominal');
		$bank_name = $this->request->getVar('bank_name');
		$rekening = $this->request->getVar('nomor_rekening');
		$nama = $this->request->getVar('nama_pemilik_rekening');
		// $message = 'Anda memiliki permintaan penarikan saldo dari user ' . user()->username
		// 	. " dengan rincian berikut : \nNominal :" . 'Rp. ' . number_format($nominal, 0, ',', '.')
		// 	. "\nBank : " . $bank_name
		// 	. "\nRekening : " . $rekening
		// 	. "\nNama : " . $nama;
		// dd($message);
		// dd($this->tabermodel->getbankcode($bank_name));
		// dd($nominal, $bank_name, $rekening, $nama);
		$check_nomial = $this->tabermodel->getchecknomialpayout($nominal);
		// dd($check_nomial);
		if ($check_nomial) {
			$this->tabermodel->setpayouttransactions($nominal, $bank_name, $rekening, $nama);
			$emailAdmin = 'muhrifan20@gmail.com';
			$title = 'PERMINTAAN TARIK UANG, TABUNGAN-BERSAMA';
			$message = 'Anda memiliki permintaan penarikan saldo dari user ' . user()->username
				. " dengan rincian berikut : \nNominal : " . 'Rp. ' . number_format($nominal, 0, ',', '.')
				. "\nBank : " . $bank_name
				. "\nRekening : " . $rekening
				. "\nNama : " . $nama;
			$this->sendEmail($emailAdmin, $title, $message);
			session()->setFlashdata('pesan', 'Permintaan tarik tabungan sebesar Rp. ' . $nominal . ' berhasil dibuat');
		} else {
			session()->setFlashdata('pesan', 'Permintaan tarik tabungan sebesar Rp. ' . $nominal . ' GAGAL dibuat ');
		}
		return redirect()->to('/taber/payout');
	}

	public function admin()
	{
		$check_admin = $this->tabermodel->getcheckadmin();
		if ($check_admin) {
			$transaksi = $this->tabermodel->getadminpayoutrequet();
			$data = [
				'title' => 'Admin',
				'transaksi' => $transaksi
			];
			return view('taber/admin', $data);
		} else {
			return redirect()->to('/taber');
		}
	}

	public function payoutbayar($payout_id)
	{
		$check_admin = $this->tabermodel->getcheckadmin();
		if ($check_admin) {
			$payout_nominal = $this->tabermodel->getpayoutnominal($payout_id);
			$this->tabermodel->setsaldo($payout_nominal);
			$this->tabermodel->setadminbayar($payout_id);
			return redirect()->to('/taber/admin');
		} else {
			return redirect()->to('/taber');
		}
	}
	public function payouttolak($payout_id)
	{
		$check_admin = $this->tabermodel->getcheckadmin();
		if ($check_admin) {
			$this->tabermodel->setadmintolak($payout_id);
			return redirect()->to('/taber/admin');
		} else {
			return redirect()->to('/taber');
		}
	}
}
