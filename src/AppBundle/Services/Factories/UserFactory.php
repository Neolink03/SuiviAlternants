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

class UserFactory
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function saveFromAdmin(AdminNewUserDto $adminUserDto){

        switch ($adminUserDto->getUserType()){
            case "responsable":
                $this->saveCourseManagerFromAdmin($adminUserDto);
                break;
            case "jury":
                $this->saveJuryFromAdmin($adminUserDto);
                break;
            default:
                throw new \Exception("Le type d'utilisateur ". $adminUserDto->getUserType() ." n'existe pas.");
        }
    }

    public function saveCourseManagerFromAdmin(AdminNewUserDto $adminUserDto){
        $courseManager = new CourseManager();
        $courseManager->setFirstName($adminUserDto->getUser()->getFirstName());
        $courseManager->setLastName($adminUserDto->getUser()->getLastName());
        $courseManager->setEmail($adminUserDto->getUser()->getEmail());
        $courseManager->setUsername($adminUserDto->getUser()->getEmail());
        $courseManager->setPhoneNumber($adminUserDto->getPhoneNumber());

        $courseManager->setPlainPassword("FakePassword");

        $this->em->persist($courseManager);
        $this->em->flush();
    }

    public function saveJuryFromAdmin(AdminNewUserDto $adminUserDto){
        $jury = new Jury();
        $jury->setFirstName($adminUserDto->getUser()->getFirstName());
        $jury->setLastName($adminUserDto->getUser()->getLastName());
        $jury->setEmail($adminUserDto->getUser()->getEmail());
        $jury->setUsername($adminUserDto->getUser()->getEmail());

        $jury->setPlainPassword("FakePassword");

        $this->em->persist($jury);
        $this->em->flush();
    }

    public function checkUser(Student $student){

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