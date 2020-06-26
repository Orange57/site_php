<?php //Mise en place library recaptcha php
require_once "recaptchalib.php";

// your secret key
$secret = "6LcwnV8UAAAAAJ5s6o_ty7GoKji5RmzkytwIfjuB";
 
// empty response
$response = null;
 
// check secret key
$reCaptcha = new ReCaptcha($secret);
// if submitted check response
if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}

 ?>
<!DOCTYPE html>
<html>
<head>
<?php include('./includes/head.php'); ?>
</head>
<body>
	<?php include('./includes/nav.php'); ?>
	<?php 
 //avis_entreprise
 //avis_email
 //avis_commentaire
 //avis_note
 //g-recaptcha-response
	$rep_captcha = json_decode($_POST['g-recaptcha-response']);
	echo($rep_captcha);
	if (isset($_POST['avis_entreprise']) AND !empty($_POST['avis_entreprise']) AND isset($_POST['avis_email']) AND !empty($_POST['avis_email']) AND isset($_POST['avis_commentaire']) AND !empty($_POST['avis_commentaire']) AND isset($_POST['avis_note']) and !empty($_POST['avis_note']))
	{
		if($response != null && $response->success)
		{
			include('./includes/bdd.php');
			$req = $bdd->prepare('INSERT INTO avis(nom, email, commentaire, note, adresse_ip) VALUES(:nom, :email, :commentaire, :note, :adresse_ip)');
				$req->execute(array(
					'nom' => $_POST['avis_entreprise'],
					'email' => $_POST['avis_email'],
					'commentaire' => $_POST['avis_commentaire'],
					'note' => $_POST['avis_note'],
					'adresse_ip' => get_ip()));
			echo('Tous les champs sont remplies, votre avis est en attente de validation.<br>Redirection vers l\'acceuil dans 5 secondes.<br>');
			header("refresh:5;url=index.php"); // Redirection automatique
		}
		else //captcha pas valide
		{
			echo('Captcha invalide, veuilliez ressayer.');
			header("refresh:3;url=avis.php"); // Redirection automatique
		}
	}
	else //un champs est pas remplie
	{
		echo('Merci de remplir tous les champs.');
		header("refresh:3;url=avis.php"); // Redirection automatique
	}
	?>
	<?php include('./includes/foot.php'); ?>
</body>
</html>

<?php
function get_ip() { //Récupérer la véritable adresse IP d'un visiteur
	// IP si internet partagé
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	// IP derrière un proxy
	else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	// Sinon : IP normale
	else {
		return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
	}
}
?>