<?php

namespace App\Action\ContactMe;

use App\Dto\ContactMe\SendMailDto;
use App\Mail\ContactMe;
use Illuminate\Support\Facades\Mail;

final class SendMailAction
{
    public function __invoke(SendMailDto $dto): array
    {
        Mail::queue(new ContactMe($dto->name, $dto->phone));

        return [];
    }
}
