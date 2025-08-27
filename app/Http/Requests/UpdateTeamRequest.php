<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\RequestWithDto;
use App\DataTransferObjects\UpdateTeamDto;
use App\Enums\Policies;
use App\Models\Team;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class UpdateTeamRequest extends FormRequest implements RequestWithDto
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::forUser($this->user())
            ->allows(Policies::UPDATE->value, [Team::class, $this->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:20',
            ],
        ];
    }

    public function dto(): UpdateTeamDto
    {
        return new UpdateTeamDto(
            identifier: $this->route('id'),
            name: $this->validated('name')
        );
    }
}
