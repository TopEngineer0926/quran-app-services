<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $word_id
 * @property string $text
 * @property string $language_name
 * @property int $language_id
 * @property int $resource_content_id
 * @property int $priority
 * @property string $created_at
 * @property string $updated_at
 */
class WordTranslations extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['word_id', 'text', 'language_name', 'language_id', 'resource_content_id', 'priority', 'created_at', 'updated_at'];

}
