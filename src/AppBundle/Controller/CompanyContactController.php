<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CompanyContact;
use AppBundle\Entity\Course;
use AppBundle\Forms\Types\CompanyContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function indexAction(Course $course)
    {
        return $this->render('AppBundle:companycontact:index.html.twig', array(
            'course' => $course,
        ));
    }

    /**
     * Creates a new companyContact entity.
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function newAction(Request $request, Course $course)
    {
        $companyContact = new Companycontact();
        $form = $this->createForm(CompanyContactType::class, $companyContact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $companyContact = $form->getData();
            $companyContact->setCourse($course);
            $em->persist($companyContact);
            $em->flush();

            return $this->redirectToRoute('companycontact_show', array('courseId' => $course->getId(), 'id' => $companyContact->getId()));
        }

        return $this->render('AppBundle:companycontact:new.html.twig', array(
            'companyContact' => $companyContact,
            'form' => $form->createView(),
            'course' => $course,
        ));
    }

    /**
     * Finds and displays a companyContact entity.
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function showAction(CompanyContact $companyContact, Course $course)
    {
        return $this->render('@App/companycontact/show.html.twig', array(
            'companyContact' => $companyContact,
            'course' => $course,
        ));
    }

    /**
     * Displays a form to edit an existing companyContact entity.
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     */
    public function editAction(Request $request, CompanyContact $companyContact, Course $course)
    {
        $editForm = $this->createForm(CompanyContactType::class, $companyContact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('companycontact_show', [
                'courseId' => $course->getId(), 
                'id' => $companyContact->getId()
            ]);
        }

        return $this->render('@App/companycontact/edit.html.twig', array(
            'companyContact' => $companyContact,
            'edit_form' => $editForm->createView(),
            'course' => $course,
        ));
    }

    /**
     * Deletes a companyContact entity.
     * @ParamConverter("course", options={"mapping": {"courseId" : "id"}})
     * @ParamConverter("companyContact", options={"mapping": {"id" : "id"}})
     */
    public function deleteAction(Request $request, CompanyContact $companyContact, Course $course)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($companyContact);
        $em->flush();

        return $this->redirectToRoute('companycontact_index', array(
            'courseId' => $course->getId(),
        ));
    }

    /**
     * Creates a form to delete a companyContact entity.
     *
     * @param CompanyContact $companyContact The companyContact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Course $course, CompanyContact $companyContact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('companycontact_delete', array('courseId' => $course->getId(), 'id' => $companyContact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
