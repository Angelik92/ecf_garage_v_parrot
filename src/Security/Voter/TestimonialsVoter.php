<?php

namespace App\Security\Voter;


use App\Entity\Testimonials;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class TestimonialsVoter extends Voter
{
    public const EDIT = 'TESTIMONIAL_EDIT';
    public const VALIDATE = 'TESTIMONIAL_VALIDATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VALIDATE])
            && $subject instanceof Testimonials;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $testimonial = $subject;

        return match ($attribute) {
            self::EDIT => $testimonial->isCreateByPhone() === true,
            self::VALIDATE => !$testimonial->isCreateByPhone() === true,
            default => false,
        };

    }
}
