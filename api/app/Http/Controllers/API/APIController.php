<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\Chapter;
use App\Models\ChapterInfos;
use App\Models\TranslatedName;
use App\Models\Verses;
use App\Models\Recitations;
use App\Models\AudioFile;
use App\Models\Resource;
use App\Models\Enum;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class APIController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('cors');
    }

    /**
     *Function gets chapters list
     *
     * @author Muhammad Omer Saleh
     * @param string language: To select translation language 'en' by default (can be array) (Optional)
     * @return array chapters: in single array
     */
    protected function chapters(Request $request)
    {
        $language = 'en';
        if (isset($request->language)) {
            $language = $request->language;
        }
        $language = Languages::where('iso_code', $language)->first();
        if ($language) {
            $language_id = $language->id;
            $translated_names = TranslatedName::select('language_name', 'name')->where('language_id', $language_id)->where('resource_type', 'chapters')->get()->toArray();
            $chapters = Chapter::get();
            $loop = 0;
            if ($chapters) {
                foreach ($chapters as $chapter) {
                    $chapter->pages = json_decode($chapter->pages);
                    $chapter->setAttribute('translated_name', $translated_names[$loop]);
                    $loop++;
                }
            }
            return ['chapters' => $chapters];
        } else {
            return [
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }

    /**
     *Function gets chapter info based on id (1 to 114)
     *
     * @author Muhammad Omer Saleh
     * @param string language: To select translation language 'en' by default (can be array) (Optional)
     * @return array chapterinfo: in single array
     */
    protected function chapter_info($id, Request $request)
    {
        $chapter_info = null;
        $language = 'en';
        if (isset($request->language)) {
            $language = $request->language;
        }
        $language = Languages::where('iso_code', $language)->first();
        if ($language) {
            $chapter_info = ChapterInfos::where('chapter_id', $id)->where('language_id', $language->id)->first();
            $chapter_info->text = json_decode($chapter_info->text);
            return ['chapter_info' => $chapter_info];
        } else {
            return [
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }

    /**
     *Function gets chapter based on id (1 to 114)
     *
     * @author Muhammad Omer Saleh
     * @param string language: To select translation language 'en' by default (can be array) (Optional)
     * @return array chapter: in single array
     */
    protected function chapter($id, Request $request)
    {
        $language = 'en';
        if (isset($request->language)) {
            $language = $request->language;
        }
        $language = Languages::where('iso_code', $language)->first();
        if ($language) {
            $language_id = $language->id;
            $translated_names = TranslatedName::select('language_name', 'name')->where('language_id', $language_id)->where('resource_type', 'chapters')->where('resource_id', $id)->get()->toArray();
            $chapter = Chapter::where('id', $id)->first();
            $loop = 0;
            if ($chapter) {

                $chapter->pages = json_decode($chapter->pages);
                $chapter->setAttribute('translated_name', $translated_names[$loop]);
                $loop++;
            }
            return ['chapter' => $chapter];
        } else {
            return [
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }

    /**
     *Function gets verses for specfic Chapter
     *
     * @author Muhammad Omer Saleh
     * @param int $id : Id for chapter (1 to 114)
     * @param string language: To select translation language 'en' by default (can be array) (Optional)
     * @param int page: page number for current chapter max 50 (Optional)
     * @param int limit: Limit verses for current page (10 by default) (Optional)
     * @param int offset: set an offset to get verses
     * @param int recitation: gets recitation audio based on id
     * @return array verses: in single array (with audio files array if recitatiton is set)
     */
    protected function verses($id, Request $request)
    {
        $language = 'en';
        $limit = 10;
        $offset = 0;
        $audio_files = array();
        if (isset($request->language)) {
            $language = $request->language;
        }
        if (isset($request->limit) && $request->limit <= 50) {
            $limit = $request->limit;
        }
        if (isset($request->offset)) {
            $offset = $request->offset;
        }
        $verses = Verses::select(
            'id',
            'verse_number',
            'chapter_id',
            'verse_key',
            'text_madani',
            'text_indopak',
            'text_simple',
            'juz_number',
            'hizb_number',
            'rub_number',
            'sajdah',
            'sajdah_number',
            'page_number'
        )->with('media_contents')
            ->with('words')
            ->with('words.translation')
            ->with('words.transliteration')
            ->with('words.chartype:id,name')
            ->where('chapter_id', $id)->where('verse_number', '>=', $offset)->paginate($limit);
        $chapter_name = Chapter::where('id', $verses->first()->chapter_id)->first()->name_complex;
        foreach ($verses as $verse) {
            if (isset($request->recitation)) {
                $recitation = Recitations::where('id', $request->recitation)->first();
                $path = $recitation->file_name;
                $xml = simplexml_load_file($path);
                $information = $xml->information;
                $verses_xml = $xml->verses;
                foreach ($verses_xml->verse as $verse_xml) {
                    if ($verse_xml->verse_id == $verse->id && $verse_xml->chapter_id == $verse->chapter_id) {
                        $audio_file = new AudioFile;
                        $audio_file->url = Enum::url_audio . $information->base_url->__toString() . '/' . $verse_xml->url->__toString();
                        $audio_file->duration = $verse_xml->duration->__toString();
                        $audio_file->segments = json_decode($verse_xml->segments);
                        $audio_file->format = $information->format->__toString();
                        $audio_file->title = $chapter_name . ' ' . str_pad($verse->verse_number, 3, "0", STR_PAD_LEFT) . ' - ' . $information->reciter_name->__toString();
                        $verse->setAttribute('audio',$audio_file);
                        array_push($audio_files, $audio_file);
                        break;
                    }
                }
            }
            if (isset($request->translations)) {
                $translations = array();
                foreach ($request->translations as $request_translation) {
                    $translation = Resource::where('id', $request_translation)->with('source')->first();
                    $path = $translation->source->url;
                    $xml = simplexml_load_file($path);
                    $information = $xml->information;
                    $verses_xml = $xml->verses;
                    foreach ($verses_xml->verse as $verse_xml) {
                        if ($verse_xml->verse_id == $verse->id && $verse_xml->chapter_id == $verse->chapter_id) {
                            $verse_translation = collect();
                            $verse_translation->put('id', $verse_xml->id->__toString());
                            $verse_translation->put('language_name', $information->language_name->__toString());
                            $verse_translation->put('text', $verse_xml->text->__toString());
                            $verse_translation->put('resource_name', $information->resource_name->__toString());
                            $verse_translation->put('resource_id', $information->resource_id->__toString());
                            array_push($translations, $verse_translation);
                            break;
                        }
                    }
                }
                $verse->setAttribute('translations', $translations);
            }
        }
        //$verses->put('audio_files', $audio_files);
        return [
            'verses' => $verses,
            'audio_files' => $audio_files
        ];
    }

    /**
     *Function gets languages list
     *
     * @author Muhammad Omer Saleh
     * @param string language: To select specfic translation language (Optional)
     * @return array languages: in single array
     */

    protected function languages(Request $request)
    {
        $language = null;
        $languages = null;
        $languages = Languages::select('name', 'iso_code', 'native_name', 'direction', 'id');
        if (isset($request->language)) {
            $language = $request->language;
            $languages = $languages->where('iso_code', $language)->with('translated_name')->first();
        } else {
            $languages = $languages->with('translated_name')->get();
        }
        return ['languages' => $languages];
    }

    /**
     *Function gets recitations list
     *
     * @author Muhammad Omer Saleh
     * @return array recitations list
     */
    protected function recitations(Request $request)
    {
        $recitations = Recitations::select('id', 'style', 'reciter_name as reciter_name_eng')->orderBy('reciter_name', 'ASC')->get();
        return ['recitaitons' => $recitations];
    }

    /**
     *Function gets audio files information
     *
     * @author Muhammad Omer Saleh
     * @param int id: To select chapter by id
     * @param int verse_id: To select verse by id
     * @param string language: To select specfic translation language (Optional)
     * @param int recitation: To select specfic recitation by id (Optional)
     * @return array audio_file/audio_files: array of recitaion audio/ audios if no recitation is provided
     */
    protected function audio_file($id, $verse_id, Request $request)
    {
        $language = 'en';
        $recitations = null;
        $recitation = null;
        $audio_files = array();
        if (isset($request->language)) {
            $language = Languages::where('iso_code', $language)->first();
        }
        if (isset($request->recitation)) {
            $recitation = Recitations::where('id', $request->recitation)->first();
            $path = $recitation->file_name;
            $xml = simplexml_load_file($path);
            $information = $xml->information;
            $verses = $xml->verses;
            foreach ($verses->verse as $verse) {
                if ($verse->verse_id == $verse_id && $verse->chapter_id == $id) {
                    $audio_file = new AudioFile;
                    $audio_file->url = $verse->url->__toString();
                    $audio_file->duration = $verse->duration->__toString();
                    $audio_file->segments = json_decode($verse->segments);
                    $audio_file->format = $information->format->__toString();
                    break;
                }
            }
            return ['audio_file' => $audio_file];
        } else {
            $recitations = Recitations::get();
            $paths = array();
            foreach ($recitations as $recitation) {
                array_push($paths, $recitation->file_name);
            }
            foreach ($paths as $path) {
                $xml = simplexml_load_file($path);
                $information = $xml->information;
                $verses = $xml->verses;
                foreach ($verses->verse as $verse) {
                    if ($verse->verse_id == $verse_id && $verse->chapter_id == $id) {
                        $audio_file = new AudioFile;
                        $audio_file->url = Enum::url_audio . $information->base_url->__toString() . '/' . $verse->url->__toString();
                        $audio_file->duration = $verse->duration->__toString();
                        $audio_file->segments = json_decode($verse->segments);
                        $audio_file->format = $information->format->__toString();
                        array_push($audio_files, $audio_file);
                        break;
                    }
                }
            }
            return ['audio_files' => $audio_files];
        }
    }

    /**
     *Function gets audio files based on start and end verse_id range based in recitation id
     *
     * @author Muhammad Omer Saleh
     * @param int id: To select chapter by id
     * @param string language: To select specfic translation language (Optional)
     * @param int recitation: To select specfic recitation by id
     * @param int start: To select a start index for verse id
     * @param int end: To select a end index for verse id
     * @return array audio_files: array of recitaion audios
     */

    protected function audio_files(Request $request, $id)
    {
        $language = 'en';
        $recitation = null;
        $start = null;
        $end = null;
        if (isset($request->start) && isset($request->end)) {
            $start = $request->start;
            $end = $request->end;
        } else {
            return [
                'status' => 'error',
                'data' => 'please select start and end range'
            ];
        }
        $audio_files = array();
        if (isset($request->language)) {
            $language = Languages::where('iso_code', $language)->first();
        }
        if (isset($request->recitation)) {
            $recitation = Recitations::where('id', $request->recitation)->first();
            $path = $recitation->file_name;
            $xml = simplexml_load_file($path);
            $information = $xml->information;
            $verses = $xml->verses;
            foreach ($verses->verse as $verse) {
                if ($verse->verse_id >= $start && $verse->chapter_id == $id && $verse->verse_id <= $end) {
                    $audio_file = new AudioFile;
                    $audio_file->url = Enum::url_audio . $information->base_url->__toString() . '/' . $verse->url->__toString();
                    $audio_file->duration = $verse->duration->__toString();
                    $audio_file->segments = json_decode($verse->segments);
                    $audio_file->format = $information->format->__toString();
                    array_push($audio_files, $audio_file);
                    if (count($audio_files) >= ($end - $start + 1)) {
                        break;
                    }
                }
            }
        } else {
            return [
                'status' => 'error',
                'data' => 'no recitation selected'
            ];
        }
        return ['audio_files' => $audio_files];
    }

    /**
     *Function gets translations list
     *
     * @author Muhammad Omer Saleh
     * @return array translations list
     */
    protected function translations()
    {
        $translations = Resource::where('type', Enum::resource_table_type['options'])
            ->where('sub_type', Enum::resource_table_subtype['translations'])
            ->where('is_available', 1)
            ->with('source')
            ->with('author')
            ->with('language')->get();


        $results = array();
        foreach ($translations as $translation) {
            $result = collect();
            $result->put('id', $translation->id);
            $result->put('author_name', $translation->author->name);
            $result->put('slug', $translation->slug);
            $result->put('name', $translation->name);
            $result->put('language_name', $translation->language->name);
            array_push($results, $result);
        }
        return ['translations' => $results];
    }

    /**
     *Function gets search results based on query.
     *
     * @author Muhammad Omer Saleh (SQL Query by Faisal Mehmood)
     * @param string q: Query to be searched
     * @param int limit: To get custom search result limit on a page - Default is 20(Optional)
     * @param int p: To get page by number of search results - Default is 1 (Optional)
     * @return array results: verses with relevant translations and words and other output information
     */

    protected function search(Request $request)
    {
        $start = microtime(true); //start timer count
        $verse_ids = array();
        $results = array();
        $limit = 20;
        $page = 1;

        if (isset($request->p)) {
            $page = $request->p;
        }
        if (isset($request->limit)) {
            $limit = $request->limit;
        }
        $query = null;
        if (isset($request->q)) {
            $query = $request->q;
        }
        $offset = ($page - 1) * $limit;

        $sql_child = "SELECT
                        verse_id,content,MATCH (content) AGAINST (
                            '$query' IN NATURAL LANGUAGE MODE
                        ) AS rank
                    FROM
                        search
                    WHERE
                        MATCH (content) AGAINST (
                            '$query' IN NATURAL LANGUAGE MODE
                        )
                    GROUP BY
                        verse_id
                    ORDER BY
                        rank DESC
                    LIMIT $limit Offset $offset";

        $records = \DB::connection('mysql')->select(\DB::raw($sql_child));

        $sql_count = "SELECT count(*) AS count FROM(
                SELECT
                content,MATCH (content) AGAINST (
                '$query' IN NATURAL LANGUAGE MODE
                ) AS rank
                FROM
                search
                WHERE
                MATCH (content) AGAINST (
                '$query' IN NATURAL LANGUAGE MODE
                )
                GROUP BY
                verse_id) as count";
        $total_count = \DB::connection('mysql')->select(\DB::raw($sql_count))[0]->count;

        $total_pages = ceil($total_count / $limit);

        foreach ($records as $record) {
            array_push($verse_ids, $record->verse_id); // gets ids of verses from result
        }
        $verse_ids_ordered = implode(',', $verse_ids);
        if ($verse_ids) {
            $results = Verses::select( // query to get verses and other related data
                'id',
                'verse_number',
                'chapter_id',
                'verse_key',
                'text_madani'
            )->whereIn('id', $verse_ids)->orderByRaw(\DB::raw("FIELD(id, $verse_ids_ordered)"))
                ->with(['translations' => function ($q) use (&$query) {
                    $q->selectRaw('verse_id,text,resource_id')->whereRaw("MATCH (verse_translations.text) AGAINST (
                    '$query' IN NATURAL LANGUAGE MODE
                    )")->with('author_name');
                }])
                ->with('words')
                ->with('words.translation')
                ->with('words.transliteration')
                ->with('words.chartype:id,name')
                ->get();
        }
        $words_query = explode(" ",$query);
        foreach($results as $result)
        {

            foreach($result->words as $word){
                if(in_array($word->text_madani,$words_query) || in_array($word->text_simple,$words_query)){
                    $word->setAttribute('highlight','hlt1');
                }
                else{
                    $word->setAttribute('highlight', null);
                }
            }
        //     if($result->translation){
        //     $result->translation->text = str_ireplace($query, '<em class="hlt1">' . $query . '</em>', $result->translation->text);
        //     //$result->translation->text = preg_replace('','<em class="hlt1">' . $query . '</em>',$query);
        //     }
        }
        $time = microtime(true) - $start; //end timer
        return [
            'query' => $query,
            'total_count' => $total_count,
            'took' => number_format((float) $time, 2, '.', '') . 's',
            'current_page' => (int) $page,
            'total_pages' => $total_pages,
            'per_page' => $limit,
            'results' => $results
        ];
    }
    /**
     *Function gets suggestion based on query string
     *
     * @author Muhammad Omer Saleh
     * @param string q: Query to be searched
     * @return array results: verses with relevant translations and words and other output information
     */
    protected function suggest(Request $request)
    {
        $query = null;
        $limit = 10;
        if(isset($request->q)){
            $query = $request->q;
        }
        $suggests = collect();
        //$query = "بسم الله الرحمن الرحيم";

        $sql = "SELECT
                    id,text_madani,text_indopak,text_simple,verse_key,MATCH (text_madani) AGAINST (
                        '$query' IN NATURAL LANGUAGE MODE
                    ) AS rank,CONCAT_WS('/',chapter_id,verse_number) AS href
                    FROM
                        verses
                    WHERE
                        MATCH (text_madani) AGAINST (
                            '$query' IN NATURAL LANGUAGE MODE
                        )
                    ORDER BY
                        rank DESC
                    LIMIT $limit Offset 0";
        $records = \DB::connection('mysql')->select(\DB::raw($sql));
        $words_query = explode(" ", $query);
        $matches = array();
        foreach ($records as $record) {
            $words_text_madani = explode(" ", $record->text_madani);
            $words_text_indopak = explode(" ", $record->text_indopak);
            $words_text_simple = explode(" ", $record->text_simple);
            foreach ($words_query as $word_query) {
                $match = array_search($word_query, $words_text_madani );
                if ($match !== false) {
                    $words_text_madani[$match] = "<em class='hlt1'>".$words_text_madani[$match]."</em>";
                 }
                 $match = array_search($word_query, $words_text_indopak);
                if ($match!== false) {
                    $words_text_madani[$match] = "<em class='hlt1'>".$words_text_madani[$match]."</em>";
                 }
                 $match = array_search($word_query, $words_text_simple);
                if ($match!== false) {
                    $words_text_madani[$match] = "<em class='hlt1'>".$words_text_madani[$match]."</em>";
                 }
            }
            $text = implode(" ",$words_text_madani);
            $suggest = collect();
            $suggest->put('text',$text);
            $suggest->put('href',$record->href);
            $suggest->put('ayah',$record->verse_key);
            $suggests->push($suggest);
        }
            if(count($suggests)<$limit)
            {
                $limit = $limit-count($suggests);
                $sql = "SELECT
                    id,verse_id,text,resource_id,MATCH (text) AGAINST (
                        '$query' IN NATURAL LANGUAGE MODE
                    ) AS rank
                    FROM
                        verse_translations
                    WHERE
                        MATCH (text) AGAINST (
                            '$query' IN NATURAL LANGUAGE MODE
                        )
                    ORDER BY
                        rank DESC
                    LIMIT $limit Offset 0";

                    $records = \DB::connection('mysql')->select(\DB::raw($sql));
                    foreach($records as $record){
                        $suggest = collect();
                        $text = $record->text;
                        foreach($words_query as $word_query){
                        $text = preg_replace("/".$word_query."/i", "<em class='hlt1'>\$0</em>", $text);
                    }
                        $suggest->put('text',$text);
                        $verse = Verses::select('chapter_id','verse_number','verse_key')->where('id',$record->verse_id)->first();
                        $href = $verse->chapter_id.'/'.$verse->verse_number.'?translations[]='.$record->resource_id;
                        $suggest->put('href',$href);
                        $suggest->put('ayah',$verse->verse_key);
                        $suggests->push($suggest);
                    }

        }
        return $suggests;
    }
}
