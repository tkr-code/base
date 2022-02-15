<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function findQuery(){
        return $this->createQueryBuilder('q');
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function chatUsers(int $emetteur){
        

    //     $conn = $this->getEntityManager()->getConnection();
    //     $sql = '
    // select distinct  u.id from user u left join message m on u.id = m.emetteur_id left join message on u.id=m.recepteur_id where not u.id = 19;    //         ';
    //     $stmt = $conn->prepare($sql);
    //    return $resultSet = $stmt->executeQuery([]);
        
        $query = $this->findQuery();
        $query->leftJoin('q.envoyer','e');
        $query->leftJoin('q.recu','r');
        $query->orwhere('e.emetteur = :emetteur or e.recepteur = :emetteur');
        $query->orwhere('r.emetteur = :emetteur or r.recepteur = :emetteur' );
        $query->andWhere('not q.id = :emetteur');
        $query->setParameter('emetteur',$emetteur);
        $query->distinct();
        return $query->getQuery()->getResult();
        // $query->join('q.envoyer','e');
        // $query->join('q.recu','r');
        // $query->andWhere('e.emetteur = :id_emmetteur OR e.recepteur = :id_emmetteur');

        // $query->andWhere('e.emetteur = :id_recepteur OR e.recepteur = :id_recepteur');

        // $query->andWhere('r.emetteur = :id_emmetteur OR r.recepteur = :id_emmetteur');
        // $query->andWhere('r.emetteur = :id_recepteur OR r.recepteur = :id_recepteur');

        // $query->setParameter('id_emmetteur',$emeteur);
        // $query->setParameter('id_recepteur',$recepteur);
        // $query->orderBy('q.id','ASC');
        // $query->distinct();
        // $query->setMaxResults(1);
        // return $query->getQuery()->getResult();
        // return
    }
    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
