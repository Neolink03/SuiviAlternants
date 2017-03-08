<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\CompanyContact;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCompanyContacts extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $contacts = array(
            array(
                'name' => 'Darty',
                'firstName' => 'Jean',
                'lastName' => 'Neige',
                'email' => 'test@test.fr',
                'phone' => '02475896586',
            ),
            array(
                'name' => 'Leclerc',
                'firstName' => 'John',
                'lastName' => 'Snow',
                'email' => 'test@test.fr',
                'phone' => '02475896586',
            ),
            array(
                'name' => 'Westeros',
                'firstName' => 'Denerys',
                'lastName' => 'Dutyphon',
                'email' => 'test@test.fr',
                'phone' => '02475896586',
            )
        );

        foreach ($contacts as $contact){
            $companyContact = new CompanyContact();
            $companyContact->setCompanyName($contact['name']);
            $companyContact->setFirstNameContact($contact['firstName']);
            $companyContact->setLastNameContact($contact['lastName']);
            $companyContact->setEmail($contact['email']);
            $companyContact->setPhoneNumber($contact['phone']);
            $manager->persist($companyContact);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
