{!! Form::open(['route'=> Route::current()->getName(), 'method'=>'GET', 'class' => 'row']) !!}
<div class="col-lg-3 py-1">
    {{ Form::label('keyword', __('messages.keyword'), ['class' => 'form-label']) }}
    {!! Form::text('keyword' , request('keyword'), ['class' => 'form-control']) !!}
</div>
{!! $slot !!}
<div class="col-lg-3 align-self-end py-1">
    <button type="submit" class="btn btn-primary mb-0">
        <i class="bi bi-search"></i>
        {{__('messages.search')}}
    </button>
    <button class="btn btn-danger mb-0 clear-filters">
        <i class="bi bi-funnel-fill"></i>
        {{__('messages.clear')}}
    </button>
</div>
{!! Form::close() !!}

@push('scripts')'
<script>
    $(document).ready(function() {
        $('.clear-filters').on('click', function(e) {
            e.preventDefault();
            const currentUrl = window.location.href;
            window.location.href = currentUrl.split('?')[0];
        });
    });
</script>
@endpush
