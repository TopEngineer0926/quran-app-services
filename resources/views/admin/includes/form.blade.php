<div class="panel panel-default">
    <div class="panel-heading">
        <i class="fa fa-external-link-square"></i>
        <!-- Panel title here -->
        <div class="panel-tools">
            <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
            </a>
        </div>
    </div>
    <div class="panel-body">
        <form role="form" id="form" class="form-horizontal" enctype="multipart/form-data" action="{{$forms->action}}" method="post">
            @csrf
            @method($forms->method)
            @foreach($forms->form as $form)

            @if(!isset($form['val']))
            @php $form['val'] = '' @endphp
            @endif

            @if(!isset($form['autocomplete']))
            @php $form['autocomplete'] = 'false' @endphp
            @endif

            @if(!isset($form['name']))
            @if(isset($form['label']))
            @php $form['name'] = strtolower($form['label']) @endphp
            @else
            @php $form['name'] = '' @endphp
            @endif
            @endif

            @if(!isset($form['id']))
            @php $form['id'] = $form['type'].'-'.$form['name'] @endphp
            @endif

            @if(!isset($form['label']))
            @php $form['label'] = '' @endphp
            @endif



            @if(!isset($form['placeholder']))
            @if(isset($form['label']))
            @php $form['placeholder'] = $form['label'] @endphp
            @else
            @php $form['placeholder'] = '' @endphp
            @endif
            @endif

            <div class="form-group">
                <label class="col-sm-2 control-label" for="{{$form['id']}}"><strong>{{$form['label']}}</strong>
                    @if(isset($form['required']) && $form['required']=='true')
                    <span class="symbol required">
                        @endif
                </label>

                <div class="col-sm-9">
                    @if($form['type'] == 'input')
                    <input autocomplete="{{$form['autocomplete']}}" value="{{$form['val']}}" type="{{$form['input-type']}}" name="{{$form['name']}}" id="{{$form['id']}}" class="{{$form['class']}}" placeholder="{{$form['placeholder']}}" @if(isset($form['required']) && $form['required']=='true' ) required @endif>

                    @elseif($form['type'] == 'select')
                    <select name="{{$form['name']}}" id="{{$form['id']}}" class="{{$form['class']}}">
                        @foreach($form['options'] as $val => $option)
                        @if($val == $form['val'])
                        <option value="{{$val}}" selected>{{$option}}</option>

                        @else
                        <option value="{{$val}}">{{$option}}</option>

                        @endif
                        @endforeach
                    </select>

                    @elseif($form['type'] == 'image')
                    <div class="@if(isset($form['class']))$form['class'] @else fileupload fileupload-new @endif" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA?text=no+image" alt="" />
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                        <div>
                            <span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                <input type="{{$form['input-type']}}" name="{{$form['name']}}" id="{{$form['id']}}" value="https://www.app.com.pk/wp-content/uploads/2018/10/NHA.jpg" @if(isset($form['required']) && $form['required']=='true' ) required @endif>
                            </span>
                            <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                                <i class="fa fa-times"></i> Remove
                            </a>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <span class="label label-warning">NOTE!</span>
                        <span> Image preview only works in IE10+, FF3.6+, Chrome6.0+ and Opera11.1+. In older browsers and Safari, the filename is shown instead. </span>
                    </div>
                    @elseif($form['type'] == 'file')
                    <div class="@if(isset($form['class']))$form['class'] @else fileupload fileupload-new @endif" data-provides="fileupload">
                        <div class="input-group">
                            <div class="form-control uneditable-input">
                                <i class="fa fa-file fileupload-exists"></i>
                                <span class="fileupload-preview"></span>
                            </div>
                            <div class="input-group-btn">
                                <div class="btn btn-light-grey btn-file">
                                    <span class="fileupload-new"><i class="fa fa-folder-open-o"></i> Select file</span>
                                    <span class="fileupload-exists"><i class="fa fa-folder-open-o"></i> Change</span>
                                    <input type="file" name="{{$form['name']}}" id="{{$form['id']}}" class="file-input" @if(isset($form['required']) && $form['required']=='true' ) required @endif>
                                </div>
                                <a href="#" class="btn btn-light-grey fileupload-exists" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                    @elseif($form['type'] == 'tags')
                    <input id="{{$form['id']}}" name="{{$form['name']}}" type="text" class="tags" value="{{$form['val']}}" @if(isset($form['required']) && $form['required']=='true' ) required @endif>

                    @elseif($form['type'] == 'knob')
                    <input id="{{$form['id']}}" name="{{$form['name']}}" class="knob" data-angleOffset=-125 data-angleArc=250 data-fgColor="#007AFF" value="{{$form['val']}}">


                    @elseif($form['type'] == 'select-muliple')
                    <select multiple="multiple" name="{{$form['name']}}" id="form-field-select-4" class="form-control search-select" placeholder="{{$form['placeholder']}}" @if(isset($form['required']) && $form['required']=='true' ) required @endif>
                        @foreach($form['options'] as $val => $option)
                        @if($form['val']!=NULL && in_array($val,$form['val']))
                        <option value="{{$val}}" selected>{{$option}}</option>
                        @else
                        <option value="{{$val}}">{{$option}}</option>
                        @endif
                        @endforeach
                    </select>

                    @elseif($form['type'] == 'textarea-autosize')
                    <textarea class="autosize form-control " name="{{$form['name']}}" id="{{$form['id']}}" placeholder="{{$form['placeholder']}}" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 69px;" @if(isset($form['required']) && $form['required']=='true' ) required @endif>{{$form['val']}}</textarea>

                    @elseif($form['type'] == 'text')
                    <label class="description" id="{{$form['id']}}">{{$form['val']}}</label>
                    @elseif($form['type'] == 'div')
                    <div class="{{$form['class']}}" id="{{$form['id']}}">{{$form['val']}}</div>
                    @elseif($form['type'] == 'text-image')
                    <img class="description" id="{{$form['id']}}" src="{{$form['val']}}" alt="Image Not Found">
                    @elseif($form['type'] == 'text-array')
                    @foreach($form['val'] as $val)
                    <label class="description">{{$val}}</label><br>
                    @endforeach
                    @elseif($form['type'] == 'video')
                    <video width="320" height="240" controls>
                        <source src="{{$form['url']}}" type="video/mp4">
                            Your browser does not support the video tag.
                    </video>
                    @elseif($form['type'] == 'select-image')
                    <span id="dropdownName" type="hidden" name = "{{$form['name']}}"></span>
                    <select id="image-dropdown">
                        @foreach($form['options'] as $option)
                        @if($form['val']!=NULL && in_array($val,$form['val']))
                        <option value="{{$option->id}}" selected="selected" data-imagesrc="{{url('/').'/'.$option->link}}" data-description="{{$option->name}}">{{$option->name}}</option>
                        @else
                        <option value="{{$option->id}}" data-imagesrc="{{url('/').'/'.$option->link}}" data-description="{{$option->name}}">{{$option->name}}</option>
                        @endif

                        @endforeach
                    </select>

                    @endif
                </div>
            </div>



            @endforeach

        </form>

    </div>
