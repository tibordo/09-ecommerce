<?php
require_once('inc/init.inc.php');

echo '<pre>'; print_r($_SESSION); echo '</pre>';


// Si l'indice 'user' est défini dans la session (!connect()), cela veut dire que l'internaute n'est pas authentifié sur le site, il n'a rien à faire sur la page profil, on le redirige (header()) vers la page connexion.php
if(!connect())
{
    header('location: connexion.php');
}

require_once('inc/inc_front/header.inc.php');
require_once('inc/inc_front/nav.inc.php');
?>

<h1 class="text-center my-5">Vos informations personnelles</h1>

<!-- Exo : afficher l'ensemble des données de l'utilisateur sur la page Web en passant par le fichier session de l'utilisateur ($_SESSION). Ne pas afficher l'id-membre sur la page Web 




                // Si l'internaute entre dans le IF ici, cela veut dire qu'il a correctement remplit le formulaire de connexion

                // La boucle FOREACH permet de parcourir les données de l'utilisateur afin de les stocker dans son fichier de session
                // On crée un tableau multidimensionnel dans la session, ici on crée un indice 'user' dans la session qui a pour valeur un tableau ARRAY contenant toutes les données de l'internaute authentifié sur le site
                // echo 'mot de passe OK!';
                -->
        <div class="card">
            <div class="card-body">
            <?php
            foreach($_SESSION['user'] as $key => $value): //  remplace {

                if($key != 'id_membre' && $key != 'statut'):
        
            ?>    
                <p class="d-flex justify-content-between">
                    <!-- ucfirst() : fonction prédéfinie permettant de mettre la première lettre de la chaîne de caractères en majuscule -->
                    <strong><?php echo ucfirst($key); ?></strong>
                    <span><?= $value; ?></span>

                </p>
            <?php
                endif;

            endforeach; // remplace }
            ?>        
            </div>      
        </div>
          
<?php
require_once('inc/inc_front/footer.inc.php');