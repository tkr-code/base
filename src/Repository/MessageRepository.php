<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findQuery(){
        return $this->createQueryBuilder('q');
    }
    public function conversation(int $emeteur, int $recepteur){
        // $query = $this->findQuery();
        // $query->andWhere('q.emetteur = :id_emmetteur AND q.recepteur = :id_recepteur');

        // $query->orWhere('q.emetteur = :id_recepteur AND q.recepteur = :id_emmetteur');

        // $query->setParameter('id_emmetteur',$emeteur);
        // $query->setParameter('id_recepteur',$recepteur);
        // $query->orderBy('q.id','ASC');
        $conn= $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * FROM message m
            ';
            $sql = "SELECT * FROM message m LEFT JOIN user u ON u.id = m.emetteur_id
            WHERE (emetteur_id = {$emeteur} AND recepteur_id = {$recepteur})
            OR (emetteur_id = {$recepteur} AND recepteur_id = {$emeteur}) ORDER BY m.id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([]);
        return $resultSet->fetchAllAssociative();
    }
    
    public function conversationUser(int $emeteur, int $recepteur){
        $query = $this->findQuery();
        $query->andWhere('q.emetteur = :id_emmetteur OR q.recepteur = :id_emmetteur');

        $query->andWhere('q.emetteur = :id_recepteur OR q.recepteur = :id_recepteur');

        $query->setParameter('id_emmetteur',$emeteur);
        $query->setParameter('id_recepteur',$recepteur);
        $query->orderBy('q.id','ASC');
        $query->distinct('q.emetteur');
        // select distinct u.id from user u left join message me on u.id = me.emetteur_id left join message mr on u.id= mr.recepteur_id  where (me.emetteur_id = 37 or me.recepteur_id = 37) or ( mr.emetteur_id = 37 or mr.recepteur_id = 37) AND  u.id <> 37;
        // $query->setMaxResults(1);
        return $query->getQuery();
    }
    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
