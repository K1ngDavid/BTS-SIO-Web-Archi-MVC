<?php

namespace Quizz;

use Couchbase\User;
use mysql_xdevapi\Exception;
use PharIo\Manifest\ElementCollectionException;
use Quizz\Model\EtudiantModel;

class FormControl // Création d'une classe qui vérifie le form
{
    private string $login;
    private string $mdp;
    private string $nom;
    private string $prenom;
    private string $email;
    /**
     * @param $login
     * @param $mdp
     * @param $nom
     * @param $prenom
     * @param $email
     */
    public function __construct($login, $mdp, $nom, $prenom, $email) // Constructeur
    {
        $this->login = $login;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
    }

    public static function controlCheck($identifiants) // fonction statique qui prend en parametre les identifiants de connexion
    {
        $alreadyExist = new EtudiantModel();

        try { // On va faire des vérifications de champ avec le try catch
            foreach ($identifiants as &$item) {
                if ($item == null) throw new \Exception('Pas tous les champs ont été rempli', code: 0); // check si tous les identifiants ont été rempli
            }
            if (strlen($_POST['login']) <= 2) { // vérifie si la taille de la string 'login' est < 2
                throw new \Exception('Login trop court', code: 1);
            } elseif (strlen($_POST['login']) >= 15) { // vérifie si la taille de la string 'login' est > 15
                throw new \Exception('Login trop long', code: 2);
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {// vérifie si le champ 'email' est dans la bonne forme
                throw new \Exception("L'email n'est pas de la bonne forme", code: 3);
            }elseif($alreadyExist->isEmailValid($_POST['email']) != 0){ // vérifie si le champ 'email' n'existe pas déja pour un autre user
                throw new \Exception("Email déja existant", code: 4);
            }elseif($alreadyExist->isLoginValid($_POST['login']) != 0){// vérifie si le champ 'login' n'existe pas déja pour un autre user
                throw new \Exception("Login déja existant", code: 5);
            }
        } catch (\Exception $e) {
            return $e->getMessage(); // si une Exception est raise , on return le message !
        }
    }

    public static function updateControlCheck($identifiants,$login,$email) // fonction statique qui prend en parametre les identifiants de connexion
    {
        $alreadyExist = new EtudiantModel();

        try { // On va faire des vérifications de champ avec le try catch
            foreach ($identifiants as &$item) {
                if ($item == null) throw new \Exception('Pas tous les champs ont été rempli', code: 0); // check si tous les identifiants ont été rempli
            }
            if (strlen($_POST['login']) <= 2) { // vérifie si la taille de la string 'login' est < 2
                throw new \Exception('Login trop court', code: 1);
            } elseif (strlen($_POST['login']) >= 15) { // vérifie si la taille de la string 'login' est > 15
                throw new \Exception('Login trop long', code: 2);
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {// vérifie si le champ 'email' est dans la bonne forme
                throw new \Exception("L'email n'est pas de la bonne forme", code: 3);
            }
            elseif($login != null){
                if($alreadyExist->isLoginValid($login)){
                    throw new \Exception("Login déja existant");
                }
            }
            elseif($email != null){
                if($alreadyExist->isEmailValid($email)){
                    throw new \Exception("Email déja existant");
                }
            }

        } catch (\Exception $e) {
            return $e->getMessage(); // si une Exception est raise , on return le message !
        }
    }

    public static function securityCheck($password){ // fonction statique qui sécurise le mdp
        $uppercase = preg_match('@[A-Z]@', $password); // renvoie un booléen s'il existe une majuscule
        $lowercase = preg_match('@[a-z]@', $password);// renvoie un booléen s'il existe une minuscule
        $number    = preg_match('@[0-9]@', $password);// renvoie un booléen s'il existe un nombre
        $specialChars = preg_match('@[^\w]@', $password);// renvoie un booléen s'il existe un caractère spécial
        try{ // raise des exceptions en fonction de la condition
            if(strlen($password) <=4) throw new \Exception('Mot de passe trop court'); // si la taille du password < 4
            elseif(!$uppercase) throw new \Exception('Mot de passe doit contenir des majuscules'); // si il n'y a pas de majuscule
            elseif(!$lowercase) throw new \Exception('Mot de passe doit contenir des minuscules');// si il n'y a pas de minuscule
            elseif(!$number) throw new \Exception('Mot de passe doit contenir des nombres');// si il n'y a pas de nombre
            elseif(!$specialChars) throw new \Exception('Mot de passe doit contenir des characteres spéciaux');// si il n'y a pas de caractère spécial
        }catch (\Exception $e){
            return $e; // si il y a une exception , la renvoyer
        }


        //CHIFFREMENT DU MOT DE PASSE
        $encrypted_passsword = password_hash($password,PASSWORD_DEFAULT,['cost' => 10]); // cryptage du mot de passe avec un cout de 10
        return $encrypted_passsword; // retourne le mdp
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getMdp(): string
    {
        return $this->mdp;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }


}