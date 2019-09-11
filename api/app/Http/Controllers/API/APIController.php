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
use App\Models\Words;
use App\Models\CharTypes;
use App\Models\WordTranslation;
use App\Models\Translations;
use Carbon;
use DOMElement;
use XMLWriter;

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
            $translated_names = TranslatedName::select('language_name', 'name')->where('language_id', $language_id)->where('resource_type', 'chapters')->where('resource_id',$id)->get()->toArray();
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
     * @param int $id: Id for chapter (1 to 114)
     * @param string language: To select translation language 'en' by default (can be array) (Optional)
     * @param int page: page number for current chapter max 50 (Optional)
     * @param int limit: Limit verses for current page (10 by default) (Optional)
     * @param int offset: set an offset to get verses
     * @param int recitation: gets recitation audio based on id
     * @return array verses: in single array
     */
    protected function verses($id, Request $request)
    {
        $language = 'en';
        $limit = 10;
        $offset = 0;
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
        )->with('words')
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
                        $audio_file->url = $verse_xml->url->__toString();
                        $audio_file->duration = $verse_xml->duration->__toString();
                        $audio_file->segments = json_decode($verse_xml->segments);
                        $audio_file->format = $information->format->__toString();
                        $verse->setAttribute('audio', $audio_file);
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
                            //$verse->setAttribute('translation', $verse_translation);
                            //$verse->setAttribute('transliteration', $verse_translation);
                            break;
                        }
                    }
                }
                $verse->setAttribute('translations', $translations);
            }
            foreach ($verse->words as $word) {
                $word->code_hex = html_entity_decode($word->code_hex, ENT_NOQUOTES);
                $word->code_hex_v3 = html_entity_decode($word->code_hex_v3, ENT_NOQUOTES);
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
    protected function audio_files($id, $verse_id, Request $request)
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
                        $audio_file->url = $verse->url->__toString();
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
        if(isset($request->q)){
            $query = $request->q;
            $results = Verses::select(
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
                'page_number')
                ->where('text_madani','like','%'.$query.'%')
                ->orWhere('text_indopak','like','%'.$query.'%')
                ->orWhere('text_simple','like','%'.$query.'%')
                ->with('words')->get();
            return ['total_count' => count($results),
                    'results' => $results];
        }
        else
        {
            return ['status' => 'error',
                    'data' => 'Please provide search query'];
        }
    }
}
