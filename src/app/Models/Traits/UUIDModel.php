<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 15:46
 */

namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Model;

/**
 * Class UuidModel
 * @property $id Uuid ID
 * @package App
 */
trait UUIDModel
{
    function setIncrementing($value){
        if($value === false) return;
        throw new \Exception('Disabled incrementing with UUID model');
    }

    function getIncrementing(){
        return false;
    }

    static function bootUUIDModel(){
        static::creating(function(Model $model) {
            $model->id = static::uuid();
        });
    }

    static protected function uuid(){
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}