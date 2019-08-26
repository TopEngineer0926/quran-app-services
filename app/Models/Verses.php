<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $chapter_id
 * @property int $verse_number
 * @property int $verse_index
 * @property string $verse_key
 * @property string $text_madani
 * @property string $text_indopak
 * @property string $text_simple
 * @property int $juz_number
 * @property int $hizb_number
 * @property int $rub_number
 * @property string $sajdah
 * @property int $sajdah_number
 * @property int $page_number
 * @property string $created_at
 * @property string $updated_at
 * @property string $image_url
 * @property int $image_width
 * @property int $verse_root_id
 * @property int $verse_lemma_id
 * @property int $verse_stem_id
 * @property string $text_imlaei
 * @property string $text_uthmani_simple
 */
class Verses extends Model
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
    protected $fillable = ['chapter_id', 'verse_number', 'verse_index', 'verse_key', 'text_madani', 'text_indopak', 'text_simple', 'juz_number', 'hizb_number', 'rub_number', 'sajdah', 'sajdah_number', 'page_number', 'created_at', 'updated_at', 'image_url', 'image_width', 'verse_root_id', 'verse_lemma_id', 'verse_stem_id', 'text_imlaei', 'text_uthmani_simple'];

    public function words()
    {
        return $this->hasMany('App\Models\Words','verse_id','id');
    }
}
