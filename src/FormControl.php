<?php

namespace Quizz;

use Couchbase\User;
use mysql_xdevapi\Exception;
use PharIo\Manifest\ElementCollectionException;
use Quizz\Model\UserModel;

class FormControl
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
    public function __construct($login, $mdp, $nom, $prenom, $email)
    {
        $this->login = $login;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
    }

    public static function controlCheck($parameters_list)
    {
        $alreadyExist = new UserModel();

        try {
            foreach ($parameters_list as &$item) {
                if ($item == null) throw new \Exception('Pas tous les champs ont été rempli', code: 0);
            }
            if (strlen($_POST['login']) <= 2) {
                throw new \Exception('Login trop court', code: 1);
            } elseif (strlen($_POST['login']) >= 15) {
                throw new \Exception('Login trop long', code: 2);
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("L'email n'est pas de la bonne forme", code: 3);
            }elseif($alreadyExist->isEmailValid($_POST['email']) != 0){
                throw new \Exception("Email déja existant", code: 4);
            }elseif($alreadyExist->isLoginValid($_POST['login']) != 0){
                throw new \Exception("Login déja existant", code: 5);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function securityCheck($password){
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        try{
            if(strlen($password) <=4) throw new \Exception('Mot de passe trop court');
            elseif(!$uppercase) throw new \Exception('Mot de passe doit contenir des majuscules');
            elseif(!$lowercase) throw new \Exception('Mot de passe doit contenir des minuscules');
            elseif(!$number) throw new \Exception('Mot de passe doit contenir des nombres');
            elseif(!$specialChars) throw new \Exception('Mot de passe doit contenir des characteres spéciaux');
        }catch (\Exception $e){
            return $e;
        }

        //CHIFFREMENT DU MOT DE PASSE
        $encrypted_passsword = password_hash($password,PASSWORD_DEFAULT,['cost' => 14]);
        return $encrypted_passsword;
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