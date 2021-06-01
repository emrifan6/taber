<?php

namespace App\Controllers;

use App\Libraries\Midtrans; // Import library
use App\Models\TaberModel;

use CodeIgniter\HTTP\Request;


// if (!defined('BASEPATH')) exit('No direct script access allowed');


class snap extends BaseController
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	protected $tabermodel;

	public function __construct()
	{
		$this->tabermodel  = new TaberModel();

		$mitrans = new Midtrans();
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: PUT, GET, POST");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

		// parent::__construct();
		$params = array('server_key' => 'SB-Mid-server-5_JO_ARLJARmwrvAOFOVIF1u', 'production' => false);
		// $this->load->library('midtrans');
		// $this->midtrans->config($params);
		// $this->load->helper('url');

		$mitrans->config($params);
		// $mitrans->helper('url');
	}



	public function index($nominal, $idgrup)
	{

		$data = [
			'title' => 'BAYAR TABUNGAN',
			'nominal' => $nominal,
			'namagrup' => $this->tabermodel->getgrname($idgrup),
			'idgrup'  => $idgrup
		];

		return view('checkout_snap', $data);
	}

	public function token($nominal, $idgrup)
	{
		// dd($nominal);
		// Required
		$transaction_details = array(
			'order_id' => rand(),
			'gross_amount' => $nominal, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
			'id' => $idgrup,
			'price' => $nominal,
			'quantity' => 1,
			'name' => $this->tabermodel->getgrname($idgrup)
			// 
		);



		// Optional
		$item_details = array($item1_details);

		// Optional
		$billing_address = array(
			'first_name'    => "Andri",
			'last_name'     => "Litani",
			'address'       => "Mangga 20",
			'city'          => "Jakarta",
			'postal_code'   => "16602",
			'phone'         => "081122334455",
			'country_code'  => 'IDN'
		);

		// Optional
		$shipping_address = array(
			'first_name'    => "Obet",
			'last_name'     => "Supriadi",
			'address'       => "Manggis 90",
			'city'          => "Jakarta",
			'postal_code'   => "16601",
			'phone'         => "08113366345",
			'country_code'  => 'IDN'
		);

		// Optional
		$customer_details = array(
			'first_name'    => "Andri",
			'last_name'     => "Litani",
			'email'         => "andri@litani.com",
			'phone'         => "081122334455",
			'billing_address'  => $billing_address,
			'shipping_address' => $shipping_address
		);

		// Data yang akan dikirim untuk request redirect_url.
		$credit_card['secure'] = true;
		//ser save_card true to enable oneclick or 2click
		//$credit_card['save_card'] = true;

		$time = time();
		$custom_expiry = array(
			'start_time' => date("Y-m-d H:i:s O", $time),
			'unit' => 'minute',
			'duration'  => 2
		);

		$transaction_data = array(
			'transaction_details' => $transaction_details,
			'item_details'       => $item_details,
			'customer_details'   => $customer_details,
			'credit_card'        => $credit_card,
			'expiry'             => $custom_expiry
		);

		$mitrans = new Midtrans();
		error_log(json_encode($transaction_data));
		$snapToken = $mitrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
	}

	public function finish()
	{
		$result = json_decode($this->input->post('result_data'));
		echo 'RESULT <br><pre>';
		var_dump($result);
		echo '</pre>';
	}
}
