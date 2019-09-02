<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enum extends Model
{
    //
    const resource_type = [ // resource types for translated_names
        'languages' => 'languages',
        'chapters' => 'chapters',
    ];
}
