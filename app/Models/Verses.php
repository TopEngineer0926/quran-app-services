<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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


    use Searchable;

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

    //protected $hidden = ['text_madani'];
    public function words()
    {
        return $this->hasMany('App\Models\Words', 'verse_id', 'id')->select(['id', 'verse_id', 'position', 'text_madani', 'text_indopak', 'text_simple', 'verse_key', 'class_name', 'line_number', 'page_number', 'code_hex', 'code as code_hex_v3', 'audio_url', 'char_type_id']);
    }

    public function media_contents()
    {
        return $this->hasOne('App\Models\MediaContents','resource_id','id')->select(['url','embed_text','provider','author_name','resource_id'])->where('resource_type','verse');
    }

    public function searchableAs()
    {
        return 'verses_text_madani';
    }

    public function translations()
    {
        return $this->hasMany('App\Models\VerseTranslations','verse_id','id');
    }

    public function toSearchableArray()
    {

        $array = $this->toArray();

        return array('text_madani' => $array['text_madani']);

    }


}
