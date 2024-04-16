<div class="basic-contact-form ptb-90">
    <div class="container">
        <div class="area-title text-center">
            <h2>Let’s talk</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi tempora veritatis nemo aut ea iusto eos est expedita, quas ab adipisci.</p>
        </div>
        <div class="row">
            <div class="col-8 offset-2">
                <form id="contact-form" action="mail.php" method="post">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="sr-only">First Name</label>
                            <input type="text" class="form-control input-lg" name="name" placeholder="First Name" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="sr-only">Email</label>
                            <input type="email" class="form-control input-lg" name="email" placeholder="Email" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="sr-only">Subject</label>
                            <input type="text" class="form-control input-lg" name="subject" placeholder="Subject" >
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 form-group">
                            <textarea class="form-control input-lg" rows="7" name="message" placeholder="Message*"></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-lg btn-round btn-dark">Send Email</button>
                        </div>

                    </div><!-- .row -->
                </form>
                <!-- Ajax response -->
                <div class="ajax-response text-center"></div>
            </div>
        </div>
    </div>
</div>
