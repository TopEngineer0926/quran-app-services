<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $reciter_id
 * @property int $resource_content_id
 * @property int $recitation_style_id
 * @property string $reciter_name
 * @property string $style
 * @property string $created_at
 * @property string $updated_at
 */
class Recitations extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['reciter_id', 'resource_content_id', 'recitation_style_id', 'reciter_name', 'style', 'created_at', 'updated_at'];

}
