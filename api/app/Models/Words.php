<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $verse_id
 * @property int $chapter_id
 * @property int $position
 * @property string $text_madani
 * @property string $text_indopak
 * @property string $text_simple
 * @property string $verse_key
 * @property int $page_number
 * @property string $class_name
 * @property int $line_number
 * @property int $code_dec
 * @property string $code_hex
 * @property string $code_hex_v3
 * @property int $code_dec_v3
 * @property int $char_type_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $pause_name
 * @property string $audio_url
 * @property string $image_blob
 * @property string $image_url
 * @property int $token_id
 * @property int $topic_id
 * @property string $location
 * @property string $char_type_name
 * @property string $text_imlaei
 * @property string $text_uthmani_simple
 */
class Words extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    // function __construct(){
    //     $this->code_hex = html_entity_decode($this->code_hex, ENT_NOQUOTES);
    // }

    /**
     * @var array
     */
    protected $fillable = ['verse_id', 'chapter_id', 'position', 'text_madani', 'text_indopak', 'text_simple', 'verse_key', 'page_number', 'class_name', 'line_number', 'code_dec', 'code_hex', 'code_hex_v3', 'code_dec_v3', 'char_type_id', 'created_at', 'updated_at', 'pause_name', 'audio_url', 'image_blob', 'image_url', 'token_id', 'topic_id', 'location', 'char_type_name', 'text_imlaei', 'text_uthmani_simple'];


    public function translation()
    {
        //     return $this->hasOneThrough(
        //     'App\Models\Translations',
        //     'App\Models\WordTranslation',
        //     'translation_id', // Foreign key on 2nd table...
        //     'id', // Foreign key on 1st table...
        //     'id', // Local key on this table...
        //     'word_id' // Local key on 2nd table...
        // );
        return $this->hasOne('App\Models\WordTranslation','word_id','id')->with('translation');
    }
    public function transliteration()
    {
            // return $this->hasOneThrough(
            // 'App\Models\Transliterations',
            // 'App\Models\WordTransliteration',
            // 'transliteration_id', // Foreign key on 2nd table...
            // 'id', // Foreign key on 1st table...
            // 'id', // Local key on this table...
            // 'word_id' // Local key on 2nd table...
            return $this->hasOne('App\Models\WordTransliteration','word_id','id')->with('transliteration');


    }
    public function chartype()
    {
        return $this->hasOne('App\Models\CharTypes','id','char_type_id');
    }
}
