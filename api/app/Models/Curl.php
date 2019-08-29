<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curl extends Model
{
    //
    protected $name = null;
    const url_languages = "http://staging.quran.com:3000/api/v3/options/languages";
    const name_languages = "languages";

    const url_chapters = "http://staging.quran.com:3000/api/v3/chapters";
    const name_chapters = "chapters";

    const url_chapter_info = "http://staging.quran.com:3000/api/v3/chapters/{id}/info";
    const name_chapter_info = "chapter_info";

    const url_juzs = "http://staging.quran.com:3000/api/v3/juzs";
    const name_juzs = "juzs";

    const url_audio_files = "https://quran.com/api/api/v3/chapters/{id}/verses/{verse_id}/audio_files?language=en";
    const name_audio_files = "audio_files";

    // const url_verses = "https://quran.com/api/api/v3/chapters/112/verses?translations%5B%5D=20&language=en";
    // const name_verses = "verses";

    const url_verses = "http://staging.quran.com:3000/api/v3/chapters/{id}/verses?language=en&page={page}&text_type=words";
    const name_verses = "verses";


    public function curl($url, $name=null){
        $curl_options = array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 9999,
            CURLOPT_CONNECTTIMEOUT => 5

        );
        $curl = curl_init();

        curl_setopt_array($curl, $curl_options);

        $result = curl_exec($curl);

        $result = (array) json_decode($result);

        if (isset($result['error']) && $result['error'] == '404') {

            $result = [];
        }
        if(isset($name)){
            return $result[$name];
        }
        else{
            return $result;
        }

    }
}
