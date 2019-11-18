<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\User\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    public static function withCredentials(UserId $userId, EmailAddress $email, Password $password): self
    {
        $self = new self;
        $self->id = (string) $userId;
        $self->email = (string) $email;
        $self->password = (string) $password;
        return $self;
    }

    public function getId(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function getEmail(): EmailAddress
    {
        return new EmailAddress($this->email);
    }

    public function getPassword(): Password
    {
        return new Password($this->password);
    }
}
