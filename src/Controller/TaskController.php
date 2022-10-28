<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TaskRepository $taskRepository): Response
    {
        date_default_timezone_set("Europe/Paris");


        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $task->setCreatedDate(new DateTime());
        $task->setUpdatedDate(new DateTime());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        date_default_timezone_set("Europe/Paris");
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedDate(new DateTime());
            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/done', name: 'app_task_done', methods: ['GET', 'POST'])]
    public function done(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        date_default_timezone_set("Europe/Paris");
        $task->setUpdatedDate(new DateTime());
        $task->setFinish(true);

        $taskRepository->save($task, true);

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/undone', name: 'app_task_undone', methods: ['GET', 'POST'])]
    public function undone(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        date_default_timezone_set("Europe/Paris");
        $task->setUpdatedDate(new DateTime());
        $task->setFinish(false);

        $taskRepository->save($task, true);

        return $this->redirectToRoute('app_main', [], Response::HTTP_SEE_OTHER);
    }
}
