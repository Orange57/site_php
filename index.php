<!DOCTYPE html>
<html>
<head>
<?php include('./includes/head.php');
require_once __DIR__ . '/vendor/autoload.php'; //Composer
//BDD nom : ap_carrelages 
//table avis, entrer : id,nom(varchar),email(varchar),commentaire(text),note(int),adresse ip(varchar), valide(boolean)
?> 
<script type="text/javascript" src="includes/jquery.js">//Jquerys</script>
<script type="text/javascript" src="includes/index/jquery.cycle.js"></script>

  <script type="text/javascript">
    document.ready(function() {
      $('#slider').cycle( {
        fx: 'scrollRight',
        timeout: 5000,
        pause: 1.
        cleartypeNoBg: true
      } );
    } );
</script>

</head>
<body>
	<?php include('./includes/nav.php'); ?>
	<section id="main_content_api_fb">
		<!- Affichage des info sur les travaux effectué et ou evenement qu'il saisira sur la page facebook ou sur le site-> 
		<?php  
		//Token a génerer sur : https://developers.facebook.com/tools/explorer/192128204746759?method=GET&path=me%3Ffields%3Did%2Cname%2Cfeed.limit(10)%7Bmessage%2Ccreated_time%2Cpicture%2Clink%2Cattachments%7Bmedia%2Csubattachments%7D%7D&version=v3.0
		$token = 'secret';
		$fb = new Facebook\Facebook([ //Connexion a l'api de facebook pour scraper
			 'app_id' => 'APPID', //Info de l'app sur le site facebook for developers
			 'app_secret' => 'APPSECRET', 
			 'default_graph_version' => 'v2.10',
			 ]);
			 ///////////////////////////
		try {
			 $response = $fb->get(
			'me?fields=id,name,feed.limit(10){message,created_time,picture,link,attachments{media,subattachments}}', //Requete qu'on envoie a l'api facebook, génerer avec https://developers.facebook.com/tools/explorer/145634995501895/ 	
			$token
			 );
		} catch(FacebookExceptionsFacebookResponseException $e) { // On recupere un objet facebook et on teste les erreurs 
			 echo 'Graph returned an error: ' . $e->getMessage();
			 exit;
		} catch(FacebookExceptionsFacebookSDKException $e) {
			 echo 'Facebook SDK returned an error: ' . $e->getMessage();
			 exit;
		}
		$graphNode = $response->getGraphNode(); // Si il y a pas d'erreur on recupere un objet graphNode
			
		$file = fopen("./includes/api_facebook.json", "w+"); // Ouverture du fichier json en ecriture+ pour un refresh des données
		//json_encode( $graphNode ); // On convertis l'objet facebook au format json pas necessaire dans notre cas
		fwrite($file,$graphNode); //ecriture des info de l'api dans un fichier json
		fclose($file); //On ferme le flux
		//$obj = json_decode($graphNode); //On convertis l'objet graphnode json en php a se servir si on utilise pas un fichier json
			
		$json = file_get_contents("./includes/api_facebook.json"); //On recupere le contenu du fichier json 
		$parsed_json = json_decode($json); //On convertis le json en php
		foreach($parsed_json->feed as $item)  //On va lire dans l'objet feed fournis par l'api facebook
		{
			$datecrea = date_format(new DateTime($item->created_time->date),"d-m-Y H:i"); // On convertis l'objet que facebook nous donne avec la date dedans dans un bon format DateTime
			if(isset($item->message)) // Si ya un message on le recupere dans la variable message
			{
				$message = $item->message;
			}
			else // Si ya pas de message
			{
				$message = '';
			}
			echo("<hr><p id='index_contenu'>".$datecrea."<br>".$message."<br>"); // On ouvre un paragraphe par post lu ou on affiche la date de creation et le message si pas de message affiche rien.
			foreach($item->attachments as $value)
				{
					if(isset($value->subattachments)) // Si plusieurs photo, facebook créer un "subattachments"
					{
						foreach($value->subattachments as $value2) //On parcours l'objet subattachments donc utilisation de foreach
						{
							$imgurl=$value2->media->image->src; //On recupere le lien de l'image
							echo("<img src='".$imgurl."' alt='Image charger de facebook' width='270' height='400'>"); //Echo d'une balise img en html 
						}
					}
					else{ //Si que une photo, elle se trouve dans attachments 
						$imgurl=$value->media->image->src; //On recupere le lien de l'image
						echo("<img src='".$imgurl."' alt='Image charger de facebook' width='270' height='400'>"); //Echo d'une balise img en html 
					}
				}
			echo("</p>"); //Fin de paragraphe
		}
			
			
			
			/* Ici je ne convertissais pas le retour de l'api facebook en json mais en array directement
			------------------------------------------------------------------------------------------------------------------------------------------------------------
			$feed = $graphNode['feed']->asArray(); // On convertis l'objet graphNode en array
			foreach($feed as $in_feed){ // On fouille dans le 1er array, l'objet feed
				foreach($in_feed as $key => $in_in_feed){ // On fouille dans chaque feed qui eux même contiens plusieur array
					if($key == 'message') // Si un feed contien un message on convertir la date en string et on affiche le poste
					{
						$date = $in_feed['created_time']->format('Y-m-d H:i');
						echo('<p id="in_contenu">' .$date . '<br>' . $in_in_feed . '<br>' . '<img src="'.$in_feed["picture"].'"/>'. '<br><a href='.$in_feed['link'].'>Cliquer ici pour voir plus de photo</a>' .'</p>'); // $in_feed['id'] id du poste 		 
						
						
					}
				}
			}
			//var_dump($feed);	
			//echo($feed['3']['message']);
			------------------------------------------------------------------------------------------------------------------------------------------------------------
			*/
		?>
	</section>
	
	<section id="avis_index">
		<!- Ici connection BDD et affichage des 5 derniers commentaire approuvé !! affichage avec htmlspecialchars sinon risque de fail et injection de code->
		
		<?php include('./includes/bdd.php'); ?>
		<?php $reponse = $bdd->query('SELECT nom, commentaire, note FROM avis WHERE valide = 1 ORDER BY id DESC LIMIT 5');
		while($donnees = $reponse->fetch())
			{ //Creation de tableau multidimensionnels pour stocker le contenu de la DDB et s'en servir dans le javascript
			$nom = $donnees['nom'];
			$commentaire = $donnees['commentaire'];
			$note = $donnees['note'];
			$array[] = array(
				'nom' => $nom,
				'commentaire' => $commentaire,
				'note' => $note);
			}
			$reponse->closeCursor(); // Termine le traitement de la requête
			?>
		
		<div id="slider">
		  <div><?php echo($array[4]['nom'] . '<br>' . $array[4]['commentaire'] . '<br>' . $array[4]['note']); ?></div>
		  <div><?php echo($array[3]['nom'] . '<br>' . $array[3]['commentaire'] . '<br>' . $array[3]['note']); ?></div>
		  <div><?php echo($array[2]['nom'] . '<br>' . $array[2]['commentaire'] . '<br>' . $array[2]['note']); ?></div>
		  <div><?php echo($array[1]['nom'] . '<br>' . $array[1]['commentaire'] . '<br>' . $array[1]['note']); ?></div>
		  <div><?php echo($array[0]['nom'] . '<br>' . $array[0]['commentaire'] . '<br>' . $array[0]['note']); ?></div>
		</div>
		
		<input type="radio" name="index_avis" id="index_radio1">
		<input type="radio" name="index_avis" id="index_radio2">
		<input type="radio" name="index_avis" id="index_radio3">
		<input type="radio" name="index_avis" id="index_radio4">
		<input type="radio" name="index_avis" id="index_radio5">
		<script>
		var rad_1 = document.getElementById('index_radio1');
		var rad_2 = document.getElementById('index_radio2');
		var rad_3 = document.getElementById('index_radio3');
		var rad_4 = document.getElementById('index_radio4');
		var rad_5 = document.getElementById('index_radio5');
		rad_1.onclick = function() {
			var nom = <?php echo json_encode($array[4]['nom']); ?>;
			var commentaire =<?php echo json_encode($array[4]['commentaire']); ?>;
			var note = <?php echo json_encode($array[4]['note']); ?>;
			switch (note)
			{
				case '1':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '2':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '3':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '4':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png">';
					break;
				case '5':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png">';
					break;
				default:
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + note + '/5';	
					break;
			}
		};
		rad_2.onclick = function() {
			var nom = <?php echo json_encode($array[3]['nom']); ?>;
			var commentaire =<?php echo json_encode($array[3]['commentaire']); ?>;
			var note = <?php echo json_encode($array[3]['note']); ?>;
			switch (note)
			{
				case '1':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '2':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '3':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '4':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png">';
					break;
				case '5':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png">';
					break;
				default:
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + note + '/5';	
					break;
			}
		}
		rad_3.onclick = function() {
			var nom = <?php echo json_encode($array[2]['nom']); ?>;
			var commentaire =<?php echo json_encode($array[2]['commentaire']); ?>;
			var note = <?php echo json_encode($array[2]['note']); ?>;
			switch (note)
			{
				case '1':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '2':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '3':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '4':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png">';
					break;
				case '5':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png">';
					break;
				default:
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + note + '/5';	
					break;
			}
		};
		rad_4.onclick = function() {
			var nom = <?php echo json_encode($array[1]['nom']); ?>;
			var commentaire =<?php echo json_encode($array[1]['commentaire']); ?>;
			var note = <?php echo json_encode($array[1]['note']); ?>;
			switch (note)
			{
				case '1':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '2':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '3':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '4':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png">';
					break;
				case '5':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png">';
					break;
				default:
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + note + '/5';	
					break;
			}
		};
		rad_5.onclick = function(){
			var nom = <?php echo json_encode($array[0]['nom']); ?>;
			var commentaire =<?php echo json_encode($array[0]['commentaire']); ?>;
			var note = <?php echo json_encode($array[0]['note']); ?>;
			switch (note)
			{
				case '1':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '2':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '3':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png"><img src="./includes/star_empty.png">';
					break;
				case '4':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_empty.png">';
					break;
				case '5':
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + '<img src="./includes/star_full.png" alt="1/5"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png"><img src="./includes/star_full.png">';
					break;
				default:
					document.getElementById('index_js_avis').innerHTML = nom + '<br>' + commentaire + '<br>' + note + '/5';		
					break;
			}
		};
		var inf_loop = 0;
		function start(){
			switch (inf_loop)
			{
				case 1:
					document.getElementById('index_radio1').click();//affichage 1
					inf_loop++;
					alert('1');
					var time = setTimeout(function(){ start() }, 1000);
					break;
				case 2:
					document.getElementById('index_radio2').click();//affichage 2
					inf_loop++;
					alert('2');
					var time = setTimeout(function(){ start() }, 1000);
					break;
				case 3:
					document.getElementById('index_radio3').click();//affichage 3
					inf_loop++;
					alert('3');
					var time = setTimeout(function(){ start() }, 1000);
					break;
				case 4:
					document.getElementById('index_radio4').click();//affichage 4
					inf_loop++;
					alert('4');
					var time = setTimeout(start, 1000);
					break;
				case 5:
					document.getElementById('index_radio5').click();//affichage 5
					inf_loop++;
					alert('5');
					var time = setTimeout(function(){ start() }, 1000);
					break;
				default:
					inf_loop = 1; //reset 
					var time = setTimeout(function(){ start() }, 1000);
					alert('hors switch');
					break;
			}
		}
		</script>
		<div id='index_js_avis' onload="start"> <!- Contennu génerer automatiquement ->
		Affichage des commentaires<br>
		Validé <br>
		Et la  note sur 5
		</div>
	</section>
	
	<?php include('./includes/foot.php'); ?>
</body>
</html>
