<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $resource_type
 * @property int $resource_id
 * @property string $url
 * @property string $duration
 * @property string $embed_text
 * @property string $provider
 * @property int $language_id
 * @property string $language_name
 * @property string $author_name
 * @property int $resource_content_id
 * @property string $created_at
 * @property string $updated_at
 */
class MediaContents extends Model
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
    protected $fillable = ['resource_type', 'resource_id', 'url', 'duration', 'embed_text', 'provider', 'language_id', 'language_name', 'author_name', 'resource_content_id', 'created_at', 'updated_at'];

}