</div>

@section('js-form')
<!-- start: JAVASCRIPTS FOR FORMS -->
<script src="{{assets('plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js')}}"></script>
<script src="{{assets('plugins/autosize/jquery.autosize.min.js')}}"></script>
<script src="{{assets('plugins/select2/select2.min.js')}}"></script>
<script src="{{assets('plugins/jquery.maskedinput/src/jquery.maskedinput.js')}}"></script>
<script src="{{assets('plugins/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
<script src="{{assets('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{assets('plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js')}}"></script>
<script src="{{assets('plugins/bootstrap-daterangepicker/moment.min.js')}}"></script>
<script src="{{assets('plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{assets('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script>
<script src="{{assets('plugins/bootstrap-colorpicker/js/commits.js')}}"></script>
<script src="{{assets('plugins/jQuery-Tags-Input/jquery.tagsinput.js')}}"></script>
<script src="{{assets('plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
<script src="{{assets('plugins/summernote/build/summernote.min.js')}}"></script>
<script src="{{assets('plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{assets('plugins/ckeditor/adapters/jquery.js')}}"></script>
<script src="{{assets('js/form-elements.js')}}"></script>
<script src="{{assets('plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js')}}" type="text/javascript"></script>
<script src="{{assets('plugins/jQRangeSlider/jQAllRangeSliders-min.js')}}"></script>
<script src="{{assets('plugins/jQuery-Knob/js/jquery.knob.js')}}"></script>
<script src="{{assets('js/ui-sliders.js')}}"></script>
<script src="{{assets('plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{assets('js/form-validation.js')}}"></script>
<script type="text/javascript" src="{{assets('plugins/jquery-ddslick/jquery.ddslick.min.js')}}"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script>
    jQuery(document).ready(function() {
        FormElements.init();
        UISliders.init();
        // $('#form').validate();
        $('#image-dropdown').ddslick({
        onSelected: function(selectedData) {
            //callback function: do something with selectedData;
            $('.dd-selected-value').attr('name', $("#dropdownName").attr("name"));
        }
    });
    });

</script>

@endsection

@section('css-form')
<link rel="stylesheet" href="{{assets('plugins/select2/select2.css')}}">
<link rel="stylesheet" href="{{assets('plugins/datepicker/css/datepicker.css')}}">
<link rel="stylesheet" href="{{assets('plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{assets('plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}">
<link rel="stylesheet" href="{{assets('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}">
<link rel="stylesheet" href="{{assets('plugins/jQuery-Tags-Input/jquery.tagsinput.css')}}">
<link rel="stylesheet" href="{{assets('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}}">
<link rel="stylesheet" href="{{assets('plugins/summernote/build/summernote.css')}}">
<link rel="stylesheet" href="{{assets('plugins/jquery-ui/jquery-ui-1.10.1.custom.min.css')}}">
<link rel="stylesheet" href="{{assets('plugins/jQRangeSlider/css/classic-min.css')}}">
@endsection
