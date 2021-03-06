<?php 

$pagename = "Myspace";
define('PAGE_NAME', $pagename);

include('templates/short_links.php');
include('database.php');
include('src/classes/CLASS_login.php');

if (Login::isLoggedIn()) {
	$user = database::query('SELECT username, email, first_name, last_name, account_type FROM utilisateurs WHERE id=:id', array(':id'=>Login::isLoggedIn()))[0];
} else {
	header('Location: ' . LOGIN_PAGE);
	die(); 
}

if ($user["account_type"]=='2'){
	header('Location:' . ADMINISTRATION_PAGE);
	die();
}

if ($user["account_type"] == '0') {
	header('Location: ' . INDEX_PAGE);
	die();
}

?><!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php
	//Import de header.php qui contient tous les codes de liens CSS, et le titre de la page défini par la variable PAGE_NAME
	include('templates/header.php');
	?>
	<!-- CSS custom pour la page login (non utilisé par les autres pages -->

	<link rel="stylesheet" type="text/css" href="src/css/myspace/myspace.css">

</head>



<?php 
if (isset($_GET["from"])) {
	if ($_GET["from"] == "creer_annonce") {
		echo "<script type=\"text/javascript\">showNotification('success', 'Votre annonce a été créée', 'Les informations ont été prises en compte');</script>";
	} else if ($_GET["from"] == "modif_annonce") {
		echo "<script type=\"text/javascript\">showNotification('info', 'Votre annonce a été modifiée', 'Les modifications ont été prises en compte');</script>";
	} else if ($_GET["from"] == "suppr_annonce") {
		echo "<script type=\"text/javascript\">showNotification('error', 'Votre annonce a été supprimée', '');</script>";
	}
}
?>


<body>
	<div class="header" style="background: url('imgs/login_3.jpg') no-repeat center left fixed; background-color: #EEEEEE;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;">
	</div>

	
</body>

	<div class="home_header">
		


		<!-- Class Container de SKELETON CSS et searchContainer de src/css/home/home.css -->
		<div class="container searchContainer">
			
			<!-- Slogan pour le site -->
			<div class="header_slogan">
				<a href="<?php echo INDEX_PAGE ?>"><img src="imgs/logo1.png" class="img_logo"></a>
			</div>

			<?php include('templates/top.php'); ?>

		</div>

		<!-- Div pour afficher les infos en haut à droite (voir home.css pour le modifier) -->
			<div class="header_information_utilisateur">
				<div class="row">
					<?php echo $user['username'] ?> (<?php echo $user['email'] ?>)
				</div>
				
				<a href="<?php echo LOGOUT_PAGE ?>" style="color: white; text-decoration: none" title="Déconnexion" >Déconnexion &emsp;<i class="fas fa-sign-out-alt"></i></a>
			</div>

	</div>

<div class="container">
	
	<div class="main_container">
			<!-- Contenus de la page -->

			<!-- Utilisé pour créer la barre de navigation -->
			<?php 
			include('templates/menu.php'); 
			?>
	</div>



			
 <?php 
 	if($user["account_type"]=='1'){

?>
	<input type="button" class="u-full-width" value="Créer annonce" onclick="location.href='<?php echo CREERANNONCE_PAGE ?>'">
				
	

	<div class="myspacerecherche_container">
		<div class="myspace_inner_container">
				<strong> Mes annonces </strong>
		</div>		
	</div>

	<?php

	$annonces = database::query("SELECT * FROM annonces WHERE user_id=:text_remplacer", array(':text_remplacer'=>Login::isLoggedIn()));
	


	foreach ($annonces as $annonce) {
	
	?>
		<div class="myspace_container" >
					<div class="myspace_inner_container">
						<div class="annonce_titre">
							<a href="<?php echo ANNONCE_PAGE . "?id=" . $annonce["id"] ?>"> <!-- LIEN OFFRE référencé par l'id de l'annonce -->
							<?php echo $annonce["titre"] ?>
							</a>
							
						</div>
						<div class="row">
							<div class="annonce_entreprise three columns">Créée par vous
							</div>
							<div class="annonce_location three columns"><span>&#128204 </span><?php echo $annonce["ville"] ?></div>
							<div class="annonce_duree three columns"><?php echo $annonce["duree"] ?> mois</div>
							<div class="annonce_duree three columns"><?php

									$categ = database::query("SELECT Nom FROM categorie_annonce, annonces WHERE categorie_annonce.id = annonces.numCategorie AND annonces.id={$annonce["id"]}")[0]["Nom"];
									echo $categ;
							?></div>
						</div>
						
						<div class="annonce_description">
							<!-- La requete SQL doit nous donner que les 100 premiers caractères de la description, et nous n'afficerons que les 100 premiers caractères -->
							<span style="white-space: pre-line;"><?php
								echo $annonce["description"];
							?></span>
							
						</div>

						<br>

						<div class= "row action"> 
							<span class="action-vue two columns"><?php echo $annonce["nbVue"] . " vues"?></span>
							<input type="button" class="button-primary five columns" value="Modifier" onclick="location.href='<?php echo MODIFANNONCE_PAGE . "?id=" . $annonce["id"] ?>'">

							<input type="button" class="five columns" value="Supprimer" onclick="location.href='<?php echo DELETEANNONCE_PAGE . "?id=" . $annonce["id"] ?>'">
						</div>
					</div>
		</div>
	<?php
	}

	} 
	?>

</div>

<br>
<br>
<br>
<?php 
include("templates/footer.php");
?>