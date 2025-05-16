@extends('dashboard.layouts.master')

@section('title')
    {{ __('messages.general_settings') }}
@endsection

@section('content')
    <x-breadcrumb 
        title="{{ __('messages.general_settings') }}" 
        pagetitle="{{ __('messages.settings') }}" 
        route="{{ route('dashboard') }}" 
    />

    <div class="row">
        <div class="col-md-12">
            @include('dashboard.settings.partials.__form', [
                'action' => ['settings.update'], 
                'method' => 'POST',
                'settings' => $settings
            ])
        </div>
    </div>
@endsection
