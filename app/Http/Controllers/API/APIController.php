<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Languages;
use App\Models\Chapter;
use App\Models\ChapterInfos;
use App\Models\TranslatedName;

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


}
