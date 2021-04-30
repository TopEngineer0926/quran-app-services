@extends('admin.layouts.app-main')
@section('content')

@if($view == 'list')
@include('admin.includes.form',[
'action' => route('admin.addUser'),
])

@include('admin.includes.table')

@elseif($view == 'edit')


@include('admin.includes.form',[
'action' => route('admin.editUser',[
    'id'=> $user->id
    ]),
])

@elseif($view == 'appusers-list')
@include('admin.includes.table')

@elseif($view == 'appusers-view')
@include('admin.includes.form',[
'action' => ''
    ]),
@endif

@endsection
