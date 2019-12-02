<?php 
//http://adnan-tech.com/tutorial/load-more-data-ajax-php !!!!!!!!!!!!!!!!!!!!!!!!!!!!

$pagename = "Administration";
define('PAGE_NAME', $pagename);

include('templates/short_links.php');
include('database.php');
include('src/classes/CLASS_login.php');

if (Login::isLoggedIn()) {
	$user = database::query('SELECT username, email, first_name, last_name, account_type FROM utilisateurs WHERE id=:id', array(':id'=>Login::isLoggedIn()))[0];
	
	if ($user["account_type"] != '2') {
		header('Location: ' . INDEX_PAGE);
		exit();
	}
} else {
	header('Location: ' . LOGIN_PAGE);
	die(); 
}


// Code executé une fois que le compte connecté est bien administrateur




?>

<!DOCTYPE html>
<html>
<head>
	<?php
	//Import de header.php qui contient tous les codes de liens CSS, et le titre de la page défini par la variable PAGE_NAME
	include('templates/header.php');
	?>
	<style type="text/css">

		.tabs {
			display: flex;
			flex-wrap: wrap;
		}
		.tabs label {
			order: 1; /* puts the labels first */
			display: block;
			cursor: pointer;
			background: #006BA8;
			width: auto;
			padding: 5px;
			margin-right: 10px;
			color: white;
			padding-left: 10px;
			padding-right: 10px;
		}
		.tabs .tab {
			order: 2; /* puts the tabs last */
			flex-grow: 1;
			width: 100%;
			display: none;
			/*background: #fff;*/
			margin-top: -5px;

		}
		.tabs input[type="radio"] {
			display: none;
		}
		.tabs input[type="radio"]:checked + label {
			background: #fff;
			color: #006BA8;
		}
		.tabs input[type="radio"]:checked + label + .tab {
			display: block;
		}

		

		body {
			box-sizing: border-box;
			background-color: #EFEFEF;
		}


		table {
		  /*border: 1px solid #ccc;*/
		  border-collapse: collapse;
		  margin: 0;
		  padding: 0;
		  width: 100%;
		  table-layout: fixed;
		}

		table tr {
		  /*border: 1px solid #ddd;*/
		}

		table th,
		table td {
		  padding: .625em;
		  text-align: center;
		  background-color: #fff;
		}

		

		table th {
		  font-size: .85em;
		  letter-spacing: .1em;
		  text-transform: uppercase;
		}

		.load-more {
			border: 1px solid lightgrey;
			border-radius: 20px;
			padding: 5px;
			cursor: pointer;
			text-align: center;
			margin-top: 10px;
			margin-bottom: 10px;
			background-color: white;
			color: #006BA8;
			margin-bottom: 10px;
		}

		.welcome {
			text-align: center;
			text-transform: uppercase;
			font-family: "Open Sans", sans-serif;
			font-size: 1.5em;
			margin: .5em 0 .75em;
			padding-top: 20px;
		}

		.bg-white {
			background-color: white;
		}

		.lien-accueil {
			text-align: center;
			padding-bottom: 20px;
		}

		.sql-output-container {
			border : 1px solid lightgrey;
			border-radius: 5px;
			width: 100%;
			margin-top: 20px;
			background-color: #fff;
			margin-bottom: 30px;
		}

		.sql-output-title {
			padding-top: 5px;
			font-size: 2em;
			text-transform: uppercase;
			text-align: center;
		}

		.sql-output {
			
			padding-left: 30px;
			padding-right: 20px;
			padding-top: 10px;
			margin-bottom: 30px;
			
			
		}

		.warning-text {
			color: red;
			font-weight: bold;
		}

		.inner-sql {
			padding-left: 15px;
			padding-right: 15px;
			padding-top: 15px;
		}

		.tab-ext {
			border-bottom-left-radius: 5px;
			border-bottom-right-radius: 5px;
		}

		ol {
			list-style: none;
		}
		li::before {
			content: "•";
			color: #006BA8;
			/* fixer la taille de la puce, et la déplacer vers la gauche */
			display: inline-block;
			width: 1em;
			margin-left: -1em;
		}



		hr {
			margin-top: 0;
			margin-bottom: 1rem;
		}


		.search_container_admin {
			padding-left: 30px;
			padding-right: 30px;
			padding-top: 15px;
		}

		.up-container {
			margin-top: 0;
			border-top: 0;
		}

		#progress {
			padding-right: 10px;
		}
		.progress-bar {
			-webkit-appearance: none;
			width: 100%;
		}
		::-webkit-progress-bar {
		   background-color:  #EFEFEF;
		}
		::-webkit-progress-value {
			background-color: #006BA8;
		}
		::-moz-progress-bar {
			background-color: #006BA8;
		}

		.fa-stack { font-size: 0.5em; margin-top: 0.5em;}

		.fa-stack i {  vertical-align: middle; }


		@media screen and (max-width: 600px) {
			.tabs .tab {
				background: #EFEFEF;

			}

			.tabs {
				flex-direction: column;

			}


			.tabs input[type="radio"]:checked + label {
				border: 2px solid red;
			}

			.tabs label {
				margin-right: 0;
			}

			

		  table {
		    border: 0;
		  }
		  
		  table thead {
		    border: none;
		    height: 1px;
		    margin: -1px;
		    overflow: hidden;
		    padding: 0;
		    position: absolute;
		    width: 1px;
		  }
		  
		  table tr {
		    border-bottom: 3px solid #ddd;
		    display: block;
		    margin-bottom: .625em;
		  }
		  
		  table td {
		    border-bottom: 1px solid #ddd;
		    display: block;
		    font-size: .8em;
		    text-align: right;
		    padding-right: .625em;
		    background-color: #fff;
		  }

		  table td:first-child {
			padding-left: .625em;
			}

			table td:last-child {
				padding-right: .625em;
			}
		  
		  table td::before {
		    /*
		    * aria-label has no advantage, it won't be read inside a table
		    content: attr(aria-label);
		    */
		    content: attr(data-label);
		    float: left;
		    font-weight: bold;
		    text-transform: uppercase;
		  }
		  
		}

	</style>
