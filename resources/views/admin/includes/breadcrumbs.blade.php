<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li>
                <i class="clip-pencil"></i>
                <a href="#">
                    <!-- Forms -->
                </a>
            </li>
            <li class="active">
                <!-- Form Validation -->
            </li>
            <!-- <li class="search-box">
                <form class="sidebar-search">
                    <div class="form-group">
                        <input type="text" placeholder="Start Searching...">
                        <button class="submit">
                            <i class="clip-search-3"></i>
                        </button>
                    </div>
                </form>
            </li> -->
        </ol>
        <div class="page-header">
            @if(isset($title))
            <h1>{{$title}} <small></small></h1>
            @else
            <h1>{{$page->heading ?? "Untitled"}} <small></small></h1>
            @endif
        </div>
    </div>
</div>
