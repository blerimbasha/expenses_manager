<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * GroupTransactions
 *
 * @ORM\Table(name="group_transactions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupTransactionsRepository")
 */
class GroupTransactions
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Transaction", inversedBy="userId")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $transactionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction" ,mappedBy="categoryId")
     */
    private $grouptransaction;

    public function __construct()
    {
        $this->grouptransaction = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set transactionId
     *
     * @param integer $transactionId
     * @return GroupTransactions
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return GroupTransactions
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}
