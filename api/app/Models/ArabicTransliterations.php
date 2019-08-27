<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $word_id
 * @property int $verse_id
 * @property string $text
 * @property string $indopak_text
 * @property int $page_number
 * @property string $created_at
 * @property string $updated_at
 * @property int $position_x
 * @property int $position_y
 * @property float $zoom
 * @property string $ur_translation
 */
class ArabicTransliterations extends Model
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
    protected $fillable = ['word_id', 'verse_id', 'text', 'indopak_text', 'page_number', 'created_at', 'updated_at', 'position_x', 'position_y', 'zoom', 'ur_translation'];

}
