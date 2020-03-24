@extends('layout')

@push('scripts')
    <script src="/js/s3upload.js"></script>
@endpush

@section('content')
<a href="{{ route('pictures.index')}}"><button>Back to gallery</button></a>

<form class="s3upload" action="{{ route('pictures.store') }}" method="POST" enctype="multipart/form-data"
data-s3attributes="{{ json_encode($s3attributes) }}" data-s3inputs="{{ json_encode($s3inputs) }}">
    @csrf

    @error('title')
        <div class="alert alert-danger">{{$message}}</div>
    @enderror
    @error('picture')
        <div class="alert alert-danger">{{$message}}</div>
    @enderror

    <input type="text" name="title"/>
    <input type="file" name="file"/>
    <input type="hidden" name="storage_path" value="{{ $s3inputs['key'] }}">
    <input type="submit"/>
</form>
@endsection
