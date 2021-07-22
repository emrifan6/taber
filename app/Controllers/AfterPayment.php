<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TaberModel;

class AfterPayment extends BaseController
{
    use ResponseTrait;
    protected $tabermodel;
    public function __construct()
    {
        $this->tabermodel  = new TaberModel();
        $this->email = \Config\Services::email();
    }

    public function index()
    {
        $input_data = $_POST;
        // dd($input_data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        $db = \Config\Database::connect();
        $dataarray = json_encode($input_data, true);
        // $string = implode($dataarray);
        $data = [
            'data' => $dataarray
        ];
        $query = $db->table('afterpayment')->insert($data);
        return $this->respond($input_data);
    }
}
