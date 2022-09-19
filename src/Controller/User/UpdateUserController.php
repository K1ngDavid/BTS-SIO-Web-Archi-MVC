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
        $this->etudiant = $unEtudiant->getFetchIdEtudiant($this->id);
        echo $this->etudiant->getEmail();
        if($_POST){
            $secure = FormControl::securityCheck($_POST['password']); // sécurisation du mdp
            if($this->etudiant->getEmail() == $_POST['email']){
                if($this->etudiant->getLogin() == $_POST['login']){
                    $test = FormControl::updateControlCheck($_POST,null,null); // on fait des vérifications sur les champs
                }else{
                    $test  = FormControl::updateControlCheck($_POST,$_POST['login'],null);
                }
            }else{
                $test = FormControl::updateControlCheck($_POST, null ,$_POST['email']);
                if($this->etudiant->getLogin() != $_POST['login']) {
                    $test = FormControl::updateControlCheck($_POST, $_POST['login'], $_POST['email']);
                }
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
                'user/updateUser.html.twig',[
                    'error' => true,
                    'causeError' =>$this->error
                ]
            );
        }
        else if($this->validate){
            $unEtudiant = new EtudiantModel();
            $this->etudiant = $unEtudiant->getFetchIdEtudiant($this->id);
            return TwigCore::getEnvironment()->render(
                'user/updateUser.html.twig',[
                    'success' => true,
                    'etudiant' => $this->etudiant
                ]
            );
        }
        else{
            $this->password = $_POST['password'];
            return TwigCore::getEnvironment()->render(
                'user/updateUser.html.twig',[
                    'etudiant' => $this->etudiant,
                    'password' => $this->password
                ]
            );
        }
    header("Refresh:0");
    }
}