<?php
/**
 * User: Antoine Lamirault
 */

namespace AppBundle\Services\Factories;

use AppBundle\Entity\User;
use Swift_Message;
use Swift_Mime_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Environment;

class SwiftMessageFactory
{
    private $twig;
    private $container;

    public function __construct(Twig_Environment $twig, ContainerInterface $container)
    {
        $this->twig = $twig;
        $this->container = $container;
    }

    public function create(string $subject, string $from, array $recipientsMail, string $templatePath, array $params) : Swift_Mime_Message
    {
        /**
         * @var \Swift_Mime_Message $message
         */
        $message=  \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($recipientsMail)
            ->setBody($this->createBody($templatePath, $params), 'text/html');

        return $message;
    }

    private function createBody(string $templatePath, array $params) : string {
        return $this->twig->render($templatePath, $params);
    }
}