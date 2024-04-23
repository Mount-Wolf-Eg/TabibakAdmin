<div class="basic-contact-form ptb-90">
    <div class="container">
        <div class="area-title text-center">
            <h2>{{__('messages.sendUsNow')}}</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi tempora veritatis nemo aut ea iusto eos est expedita, quas ab adipisci.</p>
        </div>
        <div class="row">
            <div class="col-8 offset-2 m-auto">
                <form id="contact-form" action="" method="post">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="sr-only">{{__('messages.name')}}</label>
                            <input type="text" class="form-control input-lg" name="name" placeholder="{{__('messages.name')}}" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="sr-only">{{__('messages.email')}}</label>
                            <input type="email" class="form-control input-lg" name="email" placeholder="{{__('messages.email')}}" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="sr-only">{{__('messages.subject')}}</label>
                            <input type="text" class="form-control input-lg" name="subject" placeholder="{{__('messages.subject')}}" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <textarea class="form-control input-lg" rows="7" name="message" placeholder="{{__('messages.message')}} *" style="min-height: 200px"></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-lg btn-round btn-dark">{{__('messages.send')}}</button>
                        </div>

                    </div><!-- .row -->
                </form>
                <!-- Ajax response -->
                <div class="ajax-response text-center"></div>
            </div>
        </div>
    </div>
</div>
