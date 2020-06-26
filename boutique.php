<!DOCTYPE html>
<html>
<head>
	<?php include('./includes/head.php');?> 
</head>
<body>
	<?php include('./includes/nav.php'); ?>
	<section id="main_content">
		<!-- PayPal Logo --><table border="0" cellpadding="10" cellspacing="0" align="center"><tr><td align="center"></td></tr><tr><td align="center"><a href="https://www.paypal.fr/webapps/mpp/paypal-popup" title="How PayPal Works" onclick="javascript:window.open('https://www.paypal.fr/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;"><img src="https://www.paypalobjects.com/webstatic/mktg/logo/bdg_now_accepting_pp_2line_w.png" border="0" alt="Now Accepting PayPal"></a><div style="text-align:center"><a href="https://www.paypal.com/webapps/mpp/how-paypal-works"><font size="2" face="Arial" color="#0079CD"></font></a></div></td></tr></table><!-- PayPal Logo -->
	
			<section id="content_boutique">
			
				<?php include('./includes/bdd.php');
				 $reponse = $bdd->querry('SELECT * FROM boutique WHERE *');
				 ?>
				
			</section>
	
		<!-- Deuxieme logo paypal -->Mode de payement <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppppcmcvdam.png" alt="Pay with PayPal, PayPal Credit or any major credit card" /> <!-- Deuxieme logo paypal -->
	</section>
	<?php include('./includes/foot.php'); ?>
</body>
</html>