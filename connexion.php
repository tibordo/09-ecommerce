<?php
    require_once('inc/init.inc.php');

    // echo '<pre>'; print_r($_POST); echo '</pre>';

    // Si l'indice 'user' est défini dans la session (connect()), cela veut dire que l'internaute est authentifié sur le site, il n'a rien à faire sur la page connexion, on le redirige (header()) vers la page profil.php
    if(connect())
    {
        header('location: profil.php');
    }

    // SI l'indice 'action' est définie dans l'URL et qu'il a pour valeur 'deconnexion', cela veut dire que l'internaute a cliqué sur le lien 'deconnexion' et donc transmise dans l'URL les paramètres 'action=deconnexion', alors on entre dans la condition IF et on supprime l'indice 'user' dans la session afin qu'il en soit plus authentifié sur le site
    if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
    {
        // echo "Je veux me déconnecter<hr>";
        unset($_SESSION['user']);
    }

    if(isset($_POST['pseudo_email'], $_POST['password'], $_POST['submit']))
    {
        // On sélectionne tout dans la table SQL à condition que le pseudo ou l'email saisi dans le formulaire soit égal à un pseudo ou email stocké dans la BDD
        $verifUser = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo OR email = :email");
        $verifUser->bindValue(':pseudo', $_POST['pseudo_email'], PDO::PARAM_STR);
        $verifUser->bindValue(':email', $_POST['pseudo_email'], PDO::PARAM_STR);
        $verifUser->execute();

        // echo "nb Résulat : " . $verifUser->rowCount() . '<hr>';


        // Si rowCount() retourne un résultat de 1, cela veut dire que le pseudo ou l'email saisi dans le formulaire existe en BDD, la requete SELECT retourne 1 résultat
        if($verifUser->rowCount() > 0)
        {   
            // On execute fetch sur le resultat de la requete SELECT afin de récupérer les données en BDD sous forme de tableau ARRAY de l'internaute qui a saisi le bon pseudo/email dans le formulaire
            $user = $verifUser->fetch(PDO::FETCH_ASSOC);
            // echo '<pre>' ; print_r($user); echo '</pre>';

            // Contrôle du mot de passe
            // password_verify() : fonction prédéfinie permettant de comparer une clé de hachage (le mot de passe crypté en BDD) à une chaîne de caractères (le mot de passe saisi dans le formulaire)
            // arguments :
            // 1. Le mot de passe en clair, non haché, non crypté
            // 2. La clé hachage, le mot de passe crypté dans la BDD
            if(password_verify($_POST['password'], $user['password']))
            {
                // Si l'internaute entre dans le IF ici, cela veut dire qu'il a correctement remplit le formulaire de connexion

                // La boucle FOREACH permet de parcourir les données de l'utilisateur afin de les stocker dans son fichier de session
                // On crée un tableau multidimensionnel dans la session, ici on crée un indice 'user' dans la session qui a pour valeur un tableau ARRAY contenant toutes les données de l'internaute authentifié sur le site
                // echo 'mot de passe OK!';
                foreach ($user as $key => $value) {
                    if($key != 'password')
                    {
                        $_SESSION['user'][$key] = $value;
                    }
                    // $_SESSION['user']['nom'] = LACROIX
                }
                // echo '<pre>' ; print_r($_SESSION); echo '</pre>';

                // Après l'authentification de l'utilisateur, on le redirige vers sa page profil
                header('location: profil.php');

            }
            else
            {
                $error ="<p class='col-3 bg-danger text-white text-center mx-auto p-3 mt-3'>Identifiants Invalide.</p>";
            }
        }
        else// Sinon, le pseudo ou email saisi n'est pas connu en BDD, la requete SELECT ne retourne aucun résultat
        {
            $error ="<p class='col-3 bg-danger text-white text-center mx-auto p-3 mt-3'>Identifiants Invalide.</p>";
        }


    }

                /* LES BASES A CONNAITRE ET SAVOIR FAIRE
                    C : Create (INSERT)
                    R : Read (SELECT)
                    U : Update 
                    D : Delete 
                */


    require_once('inc/inc_front/header.inc.php');
    require_once('inc/inc_front/nav.inc.php');
?>

            <?php 
            if(isset($_SESSION['valid_inscription'])) echo $_SESSION['valid_inscription'];
            if(isset($error)) echo $error;            
            ?>

            <h1 class="text-center my-5">Identifiez-vous</h1>

            <form action="" method="post" class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4 mx-auto">
                <div class="mb-3">
                    <label for="pseudo_email" class="form-label">Nom d'utilisateur / Email</label>
                    <input type="text" class="form-control" id="pseudo_email" name="pseudo_email" placeholder="Saisir votre Email ou votre nom d'utilisateur">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Saisir votre mot de passe">
                </div>
                <div>
                    <p class="text-end mb-0"><a href="" class="alert-link text-dark">Pas encore de compte ? Cliquez ici</a></p>
                    <p class="text-end m-0 p-0"><a href="" class="alert-link text-dark">Mot de passe oublié ?</a></p>
                </div>
                <input type="submit" name="submit" value="Continuer" class="btn btn-dark mb-2">
            </form>

<?php
    // On supprime dans la session l'indice 'valid_ inscription' afin d'éviter que le message ne s'affiche tout le tempssur la page de connexion
    unset($_SESSION['valid_inscription']);

    require_once('inc/inc_front/footer.inc.php');
?>