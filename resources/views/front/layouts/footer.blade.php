<!-- footer start -->
<footer>
    <div class="footer-top-area gray-bg pt-90 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-40">
                    <div class="footer-widget">
                        <h4><span>{{__('messages.sections.contact_us_title')}}</span></h4>
                        <p>{{__('messages.sections.contact_us_message')}}</p>
                        <div class="contact-widget">
                            <ul>
                                <li>
                                    <p>{{__('messages.address')}}: {{__('messages.saudi_arabia')}}</p>
                                </li>
                                <li>
                                    <p>{{__('messages.email')}}: <a href="mailto:info@tabebak.com">info@tabebak.com</a></p>
                                </li>
                                <li>
                                    <p>{{__('messages.phone')}}: <a href="tel:+9665500000">+9665500000</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-40">
                    <div class="footer-widget">
                        <h4><span>{{__('messages.sections.message_us_title')}}</span></h4>
                        <div class="newsletter clearfix">
                            <form action="#" method="POST">
                                <div class="row pb-2">
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="name" placeholder="{{__('messages.name')}}" />
                                    </div>
                                    <div class="col-6">
                                        <input class="form-control" type="text" name="phone" placeholder="{{__('messages.phone')}}" />
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control" type="email" name="email" placeholder="{{__('messages.email')}}" />
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control input-lg" rows="7" name="message" placeholder="{{__('messages.message')}}" style="min-height: 150px"></textarea>
                                    </div>
                                </div>
                                <button class="btn" type="submit">{{__('messages.send')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-40">
                    <div class="footer-widget">
                        <h4><span>{{__('messages.getApp')}}</span></h4>
                        <div class="row">
                            <div style="max-width:200px">
                                <a href="#"><img src="{{ asset('assets/images/front/getOnPlayStore.png') }}" style="max-width:180px"></a>
                            </div>
                            <div style="max-width:200px">
                                <a href="#"><img src="{{ asset('assets/images/front/getOnAppStore.png') }}" style="max-width:180px"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area theme-bg">
        <div class="container">
            <p class="copyright text-center">Copyright <script>document.write(new Date().getFullYear())</script> © MountWolf. All right reserved.</p>
        </div>
    </div>
</footer>
<!-- footer end -->
