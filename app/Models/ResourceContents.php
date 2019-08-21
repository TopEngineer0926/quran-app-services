<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $approved
 * @property int $author_id
 * @property int $data_source_id
 * @property string $author_name
 * @property string $resource_type
 * @property string $sub_type
 * @property string $name
 * @property string $description
 * @property string $cardinality_type
 * @property int $language_id
 * @property string $language_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $slug
 * @property int $mobile_translation_id
 * @property int $priority
 */
class ResourceContents extends Model
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
    protected $fillable = ['approved', 'author_id', 'data_source_id', 'author_name', 'resource_type', 'sub_type', 'name', 'description', 'cardinality_type', 'language_id', 'language_name', 'created_at', 'updated_at', 'slug', 'mobile_translation_id', 'priority'];

}
