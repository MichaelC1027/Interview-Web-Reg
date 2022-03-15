<!-- 
You   have   a   simple   website   with   registration   form.   
This   form   has   few fields   (“first   name”,  “last  name”,   “opt in”, “email  address” Plus (“confirm email”) with  validation non all fields).  
We also need a limit of 1 submission per email address, and implement a captcha


Front end should be handled with html and JavaScript (CSS is a plus or bootstrap). I don’t really care how the page looks but it should be responsive for mobile.

 

Write example  of back-end code with these requirements:


PHP 8 (I believe so)

Form data will be sending using POST request. (DONE) 

Check that all fields not empty. Return “Validation error” if not.

Check   for   a   limit   of   1   entry   per   email   per   day.   Return   “Limit error” if not. (DONE)

Insert all form data into DB and return “Success” (DONE) 

-->

<?php
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {

    //here we are getting the values from the POST that the user has submitted
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $email_confirm = $_POST['confirm_email'];


    //date checker and errors
    $errorEmpty = false;
    $errorEmail = false;
    $emailDifferent = false;
    $date = date('Y-m-d');

    //here we are checking if the optin option was yes or no and setting its value
    if (isset($_POST['optin'])) {
        $optIn = $_POST['optin'];
    } else {
        $optIn = "no";
    }

    //here we are checking if any of the inputs are empty and returning a echo of an empty field
    if (empty($firstName) || empty($lastName) || empty($email) || empty($email_confirm)) {
        echo "<span> Fill in all the fields please! (Validation error) </span>" . "<br>";
        $errorEmpty = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($email_confirm, FILTER_VALIDATE_EMAIL)) {
        echo "<span> Please submit a valid e-mail address! (Validation error) </span>" . "<br>";
        $errorEmail = true;
    } elseif ($email != $email_confirm) {

        echo "<span> Emails were incorrect from each other (Validation error) </span>" . "<br>";
        $emailDifferent = true;
    } else {
        // reCAPTCHA validation
        if (isset($_POST['g_recaptcha']) && !empty($_POST['g_recaptcha'])) {

            // Google secret API
            $secretAPIkey = '6LfqD7AcAAAAADG1Hie7I2EFrXZ3cgNw-quqPNDP';

            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretAPIkey . '&response=' . $_POST['g_recaptcha']);

            //here we are decoding the verification response
            $response = json_decode($verifyResponse);

            //if the response was successful we will run this code
            if ($response->success) {
                //we are using a try catch block incase any errors come up
                try {
                    //create instance of DynamoDB client
                    $client = Aws\DynamoDb\DynamoDbClient::factory(array(
                        'region' => 'us-east-1',
                        'version' => 'latest',
                        'credentials' => array(
                            'key' => 'AKIAQIL6GLPF6Y3DC6G3',
                            'secret'  => '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',
                        )
                    ));

                    //generate a new id
                    $new_id = (string)(mt_rand(1, 1000));
                    $id = $new_id;

                    //create new item to add to table
                    $marshaler = new Aws\DynamoDb\Marshaler();
                    $item = $marshaler->marshalItem(
                        [
                            'id' => $id,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'email_confirm' => $email_confirm,
                            'optIn' => $optIn
                        ]
                    );

                    $params = [
                        'TableName' => 'Customers',
                        'Item' => $item
                    ];

                    $result = $client->putItem($params);
                    
                } catch (\AWS\DynamoDb\Exception\DynamoDbException $e) {
                    echo "Unable to add item:\n";
                    echo $e->getMessage() . "\n";
                }
            }
        } else {
            echo "please verify you are a human and not a robot with the recaptcha!";
        }
    }
}

?>

<script>
    var errorEmpty = "<?php echo $errorEmpty; ?>";
    var errorEmail = "<?php echo $errorEmail; ?>";
    var emailDif = "<?php echo $emailDifferent; ?>"

    if (errorEmpty == false && errorEmail == false && emailDif == false) {
        $("#firstName, #lastName, #email, #confirm_email").val("");
    }
</script>