<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/jj3-logo-sm.png') }}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-dark.png') }}" alt="" height="26">
            </span>
        </a>
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/jj3-logo-sm.png') }}" alt="" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="24">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-components">{{ __('t-components') }}</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="index">
                        <i class="bi bi-command"></i> <span data-key="t-overview">{{ __('t-overview') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUI">
                        <i class="bi bi-radioactive"></i> <span data-key="t-bootstrap-ui">{{ __('t-bootstrap-ui') }}</span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-alerts" class="nav-link" data-key="t-alerts">{{ __('t-alerts') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-badges" class="nav-link" data-key="t-badges">{{ __('t-badges') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-buttons" class="nav-link" data-key="t-buttons">{{ __('t-buttons') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-colors" class="nav-link" data-key="t-colors">{{ __('t-colors') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-cards" class="nav-link" data-key="t-cards">{{ __('t-cards') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-carousel" class="nav-link" data-key="t-carousel">{{ __('t-carousel') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-dropdowns" class="nav-link" data-key="t-dropdowns">{{ __('t-dropdowns') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-grid" class="nav-link" data-key="t-grid">{{ __('t-grid') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-images" class="nav-link" data-key="t-images">{{ __('t-images') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-tabs" class="nav-link" data-key="t-tabs">{{ __('t-tabs') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-accordions" class="nav-link" data-key="t-accordion-collapse">{{ __('t-accordion-collapse') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-modals" class="nav-link" data-key="t-modals">{{ __('t-modals') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-offcanvas" class="nav-link" data-key="t-offcanvas">{{ __('t-offcanvas') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-placeholders" class="nav-link" data-key="t-placeholders">{{ __('t-placeholders') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-progress" class="nav-link" data-key="t-progress">{{ __('t-progress') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-notifications" class="nav-link" data-key="t-notifications">{{ __('t-notifications') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-media" class="nav-link" data-key="t-media-object">{{ __('t-media-object') }}
                                            </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-embed-video" class="nav-link" data-key="t-embed-video">{{ __('t-embed-video') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-typography" class="nav-link" data-key="t-typography">{{ __('t-typography') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-lists" class="nav-link" data-key="t-lists">{{ __('t-lists') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-general" class="nav-link" data-key="t-general">{{ __('t-general') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-utilities" class="nav-link" data-key="t-utilities">{{ __('t-utilities') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAdvanceUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAdvanceUI">
                        <i class="bi bi-layers"></i> <span data-key="t-advance-ui">{{ __('t-advance-ui') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAdvanceUI">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="advance-ui-sweetalerts" class="nav-link" data-key="t-sweet-alerts">{{ __('t-sweet-alerts') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-nestable" class="nav-link" data-key="t-nestable-list">{{ __('t-nestable-list') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-scrollbar" class="nav-link" data-key="t-scrollbar">{{ __('t-scrollbar') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-swiper" class="nav-link" data-key="t-swiper-slider">{{ __('t-swiper-slider') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-ratings" class="nav-link" data-key="t-ratings">{{ __('t-ratings') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-highlight" class="nav-link" data-key="t-highlight">{{ __('t-highlight') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="advance-ui-scrollspy" class="nav-link" data-key="t-scrollSpy">{{ __('t-scrollSpy') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#customUI" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="customUI">
                        <i class="bi bi-tools"></i> <span data-key="t-custom-ui">{{ __('t-custom-ui') }}</span> <span class="badge badge-pill bg-danger" data-key="t-custom">{{ __('custom') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="customUI">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-ribbons" class="nav-link"><span data-key="t-ribbons">{{ __('t-ribbons') }}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-profile" class="nav-link"><span data-key="t-profile">{{ __('t-profile') }}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-counter" class="nav-link"><span data-key="t-counter">{{ __('t-counter') }}</span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarForms" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarForms">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-forms">{{ __('t-forms') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarForms">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="forms-elements" class="nav-link" data-key="t-basic-elements">{{ __('t-basic-elements') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-select" class="nav-link" data-key="t-form-select"> {{ __('t-form-select') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-checkboxs-radios" class="nav-link" data-key="t-checkboxs-radios">{{ __('t-checkboxs-radios') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-pickers" class="nav-link" data-key="t-pickers"> {{ __('t-pickers') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-masks" class="nav-link" data-key="t-input-masks">{{ __('t-input-masks') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-advanced" class="nav-link" data-key="t-advanced">{{ __('t-advanced') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-range-sliders" class="nav-link" data-key="t-range-slider">{{ __('t-range-slider') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-validation" class="nav-link" data-key="t-validation">{{ __('t-validation') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-wizard" class="nav-link" data-key="t-wizard">{{ __('t-wizard') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-editors" class="nav-link" data-key="t-editors">{{ __('t-editors') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-file-uploads" class="nav-link" data-key="t-file-uploads">{{ __('t-file-uploads') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-layouts" class="nav-link" data-key="t-form-layouts">{{ __('t-form-layouts') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="forms-tom-select" class="nav-link" data-key="t-tom-select">{{ __('t-tom-select') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarTables" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                        <i class="bi bi-table"></i> <span data-key="t-tables">{{ __('t-tables') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarTables">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="tables-basic" class="nav-link" data-key="t-basic-tables">{{ __('t-basic-tables') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-gridjs" class="nav-link" data-key="t-grid-js">{{ __('t-grid-js') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-listjs" class="nav-link" data-key="t-list-js">{{ __('t-list-js') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="tables-datatables" class="nav-link" data-key="t-datatables">{{ __('t-datatables') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCharts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCharts">
                        <i class="bi bi-pie-chart"></i> <span data-key="t-apexcharts">{{ __('t-apexcharts') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCharts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="charts-apex-line" class="nav-link" data-key="t-line"> {{ __('t-line') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-area" class="nav-link" data-key="t-area"> {{ __('t-area') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-column" class="nav-link" data-key="t-column">
                                    {{ __('t-column') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-bar" class="nav-link" data-key="t-bar"> {{ __('t-bar') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-mixed" class="nav-link" data-key="t-mixed"> {{ __('t-mixed') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-timeline" class="nav-link" data-key="t-timeline">
                                    Timeline </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-candlestick" class="nav-link" data-key="t-candlstick"> {{ __('t-candlstick') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-boxplot" class="nav-link" data-key="t-boxplot">
                                    {{ __('t-boxplot') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-bubble" class="nav-link" data-key="t-bubble">
                                    {{ __('t-bubble') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-scatter" class="nav-link" data-key="t-scatter">
                                    {{ __('t-scatter') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-heatmap" class="nav-link" data-key="t-heatmap">
                                    {{ __('t-heatmap') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-treemap" class="nav-link" data-key="t-treemap">
                                    {{ __('t-treemap') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-pie" class="nav-link" data-key="t-pie"> {{ __('t-pie') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-radialbar" class="nav-link" data-key="t-radialbar"> {{ __('t-radialbar') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-radar" class="nav-link" data-key="t-radar"> {{ __('t-radar') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="charts-apex-polar" class="nav-link" data-key="t-polar-area">
                                    {{ __('t-polar-area') }} </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIcons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarIcons">
                        <i class="bi bi-usb-symbol"></i> <span data-key="t-icons">{{ __('t-icons') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIcons">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="icons-remix" class="nav-link" data-key="t-remix">{{ __('t-remix') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-boxicons" class="nav-link" data-key="t-boxicons">{{ __('t-boxicons') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-materialdesign" class="nav-link" data-key="t-material-design">{{ __('t-material-design') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-bootstrap" class="nav-link" data-key="t-bootstrap">{{ __('t-bootstrap') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="icons-phosphor" class="nav-link" data-key="t-phosphor">{{ __('t-phosphor') }}</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMaps" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMaps">
                        <i class="bi bi-geo-alt"></i> <span data-key="t-maps">{{ __('t-maps') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMaps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="maps-google" class="nav-link" data-key="t-google">
                                    {{ __('t-google') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-vector" class="nav-link" data-key="t-vector">
                                    {{ __('t-vector') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="maps-leaflet" class="nav-link" data-key="t-leaflet">
                                    {{ __('t-leaflet') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMultilevel" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMultilevel">
                        <i class="bi bi-share"></i> <span data-key="t-multi-level">{{ __('t-multi-level') }}</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMultilevel">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-key="t-level-1.1"> {{ __('t-level-1.1') }} </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarAccount" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAccount" data-key="t-level-1.2"> {{ __('t-level-1.2') }}
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarAccount">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link" data-key="t-level-2.1"> {{ __('t-level-2.1') }} </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#sidebarCrm" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCrm" data-key="t-level-2.2"> {{ __('t-level-2.2') }}
                                            </a>
                                            <div class="collapse menu-dropdown" id="sidebarCrm">
                                                <ul class="nav nav-sm flex-column">
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link" data-key="t-level-3.1"> {{ __('t-level-3.1') }}
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#" class="nav-link" data-key="t-level-3.2"> {{ __('t-level-3.2') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>