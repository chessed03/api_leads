<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    
    public static function processDataFile( $data )
    {
        $result_finds   = [];

        $consume_credit = 0;

        foreach($data as $key => $email){

            $result = self::where('email', $email)->first();

            if( $result ){

                $result_finds[$key] = (Object)[
                    'email'  => $result->email,
                    'pets'   => ($result->pets) ? 'Yes' : 'No',
                    'cars'   => ($result->cars) ? 'Yes' : 'No',
                    'travel' => ($result->travel) ? 'Yes' : 'No'
                ];

                $consume_credit++;
            }
                        
        }

        if( count($result_finds) > 0 ){

            $credits = User::applyCredit( $consume_credit );

        }

        return $result_finds;

    }

}
