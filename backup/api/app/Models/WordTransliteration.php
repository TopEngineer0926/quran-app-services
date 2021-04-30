<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $transliteration_id
 * @property int $id
 * @property int $word_id
 * @property string $language_code
 * @property string $value
 */
class WordTransliteration extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'word_transliteration';

    /**
     * @var array
     */
    protected $fillable = ['word_id', 'language_code', 'value'];

    public function transliteration(){
        return $this->hasOne('App\Models\Transliterations','id','transliteration_id');
    }
}
