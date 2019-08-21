<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $language_id
 * @property string $text
 * @property int $resource_content_id
 * @property string $resource_type
 * @property int $resource_id
 * @property string $language_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $resource_name
 */
class Translations extends Model
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
    protected $fillable = ['language_id', 'text', 'resource_content_id', 'resource_type', 'resource_id', 'language_name', 'created_at', 'updated_at', 'resource_name'];

}
