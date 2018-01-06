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
    static function bootUUIDModel()
    {
        static::creating(function(Model $model) {
            $model->id = uniqid();
        });
    }
}