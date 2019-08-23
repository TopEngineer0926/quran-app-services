<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $iso_code
 * @property string $native_name
 * @property string $direction
 * @property string $es_analyzer_default
 * @property string $created_at
 * @property string $updated_at
 */
class Languages extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'iso_code', 'native_name', 'direction', 'es_analyzer_default', 'created_at', 'updated_at'];

}
