<!DOCTYPE html>
<html>
<head>
<?php include('./includes/head.php'); ?>
<script src='https://www.google.com/recaptcha/api.js'>
</script>
<script type="text/javascript">
<!--
	
	var already_click = 0;
	function clicked(nb)
	{
		switch(nb)
		{
			case 1:
				note5_out();
				note1_in();
				already_click = 1;
				break;
			case 2:
				note5_out();
				note2_in();
				already_click = 2;
				break;
			case 3:
				note5_out();
				note3_in();
				already_click = 3;
				break;
			case 4:
				note5_out();
				note4_in();
				already_click = 4;
				break;
			case 5:
				note5_out();
				note5_in();
				already_click = 5;
				break;
			default:
				already_click = 0;
				note5_out();
				break;
		}
	}
	function note1_in()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_full.png';
		}
	}
	function note1_out()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_empty.png';
		}
	}
	function note2_in()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_full.png';
			note_image2.src='./includes/star_full.png';
		}
	}
	function note2_out()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_empty.png';
			note_image2.src='./includes/star_empty.png';
		}
	}
	function note3_in()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_full.png';
			note_image2.src='./includes/star_full.png';
			note_image3.src='./includes/star_full.png';
		}
	}
	function note3_out()
	{
		if (already_click == 0)
		{
			note_image1.src='./includes/star_empty.png';
			note_image2.src='./includes/star_empty.png';
			note_image3.src='./includes/star_empty.png';
		}
	}
	function note4_in()
	{
		if (already_click == 0)
		{
			 note_image1.src='./includes/star_full.png';
			 note_image2.src='./includes/star_full.png';
			 note_image3.src='./includes/star_full.png';
			 note_image4.src='./includes/star_full.png';
		}
	}
	function note4_out()
	{
		if (already_click == 0)
		{
			 note_image1.src='./includes/star_empty.png';
			 note_image2.src='./includes/star_empty.png';
			 note_image3.src='./includes/star_empty.png';
			 note_image4.src='./includes/star_empty.png';
		}
	}
	function note5_in()
	{
		if (already_click == 0)
		{
			 note_image1.src='./includes/star_full.png';
			 note_image2.src='./includes/star_full.png';
			 note_image3.src='./includes/star_full.png';
			 note_image4.src='./includes/star_full.png';
			 note_image5.src='./includes/star_full.png';
		}
	}
	function note5_out()
	{
		if (already_click == 0)
		{
			 note_image1.src='./includes/star_empty.png';
			 note_image2.src='./includes/star_empty.png';
			 note_image3.src='./includes/star_empty.png';
			 note_image4.src='./includes/star_empty.png';
			 note_image5.src='./includes/star_empty.png';
		}
	}
//-->
</script>
</head>
<body>
	<?php include('./includes/nav.php'); ?>
	<section id="main_content">
	<section id="main_explain_avis">
	Sur cette page, vous pouvez laisser un avis/commentaire sur la prestation qui vous a était fournis, tout commentaire devra attendre d'être approuvé avant de pouvoir être affiché à l'acceuil.<br>
	</section>
	
	
	
	<section id="main_avis">
		<form id="avis" action="post_avis.php" method="post">
		<input type="text" placeholder="Nom d'entreprise" name="avis_entreprise" size="90"><br>
		<input type="email" placeholder="Adresse mail, ne sera pas afficher" name="avis_email" size="90"><br>	
		<textarea maxlength="150" placeholder="Un court avis... (150 charactères max)" rows="4" cols="100" name="avis_commentaire"></textarea><br>
				<input type="radio" name="avis_note" id="note_radio1" value="1">
				 <label for="note_radio1" title="Note : 1/5"><img src="./includes/star_empty.png" alt="1/5" onmouseover="note1_in();" onmouseout="note1_out();" onclick="clicked(1);" id="note_image1"></label>
				<input type="radio" name="avis_note" id="note_radio2" value="2">
				 <label for="note_radio2" title="Note : 2/5"><img src="./includes/star_empty.png" alt="2/5" onmouseover="note2_in();" onmouseout="note2_out();" onclick="clicked(2);" id="note_image2"></label>
				<input type="radio" name="avis_note" id="note_radio3" value="3">
				 <label for="note_radio3" title="Note : 3/5"><img src="./includes/star_empty.png" alt="3/5" onmouseover="note3_in();" onmouseout="note3_out();" onclick="clicked(3);" id="note_image3"></label>
				<input type="radio" name="avis_note" id="note_radio4" value="4">
				 <label for="note_radio4" title="Note : 4/5"><img src="./includes/star_empty.png" alt="4/5" onmouseover="note4_in();" onmouseout="note4_out();" onclick="clicked(4);" id="note_image4"></label>
				<input type="radio" name="avis_note" id="note_radio5" value="5">
				 <label for="note_radio5" title="Note : 5/5"><img src="./includes/star_empty.png" alt="5/5" onmouseover="note5_in();" onmouseout="note5_out();" onclick="clicked(5);" id="note_image5"></label>
				<input type="button" name="Reset_avis" id="reset_js" value="Reset" onclick="clicked(10);"><br>
				<div class="g-recaptcha" data-sitekey="6LcwnV8UAAAAALn3Qj_RiXGtnRzgAN6RC3wIF9wh"></div>

	
		<input type="submit" value="Valider">
		
		</form>
		<!- Ici on saisie les avis dans des formulaire en html, reception en post et traitement(traité adresse mail valide(si assez chaud faire systeme de mail pour dire quand commentaire validé ) commentaire saisie), demandé adresse mail, nom prenom, tout sa pas afficher public, avis et mettre captcha pour eviter spam -> 
	</section>
	</section>
	
	<?php include('./includes/foot.php'); ?>
</body>
</html>