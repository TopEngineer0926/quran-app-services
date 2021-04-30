@error(success())

<div class="alert alert-success">
    <button data-dismiss="alert" class="close">
        &times;
    </button>
    <i class="fa fa-check-circle"></i>
    <strong>{{success()}}</strong> {{$message}}
</div>

@enderror
@error(fail())
<div class="alert alert-danger">
    <button data-dismiss="alert" class="close">
        &times;
    </button>
    <i class="fa fa-times-circle"></i>
    <strong>{{fail()}}</strong> {{$message}}
</div>
@enderror
