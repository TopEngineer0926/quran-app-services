<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    //
    public $fillable = ['url','duration','segments','format'];
}
