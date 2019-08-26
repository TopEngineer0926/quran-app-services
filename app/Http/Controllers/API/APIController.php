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

class APIController extends Controller
{
    //
    protected function chapters($language = 'en')
    {
        $language = Languages::where('iso_code',$language)->first();
        if($language){
            $language_id = $language->id;
            $translated_names = TranslatedName::select('language_name','name')->where('language_id',$language_id)->where('resource_type','chapters')->get()->toArray();
            $chapters = Chapter::get();
            $loop = 0;
            if($chapters){
                foreach($chapters as $chapter){
                    $chapter->pages = json_decode($chapter->pages);
                    $chapter->setAttribute('translated_name',$translated_names[$loop]);
                    $loop++;
                }
            }
            return ['chapters' => $chapters];
        }
        else{
            return[
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }

    protected function chapter_info($id,$language = 'en')
    {
        $chapter_info = null;
        $language = Languages::where('iso_code',$language)->first();
        if($language){
            $chapter_info = ChapterInfos::where('chapter_id',$id)->where('language_id',$language->id)->first();
            return ['chapter_info' => $chapter_info];
        }
        else{
            return[
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }
    protected function chapter($id, $language = 'en')
    {
        $language = Languages::where('iso_code',$language)->first();
        if($language){
            $language_id = $language->id;
            $translated_names = TranslatedName::select('language_name','name')->where('language_id',$language_id)->where('resource_type','chapters')->get()->toArray();
            $chapter = Chapter::where('id',$id)->first();
            $loop = 0;
            if($chapter){

                    $chapter->pages = json_decode($chapter->pages);
                    $chapter->setAttribute('translated_name',$translated_names[$loop]);
                    $loop++;

            }
            return ['chapters' => $chapter];
        }
        else{
            return[
                'status' => 'error',
                'data' => 'Language Not Found',
            ];
        }
    }

    protected function verses($id ,Request $request){
        $language = 'en';
        $limit = 10;
        if(isset($request->language)){
            $language = $request->language;
        }
        if(isset($request->limit)){
            $limit = $request->limit;
        }
        $verses = Verses::select('id','verse_number','chapter_id','verse_key','text_madani',
        'text_indopak','text_simple','juz_number','hizb_number','rub_number','sajdah','sajdah_number',
        'page_number')->with('words:id,position,text_madani,text_indopak,text_simple,verse_key,class_name,
        line_number,page_number,code_hex,code_hex_v3,audio_url')
        ->with('words.translation:translation_id,language_name,text,resource_name,resource_id')
        ->with('words.transliteration:transliteration_id,language_name,text,resource_name,resource_id')
        ->with('words.chartype:id,name')
        ->where('chapter_id',$id)->paginate($limit);

        return ['verses'=>$verses];

    }


}