</head>
<body>

	<?php
	function Uptime() {
        $str   = @file_get_contents('/proc/uptime');
        $num   = floatval($str);
        $secs  = $num % 60;
        $num   = (int)($num / 60);
        $mins  = $num % 60;
        $num   = (int)($num / 60);
        $hours = $num % 24;
        $num   = (int)($num / 24);
        $days  = $num;

        return array(
            "days"  => $days,
            "hours" => $hours,
            "mins"  => $mins,
            "secs"  => $secs
        );
    }

	function shapeSpace_memory_usage($noUnit = false) {
	
		$mem_total = memory_get_usage(true);
		$mem_used  = memory_get_usage(false);
		
		$memory = array($mem_used, $mem_total);
		
		if ($noUnit == false) {

			foreach ($memory as $key => $value) {
				
				if ($value < 1024) {
					
					$memory[$key] = $value .' B'; 
					
				} elseif ($value < 1048576) {
					
					$memory[$key] = round($value / 1024, 2) .' KB';
					
				} else {
					
					$memory[$key] = round($value / 1048576, 2) .' MB';
					
				}
				
			}

		}
		
		return $memory;
		
	}

	//echo Uptime()["days"] . "d " . Uptime()["hours"] . "h " . Uptime()["mins"] . "m " . Uptime()["secs"] . "s ";
	?>

	<div class="container">

		<div class="welcome">
			Bienvenue <?php echo $user["first_name"] ?>
			<br>
			<i class="fas fa-cogs fa-3x"></i>
		</div>
		<div class="lien-accueil">
			<a href="<?php echo INDEX_PAGE ?>">< Retour sur le site</a>
		</div>



		<div class="tabs">
			<input type="radio" name="tabs" id="tab_one" checked="checked">
			<label for="tab_one"><i class="fas fa-user"></i> Utilisateurs</label>
			<div class="tab">

				<div class="search_container_admin bg-white">
					<div class="row">
						<input type="text" name="utilisateur" id="utilisateur" class="eight columns" placeholder="ID, utilisateur, Prénom, Nom...." required autofocus>
						<button type="submit" class="four columns" onclick="search()" id="user-btn-search">Recherche</button>
					</div>
				</div>

				<hr>

				<table class="u-full-width">
				  <thead>
				    <tr>
				      <th>Pseudo</th>
				      <th>Prénom</th>
				      <th>Nom</th>
				      <th>Email</th>
				      <th>Type</th>
				    </tr>
				  </thead>
				  <tbody id="users-data"></tbody>
				</table>

				<input type="button" value="Load More" class="load-more u-full-width" id="load-more" onclick="getUserData()" style="border-radius: 5px;">
				

			</div>

			<input type="radio" name="tabs" id="tab_two">
			<label for="tab_two"><i class="far fa-list-alt"></i> Annonces</label>
			<div class="tab">

				<div class="search_container_admin bg-white">
					<div class="row">
						<input type="text" name="annonce" id="annonce" class="eight columns" placeholder="ID, Titre, Auteur...." required autofocus>
						<button type="submit" class="four columns" onclick="searchAnnonce()" id="annonce-btn-search">Recherche</button>
					</div>
				</div>

				<hr>

				<table class="u-full-width">
				  <thead>
				    <tr>
				      <th>Titre</th>
				      <th>Auteur</th>
				      <th>Catégorie</th>
				      <th>Email Auteur</th>
				      <th>Actions</th>
				    </tr>
				  </thead>
				  <tbody id="annonces-data"></tbody>
				</table>

				<input type="button" value="Charger Plus" class="load-more u-full-width" id="load-more-annonce" onclick="getAnnonceData()" style="border-radius: 5px;">
				


				lbabaqzd qzd qzd qzdqzdqz dqzd lbdlqkzdblqdkzbqzld qlzdkb qlzdk bq
				qzdlqzdihqz d

				<span class="fa-stack" style="vertical-align: top;">
				  <i class="fas fa-circle fa-stack-2x"></i>
				  <i class="fas fa-check fa-stack-1x fa-inverse"></i>
				</span>
			</div>

			<input type="radio" name="tabs" id="tab_three">
			<label for="tab_three"><i class="fas fa-terminal"></i> SQL</label>
			<div class="tab">
						<div class="sql-desc bg-white inner-sql warning-text">
							<i class="fas fa-exclamation-circle"></i>&emsp;Attention, la commande SQL que vous envoyez peut affecter la majorité des données
						</div>

						<div class="bg-white tab-ext">
							<div class="inner-sql">
								<input type="text" name="sql-cmd" class="u-full-width" placeholder="Commande SQL (SELECT / INSERT / UPDATE / DELETE)..." id="sql-cmd">
								<input type="button" name="sql-confirm" value="Envoyer Commande" class="u-full-width" onclick="getSqlOutput()" id="sql-confirm">
							</div>
						</div>
					<div class="sql-output-container">
						<div class="sql-output-title">Sortie SQL</div>
						<hr>
						<div class="sql-output" id="sql-output"><center>Pas de résultats</center></div>
					</div>
				
			</div>

			<input type="radio" name="tabs" id="tab_four">
			<label for="tab_four"><i class="fas fa-server"></i> Serveur</label>
			<div class="tab">
				<div class="sql-output-container up-container">
					<div class="sql-output-title">Serveur</div>
					<hr>
					<div class="sql-output">
						<ol>
							<li>Serveur en ligne depuis : <?php echo Uptime()["days"] . " jours " . Uptime()["hours"] . " heures " . Uptime()["mins"] . " minutes " . Uptime()["secs"] . " secondes"; ?></li>
							<li>Mémoire allouée à PHP : <?php echo shapeSpace_memory_usage()[0] . ' / ' . shapeSpace_memory_usage()[1] ?>
								<div id="progress">
								    <progress class="progress-bar" value="<?php echo round(shapeSpace_memory_usage(true)[0] / shapeSpace_memory_usage(true)[1], 2) * 100; ?>" min="0" max="100"><?php echo round(shapeSpace_memory_usage(true)[0] / shapeSpace_memory_usage(true)[1], 2) * 100; ?>%</progress>
								</div>
							</li>
							<li><?php echo "Hôte : " . php_uname("s") . " ". php_uname("n"); ?></li>
							<li><?php echo "Version PHP : " . phpversion(); ?></li>
						</ol>
					
					</div>
				</div>

				<div class="sql-output-container">
					<div class="sql-output-title">Site web</div>
					<hr>
					<div class="sql-output">
						<ol>
							<li>Nombre d'inscrits (global) : <?php echo database::query("SELECT COUNT(*) AS Cpt FROM utilisateurs")[0]["Cpt"] ?></li>
							<ol>
								<li>Nombre d'utilisateurs : <?php echo database::query("SELECT COUNT(*) AS Cpt FROM utilisateurs WHERE account_type=0")[0]["Cpt"] ?></li>
								<li>Nombre de gestionnaires : <?php echo database::query("SELECT COUNT(*) AS Cpt FROM utilisateurs WHERE account_type=1")[0]["Cpt"] ?></li>
								<li>Nombre d'administrateurs : <?php echo database::query("SELECT COUNT(*) AS Cpt FROM utilisateurs WHERE account_type=2")[0]["Cpt"] ?></li>
							</ol>
							<li>Nombre d'annonces : <?php echo database::query("SELECT COUNT(*) AS Cpt FROM annonces")[0]["Cpt"] ?></li>
						</ol>
					
					</div>
				</div>
			</div>


		</div>


	</div>


	
