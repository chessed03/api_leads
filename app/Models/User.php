<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function applyCredit( $consume_credit )
    {
        
        $user_email = auth()->user()->email;

        $data_user  = self::where('email', $user_email)->first();

        $data_user->credits = ( $data_user->credits - $consume_credit );

        $data_user->save();

    }

    public function getCredits()
    {
        $user_email = auth()->user()->email;

        $data_user  = self::where('email', $user_email)->first();

        return $data_user->credits;
    }

}
