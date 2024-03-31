<?php
namespace App\Repositories\SQL;

use App\Models\Note;
use App\Repositories\Contracts\NoteContract;

class NoteRepository extends BaseRepository implements NoteContract
{
    /**
     * NoteRepository constructor.
     * @param Note $model
     */
    public function __construct(Note $model)
    {
        parent::__construct($model);
    }
}

