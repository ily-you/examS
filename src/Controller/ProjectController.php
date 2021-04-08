<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project")
     */
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

     /**
     *@Route("/", name="project_list")
     */
    public function liste(ProjectRepository $projectRepository): Response
    {
        $projects =  $projectRepository->findAll();
        return $this->render('project/list.html.twig', ['projects' => $projects]);

    }

    /**
     * @Route("/add", name="project_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, TaskRepository $taskRepository)
    {
   
        if ($request->getMethod() === 'POST') {
            $project = new Project();
            $project->setName($request->request->get('name'));
            $project->setStartedAt(new \DateTime);
            $project->setEndedAt(new \DateTime);
            $project->setStatus("nouveau");



            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('project/add.html.twig');
    }
  
    public function update(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $project->setStatus();
        $entityManager->flush();

        return $this->redirectToRoute('project_list', [
            'id' => $project->getId()
        ]);
    }
    /**
     *@Route("/project/{id}", name="project_gestion")
     *@param $id
     *@param ProjectRepository $projectRepository
     */
    public function show($id, ProjectRepository $projectRepository)
    {
     $project = $projectRepository->find($id);
     if ($project== null) {
         throw new NotFoundHttpException();
     }
     return $this->render('project/gestion.html.twig', ['project' => $project]);
    }
    
}
