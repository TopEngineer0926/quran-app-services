<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $chapter_id
 * @property string $text
 * @property string $source
 * @property string $short_text
 * @property int $language_id
 * @property int $resource_content_id
 * @property string $language_name
 * @property string $created_at
 * @property string $updated_at
 */
class ChapterInfos extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['chapter_id', 'text', 'source', 'short_text', 'language_id', 'resource_content_id', 'language_name', 'created_at', 'updated_at'];

}
