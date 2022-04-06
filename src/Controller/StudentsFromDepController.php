<?php

namespace App\Controller;

use FOS\RestBundle\View\ViewHandler;

use App\Repository\StudentRepository;
use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View; // Utilisation de la vue de FOSRestBundle

class StudentsFromDepController extends Controller
{

    /**
     * @Get("/students/{dep}")
     */
    public function listAction(DepartmentRepository $depRepo, StudentRepository $studentRepo, $dep): Response
    {
       // $articles = $studentRepo->findAll();
       $dep = $depRepo->findOneByName($dep);
       $depId = $dep->getId();
       $articles = $studentRepo->findByDep($depId);
       

        $formatted = [];
        foreach ($articles as $p) {
            $formatted[] = [
               'id' => $p->getId(),
               'FirstName' => $p->getFirstName(),
               'LastName' => $p->getLastName(),
            ];
        }

         // Récupération du view handler
        $viewHandler = $this->get('fos_rest.view_handler');

        // Création d'une vue FOSRestBundle
        $view = View::create($formatted);
        $view->setFormat('json');

        // Gestion de la réponse
        return $viewHandler->handle($view);
    }

}