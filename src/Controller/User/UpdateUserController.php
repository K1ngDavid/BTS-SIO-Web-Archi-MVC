<?php

namespace Quizz\Controller\User;


use Quizz\Core\View\TwigCore;
use Quizz\FormControl;
use Quizz\Model\EtudiantModel;


class UpdateUserController implements \Quizz\Core\Controller\ControllerInterface
{
    private $etudiant = null;
    private $error = null;
    private $validate = false;
    private $id = null;
    private $password;
    public function inputRequest(array $tabInput)
    {
        // TODO: Implement inputRequest() method.
        $unEtudiant = new EtudiantModel();
        $this->id = $tabInput['VARS']['id'];
        $flag = 0;
        $this->etudiant = $unEtudiant->getFetchIdEtudiant($this->id);
        if($_POST){
            $secure = FormControl::securityCheck($_POST['password']); // sécurisation du mdp
            if($this->etudiant->getEmail() == $_POST['email']){ // si l'ancien mdp est égal au nouveau
                if($this->etudiant->getLogin() == $_POST['login']){ // si l'ancien login est égal au nouveau
                    $test = FormControl::updateControlCheck($_POST,null,null); // on fait des vérifications sur les champs
                }else{
                    $test  = FormControl::updateControlCheck($_POST,$_POST['login'],null); // si le login est différent alors on le modifie
                }
            }else{// si l'email est différent alors on le modifie
                $flag = null;
                if($this->etudiant->getLogin() != $_POST['login']) {
                    $flag = $_POST['login'];
                }
                $test = FormControl::updateControlCheck($_POST, $flag ,$_POST['email']);
            }
            if($test == null) {
                if (is_string($secure)) {
                    $unEtudiant->updateEtudiant($tabInput['VARS']['id'], $_POST['login'], $secure, $_POST['lastname'], $_POST['firstname'], $_POST['email']); // on update les changements
                    $this->validate = true;
                }else $this->error = $secure->getMessage(); // si erreur, on la sauvegarde pour l'afficher avec le message correspondant
            }else $this->error = $test;


        }
    }

    public function outputEvent()
    {
        if($this->error){
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig',[
                    'error' => true,
                    'causeError' =>$this->error,
                    'etudiant' => $this->etudiant
                ]
            );
        }
        else if($this->validate){
            $unEtudiant = new EtudiantModel();
            $this->etudiant = $unEtudiant->getFetchIdEtudiant($this->id);
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig',[
                    'success' => true,
                    'etudiant' => $this->etudiant
                ]
            );
        }
        else{
            return TwigCore::getEnvironment()->render(
                'user/user.html.twig',[
                    'etudiant' => $this->etudiant,
                ]
            );
        }
    }
}