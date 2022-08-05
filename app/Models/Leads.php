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

        foreach($data as $key => $email){

            $result = self::where('email', $email)->first();

            if( $result ){

                $credits = User::getCredits();

                if( $credits > 0 ){

                    $result_finds[$key] = (Object)[
                        'email'  => $result->email,
                        'pets'   => ($result->pets) ? 'Yes' : 'No',
                        'cars'   => ($result->cars) ? 'Yes' : 'No',
                        'travel' => ($result->travel) ? 'Yes' : 'No'
                    ];

                    $credits = User::applyCredit( 1 );
                    
                }else{

                    break;
    
                }

            }
                        
        }

        return $result_finds;

    }

}
