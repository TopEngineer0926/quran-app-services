<link rel="stylesheet" href="{{assets('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" type="text/css" />
<link rel="stylesheet" href="{{assets('plugins/bootstrap-modal/css/bootstrap-modal.css')}}" type="text/css" />

<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-body">
        <p class="text-danger">
            Deleting this might delete any dependant data. Do you want to continue?
        </p>
    </div>
    <div class="modal-footer">
        <a type="button" data-dismiss="modal" class="btn btn-default">
            Cancel
        </a>
        <form id="confirm-delete" action="javascript:void(0)" method="post">
            <input value="Continue" type="submit" class="btn btn-primary"/>
            @method('delete')
            @csrf
        </form>
    </div>
</div>

<script src="{{assets('plugins/bootstrap-modal/js/bootstrap-modal.js')}}"></script>
<script src="{{assets('plugins/bootstrap-modal/js/bootstrap-modalmanager.js')}}"></script>
<script src="{{assets('js/ui-modals.js')}}"></script>
<style>
    .modal-scrollable {
        z-index: 1038 !important;
    }
    div#static {
        height: auto !important;
    }
</style>
