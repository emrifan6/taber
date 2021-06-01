<?php



namespace App\Controllers;

use App\Libraries\Midtrans; // Import library


class rifan extends BaseController
{



    public function __construct()
    {

        // $this = new Midtrans();

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: PUT, GET, POST");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

        // parent::__construct();
        $params = array('server_key' => 'SB-Mid-server-5_JO_ARLJARmwrvAOFOVIF1u', 'production' => false);
        // $this->load->library('midtrans');
        // $this->config($params);
        // $this->helper('url');

        $slug = new Midtrans(); // create an instance of Library

        $string = "Online Web Tutor Blog";

        $slug->config($params); // calling method
        $slug->config('url'); // calling method
    }

    public function index()
    {
        $this->load->view('checkout_snap');
    }
}
