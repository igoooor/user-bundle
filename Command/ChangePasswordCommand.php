<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 05.02.2020
 * Time: 11:23
 */

namespace Igoooor\UserBundle\Command;

use Igoooor\UserBundle\Utils\UserManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class ChangePasswordCommand
 */
class ChangePasswordCommand extends Command
{
    protected static $defaultName = 'igoooor:user:change-password';

    private $userManipulator;

    /**
     * ChangePasswordCommand constructor.
     *
     * @param UserManipulator $userManipulator
     */
    public function __construct(UserManipulator $userManipulator)
    {
        parent::__construct();

        $this->userManipulator = $userManipulator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Change the password of a user.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ])
            ->setHelp(<<<'EOT'
The <info>igoooor:user:change-password</info> command changes the password of a user:
  <info>php %command.full_name% john@example.com</info>
This interactive shell will first ask you for a password.
You can alternatively specify the password as a second argument:
  <info>php %command.full_name% john@example.com mypassword</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $this->userManipulator->changePassword($email, $password);

        $output->writeln(sprintf('Changed password for user <comment>%s</comment>', $email));

        return Command::SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $question = new Question('Please give the email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Username can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please enter the new password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
