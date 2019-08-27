<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $resource_type
 * @property int $resource_id
 * @property string $url
 * @property int $duration
 * @property string $segments
 * @property string $mime_type
 * @property string $format
 * @property boolean $is_enabled
 * @property int $recitation_id
 * @property string $created_at
 * @property string $updated_at
 */
class AudioFiles extends Model
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
    protected $fillable = ['resource_type', 'resource_id', 'url', 'duration', 'segments', 'mime_type', 'format', 'is_enabled', 'recitation_id', 'created_at', 'updated_at'];

}
