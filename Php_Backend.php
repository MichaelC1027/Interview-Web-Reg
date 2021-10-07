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

    include"connection.php";

if(isset($_POST['submit']))
{

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
    if(isset($_POST['optin']))
    {
        $optIn = $_POST['optin'];
    }
    else
    {
        $optIn = "no";
    }

    //here we are checking if any of the inputs are empty and returning a echo of an empty field
    if(empty($firstName) || empty($lastName) || empty($email) || empty($email_confirm))
    {
        echo "<span> Fill in all the fields please! (Validation error) </span>" . "<br>";
        $errorEmpty = true;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($email_confirm, FILTER_VALIDATE_EMAIL))
    {
        echo "<span> Please submit a valid e-mail address! (Validation error) </span>" . "<br>";
        $errorEmail = true;
    }
    elseif ($email != $email_confirm) 
    {

        echo "<span> Emails were incorrect from each other (Validation error) </span>" . "<br>";
        $emailDifferent = true;
    }
    else
    {
        // reCAPTCHA validation
        if(isset($_POST['g_recaptcha']) && !empty($_POST['g_recaptcha'])) {

            // Google secret API
            $secretAPIkey = '6LfqD7AcAAAAADG1Hie7I2EFrXZ3cgNw-quqPNDP';

            // reCAPTCHA response verification
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretAPIkey.'&response='.$_POST['g_recaptcha']);      
                
                //here we are decoding the verification response
                $response = json_decode($verifyResponse);

                //if the response was successful we will run this code
                if($response->success){
                    //we are using a try catch block incase any errors come up
                    try {
                        //this variable connects to the database
                        //$connection = mysqli_connect('localhost','root','','interview');
                
                        //if the connection is good run code below
                        if($connection == true)
                        {
                            //this code block below is checking of if we have a email in the database already
                            //if so, we check the date and make sure they are not submitting more then once a day
                            $query = "SELECT TodaysDate FROM customers WHERE Email = '$email'";
                            $result = mysqli_query($connection,$query);
                            $TodaysDateCheck = "";
                            $datesAreEqual = false;
                            
                            //here we are obtaining an associative array from the database
                            while($row = mysqli_fetch_assoc($result))
                            {
                                global $TodaysDateCheck;
                                //this is turning the array information into a string
                                $TodaysDateCheck = implode("",$row);
                            }
                
                            //here we are checking the date to see if they match 
                            if($TodaysDateCheck == $date)
                            {
                                global $datesAreEqual;
                                $datesAreEqual = true;
                            }
                
                
                            //if the dates do not match we run the code down below, otherwise we give an error
                            if ($datesAreEqual != true) 
                            {
                                //here we are taking the info that was submitted and putting it into an SQL query format
                                $query = "INSERT INTO customers(FirstName,LastName,Email,Offers,TodaysDate) VALUES ('$firstName','$lastName','$email','$optIn','$date')";
                
                                //here we are using the mysqli_query to have the query string we just made run on the connection to the database
                                $result = mysqli_query($connection,$query);
                
                                //here the result is false(it didnt make a new entry) then it will kill the program and tell us we failed
                                if($result != true)
                                {
                                    die("FAILED TO INPUT");
                                }
                                else
                                {
                                    echo "Thank you for Registering, we will be emailing you soon! (Success)";
                                }
                            }
                            else
                            {
                                die("You have already submitted for today! Please try again Tomorrow (Limit Error)");
                            }
                
                
                        }
                
                    } 
                    catch (\Throwable $th) 
                    {
                        //throw $th;
                        die("Database connection failed");
                    }
                
            }

        }
        else
        {
            echo "please verify you are a human and not a robot with the recaptcha!";
        }
    }
    
    

    
}
    
    ?>

    <script>

        var errorEmpty = "<?php echo $errorEmpty; ?>";
        var errorEmail = "<?php echo $errorEmail; ?>";
        var emailDif = "<?php echo $emailDifferent; ?>"
        
        if(errorEmpty == false && errorEmail == false && emailDif == false)
        {
            $("#firstName, #lastName, #email, #confirm_email").val("");
        }

    </script>