<?php

namespace Quizz\Controller\User;

use Quizz\Core\Controller\ControllerInterface;
use Quizz\Core\View\TwigCore;
use Quizz\Entity\Etudiant;
use Quizz\Model\EtudiantModel;

class ViewUsersController implements ControllerInterface
{

    public function inputRequest(array $tabInput)
    {
        // TODO: Implement inputRequest() method.

    }

    public function outputEvent()
    {
        $tabEtudiants = new EtudiantModel();
        return TwigCore::getEnvironment()->render(
            'user/allUsers.html.twig',[
                'etudiants' => $tabEtudiants->getFetchAll()
            ]

        );
    }
}