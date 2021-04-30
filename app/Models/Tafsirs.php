<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $verse_id
 * @property int $language_id
 * @property string $text
 * @property string $language_name
 * @property int $resource_content_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $resource_name
 * @property string $verse_key
 */
class Tafsirs extends Model
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
    protected $fillable = ['verse_id', 'language_id', 'text', 'language_name', 'resource_content_id', 'created_at', 'updated_at', 'resource_name', 'verse_key'];

}
