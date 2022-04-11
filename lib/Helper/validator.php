<?php
class Validator
{
    public function recaptchaValid($recaptcha)
    {
        // Google secret API
        $secretAPIkey = '6LfqD7AcAAAAADG1Hie7I2EFrXZ3cgNw-quqPNDP';

        // reCAPTCHA response verification
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $recaptcha);

        //here we are decoding the verification response
        $response = json_decode($verifyResponse);

        if(!$response->success){
            return false;
        }

        return true;
    }

    public function validate($array)
    {
        $errors = [];
        foreach ($array as $key => $value) {
            if ($key == 'first_name') {
                if (!preg_match("/^[a-zA-Z]+$/", $value)) {
                    $errors['firstName_invalid'] = "First Name is not valid";
                }
            }
            if ($key == 'last_name') {
                if (!preg_match("/^[a-zA-Z]+$/", $value)) {
                    $errors['lastName_invalid'] = "Last Name is not valid";
                }
            }
            if ($key == 'email_confirm') {
                $email = $array['email'];
                if ($value != $email)
                    $errors['email_confirm_invalid'] = "Failed to confirm email";
            }
            if ($key == 'g_recaptcha') {
                $isValid = $this->recaptchaValid($value);
                if(!$isValid){
                    $errors['g_recaptcha_invalid'] = "Failed to pass recaptcha";
                }
            }
        }
        return $errors;
    }
}
