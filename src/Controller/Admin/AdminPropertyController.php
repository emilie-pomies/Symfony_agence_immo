<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController{

    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    //fct qui recupere toutes les annonces 
    public function index()
    {
        $properties=$this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     * @param Request $request 
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();
            $this->addFlash('success', 'Créé avec succès!');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig', [
            'property'=>$property,
            'form'=>$form->createView()
            ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function edit(Property $property, Request $request)
    {
            
        
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Modifié avec succès!');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property'=>$property,
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @return \symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete'. $property->getId(), $request->get('_token') )){
            $em = $this->getDoctrine()->getManager();
            $em->remove($property);
            $em->flush();
            $this->addFlash('success', 'Supprimé avec succès!');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}