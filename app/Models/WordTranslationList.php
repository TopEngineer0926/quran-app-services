<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordTranslationList extends Model
{
    protected $table = 'word_translation_list';

    public function language()
    {
        return $this->hasOne('App\Models\Language','id','language_id');
    }
}
