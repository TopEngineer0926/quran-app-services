<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $word_id
 * @property int $root_id
 * @property string $created_at
 * @property string $updated_at
 */
class WordRoots extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['word_id', 'root_id', 'created_at', 'updated_at'];

}
