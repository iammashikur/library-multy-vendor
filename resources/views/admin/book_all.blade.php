@php
    $page_type = 'Admin';
@endphp
@extends('admin.layouts.master')

@section('content')

<div class="main-content">
    <div class="section">
        {{-- main card --}}
        <div class="card card-primary">
            <div class="card-header" style="border-bottom-color: #d0d0d0">
                <h4>All Books</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.book.create') }}" class="btn btn-primary">
                        <b>Create Book</b>
                    </a>
                </div>
            </div>
            <div class="card-body">
                {{$dataTable->table()}}
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    {{$dataTable->scripts()}}
    {{-- this script will help to delete a entry --}}
    @include('admin.partials.sweetalert-delete');
@endpush