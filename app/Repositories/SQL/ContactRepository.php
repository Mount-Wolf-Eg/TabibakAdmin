<?php

namespace App\Repositories\SQL;

use App\Models\Contact;
use App\Repositories\Contracts\ContactContract;

class ContactRepository extends BaseRepository implements ContactContract
{
    /**
     * ContactRepository constructor.
     * @param Contact $model
     */
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }
}
