<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $resource_content_id
 * @property int $download_count
 * @property string $platform
 * @property string $created_at
 * @property string $updated_at
 */
class ResourceContentStats extends Model
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
    protected $fillable = ['resource_content_id', 'download_count', 'platform', 'created_at', 'updated_at'];

}
