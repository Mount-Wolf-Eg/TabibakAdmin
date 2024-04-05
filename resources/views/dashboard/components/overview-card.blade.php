@if (isset($attributes))
    <!-- card -->
    <div class="col my-2">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="vr rounded bg-{{$attributes['color']}} opacity-50" style="width: 4px;"></div>
                    <div class="flex-grow-1 {{ app()->getLocale() == 'ar' ? 'me-3' : 'ms-3' }}">
                        <p class="text-uppercase fw-medium text-{{$attributes['color']}} fs-14 text-truncate">{{$attributes['title']}}</p>
                        <h4 class="fs-22 fw-semibold mb-3">{{$attributes['count']}}</h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle text-{{$attributes['color']}} rounded fs-3">
                                <i class="{{$attributes['icon']}}"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end card -->
@endif
