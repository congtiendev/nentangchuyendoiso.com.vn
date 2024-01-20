@extends('layouts.main')
@section('page-title')
    {{__('Hỗ trợ kỹ thuật PDF')}}
@endsection

@section('page-action')
@endsection
@section('content')
    <div class="row">
        <div class="iframe-container justify-content-center align-items-center mb-5">
            <iframe src="https://drive.google.com/file/d/14kWbKNyIsRsY1v6QgxGHzEC6jgvkXOn5/preview"  width="70%" height="700px"></iframe>
        </div>
    </div>
@endsection

<style>
    .iframe-container {
         display: flex;
        justify-content: center;
        align-items: center;
    }

    .iframe-wrapper {
        width: 80%;
        height: 0;
        padding-bottom: 56.25%;
        position: relative;
        overflow: hidden;
    }

    .iframe-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 75%;
    }
</style>

<script>
    
    function loadIframe(iframeId) {
        var iframes = document.getElementsByClassName('iframe-wrapper');
        for (var i = 0; i < iframes.length; i++) {
            iframes[i].style.display = 'none';
        }
        document.getElementById(iframeId).style.display = 'block';
    }
</script>