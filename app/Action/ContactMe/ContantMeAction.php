<?php

namespace App\Action\ContactMe;

use App\Dto\ContactMe\ContantMeDto;
use App\Mail\ContactMe;
use Illuminate\Support\Facades\Mail;

final class ContantMeAction
{
    public function __invoke(ContantMeDto $dto): array
    {
        Mail::queue(new ContactMe($dto->name, $dto->phone));

        return [];
    }
}
