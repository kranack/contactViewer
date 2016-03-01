<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactType;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = 'SELECT c FROM AppBundle:Contact c';
        $query = $em->createQuery($query);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
           $query, /* query NOT result */
           $request->query->getInt('page', 1)/*page number*/,
           10/*limit per page*/
        );

        return $this->render('contact/index.html.twig', array(
            'contacts' => $pagination,
        ));
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm('AppBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }
        if ($request->getMethod() === "POST") {
          $success = "error";
          if (trim($request->get('lastname')) != ""
              && trim($request->get('email')) != ""
              && trim($request->get('website')) != "") {
                print_r($contact);
                $contact->setName($request->get('name'));
                $contact->setLastname($request->get('lastname'));
                $contact->setCompany($request->get('company'));
                $contact->setAddress($request->get('address'));
                $contact->setPhone($request->get('phone'));
                $contact->setEmail($request->get('email'));
                $contact->setWebsite($request->get('website'));
                $contact->setType($request->get('type'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
                $status = "success";
              }
          $response = new Response(json_encode(array("status" => $status)));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
        }
        //print_r(ContactType::getTypes())
        return $this->render('contact/new.html.twig', array(
            'contact' => $contact,
            'form'    => $form->createView(),
            'types'   => ContactType::getTypes()
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     */
    public function showAction(Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);

        return $this->render('contact/show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);
        $editForm = $this->createForm('AppBundle\Form\ContactType', $contact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        return $this->render('contact/edit.html.twig', array(
            'contact' => $contact,
            'types'   => ContactType::getTypes(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $form = $this->createDeleteForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contact_index');
    }

    /**
     * Creates a form to delete a Contact entity.
     *
     * @param Contact $contact The Contact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contact $contact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $contact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
