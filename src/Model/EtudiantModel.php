<?php

namespace Quizz\Model;

use Quizz\Core\Service\DatabaseService;
use Quizz\Entity\Etudiant;
use Quizz\Entity\Questionnaire;

class EtudiantModel
{
    private $bdd;

    public function __construct()
    {
        $this->bdd = DatabaseService::getConnect();
    }

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

    public function getFetchAll()
    {
        $requete = $this->bdd->prepare("SELECT * FROM etudiants");
        $requete->execute();
        foreach ($requete->fetchAll() as $value){
            $etudiant = new Etudiant();
            $etudiant->setIdEtudiant($value['idEtudiant']);
            $etudiant->setLogin($value['login']);
            $etudiant->setMotDePasse($value['motDePasse']);
            $etudiant->setPrenom($value['prenom']);
            $etudiant->setNom($value['nom']);
            $etudiant->setEmail($value['email']);
            $tabEtudiant[]=$etudiant;
        }
        return $tabEtudiant;
    }

    public function getFetchIdEtudiant(int $id){
        $requete = $this->bdd->prepare("SELECT * FROM etudiants WHERE idEtudiant = {$id}");
        $requete->execute();
        $result = $requete->fetch();

        $etudiant = new Etudiant();
        $etudiant->setIdEtudiant($result['idEtudiant']);
        $etudiant->setLogin($result['login']);
        $etudiant->setMotDePasse($result['motDePasse']);
        $etudiant->setPrenom($result['prenom']);
        $etudiant->setNom($result['nom']);
        $etudiant->setEmail($result['email']);

        return $etudiant;

    }

    public function updateEtudiant($id , $login , $mdp , $nom , $prenom , $email){
        $requete = $this->bdd->prepare("UPDATE etudiants SET login = '{$login}' , motDePasse = '{$mdp}' , prenom = '{$prenom}' , nom = '{$nom}' , email = '{$email}' WHERE idEtudiant = $id");
        $requete->execute();
    }

    public function deleteEtudiant($id){
        $requete =$this->bdd->prepare("DELETE FROM etudiants WHERE idEtudiant = {$id}");
        $requete->execute();
    }
}