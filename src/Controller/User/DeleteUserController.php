<?php

namespace Quizz\Controller\User;

use Couchbase\User;
use Quizz\Core\View\TwigCore;
use Quizz\Model\EtudiantModel;

class DeleteUserController implements \Quizz\Core\Controller\ControllerInterface
{
    private $id;
    public function inputRequest(array $tabInput)
    {
        $this->id = $tabInput['VARS']['id']; // on get le parametre dans l'URL
        $etudiant = new EtudiantModel();
        $etudiant->deleteEtudiant($this->id); // on delete l'étudiant
    }

    public function outputEvent()
    {
        $tabEtudiants = new EtudiantModel();
        return TwigCore::getEnvironment()->render(
            'user/allUsers.html.twig',[
                'delete' => true,
                'etudiants' => $tabEtudiants->getFetchAll()
            ]
        );
    }
}