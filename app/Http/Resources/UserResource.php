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
        // return parent::toArray($request);
        return[
            'id' => $this->id,
            'name' => $this-> name,
            'username'=> $this -> username,
            'user_role_id'=> $this ->user_role_id,
            'employee_id'=> $this ->employee_id,
            'login_status'=> $this ->login_status,
            'location_id'=> $this ->location_id,
            'is_discontinued'=> $this ->is_discontinued,
            'modified_by'=> $this ->modified_by,
            'password'=> $this ->password
        ];
    }
}
