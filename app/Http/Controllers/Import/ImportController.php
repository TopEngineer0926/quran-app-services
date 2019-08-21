<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Curl;
use App\Models\Language;
use App\Models\Chapter;
use App\Models\TranslatedName;
use App\Models\ChapterInfo;
use App\Models\Juzs;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{

    //
    protected function all()
    {
        $results = array();

        array_push($results, $this->languages());
        array_push($results, $this->chapters());
        array_push($results, $this->chapter_info());
        array_push($results, $this->juzs());
        return $results;
    }

    protected function languages()
    {
        $curl = new Curl;
        $url = Curl::url_languages;
        $name = Curl::name_languages;
        $results = $curl->curl($url, $name);
        $count = 0;
        foreach ($results as $result) {
            if (Language::find($result->id)) {
                continue;
            } else {
                $language = new Language;
                $translated_name = new TranslatedName;
                $language->id = $result->id;
                $language->name = $result->name;
                $language->iso_code = $result->iso_code;
                $language->native_name = $result->native_name;
                $language->direction = $result->direction;
                $translated_names = $result->translated_names[0];
                $translated_name->language_name = $translated_names->language_name;
                $translated_name->name = $translated_names->name;
                $translated_name->resource_type = $name;
                $translated_name->language_id = $language->id;
                $language->save();
                $translated_name->save();
                $count++;
            }
        }

        return [
            'status' => $count . " results successfully inserted",
            'data' => $results,
        ];
    }
    protected function chapters()
    {
        $curl = new Curl;
        $url = Curl::url_chapters;
        $name = Curl::name_chapters;
        $results = $curl->curl($url, $name);
        $count = 0;
        foreach ($results as $result) {
            if (Chapter::find($result->id)) {
                continue;
            } else {
                $chapter = new Chapter;
                $translated_name = new TranslatedName;
                $chapter->id = $result->id;
                $chapter->chapter_number = $result->chapter_number;
                $chapter->bismillah_pre = $result->bismillah_pre;
                $chapter->revelation_order = $result->revelation_order;
                $chapter->revelation_place = $result->revelation_place;
                $chapter->name_complex = $result->name_complex;
                $chapter->name_arabic = $result->name_arabic;
                $chapter->name_simple = $result->name_simple;
                $chapter->verses_count = $result->verses_count;
                $chapter->pages = json_encode($result->pages);
                $translated_names = $result->translated_name;
                $translated_name->language_name = $translated_names->language_name;
                $translated_name->name = $translated_names->name;
                $translated_name->resource_type = $name;
                $chapter->save();
                $translated_name->save();
                $count++;
            }
        }
        return [
            'status' => $count . " results successfully inserted",
            'data' => $results,
        ];
    }

    protected function chapter_info()
    {
        $curl = new Curl;
        $url = Curl::url_chapter_info;
        $name = Curl::name_chapter_info;
        $results = array();
        $count = 0;
        for ($i = 1; $i <= 114; $i++) {
            $loop_url = \str_replace("{id}", $i, $url);
            $result = $curl->curl($loop_url, $name);
            if (ChapterInfo::find($result->chapter_id)) {
                continue;
            } else {
                $chapter_info = new ChapterInfo;
                $chapter_info->chapter_id = $result->chapter_id;
                $chapter_info->text = $result->text;
                $chapter_info->source = $result->source;
                $chapter_info->short_text = $result->short_text;
                $chapter_info->language_name = $result->language_name;
                $chapter_info->language_id = 38;
                $chapter_info->save();
                $count++;
                array_push($results, $result);
            }
        }
        return [
            'status' => $count . " results successfully inserted",
            'data' => $results,
        ];
    }

    protected function juzs()
    {
        $curl = new Curl;
        $url = Curl::url_juzs;
        $name = Curl::name_juzs;
        $results = $curl->curl($url, $name);
        $count = 0;
        foreach ($results as $result) {
            if (Juzs::find($result->id)) {
                continue;
            } else {
                $juzs = new Juzs;
                $juzs->id = $result->id;
                $juzs->juz_number = $result->juz_number;
                $juzs->verse_mapping = json_encode($result->verse_mapping);
                $juzs->save();
                $count++;
            }
        }
        return [
            'status' => $count . " results successfully inserted",
            'data' => $results,
        ];
    }
    protected function audio(){
        $url = "http://verses.quran.com/AbdulBaset/Mujawwad/mp3/001001.mp3";
    }
}
