<?php

namespace Modules\Shared\Contracts;

interface MemberServiceInterface
{
    public function markAsBorrowing(int $memberId): void;
}