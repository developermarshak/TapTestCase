<?php

namespace App\Models;

use App\Models\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Click
 * @property string $id          Unique identifier
 * @property string $ip          User ip address
 * @property string $referer
 * @property string $ua
 * @property string $param1
 * @property string $param2
 * @property integer $error
 * @property boolean $bad_domain
 * @package App
 */
class Click extends Model
{
    public $incrementing = false;

    static function boot()
    {
        static::creating(function(Click $model) {
            $model->id = $model->getId();
        });
    }

    public $timestamps = false;

    protected $table = 'click';

    protected $fillable = [
        'id',
        'ip',
        'ua',
        'referer',
        'param1',
        'param2',
        'error',
        'bad_domain'
    ];

    protected $casts = [
        'id' => 'string',
        'ip' => 'string',
        'ua' => 'string',
        'referer' => 'string',
        'param1'   => 'string',
        'param2'   => 'string',
        'error'    => 'integer',
        'bad_domain' => 'boolean'
    ];

    /**
     * Add new record if not isset record with unique values (return true),
     * if isset update error value (return false)
     * @param $columnName
     * @param $incrementing
     * @param $value
     * @return bool
     * @throws \Exception
     */
    function updateOrInsert($columnName, $incrementing, $value = null){
        if(!$incrementing && is_null($value)){
            throw new \Exception('You should set incrementing or value arguments');
        }

        $query = $this->newBaseQueryBuilder();
        $grammar = $query->getGrammar();
        $processor = $query->getProcessor();;

        $table = $grammar->wrapTable($this->getTable());

        $this->id = $this->getId();

        $attributes = $this->getAttributes();

        $columns = $grammar->columnize(array_keys($attributes));
        $insertValues = $grammar->parameterize($attributes);

        $sql = "INSERT INTO {$table} ({$columns}) VALUES({$insertValues}) ON DUPLICATE KEY UPDATE ".$columnName;
        if($incrementing){
            $sql .= "=".$columnName."+1";
        }
        else{
            $this->$columnName = $value;
            $value = $this->getAttributeValue($columnName);
            $sql .= "=".$grammar->parameter($value);
            $attributes[] = $value;
        }
        $processor->processInsertGetId($query, $sql, array_values($attributes));
    }

    protected function getId(){
        return md5($this->ua)."-".md5($this->referer).'-'.md5($this->param1).'-'.md5($this->ip);
    }
}
