<?php

namespace Quizz\Controller\User;

use Quizz\Core\View\TwigCore;
use Quizz\FormControl;
use Quizz\Model\EtudiantModel;

class AddUserController implements \Quizz\Core\Controller\ControllerInterface
{
    private $error = null;
    private $validate = false;

    public function inputRequest(array $tabInput) // c'est ici qu'on va gérer l'input du user
    {
        // TODO: Implement inputRequest() method.
        if($_POST){ // vérifie si des variables http en méthode POST existent
            $test = FormControl::controlCheck($_POST); // on fait des vérifications sur les champs
            $secure = FormControl::securityCheck($_POST['password']); // sécurisation du mdp
            if($test == null){ // si la variable est de type null il n'y a alors aucune erreur
                if(is_string($secure)){ // si la variable est de type string alors on peut insérer l'étudiant
                    $this->validate = true;
                    $form = new FormControl($_POST['login'] , $secure ,$_POST['lastname'],$_POST['firstname'],$_POST['email']); // création d'un objet de type Form
                    $user = new EtudiantModel();
                    $user->addUser($form->getLogin(),$form->getMdp(),$form->getNom(),$form->getPrenom(),$form->getEmail()); // ajout dans la base de donnée
                }else $this->error = $secure->getMessage();
            }else $this->error = $test;
        }
    }

    public function outputEvent() // c'est ici qu'on va output le résultat :)
    {
        if($this->error){
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig',[
                    'error' => true,
                    'causeError' => $this->error
                ]
            );
        }
        else if($this->validate){
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig',[
                    'success' => true,
                ]
            );
        }
        else{
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig'
            );
        }


        // TODO: Implement outputEvent() method.
    }
}