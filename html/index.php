<?php include 'includes/header.php';?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <form role="form" action="registration.php" method="post" class="form-box">
                <div class="form-group">
                    <label for="firstname"> First Name </label>
                    <input type="text" name="firstName" placeholder="First Name" class="form-control" id="firstName">
                    <div id="firstName-group" class="error-group"></div>
                </div>
                <br>
                <div class="form-group">
                    <label for="lastname"> Last Name </label>
                    <input type="text" name="lastName" placeholder="Last Name" class="form-control" id="lastName">
                    <div id="lastName-group" class="error-group"></div>
                </div>
                <br>
                <div class="form-group">
                    <label for="email"> Email Address </label>
                    <input type="email" name="email" placeholder="Email Address" class="form-control" id="email">
                    <div id="email-group" class="error-group"></div>
                </div>
                <br>
                <div class="form-group">
                    <label for="confirm"> Confirm Email Address </label>
                    <input type="email" name="confirm_email" placeholder="Confirmation" class="form-control" id="confirm_email">
                    <div id="confirm_email-group" class="error-group"></div>
                </div>
                <br>
                <div class="form-group">
                    <label for="optin"> Would you like to Opt-in for our other offers? </label>
                    <input type="checkbox" name="optin" id="optin">
                </div>
                <br>
                <div class="form-group">
                    <div class="g-recaptcha" data-sitekey="6LfqD7AcAAAAAODVLqjGOkXyJJvEIrVy-u6s4RC0"></div>
                    <div id="g_recaptcha-group" class="error-group"></div>
                </div>
                <br>
                <input type="submit" value="Register" name="submit" class="btn btn-primary" id="submit">
                <br>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#optin').on('change', function() {
            $('input[name="optin"]').val($(this).is(':checked') ? 'Yes' : 'No');
        });
    });
</script>
</body>

</html>