@extends('layouts.main')
@section('page-title')
    {{__('Hỗ trợ kỹ thuật video')}}
@endsection

@section('page-action')
@endsection
@section('content')
    <div class="row">
        {{--  <div class="menu d-flex justify-content-center mb-3">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadIframe('iframe1')">Tài khoản nhân viên kế toán</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadIframe('iframe2')">Tài khoản nhân viên quản trị khách hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadIframe('iframe3')">Tài khoản nhân viên hành chính nhân sự</a>
                </li>
            </ul>
        </div>  --}}

        <div class="iframe-container justify-content-center align-items-center mb-5">
            {{--  <div id="iframe1" class="iframe-item">
                <iframe src="https://www.youtube.com/embed/uEVxk8hTTiw" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div id="iframe2" class="iframe-item" style="display: none;">
                <iframe src="https://www.youtube.com/embed/DlmwQZTjn-s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <div id="iframe3" class="iframe-item" style="display: none;">
                <iframe src="https://www.youtube.com/embed/iVGhM8PxXVY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>  --}}
            <iframe width="70%" height="700px" src="https://www.youtube.com/embed/NIyNelQ5by0?si=PZ8XrsdTwJJhbgn9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
    </div>
@endsection

<style>
    .iframe-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .iframe-item {
        width: 80%;
        height: 0;
        padding-bottom: 56.25%;
        position: relative;
        overflow: hidden;
    }

    .iframe-item iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 75%;
    }

    @media (max-width: 768px) {
        .iframe-item {
            padding-bottom: 75%; /* 4:3 aspect ratio for smaller screens */
        }
    }
</style>

<script>
    function loadIframe(iframeId) {
        var iframes = document.getElementsByClassName('iframe-item');
        for (var i = 0; i < iframes.length; i++) {
            iframes[i].style.display = 'none';
        }
        document.getElementById(iframeId).style.display = 'block';
    }
</script>