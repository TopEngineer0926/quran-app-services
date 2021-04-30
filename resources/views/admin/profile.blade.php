@extends('admin.layouts.app-main')
@section('content')
@include('admin.includes.form',[
'action' => route('admin.profile.update'),
])
@endsection
