<?php


namespace App\Repository;

use App\Entity\Trajet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class TrajetRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Trajet::class);
        $this->entityManager = $entityManager;
    }

    public function save(Trajet $trajet): void
    {
        $this->entityManager->persist($trajet);
        $this->entityManager->flush();
    }

    public function update(Trajet $trajet): void
    {
        $this->entityManager->flush();
    }

    public function delete(Trajet $trajet): void
    {
        $this->entityManager->remove($trajet);
        $this->entityManager->flush();
    }

    public function findAllTrajets(): array
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
    }

    public function findOneById(int $id): ?Trajet
    {
        return $this->find($id); 
    }
}
