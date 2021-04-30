<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;
/**
 * @property int $source_id
 * @property int $id
 * @property string $name
 * @property string $url
 * @property Resource[] $resources
 */
class Search extends Model
{

    use Searchable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'search';

    /**
     * @var array
     */
    protected $fillable = ['verse_id', 'text'];


    public function searchableAs()
    {
        return 'search_content';
    }


    public function toSearchableArray()
    {


//        $array = $this->toArray();
//        // Customize array...
//
//        return $array;


        $array = $this->toArray();

        //groupBy('verse_id')

        return array('content' => $array['content']);

    }

}
