<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DebugController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * @Route("/", name="debug")
     */
    public function index(): Response
    {

        $qb = $this->em->getRepository(User::class)->createQueryBuilder('u');
        $qb->andWhere("u.name IN (:foo_values)");
        $qb->setParameter("foo_values", ['bar'], Connection::PARAM_INT_ARRAY);
        $query = $qb->getQuery();
        $sql = $query->getSQL();
        $users = $query->getResult();

        return $this->render('debug/index.html.twig', [
            'controller_name' => 'DebugController',
            'sql' => $sql,
            'users' => $users,
        ]);
    }
}
