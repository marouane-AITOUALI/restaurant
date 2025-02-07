<?php
namespace App\Security\Voter;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    const VIEW = 'event_view';
    const EDIT = 'event_edit';
    const DELETE = 'event_delete';
    const CREATE = 'event_create';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE]) && (!$subject || $subject instanceof Event);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        // Admins et organisateurs peuvent gérer les événements
        if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_ORGANISATEUR_EVENT', $user->getRoles())) {
            return true;
        }

        return false;
    }
}
