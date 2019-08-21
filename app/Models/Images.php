<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $verse_id
 * @property int $resource_content_id
 * @property int $width
 * @property string $url
 * @property string $alt
 * @property string $created_at
 * @property string $updated_at
 */
class Images extends Model
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
    protected $fillable = ['verse_id', 'resource_content_id', 'width', 'url', 'alt', 'created_at', 'updated_at'];

}
