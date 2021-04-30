<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $text_madani
 * @property string $text_clean
 * @property string $text_indopak
 * @property string $created_at
 * @property string $updated_at
 */
class Tokens extends Model
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
    protected $fillable = ['text_madani', 'text_clean', 'text_indopak', 'created_at', 'updated_at'];

}
