<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'table_name'=> $this-> table_name,
            'other_name'=> $this-> other_name,
            'floor_id'=> $this->floor_id,
            'is_open'=> $this->is_open,
            'is_discontinued'=> $this->is_discontinued,
            'is_deleted'=> $this->is_deleted,
            'is_updated'=> $this->is_updated,
            'location_id'=> $this->location_id,
            'modified_by'=> $this-> modified_by
        ];
    }
}
