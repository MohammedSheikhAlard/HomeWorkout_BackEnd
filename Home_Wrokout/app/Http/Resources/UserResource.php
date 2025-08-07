<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'tall' => $this->tall,
            'weight' => $this->weight,
            'gender' => $this->gender,
            'BMI' => $this->BMI,
            'target_calories' => $this->target_calories,
            'date_of_birth' => $this->date_of_birth,
            'reminder' => $this->reminder,
            'level_id' => $this->level_id
        ];
    }
}
