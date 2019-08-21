<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $word_id
 * @property int $stem_id
 * @property string $created_at
 * @property string $updated_at
 */
class WordStems extends Model
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
    protected $fillable = ['word_id', 'stem_id', 'created_at', 'updated_at'];

}
