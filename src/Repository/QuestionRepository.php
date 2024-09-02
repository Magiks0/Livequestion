<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Response;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findMostRespondedQuestion(): ?Question
    {
        $date = new \DateTime('-3 days');

        // Requête pour trouver la question avec le plus grand nombre de réponses dans les 3 derniers jours
        return $this->createQueryBuilder('q')
            ->leftJoin(Response::class, 'r', 'WITH', 'r.question = q.id')
            ->where('r.createdAt >= :date')
            ->setParameter('date', $date)
            ->groupBy('q.id')
            ->orderBy('COUNT(r.id)', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(); // Obtient l'entité Question ou null si non trouvé
    }


    public function findQuestionsByFilters(?string $title, ?User $author, ?Category $category): array
    {
        $qb = $this->createQueryBuilder('qu');

        $qb->join('qu.author', 'a')
            ->join('qu.category', 'ca');
        if (null != $title) {
            $qb->where('qu.title LIKE :keyword')
                ->setParameter('keyword', '%' . $title . '%');
        }
        if (null !== $author) {
            $qb->andWhere('qu.author = :author')
                ->setParameter('author', $author);
        }
        if (null != $category) {
            $qb->andWhere('ca.name = :category')
                ->setParameter('category', $category->getName());
        }

        return $qb->getQuery()->getResult();
    }

    public function findRandomQuestions(): array
    {
        $qb = $this->createQueryBuilder('q')
             ->getEntityManager()->createQuery('SELECT q FROM App\Entity\Question q ORDER BY RAND()')
            ->setMaxResults(3);

        return $qb->getResult();
    }
}
