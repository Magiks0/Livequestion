<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findQuestionsByFilters(?string $title, ?User $author, ?Category $category)
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
}
