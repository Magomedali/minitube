<?php
declare(strict_types=1);
namespace Api\Infrastructure\Model\User\Entity;

use Api\Model\EntityNotFoundException;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\User;
use Api\Model\User\Entity\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUserRepository implements UserRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(User::class);
        $this->em = $em;
    }

    /**
	* @param Email $email
	* @return bool
	*/
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.email = :email')
            ->setParameter(':email', $email->getEmail())
            ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
	* @param Email $email
	* @return User
	* @throws EntityNotFoundException
	*/
    public function getByEmail(Email $email): User
    {
        /** @var User $user */
        if (!$user = $this->repo->findOneBy(['email' => $email->getEmail()])) {
            throw new EntityNotFoundException('User is not found.');
        }
        return $user;
    }

    /**
	* @param User $user
	* @return bool
	*/
    public function add(User $user): void
    {
        $this->em->persist($user);
    }
}