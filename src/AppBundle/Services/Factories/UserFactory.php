<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;


use AppBundle\Entity\User;
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

    public function saveFromAdmin(AdminNewUserDto $adminUserDto) : User{

        switch ($adminUserDto->getUserType()){
            case "responsable":
                return $this->saveCourseManagerFromAdmin($adminUserDto);
            case "jury":
                return $this->saveJuryFromAdmin($adminUserDto);
            default:
                throw new \Exception("Le type d'utilisateur ". $adminUserDto->getUserType() ." n'existe pas.");
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