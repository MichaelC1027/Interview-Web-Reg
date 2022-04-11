$(document).ready(function() {
    $("form").submit(function(event) {

        //remove errors each submission attempt
        $(".error-group").removeClass("has-error");
        $(".error").remove();

        //set form data
        var formData = {
            firstName: $("#firstName").val(),
            lastName: $("#lastName").val(),
            email: $("#email").val(),
            confirm_email: $("#confirm_email").val(),
            optin: $("#optin").val(),
            g_recaptcha: $("#g-recaptcha-response").val(),
        };

        $.ajax({
            type: "post",
            url: "ajax/registration.php",
            data: formData,
            dataType: "json",
            success: function(data) {

                //if data includes only a redirect,  then redirect to said page
                if (data.navigate) {
                    window.location = data.navigate;
                }

                //if data has no redirect and is not successful, add errors as needed
                if (!data.success) {
                    if (data.errors.firstName) {
                        $("#firstName-group").addClass("has-error");
                        $("#firstName-group").append(
                            '<div class="error">' + data.errors.firstName + "</div>"
                        );
                    }

                    if (data.errors.lastName) {
                        $("#lastName-group").addClass("has-error");
                        $("#lastName-group").append(
                            '<div class="error">' + data.errors.lastName + "</div>"
                        );
                    }

                    if (data.errors.email) {
                        $("#email-group").addClass("has-error");
                        $("#email-group").append(
                            '<div class="error">' + data.errors.email + "</div>"
                        );
                    }

                    if (data.errors.confirm_email) {
                        $("#confirm_email-group").addClass("has-error");
                        $("#confirm_email-group").append(
                            '<div class="error">' + data.errors.confirm_email + "</div>"
                        );
                    }

                    if (data.errors.g_recaptcha) {
                        $("#g_recaptcha-group").addClass("has-error");
                        $("#g_recaptcha-group").append(
                            '<div class="error">' + data.errors.g_recaptcha + "</div>"
                        );
                    }

                    if (data.errors.firstName_invalid) {
                        $("#firstName-group").addClass("has-error");
                        $("#firstName-group").append(
                            '<div class="error">' + data.errors.firstName_invalid + "</div>"
                        );
                    }

                    if (data.errors.lastName_invalid) {
                        $("#lastName-group").addClass("has-error");
                        $("#lastName-group").append(
                            '<div class="error">' + data.errors.lastName_invalid + "</div>"
                        );
                    }

                    if (data.errors.email_confirm_invalid) {
                        $("#confirm_email-group").addClass("has-error");
                        $("#confirm_email-group").append(
                            '<div class="error">' + data.errors.email_confirm_invalid + "</div>"
                        );
                    }
                }
            },
            fail: function() {
                alert("Submission Error");
            }
        })
        event.preventDefault();
    });
});