<?php
/**
 * Created by PhpStorm.
 * User: igorweigel
 * Date: 05.02.2020
 * Time: 11:01
 */

namespace Igoooor\UserBundle\Command;

use Igoooor\UserBundle\Model\UserInterface;
use Igoooor\UserBundle\Utils\PasswordGenerator;
use Igoooor\UserBundle\Utils\UserManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateUserCommand
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'igoooor:user:create';

    /**
     * @var UserManipulator
     */
    private $userManipulator;
    /**
     * @var PasswordGenerator
     */
    private $passwordGenerator;

    /**
     * CreateUserCommand constructor.
     *
     * @param UserManipulator   $userManipulator
     * @param PasswordGenerator $passwordGenerator
     */
    public function __construct(UserManipulator $userManipulator, PasswordGenerator $passwordGenerator)
    {
        parent::__construct();

        $this->userManipulator = $userManipulator;
        $this->passwordGenerator = $passwordGenerator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('igoooor:user:create')
            ->setDescription('Create a user.')
            ->setDefinition([
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputOption('admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
            ])
            ->setHelp(<<<'EOT'
The <info>igoooor:user:create</info> command creates a user:
  <info>php %command.full_name% john@example.com</info>
This interactive shell will ask you for a password.
You can alternatively specify the email and password as the first and second arguments:
  <info>php %command.full_name% john@example.com mypassword</info>
You can create an admin via the admin flag:
  <info>php %command.full_name% john@example.com --admin</info>
You can create a super admin via the super-admin flag:
  <info>php %command.full_name% john@example.com --super-admin</info>
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
        $outputPassword = false;
        if (empty($password)) {
            $password = $this->passwordGenerator->generate(12);
            $outputPassword = true;
        }

        $admin = $input->getOption('admin');
        $superadmin = $input->getOption('super-admin');

        $roles = [];
        if ($superadmin) {
            $roles[] = UserInterface::ROLE_SUPER_ADMIN;
        }
        if ($admin) {
            $roles[] = UserInterface::ROLE_ADMIN;
        }

        $this->userManipulator->create($email, $password, $roles);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $email));
        if ($outputPassword) {
            $output->writeln(sprintf('Password: <comment>%s</comment>', $password));
        }


        return Command::SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password (leave empty for random password):');
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
