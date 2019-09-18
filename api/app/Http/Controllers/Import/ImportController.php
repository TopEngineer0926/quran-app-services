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
use App\Models\Recitations;
use App\Models\QuranChapter;
use App\Models\Resource;
use App\Models\Source;
use App\Models\Author;
use App\Models\MediaContents;
use App\Models\Enum;
use App\Models\CharTypes;
use App\Models\VerseTranslations;
use App\Models\Search;
use DOMDocument;
use Illuminate\Support\Facades\DB;
use Storage;
use XMLWriter;
use Log;
use Artisan;

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

    protected function verses(Request $request)
    {
        $curl = new Curl;
        //$url = Curl::url_verses;
        $url = Curl::url_verses_live;
        $name = Curl::name_verses;
        $results = array();
        $count = 0; // language = 38
        $paths = array();
        $xmls = array();
        $resources = Resource::with('source')->get();
        foreach ($resources as $resource) {
            $paths[$resource->id] = $resource->source->url;
            $url = $url . '&translations[]=' . $resource->id;
        }
        if (isset($request->truncate) && $request->truncate == 1) {
            Verses::truncate();
            Words::truncate();
            Translations::truncate();
            WordTranslation::truncate();
            Transliterations::truncate();
            WordTransliteration::truncate();
            CharTypes::truncate();
            MediaContents::truncate();
        }
        foreach ($paths as $key => $path) {
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->openUri($path);
            $xmls[$key] = $xml;
        }
        foreach ($xmls as $key => $xml) {
            $resource = Resource::where('id', $key)->with('source')->first();
            $language = Language::where('iso_code', $resource->language_code)->first();
            $xml->startDocument('1.0', 'utf-8'); //start document [1]
            $xml->startElement('xml'); //start xml tag [2]
            $xml->startElement('information'); //start information tag [3]
            $xml->writeElement('language_id', $language->id); //write element language_id
            $xml->writeElement('language_name', $language->name); //write element language_name | $recitations->where('id',$loop)->first()->reciter_name
            $xml->writeElement('resource_id', $key); //write element resource_id
            $xml->writeElement('resource_name', $resource->name); //write element resource_name
            $xml->endElement(); // end information tag [3]
            $xml->startElement('verses'); // start verses [4]
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
                        $verse->page_number = $result->page_number;
                        $verse->save();

                        foreach ($result->media_contents as $media_content) {
                            $media = new  MediaContents;
                            $media->resource_type = Enum::resource_type_media_contents['verse'];
                            $media->resource_id = $verse->id;
                            $media->url  = $media_content->url;
                            $media->embed_text = $media_content->embed_text;
                            $media->provider = $media_content->provider;
                            $media->author_name = $media_content->author_name;
                            $media->save();
                        }
                        foreach ($result->translations as $translation) {
                            $xmls[$translation->resource_id]->startElement('verse'); //start verse [5]
                            //data here
                            $xmls[$translation->resource_id]->writeElement('chapter_id', $verse->chapter_id); // //write element chapter_id
                            $xmls[$translation->resource_id]->writeElement('verse_id', $verse->id); // //write element verse_id
                            $xmls[$translation->resource_id]->writeElement('verse_number', $verse->verse_number); // //write element verse_number
                            $xmls[$translation->resource_id]->writeElement('text', $translation->text); // //write translatin text
                            $xmls[$translation->resource_id]->writeElement('id', $translation->id); // //write id of translation
                            //data end here
                            $xmls[$translation->resource_id]->endElement(); // end verse [5]
                            //end for loop here
                        }

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
                                $char_type = CharTypes::where('name', $result_word->char_type)->first();
                                if (!$char_type) {
                                    $char_type = new CharTypes;
                                    $char_type->name = $result_word->char_type;
                                    $char_type->save();
                                }
                                $word->char_type_id = $char_type->id;
                                $word->audio_url = $result_word->audio->url;
                                $word->save();

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
        foreach ($xmls as $xml) {
            $xml->endElement(); // end verses [4]
            $xml->endElement(); // end xml tag [2]
            $xml->endDocument(); //end document [1]
        }
        return [
            'status' => $count . " verses successfully inserted",
            'data' => "Not shown intentionally (Would be Too Long)",
        ];
    }

    protected function create_wbw_index($truncate = 0) // not needed anymore
    {
        if ($truncate == 1) {
            $path = 'index.html';
            Storage::disk('public')->put($path, '<html>
        <head><title>Index of /wbw/</title></head>
        <body bgcolor="white">
        <h1>Index of /wbw/</h1><hr><pre>');
        }
        $words = Words::get();
        $count = 0;
        foreach ($words as $word) {
            $file = explode('/', $word->audio_url);
            $file_name = end($file);
            Storage::disk('public')->append($path, '<a href="' . $file_name . '">' . $file_name . '</a>                                         27-Jul-2015 08:47               97809');
            $count++;
        }
        Storage::disk('public')->append($path, '</pre><hr></body>
        </html>');

        return [
            'status' => $count . " indexes created successfully",
            'data' => $words->first()->audio_url . " to " . $words->last()->audio_url,
        ];
    }
    protected function update_pages() // not needed anyomore
    {
        $verses = Verses::get();
        foreach ($verses as $verse) {
            $word = Words::where('verse_id', $verse->id)->first();
            $verse->page_number = $word->page_number;
            $verse->save();
        }
    }
    protected function testxml()
    {
        $path = "test.xml";
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->openUri($path);
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('xml');
        $xml->startElement('info');
        $xml->writeElement('version', '2.0');
        $xml->endElement();
        for ($i = 0; $i < 6666; $i++) {
            $xml->startElement('data');
            $xml->writeElement('data', $i);
            $xml->endElement();
            //$xml->writeAttribute('id', $i);
        }
        $xml->endElement();
        $xml->endDocument();

        $content = $xml->outputMemory();
        $xml = null;

        return response($content)->header('Content-Type', 'text/xml');
    }
    protected function getxml()
    {
        $return = array();
        $path = "test.xml";
        $xml = simplexml_load_file($path);
        $datas = $xml->data;
        $version = $xml->info;
        echo $version->version;
        foreach ($datas as $data) {
            if ($data->data <= 10) {
                echo $data->data;
            }
        }
        return $return;
    }


    protected function update_chapters() // not needed anymore
    {
        $quran_chapters = QuranChapter::get();
        $loop = 1;
        $result = array();
        $chapters = Chapter::get();
        foreach ($quran_chapters as $chapter) {

            //echo $chapter->article_informaltable_tgroup_tbody_row_entry_para.'\n';
            array_push($result, $chapter->name);
            $chapter_name = $chapters->where('id', $chapter->id)->first();
            $chapter_name->name_arabic = $chapter->name;
            $chapter_name->save();
        }
        return $result;
    }

    protected function audiofiles(Request $request)
    {
        $curl = new Curl;
        $url = Curl::url_audio_files;
        $name = Curl::name_audio_files;
        $results = array();
        $count = 0;
        $paths = array();
        $xmls = array();
        $recitations = Recitations::get();
        foreach ($recitations as $recitation) {
            $paths[$recitation->id] = $recitation->file_name;
            // if (isset($request->truncate)&&$request->truncate == 1) {
            //     foreach ($paths as $path) {
            //         Storage::disk('public')->put($path, '');
            //     }
            // }
        }
        foreach ($paths as $path) {
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->openUri($path);
            array_push($xmls, $xml);
        }
        if (isset($request->truncate) && $request->truncate == 1) {
            foreach ($xmls as $xml) {
                $xml->startDocument('1.0', 'utf-8'); //start document [1]
                $xml->startElement('xml'); //start xml tag [2]
                $xml->startElement('information'); //start information tag [3]
                $xml->writeElement('reciter_id', 1); //write element reciter id
                $xml->writeElement('reciter_name', 'name'); //write element reciter_name | $recitations->where('id',$loop)->first()->reciter_name
                $xml->writeElement('reciter_style_id', 1); //write element
                $xml->writeElement('reciter_style_name', 'style'); //write element
                $xml->writeElement('format', 'mp3'); //write element format => mp3
                $xml->writeElement('base_url', 'base'); //write element
                $xml->endElement(); // end information tag [3]
                $xml->startElement('verses'); // start verses [4]
            }
        }
        if (isset($request->verse_id) && isset($request->mode) && $request->mode == 'single') {
            $verses = Verses::where('id', $request->verse_id)->get();
        }
        //for loop here
        else if (isset($request->verse_id)) {
            $verses = Verses::where('id', '>=', $request->verse_id)->get();
        } else {
            $verses = Verses::get();
        }
        foreach ($verses as $verse) {
            $loop_url = \str_replace("{id}", $verse->chapter_id, $url);
            $loop_url = \str_replace("{verse_id}", $verse->id, $loop_url);
            Log::info('Loop URL = ' . $loop_url);
            $results = $curl->curl($loop_url, $name);
            $result_loop = 0;
            foreach ($results as $result) {
                $xmls[$result_loop]->startElement('verse'); //start verse [5]
                //data here
                $xmls[$result_loop]->writeElement('verse_id', $verse->id); // //write element verse_id
                $xmls[$result_loop]->writeElement('chapter_id', $verse->chapter_id); // //write element chapter_id
                $xmls[$result_loop]->writeElement('verse_number', $verse->verse_number); // //write element verse_id
                $audio_url = explode('/', $result->url);
                $audio_url = end($audio_url);
                $xmls[$result_loop]->writeElement('url', $audio_url); // //write element url (audio name)
                $xmls[$result_loop]->writeElement('duration', $result->duration); // //write element duration
                $xmls[$result_loop]->writeElement('segments', json_encode($result->segments)); // //write element segments
                //data end here
                $xmls[$result_loop]->endElement(); // end verse [5]
                //end for loop here
                $result_loop++;
                $count++;
            }
        }
        foreach ($xmls as $xml) {
            $xml->endElement(); // end verses [4]
            $xml->endElement(); // end xml tag [2]
            $xml->endDocument(); //end document [1]
        }

        return [
            'status' => 'success',
            'data' => $count . 'data values successfully added in ' . count($paths) . ' files'
        ];
    }

    protected function chapter_info_description() //not needed any more
    {
        $chapter_infos = ChapterInfo::select('id', 'text')->get();
        $result = array();
        $dom = new DOMDocument();
        $loop = 0;

        foreach ($chapter_infos as $chapter_info) {
            //     preg_match_all('/\<\w[^<>]*?\>([^<>]+?\<\/\w+?\>)?|\<\/\w+?\>/i', $chapter_info->text, $matches);
            $nodes = array();
            $dom->loadHTML($chapter_info->text);
            foreach ($dom->getElementsByTagName('*') as $element) {
                if ($element->tagName != "html"  && $element->tagName != "body") {
                    $node = array();
                    $node = [$element->tagName => $element->nodeValue];
                    array_push($nodes, $node);
                }
            }
            $chapter_info->text = json_encode($nodes);
            $chapter_info->save();
            $loop++;
        }



        return [
            'status' => 'success',
            'data' => $loop . ' data changed',
            //'result' => $result
        ];
    }
    protected function options_translations(Request $request)
    {
        $url = Curl::url_options_translations;
        $name = Curl::name_options_translations;
        $curl = new Curl;
        $results = $curl->curl($url, $name);
        if (isset($request->truncate) && $request->truncate == 1) {
            Resource::truncate();
            Author::truncate();
            Source::truncate();
        }
        foreach ($results as $result) {
            $resource = new Resource;
            $resource->id = $result->id;
            $resource->slug = $result->slug;
            $resource->name = $result->name;
            $resource->type = Enum::resource_table_type['options'];
            $resource->sub_type = Enum::resource_table_subtype['translations'];
            $author = Author::where('name', $resource->author_name)->first();
            if (!$author) {
                $author = new Author;
                $author->name = $result->author_name;
                $author->save();
            }
            $resource->author_id = $author->id;
            $language = Language::where('name', $result->language_name)->first();
            $resource->language_code = $language->iso_code;
            $resource->is_available = 1;
            $source = Source::where('name', $resource->name)->first();
            if (!$source) {
                $source = new Source;
                $source->name = $resource->name;
                $url = $resource->slug . '.xml';
                $source->url = $url;
                $source->save();
            }
            $resource->source_id = $source->id;
            $resource->description = 'List of verse translations and authors';
            $resource->save();
        }

        return [
            'status' => 'success',
            'data' => count($results) . ' data inserted successfully',
            'results' => $results
        ];
    }

    protected function translations()
    { // to import translations of verse in different languages
        $verses = Verses::where('page_number', '<=', 5)->get();
        $verses = collect($verses);
        $verses = $verses->groupBy('page_number');
        foreach ($verses as $key => $verse) {
            echo $key;
        }
        return 'done';
        $url = Curl::url_translations;
        $translations = Resource::get();
        foreach ($translations as $translation) {
            $url = $url . '&translations[]=' . $translation->id;
        }
        $name = Curl::name_verses;
        $curl = new Curl;
        $page = 1;
        for ($chapter = 1; $chapter <= 2; $chapter++) {
            $loop_url = \str_replace("{id}", $chapter, $url);
            $loop_url = \str_replace("{page}", $page, $loop_url);
            $results = $curl->curl($loop_url);
            foreach ($results[$name] as $result) {
                foreach ($result->translations as $translation) {
                    echo 'chapter = ' . $chapter . 'verse =' . $result->verse_number . 'page = ' . $page . '\n';
                    //store here
                }
            }
            if (!$results['meta']->next_page) {
                $page = 1;
            } else {
                $page = $results['meta']->next_page;
            }
        }
        return 'done';
    }

    function str_split_unicode($str, $l = 0)
    {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function char_encode($string)
    {
        if ($string) {
            return '&#x' . dechex(mb_ord($string)) . ';';
        } else {
            return $string;
        }
    }

    protected function update_code()
    {
        $path = 'MushafAlMadinahScript.txt';
        $file = Storage::disk('public')->get($path);
        $lines = explode("\n", $file);
        $count = 1;
        foreach ($lines as $line) {
            $words = explode('|', $line);
            $chapter_id = $words[0];
            $verse_number = $words[1];
            $verses = Verses::where('chapter_id', $chapter_id)->where('verse_number', $verse_number)->with('words')->first();
            $word_verse = end($words);
            $word_verse = $this->str_split_unicode($word_verse);

            foreach ($word_verse as $key => $word) {
                $word_verse[$key] = $this->char_encode($word);
                $words = $verses->words->where('position', $key + 1)->first();
                if ($words) {
                    $words->code = $word_verse[$key];
                    $words->save();
                } else {
                    Log::alert('Word Not Found for position: ' . ($key + 1) . ' verse number: ' . $verse_number . ' chapter_id: ' . $chapter_id . ' and character is: ' . $word_verse[$key]);
                }

                //$this->char_encode($word)
                $count++;
            }
        }
        return $count . 'codes updated';
    }

    protected function chapter_resource()
    {
        $names = TranslatedName::where('resource_type', 'languages')->get();
        $loop = 1;
        foreach ($names as $name) {
            $name->resource_id = Language::where('name', $name->name)->first()->id;
            $name->language_priority = null;
            $name->save();
            $loop++;
        }
        return $loop . ' records updated successfully';
    }
    protected function translated_names(Request $request)
    {
        $language = null;
        if (isset($request->language)) {
            $language = $request->language;
        }
        $url = Curl::url_chapters;
        $name = Curl::name_chapters;
        $url = \str_replace("{language}", $language, $url);
        $curl = new Curl;
        $results = $curl->curl($url, $name);
        $language_id = Language::where('iso_code', $language)->first()->id;
        foreach ($results as $result) {
            $name = new TranslatedName;
            $name->resource_type = 'chapters';
            $name->resource_id = $result->id;
            $name->language_id = $language_id;
            $name->name = $result->translated_name->name;
            $name->language_name = $result->translated_name->language_name;
            $name->save();
        }
    }

    protected function verse_translations()
    {
        $count = 0;
        $paths = array();
        $resources = Resource::where('type', Enum::resource_table_type['options'])
                    ->where('sub_type', Enum::resource_table_subtype['translations'])
                    ->with('source')->get();
        foreach ($resources as $resource) {
            array_push($paths,$resource->source->url);
        }
        foreach($paths as $path){
        $xml = simplexml_load_file($path);
        $verses_xml = $xml->verses;
        $information = $xml->information;
        $resource_id = Resource::where('id',$information->resource_id)->first()->id;
        foreach ($verses_xml->verse as $verse_xml) {
            $count++;
            $verse_t = new VerseTranslations;
            $verse_t->id = $verse_xml->id->__toString();
            $verse_t->verse_id = $verse_xml->verse_id->__toString();
            $verse_t->text = $verse_xml->text->__toString();
            $verse_t->resource_id = $resource_id;
            $verse_t->save();
        }
    }
        return $count .' records inserted';
        //return VerseTranslations::where('text','LIKE','%Ata%')->get();
    }
    protected function search()
    {
        $paths = array();
        $verses = Verses::get();
        $resources = Resource::where('type', Enum::resource_table_type['options'])
                    ->where('sub_type', Enum::resource_table_subtype['translations'])
                    ->with('source')->get();
        foreach ($resources as $resource) {
            array_push($paths,$resource->source->url);
        }
        foreach($verses as $verse)
        {
            $count = 0;
            $search = new Search;
            $search->verse_id = $verse->id;
            $search->content = $verse->text_madani;
            $search->save();
            $count++;
            $search = new Search;
            $search->verse_id = $verse->id;
            $search->content = $verse->text_indopak;
            $search->save();
            $count++;
            $search = new Search;
            $search->verse_id = $verse->id;
            $search->content = $verse->text_simple;
            $search->save();
            $count++;
            foreach($paths as $path)
            {
                $xml = simplexml_load_file($path);
                $verses_xml = $xml->verses;
                foreach($verses_xml->verse as $verse_xml)
                {
                    if($verse_xml->verse_id == $verse->id)
                    {
                        $search = new Search;
                        $search->verse_id = $verse->id;
                        $search->content = $verse_xml->text->__toString();
                        $search->save();
                        $count++;
                        break;
                    }
                }
            }
        }
        return $count. ' records inserted';
    }
    // protected function fixwbwaudiourl()
    // {
    //     $words = Words::get();

    //     foreach($words as $word)
    //     {
    //         $word->audio_url =
    //     }
    // }
}
