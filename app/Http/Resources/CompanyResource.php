<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // Включает description logo created_at и average_rating только если они доступны
            'description' => $this->when(isset($this->description), $this->description),
            'logo' => $this->when(isset($this->logo), $this->logo), 
            'created_at' => $this->when(isset($this->created_at), $this->created_at),
            'average_rating' => $this->when(isset($this->average_rating), $this->average_rating)
        ];
    }
}
