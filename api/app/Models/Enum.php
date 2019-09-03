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
    const resource_table_type = [ // resource type for resource table
        'options' => 'options',
    ];
    const resource_table_subtype = [ // resource subtype for resource table
        'translations' => 'translations',
    ];
    const cardinality_type = [ // list of cardinality types
        'unique' => 'unique',
    ];
}
