<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\AddEmployeeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee", name="employee")
     */
    public function index()
    {
        $employeesRepo = $this->getDoctrine()->getRepository(Employee::class);
        $employees = $employeesRepo->findAll();
        
        return $this->render('employee/index.html.twig', [
            'employees' => $employees
        ]);
    }

    /**
     * @Route("/employee/add", name="addEmployee")
     */
    public function addEmployee(Request $request, SluggerInterface $slugger)
    {
        $directory = 'images/';
        $form = $this->createForm(AddEmployeeType::class, new Employee());
        $form->handleRequest($request);
        // si le formulaire est valide et envoyé
        if($form->isSubmitted() && $form->isValid()) {
            // je récupère les données du form
            $newEmployee = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                try {
                    $picture->move(
                        $directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $newEmployee->setpicture($newFilename);
            } // j'insère le nouvel employé en BDD
            $entityManager->persist($newEmployee);
            $entityManager->flush();
        
        } else {
            return $this->render('Employee/addEmployee.html.twig', [
                'form' => $form->createView(),
                'errors' => $form->getErrors()
            ]);
        }

        return $this->redirect('/employee');
    }

    /**
     * @Route("/employee/delete/{id}", name="deleteEmployee")
     */
    public function deleteEmployee($id)
    {
        
        $employee = $this->getDoctrine()->getRepository(Employee::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->redirect('/employee');
    }

}
