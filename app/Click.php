<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * Class Click
 * @property string $id          Unique identifier
 * @property string $ip          User ip address
 * @property string $referrer
 * @property string $param1
 * @property string $param2
 * @property integer $error
 * @property boolean $bad_domains
 * @package App
 */
class Click extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ip',
        'referrer',
        'param1',
        'param2',
        'error',
        'bad_domains'
    ];

    protected $casts = [
        'id' => 'string',
        'ip' => 'string',
        'referrer' => 'string',
        'param1'   => 'string',
        'param2'   => 'string',
        'error'    => 'integer',
        'bad_domains' => 'boolean'
    ];

    static function boot()
    {
        parent::boot();

        static::creating(function(Click $click) {
            $click->id = uniqid();
        });
    }


}
