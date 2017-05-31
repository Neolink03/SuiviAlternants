<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;

use AppBundle\Entity\Application;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\StatusModification;
use AppBundle\Entity\User;
use AppBundle\Entity\User\Administrator;
use AppBundle\Entity\User\CourseManager;
use AppBundle\Entity\User\Jury;
use AppBundle\Entity\User\Student;
use AppBundle\Models\AdminNewUserDto;
use AppBundle\Services\PasswordService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class UserFactory
{
    private $em;
    private $swiftMessageFactory;
    private $passwordService;
    private $mailer;
    private $session;

    public function __construct(
        EntityManager $em,
        SwiftMessageFactory $swiftMessageFactory,
        \Swift_Mailer $mailer,
        Session $session,
        PasswordService $passwordService
    )
    {
        $this->em = $em;
        $this->swiftMessageFactory = $swiftMessageFactory;
        $this->passwordService = $passwordService;
        $this->mailer = $mailer;
        $this->session = $session;
    }

    public function saveFromAdmin(AdminNewUserDto $adminUserDto){

        $managerDataBase = $this->em->getRepository(User::class)->findOneBy(array(
            'email' => $adminUserDto->getUser()->getEmail()
        ));

        if(is_null($managerDataBase)){
            switch ($adminUserDto->getUserType()) {
                case "administrateur":
                    $user = $this->saveAdministratorFromAdmin($adminUserDto);
                    $this->session->getFlashBag()->add("success", "L'utilisateur a bien été créé.");
                    break;
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

            $swiftMessage = $this->swiftMessageFactory->create(
                'New Registration',
                'send@example.com',
                [$user->getEmail()],
                '@App/email/registration.html.twig',
                [
                    "email" => $user->getEmail(),
                    "password" => $adminUserDto->getPassword()
                ]

            );

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
        $courseManager->addRole('ROLE_MANAGER');
        $courseManager->setEnabled(true);

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
        $jury->addRole('ROLE_JURY');
        $jury->setEnabled(true);

        $this->em->persist($jury);
        $this->em->flush();

        return $jury;
    }

    public function saveAdministratorFromAdmin(AdminNewUserDto $adminUserDto) : Administrator{
        $administrator = new Administrator();
        $administrator->setFirstName($adminUserDto->getUser()->getFirstName());
        $administrator->setLastName($adminUserDto->getUser()->getLastName());
        $administrator->setEmail($adminUserDto->getUser()->getEmail());
        $administrator->setUsername($adminUserDto->getUser()->getEmail());
        $administrator->setPlainPassword($adminUserDto->getPassword());
        $administrator->addRole('ROLE_ADMIN');
        $administrator->setEnabled(true);

        $this->em->persist($administrator);
        $this->em->flush();

        return $administrator;
    }

    public function getOrCreateStudentIfNotExist(Student $student){

        $studentDataBase = $this->em->getRepository(Student::class)->findOneBy(array(
            'email' => $student->getEmail()
        ));

        if(is_null($studentDataBase)){
            $password = $this->passwordService->generate();

            $studentDataBase = $student;
            $studentDataBase->setUsername($student->getEmail());
            $studentDataBase->setPlainPassword($password);
            $studentDataBase->addRole('ROLE_STUDENT');
            $studentDataBase->setEnabled(true);

            $this->em->persist($studentDataBase);
            $this->em->flush();

            $swiftMessage = $this->swiftMessageFactory->create(
                'New Registration',
                'send@example.com',
                [$student->getEmail()],
                '@App/email/registration.html.twig',
                [
                    "email" => $student->getEmail(),
                    "password" => $password
                ]

            );

            $this->mailer->send($swiftMessage);
        }
        return $studentDataBase;
    }

    public function saveStudentsfromCsvFile(string $filePath, Promotion $promotion){
        $csvFile = file($filePath);
        foreach ($csvFile as $line) {
            $studentArray = explode(';', str_replace("\r\n",'', $line));
            $student = new Student();
            $student->setLastName($studentArray[0]);
            $student->setFirstName($studentArray[1]);
            $student->setEmail($studentArray[2]);
            $student->addRole('ROLE_STUDENT');
            $student->setEnabled(true);

            $student = $this->getOrCreateStudentIfNotExist($student);
            $student = $this->createStudentApplicationFromPromotion($student, $promotion);

            $this->em->persist($student);
        }

        $this->em->flush();
    }

    public function createStudentApplicationFromPromotion(Student $student, Promotion $promotion) : Student{
        $application = New Application();
        $statusModif = new StatusModification();
        $statusModif->setApplication($application);
        $statusModif->setComment("");
        $statusModif->setDateTime(new \DateTime());

        $state = $promotion->getWorkflow()->getFirstState();

        $statusModif->setState($state);
        $application->addStatusModification($statusModif);
        $application->setCurrentState($state->getMachineName());

        $application->setPromotion($promotion);
        $student->addApplication($application);
        return $student;
    }
}