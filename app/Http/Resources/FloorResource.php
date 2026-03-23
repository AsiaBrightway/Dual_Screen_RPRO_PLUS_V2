<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FloorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
        'floor_name'=> $this->floor_name,
        'other_name' => $this->other_name,
        'floor_code'=> $this->floor_code,
        'is_discontinued'=> $this->is_discontinued,
        'is_deleted'=> $this->is_deleted,
        'location_id'=> $this->location_id,
        'is_updated'=> $this->is_updated,
        'modified_by'=> $this->modified_by
        ];
    }
}
