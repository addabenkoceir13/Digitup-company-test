<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"        => $this->id,
            "user"      => $this->user->name,
            "titre"     => $this->titre,
            "statut"    => $this->statut,
            "description"   => $this->description,
            "date_dech"     => $this->date_dech,
        ];
    }
}
