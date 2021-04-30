<div class="panel panel-default" style="position: static; zoom: 1;">
    <div class="panel-heading">
        <i class="fa fa-external-link-square"></i>
<!-- Panel title here -->
        <div class="panel-tools">
            <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
            </a>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
            <thead>
                <tr>
                    @if(isset($table->tHead))
                    @foreach($table->tHead as $key => $tHead)
                    @if(!in_array($key,$table->hide))
                    <th
                    @if(in_array($tHead,$table->no_sort))
                    class="nosort"
                    @endif
                    >{{$tHead}}</th>
                    @endif
                    @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(isset($table->rows))
                @foreach($table->rows as $rows)
                <tr>
                    @foreach($rows as $tRow)
                    @php $key = array_search($tRow,array_values($rows)) @endphp
                    @if(!in_array($key,$table->hide))
                    <td>

                        @if(isset($tRow['type']) && $tRow['type']=='image')
                        <div class="wrap-image" style = "height:100px;width:100px">
								<a class="group1" href="{{$tRow['url']}}" title="Image Viewer">
									<img src="{{$tRow['url']}}" alt="" class="img-responsive">
                                </a>
                        </div>

                        @elseif(isset($tRow['type']) &&$tRow['type']=='edit')
                        <a class="btn btn-blue  tooltips" href="{{$tRow['url']}}"  data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                        <!--<button class='btn-info'> <a href="{{$tRow['url']}}">Edit</a></button>-->
                        @elseif(isset($tRow['type']) && $tRow['type']=='delete')
                        <a class="btn btn-bricky tooltips" data-toggle="modal" onclick="confirm_delete('{{$tRow['url']}}')" href="#static" data-placement="top" data-original-title="Delete"><i class="fa fa-times fa fa-white"></i></a>

                        <!--<button class="btn-danger"> <a href="{{$tRow['url']}}" onclick="return confirmDelete('{{$tRow['data']}}')">Delete</a></button>-->
                        @elseif(isset($tRow['type']) && $tRow['type']=='restore')
                        <a class="btn btn-green btn-sm" href="{{$tRow['url']}}">Restore</a>
                        @elseif(isset($tRow['type']) && $tRow['type']=='link')
                        <a href="{{$tRow['url']}}">{{$tRow['data']}}</a>
                        @elseif(isset($tRow['type']) && $tRow['type']=='array')

                        @foreach( isset($tRow['type']) && $tRow['data'] as $data)
                        {{$data}}<br>
                        @endforeach

                        @elseif(isset($tRow['type']) && $tRow['type']=='icon')
                        <img src="{{$tRow['url']}}">

                        @elseif(isset($tRow['type']) && $tRow['type'] == 'view')
                        <a class="btn btn-blue tooltips" href="{{$tRow['url']}}" data-placement="top" data-original-title="View"><i class="fa fa-arrow-circle-right"></i></a>

                        @else
                        @if(isset($tRow['alert']) && $tRow['alert'] == 'danger')
                        <div class = "text-danger">{{$tRow['data']}}</div>
                        @else
                        @if(isset($tRow['data']))
                        {{$tRow['data']}}
                        @else
                        {!!$tRow!!}
                        @endif

                        @endif

                        @endif


                    </td>
                    @endif
                    @endforeach
                    @if(isset($table->actions))
                    <td style="white-space: nowrap;">


                            <div class="actions">
                            @foreach($table->actions as $action)
                            @if($action == 'delete')

                            <a href="#static" data-toggle="modal" class="btn btn-bricky tooltips" value="Delete"  data-placement="top" data-original-title="Delete" onclick=confirm_delete("{{route("$table->route.destroy", ['id' => $rows['id']])}}")>Delete</a>

                            @elseif($action == 'edit')
                            <a class="btn btn-blue  tooltips" data-toggle="modal" href="{{route("$table->route.edit", ['id' => $rows['id']])}}" data-placement="top" data-original-title="Edit">Edit</a>

                            @elseif($action == 'view')
                            <a class="btn btn-green tooltips" data-toggle="modal" href="{{route("$table->route.show", ['id' => $rows['id']])}}" data-placement="top" data-original-title="View">View</a>
                            @endif
                            @endforeach
                            </div>
                    </td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@section('js-table')
<!-- start: JAVASCRIPTS REQUIRED DATA DABLES -->
<script type="text/javascript" src="{{assets('plugins/DataTables/media/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{assets('plugins/select2/select2.min.js')}}"></script>
<script type="text/javascript" src="{{assets('plugins/DataTables/media/js/DT_bootstrap.js')}}"></script>
<script src="{{assets('js/table-data.js')}}"></script>
<script src="{{assets('plugins/colorbox/jquery.colorbox-min.js')}}"></script>
<script src="{{assets('js/pages-gallery.js')}}"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    jQuery(document).ready(function() {
        TableData.init();
        PagesGallery.init();
    });

</script>
@endsection

@section('css-table')
<link rel="stylesheet" href="{{assets('plugins/select2/select2.css')}}" />
<link rel="stylesheet" href="{{assets('plugins/DataTables/media/css/DT_bootstrap.css')}}" />
<link rel="stylesheet" href="{{assets('plugins/colorbox/example2/colorbox.css')}}">
@endsection
