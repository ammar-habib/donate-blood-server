<?php
function SIGNUP($conn)
{
  #ID
  #NAME
  #CONTACT_NUMBER
  #BLOOD_GROUP
  #GENDER
  #DOB
  #SIGNUP_DATE
  #LOCATION
  $sql = $conn->prepare("INSERT INTO user VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
		$sql->bind_param("sssssssss", $n, $e, $p, $c, $b, $g, $d, $sd, $cc);
		
		$n = $_REQUEST["NAME"];
		$e = $_REQUEST["EMAIL"];
		$p = $_REQUEST["PASSWORD"];
		$c = $_REQUEST["CONTACT_NUMBER"];
		$b = $_REQUEST["BLOOD_GROUP"];
		$g = $_REQUEST["GENDER"];
		$d = $_REQUEST["DOB"];
		$sd = $_REQUEST["SIGNUP_DATE"];
		$cc = $_REQUEST["CURRENT_CITY"];

		if ($sql->execute())
		{
			$json["STATUS"] = "SUCCESS";
			$json["MESSAGE"] = "Account successfully created";

		}
		else
		{
			$json["STATUS"] = "FAIL";
			$json["MESSAGE"] = "Account not created. Reason: " . $sql->error;
		}

		$sql->close();

	
	return json_encode($json);

#function ends
}

function ACCOUNT_DELETE($conn)
{		
	$sql = $conn->prepare("UPDATE user SET status = 2 WHERE user_id = ?");
	$sql->bind_param("i",  $i);
	$i = $_REQUEST["USER_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Account successfully delete";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Account not delete. Reason: " . $sql->error;
	}

	$sql->close();
	
	return json_encode($json);

#function ends
  
}

function ACCOUNT_UPDATE($conn)
{
	$sql = $conn->prepare("UPDATE user SET name = ?, email = ?, password = ?, contact_number = ?, blood_group = ?, gender = ?, dob = ?, signup_date = ?, current_city = ? WHERE user_id = ?");
	
	$sql->bind_param("sssssssssi", $n, $e, $p, $c, $b, $g, $d, $sd, $cc, $i);
	
	$i = $_REQUEST["USER_ID"];
	$n = $_REQUEST["NAME"];
	$e = $_REQUEST["EMAIL"];
	$p = $_REQUEST["PASSWORD"];
	$c = $_REQUEST["CONTACT_NUMBER"];
	$b = $_REQUEST["BLOOD_GROUP"];
	$g = $_REQUEST["GENDER"];
	$d = $_REQUEST["DOB"];
	$sd = $_REQUEST["SIGNUP_DATE"];
	$cc = $_REQUEST["CURRENT_CITY"];
	
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Account successfully update";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Account not update. Reason: " . $sql->error;
	}

	$sql->close();


return json_encode($json);

#function ends
  
}
 
 function ACCOUNT_VIEW($conn)
{  
    $sql = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
	$sql->bind_param("i", $i);
	$i = $_REQUEST["USER_ID"];
	if($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		return json_encode($json);
	}
	$sql->bind_result($i, $n, $e, $p, $c, $b, $g, $d, $sd, $cc, $s);
	 
	$count =0;
	while ($sql->fetch())
	{
		$_data["name"] = $n;
		$_data["email"] = $e;
		$_data["password"] = $p;
		$_data["contact_number"] = $c;
		$_data["blood_group"] = $b;
		$_data["gender"] = $g;
		$_data["dob"] = $d;
		$_data["signup_date"] = $sd;
		$_data["current_city"] = $cc;
		$_data["status"] = $s;
		$json["DATA"][] = $_data;
		unset($_data);
		$count++;
	}
	if ($count == 0)
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "No rows retrived";

	}
	$sql->close();
		
	return json_encode($json);

#function ends
}

