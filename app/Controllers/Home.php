<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		$data = [
			'title' => 'HOME Taber'
		];

		return view('taber/landing_page', $data);
	}

	public function contact()
	{
		$data = [
			'title' => 'Contact Us',
			'alamat' => [
				[
					'tipe' => 'Rumah',
					'alamat' => 'JL Gasem Wulung Raya No.60',
					'kota' => 'Semarang'
				],
				[
					'tipe' => 'Kantor',
					'alamat' => 'Jl. Trilomba Juang No 1',
					'kota' => 'Semarang'
				]
			]
		];
		return view('taber/contact', $data);
	}
}
