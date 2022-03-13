<?php
session_start();
 require ('bdd.php');
   require ('config.php');



    const LAYOUT = "formRechercheDate";
 $bdh = dbconnexion();
  
  	$bdh = new PDO('mysql:host=db.3wa.io;dbname=julienhennebo_c1dev;charset=utf8','julienhennebo','2d1043b81bdfee62f9bb561d70d43c8a');


// VERIFICATION DE LA CONNEXION non obligatoire

  if($bdh) { var_dump("Connexion à la base de données réalisée avec succès !");
   }else{          die("Echec de connexion à la base"); }
      
// Ma requete SQL récupérée depuis phpmyadmin   
//qui fonctionne "TU PEUX Décommenter"

 //$sql = "SELECT *FROM webseance WHERE date_debut BETWEEN '2022-01-01' AND '2022-04-20'";

    // Le lancement en php de ma  requête        
   // $sth = $bdh->query($sql);        
    
// J'exécute ma 2eme requête    
  //  $sth->execute();
    

//fetchall recupere ttes les informations
  //  $resultdate= $sth->fetchAll(); 
  
 
   

	 // Vérifier si le formulaire est soumis 
	 if ( isset( $_GET['submit'] ) ) {
		 /* récupérer les données du formulaire en utilisant 
		    la valeur des attributs name comme clé 
	     */
	 $date_debut = $_GET['date_debut']; 
		 $date_fin = $_GET['date_fin']; 
		 
		 // afficher le résultat
 echo '<h3>Informations récupérées en utilisant GET</h3>'; 
	 echo 'date_debut : ' . $date_debut . ' date_fin : ' . $date_fin; 
	//	 exit;
	}

// code ne fonctionne pas;  
        $sth = $bdh->prepare('SELECT * FROM webseance WHERE date_debut = ? BETWEEN date_fin = ?');
        $sth->bindValue(1, $date_debut, PDO::PARAM_INT);
        $sth->bindValue(2, $date_fin, PDO::PARAM_STR);
        $sth->execute();  
     
    //   fetchall recupere ttes les informations
        $resultdate= $sth->fetchAll();  
            
  



 /* $sth = $bdh->prepare("SELECT * FROM webseance WHERE date_debut BETWEEN date_debut=? AND date_end=?

           VALUES (?, ?)");       
            
  /*/          
 
           
             
                                     

 var_dump($resultdate);
    
  include( LAYOUT . '.phtml');
?>