function LOGIN($conn)
{
	#CONTACT_NUMBER
	#BLOOD_GROUP
	$sql = $conn->prepare("SELECT * FROM user WHERE  email = ? AND password = ? AND status != 2");
	$sql->bind_param("ss", $e, $p);

	$e = $_REQUEST["EMAIL"];
	$p = $_REQUEST["PASSWORD"];

	if($sql->execute()){
	    $json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Login Successful";
	}
	else{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Login Failed.";
		return json_encode($json);
	}
	
	$sql->bind_result($i, $n, $e, $p, $c, $b, $g, $d, $sd, $cc, $s);
	$count =0;
	while($sql->fetch())
	{
		$_data["user_id"] = $i;
		$_data["email"] = $e;
		$_data["password"] = $p;
		$json["DATA"][] = $_data;
		$count++;
	}
	if ($count == 0)
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Login Failed";
    }
	
	$sql->close();
	return json_encode($json);
	#function ends
 } 
 
 function FORGET_PASSWORD($conn)
{
	$sql = $conn->prepare("SELECT * FROM user WHERE  email = ? ");
	$sql->bind_param("s",$e);
    $e = $_REQUEST["EMAIL"];
	if($sql->execute()){
	    $json["STATUS"] = "SUCCESS";	
	}
	else{
		$json["STATUS"] = "FAIL";
	}
	$sql->bind_result($i, $n, $e, $p, $c, $b, $g, $d, $sd, $cc, $s);
	$count =0;
	while($sql->fetch())
	{
		$_data["email"] = $e;
		$_data["password"] = $p;
		$json["DATA"][] = $_data;
		$from = "Coding Cyber";
		$url = "http://www.codingcyber.com/";
		$body  =  "Coding Cyber password recovery Script
		-----------------------------------------------
		Url : $url;
		email Details is : $e;
		Here is your password  : $p;
		Sincerely,
		Coding Cyber";
		$from = "ammarhabib@yahoo.com";
		$subject = "CodingCyber Password recovered";
		$headers1 = "From: $from\n";
		$headers1 .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$headers1 .= "X-Priority: 1\r\n";
		$headers1 .= "X-MSMail-Priority: High\r\n";
		$headers1 .= "X-Mailer: Just My Server\r\n";
		$sentmail = mail( $e, $subject, $body, $headers1 );
		$count++;
	}
	if ($_REQUEST["EMAIL"]!=="")
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Not found your email in our database";
    }
	if($sentmail==1)
	{
		$json["MESSAGE"] = "Your Password Has Been Sent To Your Email Address.";
	}
		else
		{
		if($_REQUEST["EMAIL"]!=="")
		$json["MESSAGE"] = "Cannot send password to your e-mail address.Problem with sending mail...";
	   }
	
	$sql->close();
	return json_encode($json);
	#function ends
 } 
 
