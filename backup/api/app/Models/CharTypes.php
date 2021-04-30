<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class CharTypes extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'description', 'created_at', 'updated_at'];

}
