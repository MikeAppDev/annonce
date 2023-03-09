<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class AnnounceVoter extends Voter
{
    public const EDIT = 'ANNOUNCE_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'ANNOUNCE_DELETE';
    public const REMOVE = 'PICTURE_REMOVE';
    

    public function __construct(protected Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::REMOVE])
            && $subject instanceof \App\Entity\Announce;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // Si l'utilisateur est admin, on autorise l'édition
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getAuthor() === $user;
                break;
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return true;
                break;
            case self::DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                return $subject->getAuthor() === $user;
                break;
            case self::REMOVE:
                // logic to determine if the user can VIEW
                // return true or false
                return $subject->getAuthor() === $user;
                break;
        }

        return false;
    }
}
