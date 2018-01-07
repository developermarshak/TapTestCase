<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 06.01.2018
 * Time: 15:43
 */

namespace App\Models;


use App\Models\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BadDomain
 * @property string $id
 * @property string $name
 * @package App
 */
class BadDomain extends Model
{
    use UUIDModel;

    public $timestamps = false;

    protected $table = 'bad_domains';

    protected $fillable = [
        'id',
        'name'
    ];

    static function isExist($domain){
        return (static::query()
                ->where('name', '=', $domain)
                ->limit(1)
                ->count() > 0)
            ? true : false;
    }
}