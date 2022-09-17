<?php

namespace Quizz\Controller;

use Couchbase\User;
use phpDocumentor\Reflection\Types\String_;
use Quizz\Core\View\TwigCore;
use Quizz\FormControl;
use Quizz\Model\UserModel;

class UserController implements \Quizz\Core\Controller\ControllerInterface
{
    private $error = null;
    private $validate = false;

    public function inputRequest(array $tabInput)
    {
        // TODO: Implement inputRequest() method.
        if($_POST){
            $test = FormControl::controlCheck($_POST);
            $secure = FormControl::securityCheck($_POST['password']);
            if($test == null){
                if(is_string($secure)){
                    $this->validate = true;
                    $form = new FormControl($_POST['login'] , $secure ,$_POST['lastname'],$_POST['firstname'],$_POST['email']);
                    $user = new UserModel();
                    $user->addUser($form->getLogin(),$form->getMdp(),$form->getNom(),$form->getPrenom(),$form->getEmail());
                }else $this->error = $secure->getMessage();
            }else $this->error = $test;
        }
    }

    public function outputEvent()
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