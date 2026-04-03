<?php

namespace Am2Tec\LaravelBrazilianCeps\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
use Am2Tec\LaravelBrazilianCeps\Entities\CepEntity;

/**
 * @mixin CepEntity
 */
class CepResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'city'         => $this->city,
            'cep'          => $this->cep,
            'street'       => $this->street,
            'state'        => $this->state,
            'uf'           => $this->uf,
            'neighborhood' => $this->neighborhood,
            'number'       => $this->whenNotNull($this->number),
            'complement'   => $this->whenNotNull($this->complement)
        ];
    }
}
