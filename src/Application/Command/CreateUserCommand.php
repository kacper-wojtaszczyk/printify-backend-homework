<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Command;

use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Security\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception\UserAlreadyExistsException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Password;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CreateUserCommand extends Command
{
    private const NAME = 'printify:user:create';
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        parent::__construct(self::NAME);

        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function configure(): void
    {
        $this->setDescription('Creates new user. You will be asked for credentials.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $email = new EmailAddress($this->askForEmail($helper, $input, $output));
        $passwordString = $this->askForPassword($helper, $input, $output);

        try {
            if($this->userRepository->findOneByEmailAddress($email))
            {
                throw UserAlreadyExistsException::withEmail((string)$email);
            }

            $securityUser = new User((string)$email, '');
            $encodedPassword = new Password($this->passwordEncoder->encodePassword($securityUser, $passwordString));

            $userId = UserId::fromEmail($email);

            $user = \KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User::withCredentials(
                $userId,
                $email,
                $encodedPassword
            );

            $this->userRepository->save($user);
            $output->writeln(sprintf('<info>User %s created</info>', $email));
        } catch (\Throwable $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
        }
    }

    private function askForEmail(QuestionHelper $helper, InputInterface $input, OutputInterface $output): string
    {
        $default = 'test@example.org';
        $question = new Question(sprintf('Enter user email [<comment>%s</comment>]: ', $default), $default);

        return $helper->ask($input, $output, $question);
    }

    private function askForPassword(QuestionHelper $helper, InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Enter user password (min. 8 char.): ');
        $question->setHidden(true);
        $question->setHiddenFallback(true);

        return $helper->ask($input, $output, $question);
    }
}
