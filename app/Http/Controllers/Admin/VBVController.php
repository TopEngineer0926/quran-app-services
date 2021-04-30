<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use XMLWriter;
use App\Models\Page\Page;
use App\Models\Resource;
use App\Models\Language;
use App\Models\Author;
use App\Models\Source;
use App\Models\Enum;
use App\Models\Search;
use Illuminate\Support\Facades\Validator;
use Exception;
use League\Csv\Reader;
use League\Csv\Statement;

class VBVController extends CrudController
{
    public $model = 'Resource';
    public $route = 'vbv';
    public $view = 'admin.view';
    private $title = 'Verse By Verse';

    function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct($this->model,$this->route);
    }
    public function index()
    {
        $resource = Resource::select('id','name','author_id','language_code','is_available')->with('language','author')->orderBy('created_at', 'desc')->get();
        $page = new Page($this->title,$this->title);
        $page->create_button('Add',$this->route.'.create','add');
        $table = $page->table(['ID','Name','Author','Language','Status'],$this->route);
        $table->add_actions(['delete']);
        $table->replace_column('language_code','language','name');
        $table->replace_column('author_id','author','name');
        $table->hide_columns(['ID']);
        $table->render($resource);
        $page->add($table);
        return view($this->view)->with(['page'=>$page]);
    }

    public function create()
    {
        $page = new Page($this->title,$this->title);
        $form = $page->form(route('vbv.store'));
        $languages = Language::orderby('name', 'asc')->get();
        $languages_list = $languages->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name'] . ' (' . $item['iso_code'] . ')'];
        });
        //return $languages_list;
        $form->render([
            ['label' => 'Display Name', 'type' => 'input', 'input-type' => 'text', 'name' => 'display_name', 'class' => 'form-control form-control-alternative', 'placeholder' => 'Display Name', 'required' => true],
            ['label' => 'Author Name', 'type' => 'input', 'input-type' => 'text', 'name' => 'author_name', 'class' => 'form-control form-control-alternative', 'placeholder' => 'Author Name', 'required' => true],
            ['label' => 'Slug(eg. en-mehdi)', 'type' => 'input', 'input-type' => 'text', 'name' => 'slug', 'class' => 'form-control form-control-alternative', 'placeholder' => 'Slug', 'required' => true],
            ['label' => 'Language', 'type' => 'select', 'name' => 'language_id', 'class' => 'form-control search-select', 'options' => $languages_list],
            ['label' => 'Upload CSV', 'type' => 'file', 'name' => 'file', 'required' => true],
            ['type' => 'input', 'input-type' => 'submit', 'class' => 'form-control form-control-alternative btn btn-success', 'name' => 'submit', 'val' => 'Submit']
        ]);
        $page->add($form);
        return view($this->view)->with(['page' => $page]);
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'language_id' => ['required'],
                'author_name' => ['required'],
                'display_name' => ['required'],
                'slug' => ['required'],
                'file' => ['required', 'mimes:csv,txt'],
            ]);
            if ($validator->fails()) {
                return back()->withErrors([Enum::fail => $validator->errors()->first()]);
            }
            $language_id = $request->language_id;
            $author_name = $request->author_name;
            $display_name = $request->display_name;
            $slug = $request->slug;
            $file = $request->file('file');
            $file_name = $slug . '.xml';
            $language = Language::where('id', $language_id)->first();
            $author = Author::where('name', $author_name)->first();
            if (!$author) {
                $author = new Author;
                $author->name = $author_name;
                $author->save();
            }
            $source = Source::where('name', $display_name)->where('url', $file_name)->first();
            if (!$source) {
                $source = new Source;
                $source->name = $display_name;
                $source->url = $file_name;
                $source->save();
            }
            $resource = Resource::where('author_id', $author->id)->where('source_id', $source->id)->first();
            if (!$resource) {
                $resource = new Resource;
                $resource->type = Enum::resource_table_type['options'];
                $resource->sub_type = Enum::resource_table_subtype['translations'];
                $resource->cardinality_type = '1_ayah';
                $resource->language_code = $language->iso_code;
                $resource->slug = $slug;
                $resource->is_available = 1;
                $resource->description = 'List of verse translations and authors';
                $resource->author_id = $author->id;
                $resource->source_id = $source->id;
                $resource->name = $display_name;
                $resource->save();
            }
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setHeaderOffset(0); //set the CSV header offset
            $stmt = (new Statement())
                ->offset(0); //start getting data from first row

            $records = $stmt->process($csv);
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->openUri($file_name);

            $xml->startDocument('1.0', 'utf-8'); //start document [1]
            $xml->startElement('xml'); //start xml tag [2]
            $xml->startElement('information'); //start information tag [3]
            $xml->writeElement('language_id', $language->id); //write element language_id
            $xml->writeElement('language_name', $language->name); //write element language_name | $recitations->where('id',$loop)->first()->reciter_name
            $xml->writeElement('resource_id', $resource->id); //write element resource_id
            $xml->writeElement('resource_name', $resource->name); //write element resource_name
            $xml->endElement(); // end information tag [3]
            $xml->startElement('verses'); // start verses [4]

            foreach ($records as $record) {
                $xml->startElement('verse'); //start verse [5]
                //                             //data here
                $xml->writeElement('chapter_id', $record['chapter_id']); // //write element chapter_id
                $xml->writeElement('verse_id', $record['verse_id']); // //write element verse_id
                $xml->writeElement('verse_number', $record['verse_number']); // //write element verse_number
                $xml->writeElement('text', $record['text']); // //write translatin text
                $xml->writeElement('id', $record['verse_id']); // //write id of translation
                //                             //data end here
                $xml->endElement(); // end verse [5]
                $search = Search::where('verse_id', $record['verse_id'])->where('content', $record['text'])->first();
                if (!$search) {
                    $search = new Search;
                    $search->verse_id = $record['verse_id'];
                    $search->content = $record['text'];
                    $search->save();
                }
            }

            $xml->endElement(); // end verses [4]
            $xml->endElement(); // end xml tag [2]
            $xml->endDocument(); //end document [1]
            return back()->withErrors([Enum::success => [Enum::success_add]]);
        } catch (Exception $e) {
            return back()->withErrors([Enum::fail => $e->getMessage()]);
        }
    }
    public function activate($id)
    {
        Resource::where('id',$id)->update(['is_available'=>1]);
        return back()->withErrors([Enum::success => [Enum::success_update]]);
    }
    public function deactivate($id)
    {
        Resource::where('id',$id)->update(['is_available'=>0]);
        return back()->withErrors([Enum::success => [Enum::success_update]]);
    }
}
