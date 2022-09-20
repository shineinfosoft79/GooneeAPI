<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
</head>
<body style="margin:0px; padding: 0px;">

	<style type="text/css">

		.header {

			width: 650px;
		}


	@media only screen and (max-width:480px){
        .header {

			width: 100%;
			
		}

    }
		
	</style>

<table style="margin: 0px auto; padding:0px; border:1px solid #e4e4e4;" cellpadding:0; cellspacing:0; class="header" >
	<tr>
		<td style="border-bottom:solid 2px #62adc8; background: #e4e4e4; text-align: center; padding: 10px 0px;">
			<img src="<?=assets('images/email-parser-logo.png')?>" alt="logo" width="200px">
		</td>
	</tr>
	<tr>
		<td style="font-size: 20px; padding: 20px 0px 10px 10px; color: #000; font-weight: 700; font-family: 'Roboto', sans-serif;">
			Dear User,
		</td>
	</tr>
	<tr>
		<td style="font-size: 18px; color: #666; line-height: 22px; font-family: 'Roboto', sans-serif; padding: 10px 10px 10px 10px; ">
			You recently requested to verify your {v_email} email for Erdos account.
		</td>
	</tr>
	<tr>
		<td style="font-size: 19px; padding: 5px 0px 10px 10px; color: #000; font-weight: 700; font-family: 'Roboto', sans-serif;">
			Your one time OTP is : {otp}
		</td>
	</tr>
	<tr>
		<td style="font-size: 15px; color: #666; line-height: 22px; font-family: 'Roboto', sans-serif; padding: 10px 10px 10px 10px; ">
			<div style="background-color: #ff572226;padding: 10px;border-radius: 5px;">
				If you did not request a verify your email, Please ignore this email or reply to let us know.
			</div>
		</td>
	</tr>
	<tr>
		<td style="text-align:left; color: #666; font-size: 14px; font-family: 'Roboto', sans-serif; padding:20px 10px 10px 10px">
		<hr>
			Thanks & Regards<br>Erdos <?php echo date('Y');?>
		</td>
	</tr>
</table>

</body>
</html>