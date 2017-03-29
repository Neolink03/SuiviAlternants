<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CompanyContact;
use AppBundle\Forms\Types\CompanyContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Companycontact controller.
 *
 */
class CompanyContactController extends Controller
{
    /**
     * Lists all companyContact entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companyContacts = $em->getRepository('AppBundle:CompanyContact')->findAll();

        return $this->render('AppBundle:companycontact:index.html.twig', array(
            'companyContacts' => $companyContacts,
        ));
    }

    /**
     * Creates a new companyContact entity.
     *
     */
    public function newAction(Request $request)
    {
        $companyContact = new Companycontact();
        $form = $this->createForm(CompanyContactType::class, $companyContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($companyContact);
            $em->flush();

            return $this->redirectToRoute('companycontact_show', array('id' => $companyContact->getId()));
        }

        return $this->render('AppBundle:companycontact:new.html.twig', array(
            'companyContact' => $companyContact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a companyContact entity.
     *
     */
    public function showAction(CompanyContact $companyContact)
    {
        $deleteForm = $this->createDeleteForm($companyContact);

        return $this->render('@App/companycontact/show.html.twig', array(
            'companyContact' => $companyContact,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing companyContact entity.
     *
     */
    public function editAction(Request $request, CompanyContact $companyContact)
    {
        $deleteForm = $this->createDeleteForm($companyContact);
        $editForm = $this->createForm(CompanyContactType::class, $companyContact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('companycontact_show', array('id' => $companyContact->getId()));
        }

        return $this->render('@App/companycontact/edit.html.twig', array(
            'companyContact' => $companyContact,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a companyContact entity.
     *
     */
    public function deleteAction(Request $request, CompanyContact $companyContact)
    {
        $form = $this->createDeleteForm($companyContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($companyContact);
            $em->flush();
        }

        return $this->redirectToRoute('companycontact_index');
    }

    /**
     * Creates a form to delete a companyContact entity.
     *
     * @param CompanyContact $companyContact The companyContact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CompanyContact $companyContact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companycontact_delete', array('id' => $companyContact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
