<h1> Hello Everyone! </h1>
I would like to first apologies for all this time I have taken from you wonderful people at DJA. When I was first talking with eric, it was very slow at my job and was hoping to find a new one asap. The moment I was making the project though, My Job put me onto a new project and that took over everything for a total of 5 months. <b> So I do Apologies for the time waste! </b>
 Now let’s get back to the important part, The project!
 
<h2> URL to the project to test it </h2>
http://michaelcastillo2022-env.eba-hk7zh22y.us-east-1.elasticbeanstalk.com/
 
<h2> Project Overview </h2>
The project I will be showing you today is a simple website with a registration form
The fields I must provide and validate for this project are:

 * First Name
 * Last Name
 * Email Address + Confirming Email Address
 * Opt in
 
If any information is wrong/incorrect I will return a validation error to the user and tell them what is wrong.
When all the data that is provided is validated, I will be sending it to the backend using POST request.
I must make sure that the email provided can only submit once time a day, any submissions on the same date with the same email should send the user to a new page telling the user they already submitted for the day.
<b> I will be using:</b>
PHP 8
AWS Dynamo DB
 
<h2> The Code </h2>
The user is given a basic form to fill out and submit. When the form is submitted, an ajax function is used within the javascript file called form. Ajax will take all the information given from the form and send it over to a php file called registration. The registration file will parse through the information provided with if statements in order to ensure the post data given is not empty. If any of the data is empty, it will fill in an associative array named errors, and send that array to the form file. The form file will then add comprehensive front-end error messages to the index file in order to prompt proper user interaction. The submission process is also terminated until the user resubmits the form. If no errors have been found, then registration will try to take the information provided and begin the process of pushing the information into the database. First, we create objects of the database class and the validator class, then we will send the information to the validator object. We specifically validate the following:

* That the first name and last name do not have numbers or symbols based on a regex. 
* Check that both the initial email and confirmation email are the exact same. 
* Validate the google reCAPTCHA with the token provided on submission. 

If any errors have occurred during this process the validator class will put it in an associative array named errors and return the errors. The errors are then sent to the form file, and errors are added the same way as the previous error check.  If no errors have been returned, we move on to the next phase of the submission process and check to see if the user has already submitted for today.

We do this by performing a query check in the database for the users email and the current date. When the database object is initialized, we make sure that the database class can connect to the DynamoDB table that is set up on AWS. If it fails, we will catch the error and a “failed to connect to db” exception.. If the user already has a submission for the day, we will navigate them to the php page called alreadyentered, otherwise we will call the database object again to begin the process of pushing the information with a function called addItem. The first thing the addItem will do is create an ID with a function called createID. Afterwards the function will make sure that the information is sent to the right database table via an associative array called params. Once this is done, the user data is pushed to the database. If any errors happen here, we will catch it with our try catch block and throw an error that reads “failed to add item”.  If no error occurs, then the user will be navigated to the php page called thankyou.
