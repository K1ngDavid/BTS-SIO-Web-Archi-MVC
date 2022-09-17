<?php

namespace Quizz\Model;

use Quizz\Core\Service\DatabaseService;
use Quizz\Entity\Questionnaire;

class UserModel
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = DatabaseService::getConnect();
    }

//    public static function ifValid($login , $email){
//        try{
//            if( self::ifLoginValid($login)!= 0){
//                throw new \Exception('Login existant');
//            }
//            elseif(self::ifEmailValid($email) != 0){
//                throw new \Exception('Email existant');
//            }
//        }catch (\Exception $e){
//            return $e->getMessage();
//        }
//        return true;
//    }

    public function isLoginValid($login){
        $request = "SELECT idEtudiant FROM etudiants WHERE login LIKE '{$login}'";
        $result = $this->bdd->prepare($request);
        $result->execute();
        return $result->rowCount();
    }
    public  function isEmailValid($email){
        $request = "SELECT idEtudiant FROM etudiants WHERE email LIKE '{$email}'";
        $result = $this->bdd->prepare($request);
        $result->execute();
        return $result->rowCount();
    }

    public function addUser($login , $mdp , $nom , $prenom , $email){
        $request = "INSERT INTO etudiants(login , motDePasse, nom , prenom , email) VALUES ('{$login}' , '{$mdp}' , '{$nom}' , '{$prenom}' , '{$email}');";
        $result =  $this->bdd->prepare($request);
        $result->execute();
    }
}