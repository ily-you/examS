<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
     /**
     *@Route("/", name="task_list")
     */
    public function liste(TaskRepository $taskRepository): Response
    {
        $tasks =  $taskRepository->findAll();
        return $this->render('project/list.html.twig', ['tasks' => $tasks]);

    }
    /**
     * @Route("/add_task/{id}", name="add_task")
     * @param Request $request
     */
    public function addTask($id, Request $request, 
                            EntityManagerInterface $entityManager, 
                            ProjectRepository $projectRepository)
    {
        $project = $projectRepository->find($id);
        if ($request->getMethod() == 'POST') {
            

            $task = new Task();

            $task->setTitle($request->request->get('title'));
            $task->setDescription($request->request->get('description'));
            $task->setCreatedAt(new \DateTime);
            $task->setProject($project);

            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('project_list');
        }

        return $this->render('task/addtask.html.twig', ['project' => $project]);
    }
}
