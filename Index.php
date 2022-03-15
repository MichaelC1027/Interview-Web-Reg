<!-- 
You   have   a   simple   website   with   registration   form.   
This   form   has   few fields   (“first   name”,  “last  name”,   “opt in”, “email  address” Plus (“confirm email”) with  validation non all fields).  
We also need a limit of 1 submission per email address, and implement a captcha


Front end should be handled with html and JavaScript (CSS is a plus or bootstrap). I don’t really care how the page looks but it should be responsive for mobile.

 

Write example  of back-end code with these requirements:


PHP 8

Form data will be sending using POST request.

Check that all fields not empty. Return “Validation error” if not.

Check   for   a   limit   of   1   entry   per   email   per   day.   Return   “Limit error” if not.

Insert all form data into DB and return “Success”

-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="global.css" />
    <title>Registration Website</title>

    <!--The Link to the Bootstrap page-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!--The Bootstrap insert script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>

    <!--The J Query insert script-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>

    <!--THe J Query code section-->
    <script>
        // Jquery Code in here to help with validation
        $(document).ready(function() {
            $("form").submit(function(event) {
                event.preventDefault();
                var firstName = $("#firstName").val();
                var lastName = $("#lastName").val();
                var email = $("#email").val();
                var confirm_email = $("#confirm_email").val();
                var optin = $("#optin").val();
                var g_recaptcha = $("#g-recaptcha-response").val();
                var submit = $("#submit").val();


                $(".form-message").load("Php_Backend.php", {
                    //the first variable is the actual post variable, the variable after is the actual value we have obtained
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    confirm_email: confirm_email,
                    optin: optin,
                    g_recaptcha: g_recaptcha,
                    submit: submit
                });
            });
        });
    </script>

    <!--The Google recaptcha insert script-->
    <script src="https://www.google.com/recaptcha/api.js"></script>

</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form action="Php_Backend.php" method="post" class="form-box">

                    <div class="form-group">
                        <label for="firstname"> First Name </label>
                        <input type="text" name="firstName" placeholder="First Name" class="form-control" id="firstName">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="lastname"> Last Name </label>
                        <input type="text" name="lastName" placeholder="Last Name" class="form-control" id="lastName">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="email"> Email Address </label>
                        <input type="email" name="email" placeholder="Email Address" class="form-control" id="email">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="confirm"> Confirm Email Address </label>
                        <input type="email" name="confirm_email" placeholder="Confirmation" class="form-control" id="confirm_email">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="optin"> Would you like to Opt-in for our other offers? </label>
                        <input type="checkbox" name="optin" value="Yes" id="optin">
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="6LfqD7AcAAAAAODVLqjGOkXyJJvEIrVy-u6s4RC0" id="g-recaptcha-response"></div>
                    </div>
                    <br>
                    <input type="submit" value="Register" name="submit" class="btn btn-primary" id="submit">
                    <br>
                    <div class="form-group">
                        <p class="form-message"></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>