<!-- offcanvas area start -->
<div class="offcanvas__area">
    <div class="offcanvas__wrapper">
        <div class="offcanvas__content">
            <div class="offcanvas__top mb-40 d-flex justify-content-between align-items-center">
                <div class="offcanvas__logo logo">
                    <a href="index.html">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" style="width: 80px">
                    </a>
                </div>
                <div class="offcanvas__close">
                    <button class="offcanvas__close-btn offcanvas-close-btn">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="mobile-menu fix mb-40 mobile-menu"></div>

            <div class="offcanvas__contact mt-30 mb-20">
                <h4>{{__('messages.contactInfo')}}</h4>
                <ul>
                    <li class="d-flex align-items-center">
                        <div class="offcanvas__contact-icon mr-15">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <div class="offcanvas__contact-text {{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">
                            <a target="_blank" href="https://www.google.com/maps/place/Dhaka/@23.7806207,90.3492859,12z/data=!3m1!4b1!4m5!3m4!1s0x3755b8b087026b81:0x8fa563bbdd5904c2!8m2!3d23.8104753!4d90.4119873">12/A,
                                Mirnada City Tower, NYC</a>
                        </div>
                    </li>
                    <li class="d-flex align-items-center">
                        <div class="offcanvas__contact-icon mr-15">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="offcanvas__contact-text {{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">
                            <a href="mailto:support@gmail.com">088889797697</a>
                        </div>
                    </li>
                    <li class="d-flex align-items-center">
                        <div class="offcanvas__contact-icon mr-15">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="offcanvas__contact-text {{ app()->getLocale() == 'ar' ? 'pe-2' : '' }}">
                            <a href="tel:+012-345-6789">support@mail.com</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="offcanvas__social">
                <ul>
                    <li><a href="#"><i class="bi bi-facebook"></i></a></li>
                    <li><a href="#"><i class="bi bi-twitter-x"></i></a></li>
                    <li><a href="#"><i class="bi bi-google"></i></a></li>
                    <li><a href="#"><i class="bi bi-linkedin"></i></a></li>
                    <li><a href="#"><i class="bi bi-youtube"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
