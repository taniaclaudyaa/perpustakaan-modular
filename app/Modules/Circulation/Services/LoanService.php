<?php

namespace Modules\Circulation\Services;

use Illuminate\Support\Facades\DB;
use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\Models\Loan;
use Modules\Circulation\Models\LoanDetail;
use Modules\Shared\Contracts\BookServiceInterface;
use Modules\Shared\Contracts\MemberServiceInterface;
use Modules\Shared\Exceptions\InsufficientStockException;

class LoanService
{
    public function __construct(
        private BookServiceInterface $bookService,
        private MemberServiceInterface $memberService
    ) {}

    public function borrow(int $memberId, array $items, int $days = 7): Loan
    {
        $loan = DB::transaction(function () use ($memberId, $items, $days) {
            $loan = Loan::create([
                'loan_number' => 'LN-' . strtoupper(uniqid()),
                'member_id'   => $memberId,
                'status'      => 'borrowed',
                'borrowed_at' => now(),
                'due_at'      => now()->addDays($days),
            ]);

            foreach ($items as $item) {
                $book = $this->bookService->getBookForLoan($item['book_id']);

                if (!$book) {
                    throw new \Exception("Buku tidak ditemukan: {$item['book_id']}");
                }

                if (!$this->bookService->isStockAvailable($item['book_id'], $item['quantity'])) {
                    throw new InsufficientStockException(
                        $book['title'], $book['stock'], $item['quantity']
                    );
                }

                LoanDetail::create([
                    'loan_id'    => $loan->id,
                    'book_id'    => $book['id'],
                    'book_title' => $book['title'],   // snapshot
                    'book_isbn'  => $book['isbn'],    // snapshot
                    'quantity'   => $item['quantity'],
                ]);

                $this->bookService->reduceStock($item['book_id'], $item['quantity']);
            }

            $this->memberService->markAsBorrowing($memberId);

            return $loan->load('details');
        });

        // Event ditembak SETELAH commit, supaya listener tidak jalan kalau transaksi gagal
        event(new BookBorrowed($loan));

        return $loan;
    }
}