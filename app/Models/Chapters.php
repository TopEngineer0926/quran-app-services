<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $revelation_place
 * @property boolean $bismillah_pre
 * @property int $revelation_order
 * @property string $name_complex
 * @property string $name_arabic
 * @property string $name_simple
 * @property string $pages
 * @property int $verses_count
 * @property int $chapter_number
 * @property string $created_at
 * @property string $updated_at
 */
class Chapters extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['bismillah_pre', 'revelation_order', 'name_complex', 'name_arabic', 'name_simple', 'pages', 'verses_count', 'chapter_number', 'created_at', 'updated_at'];

}
