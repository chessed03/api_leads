<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLeadsRequest;
use App\Http\Requests\StoreLeadsRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Leads;
use App\Models\User;
use Carbon\Carbon;
use Response;

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

    public function randomNumber()
    {
        $varible_length = 4;

        $number = '';

        for($i = 0; $i < $varible_length; $i++)
        {
            $number .= mt_rand(0,9);
        }

        $now = Carbon::now();

        $date_now = Carbon::createFromDate($now)->format('Ymd');

        return $number.'-'.$date_now;
    }

    function processDataFile(Request $request)
    {   
        //return $request->all();
        
        $credits = User::getCredits();

        if( $credits > 0 ){

            if($request->file('file'))
            {
            
                $data_content = preg_split('/\n|\r\n?/', $request->file('file')->get());
                
                $process_data = Leads::processDataFile( $data_content );

                $str_content  = '       email         |   pets   |    cars    |   travel  ';

                $str_content .= "\n";

                foreach($process_data as $key => $text){
                    
                    $str_content .= $text->email;
                    $str_content .= '     '.$text->pets;
                    $str_content .= '           '.$text->cars;
                    $str_content .= '           '.$text->travel;

                    $str_content .= "\n";

                }
                
                $content_data = $str_content;

                $code_file    = $this->randomNumber();

                $file_created = $code_file.'.txt';

                Storage::disk('public')->put( $file_created, $str_content);

                $file_path = storage_path() . "/app/public/" . $file_created;

                $result_process = [
                    'download'  => asset('api/downloadFile?file='. $file_created),
                    'credits'   => User::getCredits()
                ];

                return $this->responseApi( $result_process, 200 );

            }else{
                return $this->responseApi( 'archivo inválido', 200 );
            }

        }
        else
        {
            return $this->responseApi( 'No credits', 200 );
        }

        
    }

    function downloadFile(Request $request)
    {
       
        $file_name = $request->file;

        return response()->download(storage_path("app/public/".$file_name));
        
    }


}
