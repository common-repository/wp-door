<?php
session_start();

/*
WP-Door file
	
door_validated : Required in Cookie / Session to Validate That User has Clicked Enter. If None of these two set, Wp will Allways Redirect Users back to This Page!
enter: Submit Button For Enter Should Have "enter" words. If Language Reason changed these word, Please Change The PHP Bellow as well...

*/
if($_SERVER['REQUEST_METHOD']=='POST')
{
	if(isset($_POST['btnEnter']))
	{
		// Redirect TO:
		$redirect=isset($_GET['return'])?urldecode($_GET['return']):'./';
		
		// Get EXPIRY
		$expire=isset($_GET['e']) && is_numeric($_GET['e'])?intval($_GET['e']):-1;
		
		if($expire==-1)
		{
			$_SESSION['door_validated']="yes";
			header("location: ".$redirect);
			exit(0);
		}
		
		if($expire==0)
		{
			setcookie("door_validated", "yes",mktime(0,0,0,01,01,date("Y")+30));
			$_SESSION['door_validated']="yes";
			header("location: ".$redirect);
			exit(0);
		}
		
		setcookie("door_validated", "yes",(time()+$expire));
		$_SESSION['door_validated']="yes";
		header("location: ".$redirect);
		exit(0);

	}else{
		header("location: http://www.google.com");
		exit(0);
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Door Page</title>
<style type="text/css">
	html, body{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:0.8em;
		color:#333333;
		background-color:#F8F5EA;
	}
	
	#container{
		background-color:#FFFFFF;
		width:600px;
		margin:100px auto 10px;
		padding:50px;
		
		line-height:150%;
	}
	
	H1{
		background-color:#88071E;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:180%;
		color:#fff;
		margin:0; padding:0;
		margin-bottom:20px;
		padding:5px;
	}
	
	#buttons{
		clear:both;
		display:block;
		margin-top:20px;
		padding:5px;
		
		text-align:center;
	}
	
	#buttons input{
		margin:0 50px;
	}
	form{ padding:0; margin:0; border:0;}
	img, div{ outline:0;}
</style>
</head>

<body>
<form action="" method="POST">
<div id="container">
	<h1>(!) Content Warning</h1>
    <div>This Site Contains Following Contents... You Must be Atleast some age to Continue Browsing This Site... If you Are Please Click On Enter The Site to Contine... Of Please Leave By Clicking Leave The Site...</div>
    <div id="buttons">
    	<input name="btnExit" type="Submit" value="Leave The Site" />
    	<input name="btnEnter" type="Submit" value="Enter The Site" />
    </div>
</div>
</form>
</body>
</html>