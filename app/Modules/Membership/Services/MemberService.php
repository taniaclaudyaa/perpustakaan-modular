<?php

namespace Modules\Membership\Services;

use Modules\Membership\Models\Member;
use Modules\Shared\Contracts\MemberServiceInterface;

class MemberService implements MemberServiceInterface
{
    public function markAsBorrowing(int $memberId): void
    {
        Member::where('id', $memberId)->update(['status' => 'borrowing']);
    }
}