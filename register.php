<?php 
include('database.php');

include('templates/short_links.php');
include('src/classes/CLASS_login.php');

$nompage = "Inscription au service en ligne";
define('PAGE_NAME', $nompage);

if (Login::isLoggedIn()) {
	
	header('Location: ' . INDEX_PAGE);
	die(); //On arrete tout et on n'execute pas le reste (évite les erreurs)
}

if(isset($_POST['creerCompte'])){


	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_verif =$_POST['password_verif'];
	$email = $_POST['email'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];

	$account_type = $_POST['accountType'];

	$company_name = (isset($_POST['gest_entreprise']) ? $_POST['gest_entreprise'] : NULL);
	$phone = (isset($_POST ['gest_phone']) ? $_POST ['gest_phone'] : NULL);
	$contact_mail = (isset($_POST ['gest_mail']) ? $_POST ['gest_mail'] : NULL);
		
	$is_activated = ($account_type == 1 ? 0 : 1); //Compte activé par défaut si utilisateur normal, non activé si gestionnaire

	//Si le nom d'utilisateur n'est pas utilisé
	if (!database::query('SELECT username FROM utilisateurs WHERE username=:username', array(':username'=>$username))) {


		//si la taille du nom d'utilisateur est entre 3 et 32
		if (strlen($username) >= 3 && strlen($username) <= 32){
			//Si le nom d'utilisateur contient uniquement des lettres et des chiffres
			if (preg_match('/[a-zA-Z0-9_]+/', $username)){

				//Password verif c'est une autre input de mot de passe mais pour vérifier, genre tu dois mettre deux fois le mdp
				if ($password == $password_verif){

					//Si le mot de passe est entre 6 et 60 , pareil pour le password_verif
					if (strlen($password) >= 6 && strlen($password) <= 60 && strlen($password_verif) >= 6 && strlen($password_verif) <= 60 ) {
						//Si l'email est sous la forme d'une vraie email
						if (filter_var($email, FILTER_VALIDATE_EMAIL)){

							if((strlen($phone) == 10 && $account_type == 1) || ($account_type == 0)) {

								if((filter_var($contact_mail, FILTER_VALIDATE_EMAIL) && $account_type == 1) || ($account_type == 0)){

									if(!database::query('SELECT email FROM utilisateurs WHERE email=:email', array(':email'=>$email))) {
				
				
										database::query('INSERT INTO utilisateurs VALUES (:id, :username, :password, :email, :first_name, :last_name, :account_type , :company_name, :phone, :contact_mail, :is_activated)', array('id'=>NULL, ':username'=>$username,':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':first_name'=>$first_name, 'last_name'=>$last_name, 'account_type' =>$account_type, 'company_name'=>$company_name, 'phone'=>$phone, 'contact_mail'=>$contact_mail, ':is_activated'=>$is_activated));


										//Tout fonctionne
										$redirect_url_comp = ($account_type == 1 ? "register_gest" : "register_user");

			 							header('Location: ' . LOGIN_PAGE . "?from=" . $redirect_url_comp);
			 							exit();

			 						} else {
			 							$error = 'Email deja utilisée';
		 							}
		 						} else {
		 							$error = 'Email de contact invalide';
		 						}
		 					} else {
		 						$error = 'Numéro de telephone invalide';
		 					}

						}else{
							$error = 'Votre adresse mail n\'est pas conforme';
						}
					} else {
						$error = 'Votre mot de passe doit contenir entre 6 et 60 caractères.';
					}
				}else{
					$error = 'Votre mot de passe doit être le même dans les deux champs';
				}
			}else{
				$error = 'Votre nom d\'utilisateur ne peut pas contenir de caractères spéciaux';
			}
		}else{
			$error = 'Votre nom utilisateur doit contenir entre 3 et 32 caractères';
		}	
	}else{
		$error = 'Nom utilisateur déjà utilisé';
	}
}



?>




<html>
<body>

<head>
	<?php
	//Import de header.php qui contient tous les codes de liens CSS, et le titre de la page défini par la variable PAGE_NAME
	include('templates/header.php');
	?>

	<!-- CSS custom pour la page register (non utilisé par les autres pages -->
	<link rel="stylesheet" type="text/css" href="src/css/global/global_register_login.css">

	<link rel="stylesheet" type="text/css" href="src/css/register/register.css">

</head>

<div class="header" style="background: url('imgs/login.jpg') no-repeat center center fixed; background-color: #EEEEEE;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;">
</div>

<!-- Container centré et réduit par Skeleton.css et re-réduit par la classe CSS "login" -->
<div class="container register" style="border: 0px solid black">

	
	<!-- div blanche sur le site contenant la form -->
	<div class="super">

		<!-- Logo pour le site -->
		<div class="logo">
			<a href="<?php echo INDEX_PAGE ?>"><img src="imgs/logo1.png" class="img_logo"></a>
		</div>

	  	<form action="<?php echo REGISTER_PAGE ?>" method="post">
	  		
	  		<!-- Contenu dans la form pour mettre un padding left et right sur le contenu de la forme -->
	  		<div class="lower">

	  			<h3><strong><center>Inscription</center></strong></h3>

	  			<!-- Message à afficher quand erreur inscription -->
	  			<div class="erreur_login"><?php 
	  			if (!empty($error)) echo $error; ?></div>

	  			<!-- class = "row" créé par Skeleton CSS pour faire une "ligne" -->
	  			<div class="row">
	  				<label class="six columns" for="first_nameInput">Prénom</label>
	  				<label class="six columns" for="last_nameInput">Nom de famille</label>
	  			</div>
	  		<!-- <label class='offset-by-six columns' for="last_nameInput">Nom de famille</label> -->
	  			<div class="row">
					
					<input class="six columns" type="text" name="first_name" value="" placeholder="Prénom"  style="border-radius: 50px;" autofocus required maxlength="60">
					<!-- <label for="last_nameInput">Nom de famille</label> -->
		      		<input class="six columns" type="text" name="last_name" value="" placeholder="Nom de famille" style="border-radius: 50px;" required maxlength="60">
		  		</div>

		  		<div class="row">
					<label for="usernameInput">Nom d'utilisateur</label>
		      		<input class="u-full-width" type="text" name="username" value="" placeholder="Nom d'utilisateur" style="border-radius: 50px;" required maxlength="60">
		  		</div>

		  		<div class="row">
					<label for="emailInput">Adresse électronique</label>
		      		<input class="u-full-width" type="text" name="email" value="" placeholder="Adresse Mail de connexion" style="border-radius: 50px;" required maxlength="60">
		  		</div>

		  		<div class="row">
		  			<label class="six columns" for="passwordInput">Mot de passe</label>
		  			<label class="six columns" for="passwordInput">Confirmer mot de passe</label>
		  		</div>


		  		<div class="row">
					
		      		<input class="six columns" type="password" name="password" value="" placeholder="Mot de passe" style="border-radius: 50px;" required maxlength="60">
		 
		      		<input class="six columns" type="password" name="password_verif" value="" placeholder="Confirmer mot de passe" style="border-radius: 50px;" required maxlength="60">
		  		</div>

		  		<div class="row">
		  			<center>
			  			<input type="radio" id="acc_check" name="account_check" onclick="checkbox_test()" checked="checked">&emsp;Etudiant</input>
			  			&emsp;
						<input type="radio" id="acc_check2" name="account_check" onclick="checkbox_test()" >&emsp;Gestionnaire</input>
					</center>
		  		</div>

		  		<div id="gestionaire_form">
		  			
		  			<div class="row">
			  			<input class="six columns" id="gest_entreprise" type="text" name="gest_entreprise" value="" placeholder="Nom de l'entreprise" style="border-radius: 50px;" required maxlength="60">
			  			<input class="six columns" id="gest_phone" type="text" name="gest_phone" value="" placeholder="Numéro de téléphone" style="border-radius: 50px;" required maxlength="10">

			  		</div>
			  		<div class="row">
			  			<input class="u-full-width" type="text" name="gest_mail" value="" placeholder="Adresse Mail de contact" style="border-radius: 50px;" required maxlength="60">
			  		</div>
			  		<div class="row" id="text_warning_gest" style="color: green">
		  				<center>
			  			L'administrateur devra vérifier votre compte avant de l'activer
			  			</center>
			  		</div>
		  		</div>
		  		
		  		<input type="hidden" name="accountType" id="accType" value="0">



		  		<br>
		  		
		  		<!-- button-primary créé par Skeleton.css et change la couleur du bouton par la couleur primaire (à changer par la couleur de l'école) -->
		  		<!-- type="submit" pour confirmer la form -->
		  		<input class="u-full-width button-primary" type="submit" name="creerCompte" value="S'inscrire" style="border-radius: 50px;">

	  		
	  		</div>
		  
		</form>

		<p class="inscription"><a href="<?php echo LOGIN_PAGE ?>">< Un compte ? Connectez-vous</a></p>

	</div>

</div>

<?php 
	include("templates/footer.php");
?>



<script type="text/javascript">

	function checkbox_test() {
		var radio_gest = document.getElementById("acc_check2");
		var text_gest = document.getElementById("gestionaire_form").getElementsByTagName('input');
		if (radio_gest.checked == true) {
			for(i=0; i < text_gest.length; i++) {
				text_gest[i].style.display = "block";
				text_gest[i].disabled = false;
			}
			
			document.getElementById("accType").value = "1";

			document.getElementById("text_warning_gest").style.display = "block";
		} else {
			for(i=0; i < text_gest.length; i++) {
				text_gest[i].style.display = "none";
				text_gest[i].disabled = true;
			}	
			document.getElementById("accType").value = "0";
			document.getElementById("text_warning_gest").style.display = "none";
		}
	}
	
	checkbox_test();
</script>
	
	<!-- <h1> Authentification </h1>
		<form action="loginMoche.php" method="post">
			<input type="text" name="username" value="" placeholder="Utilisateur..."> </p>
			<input type="password" name="password" value="" placeholder="Mot de passe ..."></p>
			<input type="password_verif" name="password_verif" value="" placeholder="Verification mot de passe.."></p>
			<input type="email" name="email" value="" placeholder="blabla@blabla.bla"></p>
			<input type="first_name" name="first_name" value="" placeholder="Prénom..."> </p>
			<input type="last_name" name="last_name" value="" placeholder="Nom..."> </p>
			<input type="submit" name="creerCompte" value="Créer compte" >
		</form> -->
	
</body>
</html>