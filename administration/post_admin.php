
<?php
if(isset($_GET['action']) AND !empty($_GET['action']))//Si une action est effectué
{
	$Post_ID = htmlspecialchars($_GET['id']); //Secu des données recuperé 
	$Post_Action = htmlspecialchars($_GET['action']); //Secu des données recuperé 
	
	if($Post_Action=='Supprimer')//Action = Supprimer
	{
		$bdd->query('DELETE FROM `avis` WHERE id = '.$Post_ID.'');
		echo('L\'avis avec l\'ID numero '.$Post_ID.' à était supprimé!<br>');
	}
	else if($Post_Action=='Valider')//Action = Valider
	{
		$bdd->query('UPDATE avis SET valide = 1 WHERE id = '.$Post_ID.'');
		echo('L\'avis avec l\'ID numero '.$Post_ID.' à était validé!<br>');
	}
	else//Erreur action non reconnu
	{
		echo('Erreur, cliquer <a href="administration.php">ici</a> pour reessayer, si cela persiste, contacter Claude!<br>');
	}
}
else//Pas d'action
{
	
}

?>