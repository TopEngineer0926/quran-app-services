<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\Chapter;
use App\Models\ChapterInfos;
use App\Models\TranslatedName;
use App\Models\Verses;
use App\Models\Words;
use App\Models\CharTypes;
use App\Models\WordTranslation;
use App\Models\Translations;
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
            $translated_names = TranslatedName::select('language_name', 'name')->where('language_id', $language_id)->where('resource_type', 'chapters')->get()->toArray();
            $chapter = Chapter::where('id', $id)->first();
            $loop = 0;
            if ($chapter) {

                $chapter->pages = json_decode($chapter->pages);
                $chapter->setAttribute('translated_name', $translated_names[$loop]);
                $loop++;
            }
            return ['chapters' => $chapter];
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
            ->with('words.translation:translation_id,language_name,text,resource_name,resource_id')
            ->with('words.transliteration:transliteration_id,language_name,text,resource_name,resource_id')
            ->with('words.chartype:id,name')
            ->where('chapter_id', $id)->where('verse_number', '>=', $offset)->paginate($limit);

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
}
