<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\Chapter;
use App\Models\Search;
use App\Models\ChapterInfos;
use App\Models\TranslatedName;
use App\Models\Verses;
use App\Models\Recitations;
use App\Models\AudioFile;
use App\Models\Resource;
use App\Models\Enum;
use App\Models\Words;
use App\Models\CharTypes;
use App\Models\WordTranslation;
use App\Models\Translations;
use Carbon;
use DOMElement;
use XMLWriter;
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
            //->with('words.translation:translation_id,language_name,text,resource_name,resource_id')
            ->with('words.translation')
            //->with('words.transliteration:transliteration_id,language_name,text,resource_name,resource_id')
            ->with('words.transliteration')
            ->with('words.chartype:id,name')
            ->where('chapter_id', $id)->where('verse_number', '>=', $offset)->paginate($limit);
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
                        array_push($audio_files, $audio_file);
                        //$verse->setAttribute('audio', $audio_file);
                        break;
                    }
                }
                $verses->put('audio_files', $audio_files);
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
                            //$verse->setAttribute('translation', $verse_translation);
                            //$verse->setAttribute('transliteration', $verse_translation);
                            break;
                        }
                    }
                }
                $verse->setAttribute('translations', $translations);
            }
        }
        return ['verses' => $verses];
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
                    $audio_file->url = $verse->url->__toString();
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

    protected function search(Request $request)
    {
        $query = null;
        $results = collect([]);
        $result = null;
        if (isset($request->q)) {
            $query = $request->q;
            if (preg_match("/:/", $query)) {
                $result = Verses::select(
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
                )
                    ->where('verse_key', $query)
                    ->with('words')->get();
                //$results = $results->push($result);
                $results = $results->merge($result);
            } else {
                $result = Verses::select(
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
                )
                    ->where('text_madani', 'like', '%' . $query . '%')
                    ->orWhere('text_indopak', 'like', '%' . $query . '%')
                    ->orWhere('text_simple', 'like', '%' . $query . '%')
                    ->with('words')->get();
                $results = $results->merge($result);

                $paths = array();
                $resources = Resource::where('type', Enum::resource_table_type['options'])
                    ->where('sub_type', Enum::resource_table_subtype['translations'])
                    ->with('source')->get();
                foreach ($resources as $resource) {
                    $paths[$resource->id] = $resource->source->url;
                }
                foreach ($paths as $path) {
                    $xml = simplexml_load_file($path);
                    $information = $xml->information;
                    $verses_xml = $xml->verses;
                    foreach ($verses_xml->verse as $verse_xml) {
                        if (preg_match('/\b' . $query . '(?=$|\s)/', $verse_xml->text->__toString())) {
                            $verse_translation = collect();
                            $verse_translation->put('id', $verse_xml->id->__toString());
                            $verse_translation->put('language_name', $information->language_name->__toString());
                            $text = str_ireplace($query, '<em class="hlt1">' . $query . '</em>', $verse_xml->text->__toString());
                            $verse_translation->put('text', $text);
                            $verse_translation->put('resource_name', $information->resource_name->__toString());
                            $verse_translation->put('resource_id', $information->resource_id->__toString());
                            $results = $results->push($verse_translation);
                        }
                    }
                }
            }
            return $results;
        } else {
            return [
                'status' => 'error',
                'data' => 'Please provide search query'
            ];
        }
        $p = 1;
        $limit = 20;
        if (isset($request->p)) {
            $p = $request->p;
        }
        if (isset($request->limit)) {
            $limit = $request->limit;
        }
        $count = count($results);
        $results = $results->chunk($limit);
        return [
            'query' => $query,
            'total_count' => $count,
            'current_page' => (int) $p,
            'total_pages' => (int) ($count / $limit) + 1,
            'per_page' => (int) $limit,
            'results' => $results[$p - 1]
        ];
    }

    protected function test()
    {
        // $path = 'al-hasan-efendi.xml';
        // $xml = simplexml_load_file($path);
        // $results = $xml->xpath('//xml/verses/verse/text[contains(text()," mëdhenj ")]');
        // return $results;
        $page = 1;
        $perpage = 1;
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $t1 = "b";
        $pagelinks = $this->makeLengthAware($collection, count($collection), $perpage, ['t1' => $t1]);
        return $pagelinks;
    }

    public static function makeLengthAware($collection, $total, $perPage, $appends = null)
    {
        $paginator = new LengthAwarePaginator(
            $collection,
            $total,
            $perPage,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        );

        if ($appends)
            $paginator->appends($appends);

        return str_replace('/?', '?', $paginator->render());
    }


    protected function search2(Request $request)
    {
        $verse_ids = array();
        $limit = 20;
        $page = 1;

        if (isset($request->p)) {
            $page = $request->p;
        }
        if (isset($request->limit)) {
            $limit = $request->limit;
        }

        $finalResult = ['total-count', 'page_no', 'data'];

        // $query = 'Allah';
        $query = 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ';
        if (isset($request->q)) {
            $query = $request->q;
        }
        //$query = 'اللَّهِ';
        // $query = 'الله‎';


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
                    LIMIT $limit Offset $page";


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
        //return ['count' => $count, 'data' =>$records];

        $previous_records = ($page - 1) * $limit;

        $total_pages = ceil($total_count / $limit);

        foreach ($records as $record) {
            array_push($verse_ids, $record->verse_id);
        }
        //return $verse_ids;
        $results = Verses::select(
            'id',
            'verse_number',
            'chapter_id',
            'verse_key',
            'text_madani',
        )->whereIn('id', $verse_ids)
            ->with('words')
            ->with('words.translation')
            ->with('words.transliteration')
            ->with('words.chartype:id,name')
            ->get();

        return $results;

        //$records = collect($records;
        //
        //
        //        $pageNo = 1;
        //        $paginate = 50;
        //        $fullTextSearchCount = 0;
        //        $fullTextSearchPageCount = 0;
        //
        ////        $fullTextSearchCount = Search::search($query)->get()->groupBy('verse_id')->count();
        ////
        ////        if (isset($fullTextSearchCount) && !empty($fullTextSearchCount)) {
        ////            $fullTextSearchPageCount = ceil($fullTextSearchCount / $paginate);
        ////            $fullTextSearchPageCount = (int)$fullTextSearchPageCount;
        ////        }
        //
        //
        //        \DB::enableQueryLog();
        //
        //        // $fullTextSearchCount = Search::search($query)->paginate($paginate, '', $pageNo);
        //        $fullTextSearchCount = Search::search($query)->get()->groupBy('verse_id');
        //
        //
        //        dd(\DB::getQueryLog());
        //
        //
        //        dd($fullTextSearchCount->toArray());
        //// ->load('words')
        //
        //
        //        //dd($fullTextSearchPageCount);
        //
        //
        //        $fullTextSearch = Search::search($query)->get()->groupBy('verse_id')->toArray();
        //
        //
        //        $translationResult = [];
        //        $translationResult = $this->translationRecord($query, true);
        //        $translationCount = isset($translationResult['total_rec']) && !empty($translationResult['total_rec']) ? $translationResult['total_rec'] : 0;
        //
        //        $totalRecordCount = $totalPages = 0;
        //        $totalRecordCount = $fullTextSearchPageCount + $translationCount;
        //        $totalPages = ceil($totalRecordCount / $paginate);
        //        $totalPages = (int)$totalPages;
        //
        //
        //        $result = [];
        //        if ($pageNo < ($fullTextSearchPageCount - 1)) {
        //            $result = Verses::search($query)->paginate($paginate, '', $pageNo)->load('words');
        //            $result = $result->toArray();
        //
        //        } else if ($pageNo == ($fullTextSearchPageCount)) {
        //
        //            $fullTextSearch = Verses::search($query)->paginate($paginate, '', $pageNo)->load('words');
        //            $fullTextSearch = $fullTextSearch->toArray();
        //
        //
        //            $moreNeedToFetch = $paginate;
        //            $moreNeedToFetch = $paginate - count($fullTextSearch);
        //
        //
        //            $result = Verses::with('words')
        //                ->where("id", "<", "100")
        //                ->paginate($moreNeedToFetch);
        //            $result = $result->items();
        //            foreach ($result as $r) {
        //                $fullTextSearch[] = $r->toArray();
        //
        //            }
        //
        //        } else if ($pageNo > ($fullTextSearchPageCount)) {
        //
        //
        //            $iDs = [];
        //            $paths = array();
        //            $resources = Resource::where('type', Enum::resource_table_type['options'])
        //                ->where('sub_type', Enum::resource_table_subtype['translations'])
        //                ->with('source')->get();
        //            foreach ($resources as $resource) {
        //                $paths[$resource->id] = $resource->source->url;
        //            }
        //
        //            foreach ($paths as $path) {
        //                $xml = simplexml_load_file($path);
        //                $information = $xml->information;
        //                $verses_xml = $xml->verses;
        //                foreach ($verses_xml->verse as $verse_xml) {
        //                    if (preg_match('/\b' . $query . '(?=$|\s)/i', $verse_xml->text->__toString())) {
        //                        $verse_translation = collect();
        //
        //                        $iDs[$verse_xml->verse_id->__toString()] = $verse_xml->verse_id->__toString();
        //                        $verse_translation->put('id', $verse_xml->id->__toString());
        //                        $verse_translation->put('verse_id', $verse_xml->verse_id->__toString());
        //                        $verse_translation->put('language_name', $information->language_name->__toString());
        //                        $text = str_ireplace($query, '<em class="hlt1">' . $query . '</em>', $verse_xml->text->__toString());
        //                        $verse_translation->put('text', $text);
        //                        $verse_translation->put('resource_name', $information->resource_name->__toString());
        //                        $verse_translation->put('resource_id', $information->resource_id->__toString());
        //
        //
        //                        //  $results = $results->push($verse_translation);
        //                    }
        //                }
        //            }
        //
        //
        //            $iDs = array_values($iDs);
        //
        //            $result = Verses::with('words')
        //                ->whereIn("id", $iDs)
        //                ->paginate($paginate, '*', '', 3);
        //
        //
        //            $result = $result->items();
        //            foreach ($result as $r) {
        //                $fullTextSearch[] = $r->toArray();
        //            }
        //
        //            $result = $fullTextSearch;
        //
        //
        //        }
        //
        //
        //        //$fullTextSearchPageCount &&
        //        dd($result);


    }
}
