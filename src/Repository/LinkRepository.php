<?php

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LinkRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly int $linkLifetimeLimitInMinutes,
    )
    {
        parent::__construct($registry, Link::class);
    }

    public function persist(Link $link): void
    {
        $this->_em->persist($link);
        $this->_em->flush();
    }

    public function getLinkByToken(string $token): ?Link
    {

        $datetimeLimit = (new \DateTime())
            ->modify('-' . $this->linkLifetimeLimitInMinutes . ' minutes')
        ;

        $qb = $this->createquerybuilder('a')
            ->andWhere('a.token = :token and a.createdAt >= :datetime')
            ->setParameter('token', $token)
            ->setParameter('datetime', $datetimeLimit)
        ;

        return $qb->getQuery()->execute()[0] ?? null;
    }
}