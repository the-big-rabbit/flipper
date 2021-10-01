<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/06/2018
 * Time: 12:19
 */

namespace App\DataFixtures\ORM;


use ScyLabs\NeptuneBundle\Entity\PageType;
use ScyLabs\NeptuneBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Tests\Encoder\PasswordEncoder;


class LoadUserData extends Fixture implements OrderedFixtureInterface,ContainerAwareInterface
{

    private $container;

    public function load(ObjectManager $manager){


        $userManager = $this->container->get('fos_user.user_manager');
        $templating = $this->container->get('templating');

        $mailer = $this->container->get('mailer');

        $users = array();
        /*
         * Alex
         */
        $users[] = $userManager->createUser()
            ->setUsername('alexandre.sciacca@gmail.com')
            ->setEmail('alexandre.sciacca@gmail.com')
            ->setFirstConnexion(true)
            ->setRoles(array('ROLE_SUPER_ADMIN'));
        

        foreach ($users as $user){
            $pass = substr(hash('sha256',random_bytes(10)),0,10);


            $user->setPlainPassword($pass);
            var_dump($pass);

            $message = (new \Swift_Message('Création de compte'))
                ->setFrom('web@e-corses.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $templating->render(
                        '@ScyLabsNeptune/mail/mail_account.html.twig',
                        array(
                            'login' =>  $user->getEmail(),
                            'pass'  =>  $pass,
                        )
                    )
                    ,'text/html');
            $mailer->send($message);
            $userManager->updateUser($user);


        }

    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder(){
        return 1;
    }
}