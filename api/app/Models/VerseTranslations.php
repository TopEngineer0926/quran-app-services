<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerseTranslations extends Model
{
    //
    public function author_name()
    {
        return $this->hasOne('App\Models\Resource','id','resource_id')->select('id','name');
    }
}
