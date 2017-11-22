<?php

namespace AppBundle\Entity;



use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{

    /**
     * @Assert\Length(
     *     min = 6,
     * )
     */
    protected $newPassword;


    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }
}
