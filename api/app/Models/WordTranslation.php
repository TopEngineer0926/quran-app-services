<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $translation_id
 * @property int $id
 * @property int $word_id
 * @property string $language_code
 * @property string $value
 * @property Language $language
 * @property Word $word
 */
class WordTranslation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'word_translation';

    /**
     * @var array
     */
    protected $fillable = ['word_id', 'language_code', 'value'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function translation(){
        return $this->hasOne('App\Models\Translations','id','translation_id');
    //->select('id','text','language_name')
    }

    public function language()
    {
        return $this->belongsTo('App\Language', 'language_code', 'language_code');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function word()
    {
        return $this->belongsTo('App\Word', null, 'word_id');
    }
}
