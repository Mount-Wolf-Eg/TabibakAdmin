<!-- breadcrumb area -->
<div class="basic-breadcrumb-area bg-opacity bg-1 ptb-100">
    <div class="container">
        <div class="basic-breadcrumb text-center">
            <h3 class="">{{$title}}</h3>
            <ol class="breadcrumb text-xs">
                <li><a href="{{ route('front.home') }}">Home</a></li>
                <li><a href="{{ route($link['route']) }}">{{$link['text']}}</a></li>
                <li class="active">{{$title}}</li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb area -->
