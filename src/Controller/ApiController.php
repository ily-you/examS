<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ApiController extends AbstractController
{
   /**
     * @Route("/api/projects", name="api_projects_list")
     */
    public function list(ProjectRepository $projectRepository)
    {
        
        $projects = $projectRepository->findAll();
        return $this->json($projects, 200, [], [
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH  => true,
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'startedAt',
                'endedAt',
                'status' ]
        ]);
    }
    /**
     * @Route("/api/projects/{id}", name="api_project_by_id")
     */
    public function projectFindById(ProjectRepository $projectRepository, $id)
    {
        $project = $projectRepository->find($id);
        if ($project === null) {
            throw new NotFoundHttpException();
        }
        return $this->json($project, 200, [], [
            AbstractObjectNormalizer::ENABLE_MAX_DEPTH  => true,
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'startedAt',
                'endedAt',
                'status' ]
        ]);
    }
}