<?php


namespace App\Security\Voter;


use App\Entity\Task;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    public const DELETE = 'delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        if ($attribute !== self::DELETE) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete($task, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $this->isUserTheSameAsAuthorOrIsUserAdminAndTaskAnon($task, $user);
    }

    private function isUserTheSameAsAuthorOrIsUserAdminAndTaskAnon(Task $task, User $user): bool
    {
        return $user === $task->getUser() || ($task->getUser() === null && $this->security->isGranted('ROLE_ADMIN'));
    }
}
