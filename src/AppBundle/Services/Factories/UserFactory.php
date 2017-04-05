<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;

use AppBundle\Entity\User\CourseManager;
use AppBundle\Entity\User\Jury;
use AppBundle\Entity\User\Student;
use AppBundle\Models\AdminNewUserDto;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class UserFactory
{
    private $em;
    private $swiftMessageFactory;
    private $mailer;
    private $session;

    public function __construct(EntityManager $em, SwiftMessageFactory $swiftMessageFactory, \Swift_Mailer $mailer, Session $session)
    {
        $this->em = $em;
        $this->swiftMessageFactory = $swiftMessageFactory;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    public function saveFromAdmin(AdminNewUserDto $adminUserDto){

        $managerDataBase = $this->em->getRepository(CourseManager::class)->findOneBy(array(
            'email' => $adminUserDto->getUser()->getEmail()
        ));

        if(is_null($managerDataBase)){
            switch ($adminUserDto->getUserType()) {
                case "responsable":
                    $user = $this->saveCourseManagerFromAdmin($adminUserDto);
                    $this->session->getFlashBag()->add("success", "L'utilisateur a bien été créé.");
                    break;
                case "jury":
                    $user = $this->saveJuryFromAdmin($adminUserDto);
                    $this->session->getFlashBag()->add("success", "L'utilisateur a bien été créé.");
                    break;
                default:
                    throw new \Exception("Le type d'utilisateur " . $adminUserDto->getUserType() . " n'existe pas.");
            }
            $swiftMessage = $this->swiftMessageFactory->createRegistration($user, $adminUserDto->getPassword());
            $this->mailer->send($swiftMessage);
        }else{
            $this->session->getFlashBag()->add("danger", "Cet email est déjà utilisé pour un autre utilisateur.");
        }
    }

    public function saveCourseManagerFromAdmin(AdminNewUserDto $adminUserDto) : CourseManager{
        $courseManager = new CourseManager();
        $courseManager->setFirstName($adminUserDto->getUser()->getFirstName());
        $courseManager->setLastName($adminUserDto->getUser()->getLastName());
        $courseManager->setEmail($adminUserDto->getUser()->getEmail());
        $courseManager->setUsername($adminUserDto->getUser()->getEmail());
        $courseManager->setPhoneNumber($adminUserDto->getPhoneNumber());
        $courseManager->setPlainPassword($adminUserDto->getPassword());

        $this->em->persist($courseManager);
        $this->em->flush();

        return $courseManager;
    }

    public function saveJuryFromAdmin(AdminNewUserDto $adminUserDto) : Jury{
        $jury = new Jury();
        $jury->setFirstName($adminUserDto->getUser()->getFirstName());
        $jury->setLastName($adminUserDto->getUser()->getLastName());
        $jury->setEmail($adminUserDto->getUser()->getEmail());
        $jury->setUsername($adminUserDto->getUser()->getEmail());
        $jury->setPlainPassword($adminUserDto->getPassword());

        $this->em->persist($jury);
        $this->em->flush();

        return $jury;
    }

    public function checkStudent(Student $student){

        $studentDataBase = $this->em->getRepository(Student::class)->findOneBy(array(
            'email' => $student->getEmail()
        ));

        if(is_null($studentDataBase)){
            $studentDataBase = $student;
            $studentDataBase->setUsername($student->getEmail());
            $studentDataBase->setPlainPassword("FakePassword");

            $this->em->persist($studentDataBase);
            $this->em->flush();
        }

        return $studentDataBase;
    }
}