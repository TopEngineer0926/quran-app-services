@if(isset($page))
@if(isset($page->content))

@foreach($page->content as $content)

@if($content['type'] == 'table')
@include('admin.includes.table',['table'=>$content['data']])
@elseif($content['type'] == 'form')
@include('admin.includes.form',['forms'=>$content['data']])
@elseif($content['type'] == 'html')
{!!$content['data']!!}
@endif

@endforeach

@endif
@endif
