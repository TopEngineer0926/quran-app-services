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
use App\Models\Verses;
use App\Models\Words;
use App\Models\Translations;
use App\Models\Transliterations;
use App\Models\WordTranslation;
use App\Models\WordTransliteration;
use Illuminate\Support\Facades\DB;
use Storage;

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
    protected function audio()
    {
        $url = "http://verses.quran.com/AbdulBaset/Mujawwad/mp3/001001.mp3";
    }

    protected function wbw_index_generator()
    {
        $path = 'index.html';

        Storage::disk('public')->put($path, '<html>
        <head><title>Index of /wbw/</title></head>
        <body bgcolor="white">
        <h1>Index of /wbw/</h1><hr><pre>');

        $curl = new Curl;
        $url = Curl::url_verses;
        $name = Curl::name_verses;
        $results = array();

        for ($surah = 1; $surah <= 114; $surah++) {
            $loop_url_id = \str_replace("{id}", $surah, $url);
            $page = 1;
            $loop_url = \str_replace("{page}", $page, $loop_url_id);
            while ($results = $curl->curl($loop_url, $name)) {
                foreach ($results as $result) {
                    foreach ($result->words as $word) {
                        $file = explode('/', $word->audio->url);
                        $file_name = end($file);
                        Storage::disk('public')->append($path, '<a href="' . $file_name . '">' . $file_name . '</a>                                         27-Jul-2015 08:47               97809');
                    }
                }
                $page++;
                $loop_url = \str_replace("{page}", $page, $loop_url_id);
            }
        }
        Storage::disk('public')->append($path, '</pre><hr></body>
        </html>');
    }


    public function does_url_exist($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        //curl_setopt($ch,CURLOPT_TIMEOUT,0);
        // set_time_limit (0);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    protected function verses($truncate = 0)
    {
        $curl = new Curl;
        $url = Curl::url_verses;
        $name = Curl::name_verses;
        $results = array();
        $count = 0; // language = 38
        $path = 'index.html';

        Storage::disk('public')->put($path, '<html>
        <head><title>Index of /wbw/</title></head>
        <body bgcolor="white">
        <h1>Index of /wbw/</h1><hr><pre>');
        if($truncate == 1){
            Verses::truncate();
            Words::truncate();
            Translations::truncate();
            WordTranslation::truncate();
            Transliterations::truncate();
            WordTransliteration::truncate();
        }
        for ($surah = 1; $surah <= 114; $surah++) {
            $loop_url_id = \str_replace("{id}", $surah, $url);
            $page = 1;
            $loop_url = \str_replace("{page}", $page, $loop_url_id);
            while ($results = $curl->curl($loop_url, $name)) {
                foreach ($results as $result) {
                    if (!Verses::find($result->id)) {
                        $verse = new Verses;
                        $verse->id = $result->id;
                        $verse->verse_number = $result->verse_number;
                        $verse->chapter_id = $result->chapter_id;
                        $verse->verse_key = $result->verse_key;
                        $verse->text_madani = $result->text_madani;
                        $verse->text_indopak = $result->text_indopak;
                        $verse->text_simple = $result->text_simple;
                        $verse->juz_number = $result->juz_number;
                        $verse->hizb_number = $result->hizb_number;
                        $verse->rub_number = $result->rub_number;
                        $verse->sajdah = $result->sajdah;
                        $verse->sajdah_number = $result->sajdah_number;
                        $verse->page_number = $result->verse_number;
                        $verse->save();
                        foreach ($result->words as $result_word) {
                            if (!Words::find($result_word->id)) {
                                $word = new Words;
                                $word->id = $result_word->id;
                                $word->position = $result_word->position;
                                $word->verse_id = $result->id;
                                $word->chapter_id = $result->chapter_id;
                                $word->text_madani = $result_word->text_madani;
                                $word->text_indopak = $result_word->text_indopak;
                                $word->text_simple = $result_word->text_simple;
                                $word->verse_key = $result_word->verse_key;
                                $word->class_name = $result_word->class_name;
                                $word->line_number = $result_word->line_number;
                                $word->page_number = $result_word->page_number;
                                $word->code_hex = $result_word->code;
                                $word->code_hex_v3 = $result_word->code_v3;
                                $word->char_type_id = 1;
                                $word->audio_url = $result_word->audio->url;
                                $word->save();

                                $file = explode('/', $result_word->audio->url);
                                $file_name = end($file);
                                Storage::disk('public')->append($path, '<a href="' . $file_name . '">' . $file_name . '</a>                                         27-Jul-2015 08:47               97809');

                                if ($result_word->translation != null && !Translations::find($result_word->translation->id)) {
                                    $translation = new Translations;
                                    $translation->id = $result_word->translation->id;
                                    $translation->language_name = $result_word->translation->language_name;
                                    $translation->text = $result_word->translation->text;
                                    $translation->resource_name = $result_word->translation->resource_name;
                                    $translation->resource_id = $result_word->translation->resource_id;
                                    $translation->save();
                                    if (!WordTranslation::where('translation_id', $result_word->translation->id)->where('word_id', $result_word->id)->first()) {
                                        $word_translation = new WordTranslation;
                                        $word_translation->translation_id = $result_word->translation->id;
                                        $word_translation->word_id = $result_word->id;
                                        $word_translation->language_code = 'en';
                                        $word_translation->save();
                                    }
                                }
                                if ($result_word->transliteration != null && !Transliterations::find($result_word->transliteration->id)) {
                                    $transliteration = new Transliterations;
                                    $transliteration->id = $result_word->transliteration->id;
                                    $transliteration->language_name = $result_word->transliteration->language_name;
                                    $transliteration->text = $result_word->transliteration->text;
                                    $transliteration->resource_name = $result_word->transliteration->resource_name;
                                    $transliteration->resource_id = $result_word->transliteration->resource_id;
                                    $transliteration->save();
                                    if (!WordTransliteration::where('transliteration_id', $result_word->transliteration->id)->where('word_id', $result_word->id)->first()) {
                                        $word_transliteration = new WordTransliteration;
                                        $word_transliteration->transliteration_id = $result_word->transliteration->id;
                                        $word_transliteration->word_id = $result_word->id;
                                        $word_transliteration->language_code = 'en';
                                        $word_transliteration->save();
                                    }
                                }
                            }
                        }
                        $count++;
                    }
                }
                $page++;
                $loop_url = \str_replace("{page}", $page, $loop_url_id);
            }
        }
        Storage::disk('public')->append($path, '</pre><hr></body>
        </html>');
        return [
            'status' => $count . " verses successfully inserted",
            'data' => "Not shown intentionally (Would be Too Long)",
        ];
    }
}
