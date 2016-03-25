<? include("inc/incfiles/header.inc.php"); ?>

<?
if (isset($_SESSION["user_login"])) {
    echo "<meta http-equiv=\"refresh\" content=\"0; url=home.php\">";
}
else
{
?>

<?

$reg = @$_POST['reg'];
//declaring variables to prevent errors
$fn = ""; //First Name
$ln = ""; //Last Name
$un = ""; //Username
$em = ""; //Email
$em2 = ""; //Email 2
$pswd = ""; //Password
$pswd2 = ""; // Password 2
$d = ""; // Sign up Date
$u_check = ""; // Check if username exists
//registration form

$fn = strip_tags(@$_POST['fname']);
$ln = strip_tags(@$_POST['lname']);
$un = strip_tags(@$_POST['username']);
$em = strip_tags(@$_POST['email']);
$em2 = strip_tags(@$_POST['email2']);
$pswd = strip_tags(@$_POST['password']);
$pswd2 = strip_tags(@$_POST['password2']);
$d = date("Y-m-d"); // Year - Month - Day

if ($reg) {

if ($em==$em2) {
// Check if user already exists
$u_check = mysql_query("SELECT username FROM users WHERE username='$un'");
// Count the amount of rows where username = $un
$check = mysql_num_rows($u_check);
if ($check == 0) {
//check all of the fields have been filed in
if ($fn&&$ln&&$un&&$em&&$em2&&$pswd&&$pswd2) {
// check that passwords match
if ($pswd==$pswd2) {
// check the maximum length of username/first name/last name does not exceed 8 characters
if (strlen($fn)>8||strlen($ln)>8) {
echo '<script type="text/javascript">alert("The maximum limit for first name/last name is 8 characters!");</script>';
}
if (strlen($un)>16) {
echo '<script type="text/javascript">alert("The maximum limit for the username is 16 characters!");</script>';
}
if ($pswd2 == "Password ...") {
echo '<script type="text/javascript">alert("You must insert a password!");</script>';
}
if ($pswd2 == "") {
echo '<script type="text/javascript">alert("You must insert a password!");</script>';
}

else
{
// check the maximum length of password does not exceed 30 characters and is not less than 5 characters
if (strlen($pswd)>30||strlen($pswd)<5) {
echo '<script type="text/javascript">alert("Your password must be between 5 and 30 characters long!");</script>';
}

else
{
//encrypt password and password 2 using encrypt before sending to database
$pswd = encrypt($pswd);
$hash = encrypt($un);
$query = mysql_query("INSERT INTO users VALUES ('','$un','$fn','$ln','$em','$pswd','$d','0','0','0','','$hash','Hello, I do not currently have a bio of myself!')");
$picquery = mysql_query("INSERT INTO profile_pictures VALUES ('$un','','')");

//Email Verification
$to      = $em; // Send email to our user
$subject = 'Signup | Verification'; // Give the email a subject 
$message = '
 
Thank you for signing up!
Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
 
------------------------
Username: '.$un.'
Password: '.$pswd2.'
------------------------
 
Please click this link to activate your account:
http://www.ste3med.rylantaylor.com/verify.php?email='.$em.'&hash='.$hash.'
 
'; // Our message above including the link
                     
$headers = 'From:noreply@rylantaylor.com' . "\r\n"; // Set from headers
mail($to, $subject, $message, $headers); // Send our email

die("<h2>Welcome to ste3med</h2>Login to your account to get started ...");
}
}
}
else {
echo '<script type="text/javascript">alert("Your passwords dont match!");</script>';

}
}
else
{
echo '<script type="text/javascript">alert("Please fill in all of the fields!");</script>';
}
}
else
{
echo '<script type="text/javascript">alert("Username already taken!");</script>';
}
}
else {
echo '<script type="text/javascript">alert("Your E-mails dont match!");</script>';
}
}
?>
<?
//Login Script
if (isset($_POST["user_login"]) && isset($_POST["password_login"])) {
	$user_login = $_POST["user_login"]; // filter everything but numbers and letters
    $password_login = $_POST["password_login"]; // filter everything but numbers and letters
	$encryptpassword_login = encrypt($password_login);
    $sql = mysql_query("SELECT id FROM users WHERE username='$user_login' AND password='$encryptpassword_login' LIMIT 1"); // query the person
	//Check for their existance
	$userCount = mysql_num_rows($sql); //Count the number of rows returned
	if ($userCount == 1) {
		while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
	}
		 $_SESSION["id"] = $id;
		 $_SESSION["user_login"] = $user_login;
		 $_SESSION["password_login"] = $password_login;
         exit("<meta http-equiv=\"refresh\" content=\"0\">");
		} else {
		echo 'That information is incorrect, try again';
		exit();
	}
}
}
?>
<div style="float: left;">
            <h2>Already a Memeber? Login below ...</h2>
            <form action="index.php" method="post" name="form1" id="form1">
				<input type="text" size="40" name="user_login" id="user_login" class="auto-clear" title="Username ..." /><p />
				<input type="password" size="40" name="password_login" id="password_login" value="Password ..." /><p />
				<input type="submit" name="button" id="button" value="Login to your account">
			</form>
            </div>
           <div style="float: right; width: 240px;">
            <h2>Sign up Below ...</h2>
           <form action="#" method="post">
           <input type="text" size="40" name="fname"  class="auto-clear" title="First Name" value="<? echo $fn; ?>"><p />
           <input type="text" size="40" name="lname" class="auto-clear" title="Last Name" value="<? echo $ln; ?>"><p />
           <input type="text" size="40" name="username" class="auto-clear" title="Username" value="<? echo $un; ?>"><p />
           <input type="text" size="40" name="email" class="auto-clear" title="Email" value="<? echo $em; ?>"><p />
           <input type="text" size="40" name="email2" class="auto-clear" title="Repeat Email" value="<? echo $em2; ?>"><p />
           <input type="password" size="40" name="password" value="Password ..."><p />
           <input type="password" size="40" name="password2" value="Password ..."><p />
		   <input type="submit" name="reg" value="Sign Up!">
           </form>
           </div>
</div>
</body>
</html>