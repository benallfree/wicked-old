<?php 

$err = array();
					 
if(p('doRegister')== 'Register') 
{ 
  /******************* Filtering/Sanitizing Input *****************************
  This code filters harmful script code and escapes data of all POST data
  from the user submitted form.
  *****************************************************************/
  foreach($_POST as $key => $value) {
  	$data[$key] = filter($value);
  }
  
  /********************* RECAPTCHA CHECK *******************************
  This code checks and validates recaptcha
  ****************************************************************/
       
        $resp = recaptcha_check_answer ($config['recaptcha']['private_key'],
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
  
        if (!$resp->is_valid) {
          $err[] = "Image Verification failed.";			
        }
  /************************ SERVER SIDE VALIDATION **************************************/
  /********** This validation is useful if javascript is disabled in the browswer ***/
  
  
  // Validate User Name
  if (!isUserID($data['user_name'])) {
  $err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
  //header("Location: register.php?msg=$err");
  //exit();
  }
  
  // Validate Email
  if(!isEmail($data['usr_email'])) {
  $err[] = "ERROR - Invalid email address.";
  //header("Location: register.php?msg=$err");
  //exit();
  }
  // Check User Passwords
  if (!checkPwd($data['pwd'],$data['pwd2'])) {
  $err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
  //header("Location: register.php?msg=$err");
  //exit();
  }
  	  
  $user_ip = $_SERVER['REMOTE_ADDR'];
  
  // stores sha1 of password
  $sha1pass = PwdHash($data['pwd']);
  
  // Automatically collects the hostname or domain  like example.com) 
  $host  = $_SERVER['HTTP_HOST'];
  $host_upper = strtoupper($host);
  $path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  
  // Generates activation code simple 4 digit number
  $activ_code = rand(1000,9999);
  
  $usr_email = $data['usr_email'];
  $user_name = $data['user_name'];
  
  /************ USER EMAIL CHECK ************************************
  This code does a second check on the server side if the email already exists. It 
  queries the database and if it has any existing email it throws user email already exists
  *******************************************************************/
  
  $rs_duplicate = mysql_query("select count(*) as total from users where email='$usr_email' OR login='$user_name'") or die(mysql_error());
  list($total) = mysql_fetch_row($rs_duplicate);
  
  if ($total > 0)
  {
    $err[] = "ERROR - The username/email already exists. Please try again with different username and email or use the reset password feature.";
  }
  
  if(empty($err))
  {
  
    $u = User::create( array(
      'attributes'=>array(
        'email'=>$usr_email,
        'pwd'=>$sha1pass,
        'date'=>time(),
        'users_ip'=>$user_ip,
        'activation_code'=>$activ_code,
        'login'=>$user_name,
        'md5_id'=>md5($user_name.$sha1pass.microtime(true)),
      ),
    ));
    
    flash("Please check your email.");
    redirect_to('/account/check?u='.$usr_email);
  }					 
}

?>
  <script>
  $(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
  });
  </script>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr> 
    <td width="160" valign="top"><p>&nbsp;</p>
      <p>&nbsp; </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top"><p>
	<?php 
	 if (isset($_GET['done'])) { ?>
	  <h2>Thank you</h2> Your registration is now complete and you can <a href="login.php">login here</a>";
	 <?php exit();
	  }
	?></p>
      <h3 class="titlehdr">Free Registration / Signup</h3>
      <p>Lessons require a webcam, microphone, and Skype. When you sign up, you'll receive 2 free lessons and 1 lesson per week.
      <p>
        Registration is quick and free! Please note that fields marked <span class="required">*</span> 
        are required.</p>
	 <?php	
	 if(!empty($err))  {
	   echo "<div class=\"msg\"><ul>";
	  foreach ($err as $e) {
	    echo "<li>".h($e);
	    }
	  echo "</ul></div>";	
	   }
	 ?> 
	 
	  <br>
      <form action="register" method="post" name="regForm" id="regForm" >
        <table width="95%" border="0" cellpadding="3" cellspacing="3" class="forms">
          <tr> 
            <td>Username<span class="required"><font color="#CC0000">*</font></span></td>
            <td><input name="user_name" type="text" id="user_name" class="required username" minlength="5" value="<?=p('user_name')?>" > 
              <input name="btnAvailable" type="button" id="btnAvailable"			  value="Check Availability"> 
              <script>
                $(function() {
                  var check = function()
                  {
                    $("#checkid").html("Please wait..."); 
                    $.get("/account/checkuser",{ 
                      cmd: "check", 
                      user: $("#user_name").val() 
                      } ,function(data){  
                        $("#checkid").html(data);
                      }
                    ); 
                  }
                  $('#user_name').blur(function() {
                    check();
                  });
                  $('#btnAvailable').click(function() {
                    check();
                  });
                });
              </script>
			    <span style="color:red; font: bold 12px verdana; " id="checkid" ></span> 
            </td>
          </tr>
          <tr> 
            <td>Your Email<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="usr_email" type="text" id="usr_email3" class="required email" value="<?=p('usr_email')?>"> 
              <span class="example">** Valid email please..</span></td>
          </tr>
          <tr> 
            <td>Password<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="pwd" type="password" class="required password" minlength="5" id="pwd"  value="<?=p('pwd')?>"> 
              <span class="example">** 5 chars minimum..</span></td>
          </tr>
          <tr> 
            <td>Retype Password<span class="required"><font color="#CC0000">*</font></span> 
            </td>
            <td><input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd"  value="<?=p('pwd2')?>"></td>
          </tr>
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="22%"><strong>Image Verification </strong></td>
            <td width="78%"> 
              <?php 
				echo recaptcha_get_html($config['recaptcha']['public_key']);
			?>
            </td>
          </tr>
        </table>
        <p align="center">
          <input name="doRegister" type="submit" id="doRegister" value="Register">
        </p>
      </form>
      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
