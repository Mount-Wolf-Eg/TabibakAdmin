<!-- breadcrumb area -->
<div class="basic-breadcrumb-area bg-opacity {{ $headerBgClass }} ptb-100">
    <div class="container">
        <div class="basic-breadcrumb text-center">
            <h3 class="">{{$title}}</h3>
            <ol class="breadcrumb text-xs">
                <li><a href="{{ route('front.home') }}">{{__('messages.home')}}</a></li>
                <li class="active">{{$title}}</li>
            </ol>
        </div>
    </div>
</div>
<!-- breadcrumb area -->
