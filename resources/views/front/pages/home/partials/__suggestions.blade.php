<!-- suggestions-area start -->
<div class="suggestions-area gray-bg pt-90 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-md-7 mb-30">
                <div class="area-title2">
                    <h2>{{__('messages.suggestions')}}</h2>
                </div>

                <div class="my-tab">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="teeth-tab" data-bs-toggle="tab"
                                data-bs-target="#teeth-tab-pane" type="button" role="tab"
                                aria-controls="teeth-tab-pane" aria-selected="true">Teeth</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cardio-tab" data-bs-toggle="tab"
                                data-bs-target="#cardio-tab-pane" type="button" role="tab"
                                aria-controls="cardio-tab-pane" aria-selected="false">Cardio</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="neurology-tab" data-bs-toggle="tab"
                                data-bs-target="#neurology-tab-pane" type="button" role="tab"
                                aria-controls="neurology-tab-pane" aria-selected="false">Neurology</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="Pregnacy-tab" data-bs-toggle="tab"
                                data-bs-target="#Pregnacy-tab-pane" type="button" role="tab"
                                aria-controls="Pregnacy-tab-pane" aria-selected="false" Pregnacy>Pregnacy</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="teeth-tab-pane" role="tabpanel"
                            aria-labelledby="teeth-tab" tabindex="0">
                            <div class="content">
                                <p>Morem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                    unknown printer took a galley of type and scrambled it to make a type specimen
                                    book.
                                </p><br>
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting, remaining essentially unchanged. </p>
                                <a href="#" class="btn border small">Read More</a>
                            </div>

                            <div class="image">
                                <img src="{{ asset('assets/images/front/avatar-1.jpg') }}" alt="">
                                <p class="name">Milan Markovic</p>
                                <p class="spec">General Dentist</p>
                            </div>

                            <span class="clearfix"></span>
                        </div>
                        <div class="tab-pane fade" id="cardio-tab-pane" role="tabpanel" aria-labelledby="cardio-tab"
                            tabindex="0">
                            <div class="content">
                                <p>Torem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                    unknown printer took a galley of type and scrambled it to make a type specimen
                                    book.
                                </p><br>
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting, remaining essentially unchanged. </p>
                                <a href="#" class="btn border small">Read More</a>
                            </div>

                            <div class="image">
                                <img src="{{ asset('assets/images/front/avatar-2.jpg') }}" alt="">
                                <p class="name">Milan Markovic</p>
                                <p class="spec">General Dentist</p>
                            </div>

                            <span class="clearfix"></span>
                        </div>
                        <div class="tab-pane fade" id="neurology-tab-pane" role="tabpanel"
                            aria-labelledby="neurology-tab" tabindex="0">
                            <div class="content">
                                <p>Morem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                    unknown printer took a galley of type and scrambled it to make a type specimen
                                    book.
                                </p><br>
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting, remaining essentially unchanged. </p>
                                <a href="#" class="btn border small">Read More</a>
                            </div>

                            <div class="image">
                                <img src="{{ asset('assets/images/front/avatar-3.jpg') }}" alt="">
                                <p class="name">Milan Markovic</p>
                                <p class="spec">General Dentist</p>
                            </div>

                            <span class="clearfix"></span>
                        </div>
                        <div class="tab-pane fade" id="Pregnacy-tab-pane" role="tabpanel"
                            aria-labelledby="Pregnacy-tab" tabindex="0">
                            <div class="content">
                                <p>Korem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                    unknown printer took a galley of type and scrambled it to make a type specimen
                                    book.
                                </p><br>
                                <p>It has survived not only five centuries, but also the leap into electronic
                                    typesetting, remaining essentially unchanged. </p>
                                <a href="#" class="btn border small">Read More</a>
                            </div>

                            <div class="image">
                                <img src="{{ asset('assets/images/front/avatar-1.jpg') }}" alt="">
                                <p class="name">Milan Markovic</p>
                                <p class="spec">General Dentist</p>
                            </div>

                            <span class="clearfix"></span>
                        </div>
                    </div>
                </div>



            </div>
            <div class="col-md-5 mb-30">
                <div class="area-title2">
                    <h2>{{__('messages.faqs')}}</h2>
                </div>


                <div class="accordion tp-accordion" id="accordionExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1">
                            <i class="fa fa-heartbeat" aria-hidden="true"></i> <span class="{{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">Cardio health</span>
                        </button>
                      </h2>
                      <div id="collapseOne1" class="accordion-collapse collapse show" aria-labelledby="headingOne1" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.</div>
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingTwo1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo1" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fa fa-medkit" aria-hidden="true"></i><span class="{{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">Teeth whitening</span>
                        </button>
                      </h2>
                      <div id="collapseTwo1" class="accordion-collapse collapse" aria-labelledby="headingTwo1" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.</div>
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingThree1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree1">
                            <i class="fa fa-user-md" aria-hidden="true"></i> <span class="{{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">Oral exams</span>
                        </button>
                      </h2>
                      <div id="collapseThree1" class="accordion-collapse collapse" aria-labelledby="headingThree1" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.</div>
                        </div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingFour1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour1" aria-expanded="false" aria-controls="collapseFour1">
                            <i class="fa fa-user-md" aria-hidden="true"></i> <span class="{{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">Dental exams</span>
                        </button>
                      </h2>
                      <div id="collapseFour1" class="accordion-collapse collapse" aria-labelledby="headingFour1" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                commodo consequat.</div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
<!-- suggestions-area end -->
