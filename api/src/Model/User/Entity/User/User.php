<?php
declare(strict_types=1);
namespace Api\Model\User\Entity\User;

use DateTimeImmutable;
use DomainException;
use Doctrine\ORM\Mapping as ORM;
use Api\Model\AggregateRoot;
use Api\Model\EventTrait;
use Api\Model\User\Entity\User\Event\UserConfirmed;
use Api\Model\User\Entity\User\Event\UserCreated;

/**
* @ORM\Entity
* @ORM\HasLifecycleCallbacks
* @ORM\Table(name="user_users", uniqueConstraints={
* 	@ORM\UniqueConstraint(columns={"email"})
* })
*/
class User implements AggregateRoot
{
	use EventTrait;

	const STATUS_WAIT = 'wait';
	const STATUS_ACTIVE = 'active';

	/**
	* @var UserId
	* @ORM\Column(type="user_user_id")
	* @ORM\Id
	*/
	private $id;

	/**
	* @var DateTimeImmutable
	* @ORM\Column(type="datetime_immutable")
	*/
	private $date;

	/**
	* @var Email
	* @ORM\Column(type="user_user_email")
	*/
	private $email;

	/**
	* @var string
	* @ORM\Column(type="string", name="password_hash")
	*/
	private $passwordHash;

	/**
	* @var ConfirmToken
	* @ORM\Embedded(class="ConfirmToken", columnPrefix="confirm_token_")
	*/
	private $confirmToken;

	/**
	* @var string
	* @ORM\Column(type="string", length=16)
	*/
	private $status;

	/**
	* @param UserId $id
	* @param DateTimeImmutable $date
	* @param Email $email
	* @param string $hash
	* @param ConfirmToken $confirmToken
	*/
	public function __construct(UserId $id,DateTimeImmutable $date, Email $email, string $hash, ConfirmToken $confirmToken)
	{
		$this->id = $id;
		$this->date = $date;
		$this->email = $email;
		$this->passwordHash = $hash;
		$this->confirmToken = $confirmToken;
		$this->status = self::STATUS_WAIT;
		$this->recordEvent(new UserCreated($this->id,$this->email,$this->confirmToken));
	}
	

	/**
	* @param string $token
	* @param DateTimeImmutable $date
	* @return void
	* @throws DomainException
	*/
	public function confirmSignup(string $token, DateTimeImmutable $date): void
	{
		if($this->isActive())
		{
			throw new DomainException('User is already active.');
		}

		$this->confirmToken->validate($token,$date);

		$this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;

		$this->recordEvent(new UserConfirmed($this->id));
	}

	/**
	* @return bool
	*/
	public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
	* @return bool
	*/
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
	* @return UserId
	*/
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
	* @return DateTimeImmutable
	*/
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
	* @return Email
	*/
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
	* @return string
	*/
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
	* @return ConfirmToken
	*/
    public function getConfirmToken(): ?ConfirmToken
    {
        return $this->confirmToken;
    }


    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
    	if ($this->confirmToken->isEmpty()) {
            $this->confirmToken = null;
        }
    }

}