<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use LogicException;
use App\Entity\Character;


class CharacterVoter extends Voter
{
    public const CHARACTER_INDEX = 'characterIndex';
    public const CHARACTER_UPDATE = 'characterUpdate';

   // Checks if is allowed to create
    private function canCreate($token, $subject)
    {
        return true;
    }

    public const CHARACTER_CREATE = 'characterCreate';
    // Checks if is allowed to display
    private function canDisplay($token, $subject)
    {
        return true;
    }
    public const CHARACTER_DISPLAY = 'characterDisplay';
        private const ATTRIBUTES = array(
            self::CHARACTER_CREATE,
            self::CHARACTER_DISPLAY,
            self::CHARACTER_INDEX,
            self::CHARACTER_UPDATE,
        );


    protected function supports(string $attribute, mixed $subject): bool
    {
         if (null !== $subject) {
                 return $subject instanceof Character && in_array($attribute, self::ATTRIBUTES);
             }
             return in_array($attribute, self::ATTRIBUTES);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        switch ($attribute) {
            case self::CHARACTER_CREATE:
                return $this->canCreate($token, $subject);
                break;
            case self::CHARACTER_DISPLAY:
                return $this->canDisplay($token, $subject);
                break;
            case self::CHARACTER_INDEX:
                return $this->canDisplay($token, $subject);
                break;
            case self::CHARACTER_UPDATE:
                return $this->canUpdate($token, $subject);
                break;
        }

        throw new LogicException('Invalid attribute: ' . $attribute);
    }

    // Checks if is allowed to update
    private function canUpdate($token, $subject)
    {
        return true;
    }
}
