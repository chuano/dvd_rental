<?php

declare(strict_types=1);

namespace App\Shared\Movie\Infra;

use App\Shared\Domain\Exception\EntityNotFoundException;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class MovieRepositoryImpl implements MovieRepositoryInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Movie $movie): void
    {
        $this->entityManager->persist($movie);
        $this->entityManager->flush();
    }

    public function getById(Uuid $id): ?Movie
    {
        return $this->entityManager->getRepository(Movie::class)->find($id);
    }

    /**
     * @return Movie[]
     */
    public function getAll(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->entityManager
            ->getRepository(Movie::class)
            ->findBy([], ['metadata.title' => 'ASC'], $limit, $offset);
    }

    /**
     * @throws NoResultException|NonUniqueResultException
     */
    public function count(): int
    {
        return (int)$this->entityManager
            ->getRepository(Movie::class)
            ->createQueryBuilder('movie')
            ->select('count(movie.id)')
            ->getQuery()->getSingleScalarResult();
    }

    public function delete(Uuid $id): void
    {
        $movie = $this->entityManager->getRepository(Movie::class)->find($id);
        if (!$movie) {
            throw new EntityNotFoundException('Movie not found');
        }
        $this->entityManager->remove($movie);
        $this->entityManager->flush();
    }
}
