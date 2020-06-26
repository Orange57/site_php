<!DOCTYPE html>
<html>
<head>
<title>AP CARRELAGES SAS</title>
 <meta charset="UTF-8">
 <link rel="icon" href="../includes/favicon.ico" />
 <link rel="stylesheet" href="../includes/styleees.css"> 
</head>

<body>
	<header id="navigation_head">
	<section id="navigation_list">
		<img id="logo" onClick="document.location.href='../index.php'" src="../includes/favicon.ico" alt="logo">
		<div onClick="document.location.href='../index.php'">Acceuil</div>
		<div onClick="document.location.href='../a_propos.php'">À propos</div>
		<div onClick="document.location.href='../boutique.php'">Boutique</div>
		<div onClick="document.location.href='../avis.php'">Laisser un avis</div>
		<div onClick="document.location.href='../contact.php'">Contact</div>
	</section>
	</header>
	<?php include('../includes/bdd.php'); ?>
	<?php include('./post_admin.php'); ?>
	
	<section id="main_content">
	
	<?php $reponse = $bdd->query('SELECT id, nom, email, commentaire FROM avis WHERE valide=0 ORDER BY id DESC');
		while($donnees = $reponse->fetch())
					{?>
					<p id="contenu">
					<?php echo('Nom : ' . htmlspecialchars($donnees['nom']) . ' <br>email : '. htmlspecialchars($donnees['email']) . '<br>Commentaire : ' . htmlspecialchars($donnees['commentaire']) . '<br> ID du commentaire : ' . $donnees['id'] . '<br>'); ?>
					<form method="get" action="administration.php">
						<input type="hidden" name="id" value="<?php echo($donnees['id']); ?>">
						<input type="submit" name="action" value="Valider">
						<input type="submit" name="action" value="Supprimer"><br><br>
					</form>
					</p>
					<?php
					}
					$reponse->closeCursor(); // Termine le traitement de la requête
					?>
		<!- Affichage des info sur commentaires saisie et en attente de validation, si refusé supprimé de la BDD sinon passer le validé en 1-> 
	</section>
	
	<?php include('../includes/foot.php'); ?>
</body>
</html>

