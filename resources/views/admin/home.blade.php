@extends('admin.layouts.app-main')
@section('content')
<div class="dashboard-container">
    <div class="row">
        <div class="col-sm-3">
            <a class="btn btn-icon btn-block" href="{{route('vbv.index')}}">
                <br>
                <i class="fa fa-upload"></i>
                Verse By Verse
            </a>
        </div>
        <div class="col-sm-3">
            <a class="btn btn-icon btn-block" href="{{route('wbw.index')}}">
                <br>
                <i class="fa fa-upload"></i>
                Word By Word
            </a>
        </div>
    </div>
    @endsection
