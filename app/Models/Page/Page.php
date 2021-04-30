<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page\Table;
use App\Models\Page\Form;

class Page extends Model
{
    public $content = null;
    public $title = null;
    public $heading = null;

    public function __construct($title = null,$heading = null)
    {
        $this->title = $title;
        $this->content = collect();

        if(isset($heading)){
        $this->heading = $heading;
        }
        else{
            $this->heading = $title;
        }
        $this->add('<a class = "btn btn-dark-grey btn-navigate" href="javascript:history.back()"><i class="fa fa-arrow-circle-left"></i> Back</a>');
      }

    public function table($tHead=null,$route = null)
    {
        return new Table($tHead,$route);
    }

    public function form($action='',$method='post')
    {
        return new Form($action,$method);
    }

    public function create_button($value,$route,$type)
    {
        if($type == 'add'){
        $this->add('<a class = "btn btn-teal btn-navigate" href="'.route($route).'"><i class="fa fa-plus"></i>'.$value.'</a>');
        }
    }

    public function add($content){
        if($content instanceof Table){
            $this->content->push(['type'=>'table','data'=>$content]);
        }
        else if($content instanceof Form){
            $this->content->push(['type'=>'form','data'=>$content]);
        }
        else{
            $this->content->push(['type'=>'html','data'=>$content]);
        }
        return true;
    }
}
