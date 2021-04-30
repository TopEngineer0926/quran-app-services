<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enum extends Model
{
    //
    const url_audio = 'https://s3.us-east-2.amazonaws.com/quran.com/verses/';
    const resource_type = [ // resource types for translated_names
        'languages' => 'languages',
        'chapters' => 'chapters',
    ];
    const resource_table_type = [ // resource type for resource table
        'options' => 'options',
    ];
    const resource_table_subtype = [ // resource subtype for resource table
        'translations' => 'translations',
    ];
    const cardinality_type = [ // list of cardinality types
        'unique' => 'unique',
    ];
    const resource_type_media_contents = [ // rource types for media_contents table
        'verse' => 'verse',
    ];
}
