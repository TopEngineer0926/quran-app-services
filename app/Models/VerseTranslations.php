<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerseTranslations extends Model
{
    //
    protected $hidden =['created_at','updated_at'];
    public function author_name()
    {
        return $this->hasOne('App\Models\Resource','id','resource_id')->select('id','name');
    }
}
