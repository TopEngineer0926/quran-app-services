<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $resource_type
 * @property int $resource_id
 * @property int $language_id
 * @property string $text
 * @property string $language_name
 * @property int $resource_content_id
 * @property string $created_at
 * @property string $updated_at
 */
class Transliterations extends Model
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
    protected $fillable = ['resource_type', 'resource_id', 'language_id', 'text', 'language_name', 'resource_content_id', 'created_at', 'updated_at'];

}
