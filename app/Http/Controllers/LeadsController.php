<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadsRequest;
use App\Http\Requests\UpdateLeadsRequest;
use App\Models\Leads;

class LeadsController extends Controller
{
   
    function responseApi( $data, $status )
    {

        $result = [
            'status'   => $status,
            'response' => [
                'results' => $data
            ]
        ];

        return $result;

    }

    function getAllLeads()
    {

        $data = Leads::all();

        return $this->responseApi( $data, 200 );

    }


}
