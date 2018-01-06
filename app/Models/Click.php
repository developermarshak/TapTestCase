<?php

namespace App\Models;

use App\Models\Traits\UUIDModel;
use Illuminate\Database\Eloquent\Model;

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
    use UUIDModel;

    protected $table = 'click';

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

    /**
     * Add new record if not isset record with unique values (return true),
     * if isset update error value (return false)
     * @param $incrementColumnName
     * @return bool
     * @throws \Exception
     */
    function incrementOrInsert($incrementColumnName){
        if(!isset($this->$incrementColumnName)){
            throw new \Exception('Column ('.$incrementColumnName.') not found');
        }
        $query = $this->newBaseQueryBuilder();
        $grammar = $query->getGrammar();
        $processor = $query->getProcessor();;

        $table = $grammar->wrapTable($this->getTable());

        $attributes = $this->getAttributes();
        $attributes['id'] = uniqid();

        $columns = $grammar->columnize(array_keys($attributes));
        $insertValues = $grammar->parameterize($attributes);


        $sql = "INSERT {$table} ({$columns}) VALUES({$insertValues}) ON DUPLICATE KEY UPDATE ".$incrementColumnName."=".$incrementColumnName."+1";

        $id = $processor->processInsertGetId($query, $sql, array_values($attributes));

        if(!$id){
            throw new \Exception('Failed add to DB');
        }

        $this->id = $id;

        return ($id === $attributes['id']);
    }
}