</body>
<script>
	
	// Starting position to get new records
    var start = 0;
    var start_annonce = 0;

    var like = "";
    var annonce_like = "";

    // This function will be called every time a button pressed 
    function getUserData() {
        // Creating a built-in AJAX object
        var ajax = new XMLHttpRequest();

        // Sending starting position
        ajax.open("GET", "src/ajax/request.php?start=" + start + "&limit=5&like=" + like, true);

        // Actually sending the request
        ajax.send();

        document.getElementById("load-more").disabled = true;
        document.getElementById("load-more").style.backgroundColor = "lightgrey";

        // Detecting request state change
        ajax.onreadystatechange = function () {
		    if (this.readyState == 4 && this.status == 200) {

		    	document.getElementById("load-more").disabled = false;
        		document.getElementById("load-more").style.backgroundColor = "#006BA8";
        		document.getElementById("load-more").style.color = "#fff";
        		
		        
		        // Converting JSON string to Javasript array
		        var data = JSON.parse(this.responseText);
		        var html = "";
		        var type = "";


		        // Appending all returned data in a variable called html
		        for (var a = 0; a < data.length; a++) {
		            html += "<tr>";
		                html += "<td data-label='Username' style=\"overflow: hidden; word-break: break-word\">" + data[a].username + " (" + data[a].id + ") </td>";
		                html += "<td data-label='Prenom' style=\"overflow: hidden; word-break: break-word\">" + data[a].first_name + "</td>";
		                html += "<td data-label='Nom' style=\"overflow: hidden; word-break: break-word\">" + data[a].last_name + "</td>";
		                html += "<td data-label='Email' style=\"overflow: hidden; word-break: break-word\">" + data[a].email + "</td>";
		                
		                switch (data[a].account_type) {

		                	case '0':
		                		type = "Utilisateur";
		                		break;
		                	case '1':
		                		type = "Gestionnaire";
		                		break;
		                	case '2':
		                		type = "Administrateur";
		                		break;
		                	default:
		                		type = "ND";
		                }
		                html += "<td data-label='Type' style=\"overflow: hidden; word-break: break-word\">" + type + "</td>";
		            html += "</tr>";
		        }

		        // Appending the data below old data in <tbody> tag
		        document.getElementById("users-data").innerHTML += html;

		        // Incrementing the offset so you can get next records when that button is clicked
		        start = start + 5;
		    }
		};
    }

    function search() {
    	var temp = document.getElementById("utilisateur").value;
    	
		like = temp;
		start = 0;
		document.getElementById("users-data").innerHTML="";
		getUserData();
    	
    }


    function getAnnonceData() {
        // Creating a built-in AJAX object
        var ajax = new XMLHttpRequest();

        // Sending starting position
        ajax.open("GET", "src/ajax/request.php?start=" + start_annonce + "&limit=5&annonce=" + annonce_like, true);

        // Actually sending the request
        ajax.send();

        document.getElementById("load-more-annonce").disabled = true;
        document.getElementById("load-more-annonce").style.backgroundColor = "lightgrey";

        // Detecting request state change
        ajax.onreadystatechange = function () {
		    if (this.readyState == 4 && this.status == 200) {

		    	document.getElementById("load-more-annonce").disabled = false;
        		document.getElementById("load-more-annonce").style.backgroundColor = "#006BA8";
        		document.getElementById("load-more-annonce").style.color = "#fff";
        		
		        
		        // Converting JSON string to Javasript array
		        console.log(data);
		        var data = JSON.parse(this.responseText);
		        var html = "";
		        var type = "";


		        // Appending all returned data in a variable called html
		        for (var a = 0; a < data.length; a++) {
		            html += "<tr>";
		                html += "<td data-label='Titre' style=\"overflow: hidden; word-break: break-word\"><a href=\"<?php echo ANNONCE_PAGE . "?id="?>" + data[a].id +"\">" + data[a].titre + "</a> (" + data[a].id + ") </td>";

		               	html += "<td data-label='Auteur' style=\"overflow: hidden; word-break: break-word\">" + data[a].username + "</td>";
		               	html += "<td data-label='Catégorie' style=\"overflow: hidden; word-break: break-word\">" + data[a].categorieName + "</td>";
		               	html += "<td data-label='Email Auteur' style=\"overflow: hidden; word-break: break-word\">" + data[a].contactMail + "</td>";
		                 
		               /* html += "<td data-label='Prenom' style=\"overflow: hidden; word-break: break-word\">" + data[a].first_name + "</td>";
		                html += "<td data-label='Nom' style=\"overflow: hidden; word-break: break-word\">" + data[a].last_name + "</td>";
		                html += "<td data-label='Email' style=\"overflow: hidden; word-break: break-word\">" + data[a].email + "</td>";*/
		                
		                /*switch (data[a].account_type) {

		                	case '0':
		                		type = "Utilisateur";
		                		break;
		                	case '1':
		                		type = "Gestionnaire";
		                		break;
		                	case '2':
		                		type = "Administrateur";
		                		break;
		                	default:
		                		type = "ND";
		                }
		                html += "<td data-label='Type' style=\"overflow: hidden; word-break: break-word\">" + type + "</td>";*/
		            html += "</tr>";
		        }

		        // Appending the data below old data in <tbody> tag
		        document.getElementById("annonces-data").innerHTML += html;

		        // Incrementing the offset so you can get next records when that button is clicked
		        start_annonce = start_annonce + 5;
		    }
		};
    }

    function searchAnnonce() {
    	var temp = document.getElementById("annonce").value;
    	console.log(temp);
    	
		annonce_like = temp;
		start_annonce = 0;
		document.getElementById("annonces-data").innerHTML="";
		getAnnonceData();
    	
    }

 	function getSqlOutput() {

        // Creating a built-in AJAX object
        var ajax = new XMLHttpRequest();

        cmd = document.getElementById("sql-cmd").value;

 		if (document.getElementById("sql-output").innerHTML != "") {
 			document.getElementById("sql-output").innerHTML = "";
 		}

        // Sending starting position
        ajax.open("GET", "src/ajax/sqlOutput.php?cmd=" + cmd, true);

        // Actually sending the request
        ajax.send();

        document.getElementById("sql-confirm").disabled = true;
        document.getElementById("sql-confirm").style.backgroundColor = "lightgrey";

        // Detecting request state change
        ajax.onreadystatechange = function () {
		    if (this.readyState == 4 && this.status == 200) {

		    	document.getElementById("sql-confirm").disabled = false;
		    	document.getElementById("sql-confirm").style.backgroundColor = "";
		        
		        // Converting JSON string to Javasript array
		        //console.log(this.responseText);
		        try {
		        	var data = JSON.parse(this.responseText);

			        var html = "";

			        html += "<ol>";
			        
			        for (var a = 0; a < data.length; a++) {
			        	html += "<li>";
			        	for ( var b = 0; b < Object.keys(data[a]).length /2 ; b++) {
			        	//for (var b in data[a]) {
			        		html += JSON.stringify(data[a][b]) + " ";
			        	}
			        	html += "</li>";
			        }

			        html += "</ol>";

			        if (html == "<ol></ol>") {
			        	html = "<center>Pas de résultats</center>";
			        }

		        } catch(error) {
		        	if (error.name === "SyntaxError") {
		        		html = "<center><i class=\"fas fa-bug\"></i>&emsp;Code 1: Erreur de syntaxe</center>";
		        	} else if (error.name === "TypeError") {
		        		html = "<center><i class=\"fas fa-info-circle\"></i>&emsp;Code 2: La commande n'a rien retourné</center>";
		        	} else {
		        		html = "<center><i class=\"fas fa-bug\"></i>&emsp;Code 3: " + error.name + " (" + error.message + ")</center>";
		        	}
		        	//html = "<center>Erreur<br><i class=\"fas fa-bug\"></i>&emsp;" + error.name + "</center>";
		        }
		        

		        

		        document.getElementById("sql-output").innerHTML = html;
		        
		    }
		};
    }


    document.getElementById("utilisateur")
	    .addEventListener("keyup", function(event) {
	    event.preventDefault();
	    if (event.keyCode === 13) {
	        document.getElementById("user-btn-search").click();
	    }
	});

	document.getElementById("annonce")
	    .addEventListener("keyup", function(event) {
	    event.preventDefault();
	    if (event.keyCode === 13) {
	        document.getElementById("annonce-btn-search").click();
	    }
	});

    // Calling the function on page load
    getUserData();
    getAnnonceData();

</script>
</html>