function ALL_REQUEST_VIEW($conn)
   {
    $sql = $conn->prepare("select u.user_id, u.name, r.request_id, r.blood_group, r.request_date, u.current_city, r.status from user u left join request_blood r ON (u.user_id = r.user_id) where r.status != '2'");
	if($sql->execute()){
		$json["STATUS"] = "SUCCESS";
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		return json_encode($json);
	}
	$sql->bind_result($i, $n, $ri, $b, $rd, $cc, $s);
	 
	$count =0;
	while ($sql->fetch())
	{
		$_data["USER_ID"] = $i;
		$_data["NAME"] = $n;
		$_data["REQUEST_ID"] = $ri;
	    $_data["BLOOD_GROUP"] = $b;
		$_data["REQUEST_DATE"] = $rd;
		$_data["CURRENT_CITY"] = $cc;
		$_data["STATUS"] = $s;
		$json["DATA"][] = $_data;
		unset($_data);
		$count++;
	}
	if ($count == 0)
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "No rows retrived". $sql->error;

	}
	$sql->close();
		
	return json_encode($json);
 #function ends
 }
 
 function REQUEST_ACCEPT($conn)
 {
	$sql = $conn->prepare("INSERT INTO request_accept VALUES ('', ?, ?, 1)");
	
	$sql->bind_param("ii", $i, $ri);
	$i = $_REQUEST["USER_ID"];
	$ri = $_REQUEST["REQUEST_ID"];
	if ($sql->execute())
	{
        
		$sql = $conn->prepare("UPDATE request_blood SET status = 1 WHERE request_id = ?");
        $sql->bind_param("i", $ri);
		$ri = $_REQUEST["REQUEST_ID"];
		if ($sql->execute()){
			$json["STATUS"] = "SUCCESS";
			$json["MESSAGE"] = "You Accpet Blood Request Successfully ";
		}
		else
		{
			$json["STATUS"] = "FAIL";
			$json["MESSAGE"] = "your Blood Request Not Accpet". $sql->error;
		}
	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your Blood Request Not Accpet". $sql->error;
	}

	$sql->close();
    return json_encode($json);

#function ends
 }
 
  function REQUEST_RESEND($conn)
 {
	$sql = $conn->prepare("UPDATE request_blood SET status = 0 WHERE user_id = ? AND request_id = ?");
	
	$sql->bind_param("ii", $i, $ri);
	$i = $_REQUEST["USER_ID"];
	$ri = $_REQUEST["REQUEST_ID"];
	if ($sql->execute())
	{
        $sql = $conn->prepare("UPDATE request_accept SET status = 0 WHERE user_id = ? AND request_id = ?");
        $sql->bind_param("ii", $i, $ri);
		$i = $_REQUEST["USER_ID"];
		$ri = $_REQUEST["REQUEST_ID"];
		if ($sql->execute()){
			$json["STATUS"] = "SUCCESS";
			$json["MESSAGE"] = "You Again Send Blood Request Successfully ";
		}
		else
		{
			$json["STATUS"] = "FAIL";
			$json["MESSAGE"] = "your can not send the Blood Request". $sql->error;
		}
	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your can not send the Blood Request". $sql->error;
	}

	$sql->close();
    return json_encode($json);

#function ends
 }
 
 function REQUEST_VIEW($conn)
 {
    $sql = $conn->prepare("SELECT * FROM request_blood WHERE user_id = ? AND (status = 0 OR status = 1)");
	$sql->bind_param("i", $i);
	$i = $_REQUEST["USER_ID"];
	if($sql->execute()){
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "This is your Blood Request";
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		return json_encode($json);
	}
	$sql->bind_result($ri, $i, $lo, $la, $b, $rd, $s);
	 
	$count =0;
	while ($sql->fetch())
	{
		$_data["REQUEST_ID"] = $ri;
		$_data["LONGITUDE"] = $lo;
		$_data["LATITUDE"] = $la;
		$_data["BLOOD_GROUP"] = $b;
		$_data["REQUEST_DATE"] = $rd;
		$_data["STATUS"] = $s;
		$json["DATA"][] = $_data;
		unset($_data);
		$count++;
	}
	if ($count == 0)
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "No rows retrived". $sql->error;

	}
	$sql->close();
		
	return json_encode($json);
 #function ends
 }
 function REQUEST_BLOOD($conn)
 {
    #USER_ID
    #LONGITUDE
	#LATITUDE
	#BLOOD_GROUP
	#STATUS
	
	$sql = $conn->prepare("INSERT INTO request_blood VALUES ('', ?, ?, ?, ?, ?, 0)");
	$sql->bind_param("issss", $i, $lo, $la, $b, $rd);
	$i = $_REQUEST["USER_ID"];
	$lo = $_REQUEST["LONGITUDE"];
	$la = $_REQUEST["LATITUDE"];
	$b = $_REQUEST["BLOOD_GROUP"];
	$rd = $_REQUEST["REQUEST_DATE"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your Request Successfully Send";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your Request Not Send". $sql->error;
	}

	$sql->close();

	
	return json_encode($json);

#function ends
 }
 
 function REQUEST_UPDATE($conn)
 {
    $sql = $conn->prepare("UPDATE request_blood SET longitude = ?,latitude = ?,blood_group = ?,request_date = ? WHERE request_id = ? AND user_id = ? AND status = 0");
	$sql->bind_param("ssssii", $lo, $la, $b, $rd, $ri, $i);
    $lo = $_REQUEST["LONGITUDE"];
	$la = $_REQUEST["LATITUDE"];
	$b = $_REQUEST["BLOOD_GROUP"];
	$rd = $_REQUEST["REQUEST_DATE"];
	$ri = $_REQUEST["REQUEST_ID"];
	$i =  $_REQUEST["USER_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your blood request is successfully update";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Your blood request is not update. Reason: " . $sql->error;
	}

	$sql->close();
    return json_encode($json);
 #function ends
 }

 
 
 function REQUEST_DELETE($conn)
 {
    #REQUEST_ID
	#STATUS
	$sql = $conn->prepare("UPDATE request_blood SET status = 2 WHERE request_id = ?");
	$sql->bind_param("i", $ri);
	$ri = $_REQUEST["REQUEST_ID"];
	
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "your blood request Delete Successfully";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your blood request not Deletetion";
	}

	$sql->close();

		return json_encode($json);
 
 #function ends
 }
 
 
 function DONATE_BLOOD($conn)
 {
    #USER_ID
    #REQUEST_ID
	#BLOOD_GROUP
	#STATUS
	
	$sql = $conn->prepare("INSERT INTO donate_blood VALUES ('', ?, ?, ?,0)");
	$sql->bind_param("iis", $i, $ri, $b);
	$i = $_REQUEST["USER_ID"];
	$ri = $_REQUEST["REQUEST_ID"];
	$b = $_REQUEST["BLOOD_GROUP"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your Are Donate Blood Successfully";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your Can Not Donate Blood ". $sql->error;
	}

	$sql->close();

	
	return json_encode($json);
    
 #function ends
 }
 
 function DONATE_VIEW($conn)
 {
    $sql = $conn->prepare("SELECT * FROM donate_blood WHERE donate_id = ?");
	$sql->bind_param("i", $di);
	$di = $_REQUEST["DONATE_ID"];
	if($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		return json_encode($json);
	}
	$sql->bind_result($di, $i, $ri, $b, $s);
	 
	$count =0;
	while ($sql->fetch())
	{
	    $_temp["USER_ID"] = $i;
		$_temp["REQUEST_ID"] = $ri;
		$_temp["BLOOD_GROUP"] = $b;
		$_temp["STATUS"] = $s;
		$json["DATA"][] = $_temp;
		unset($_temp);
		$count++;
	}
	if ($count == 0)
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "No rows retrived". $sql->error;

	}
	$sql->close();
		
	return json_encode($json);
 
 #function ends
 }
 
 function DONATE_DELETE($conn)
 {
    #DONATE_ID
	#STATUS
	$sql = $conn->prepare("UPDATE donate_blood SET Status = ? WHERE donate_id = ?");
	$sql->bind_param("ii", $s, $di);
    $di = $_REQUEST["DONATE_ID"];
	$s = $_REQUEST["STATUS"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "you Delete Blood Request Successfully";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your Can Not DELETE Blood Request ";
	}

	$sql->close();

		return json_encode($json);
 
     #function ends
 }
 
 function DONATE_UPDATE($conn)
 {
    $sql = $conn->prepare("UPDATE donate_blood SET blood_group = ? ,status = ? WHERE donate_id = ?");
	$sql->bind_param("sii",$b, $s, $di );
	$S = $_REQUEST["STATUS"];
	$b = $_REQUEST["BLOOD_GROUP"];
	$di =  $_REQUEST["DONATE_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your donate blood request is successfully update";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Your donate blood request is not update. Reason: " . $sql->error;
	}

	$sql->close();

	
	return json_encode($json);
 
 #function ends
 }
 
 function CARD($conn)
{
    $sql = $conn->prepare("INSERT INTO card VALUES ('', ?, ?, 0)");
	$sql->bind_param("ii", $di, $ri);
	$di = $_REQUEST["DONATE_ID"];
	$ri = $_REQUEST["REQUEST_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your Send Card Successfully";
	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "your Can Not Send Card  ". $sql->error;
	}

	$sql->close();

	return json_encode($json);
  
  #function ends
}

function CARD_DELETE($conn)
 {
    #DONATE_ID
	#STATUS
	$sql = $conn->prepare("UPDATE card SET Status = ? WHERE CARD_id = ?");
	$sql->bind_param("ii", $s, $ci);
    $ci = $_REQUEST["CARD_ID"];
	$s = $_REQUEST["STATUS"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "You Delete Thank you Card Successfully";

	}
	else
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Your Can Not DELETE Thank YOU Card ";
	}

	$sql->close();

		return json_encode($json);
 
     #function ends
 }
?>