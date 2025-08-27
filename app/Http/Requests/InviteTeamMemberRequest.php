<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Contracts\RequestWithDto;
use App\DataTransferObjects\InviteTeamMemberDto;
use App\Enums\Policies;
use App\Models\TeamInvite;
use App\Models\TeamRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

final class InviteTeamMemberRequest extends FormRequest implements RequestWithDto
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::forUser($this->user())
            ->allows(Policies::CREATE->value, [TeamInvite::class, $this->route('id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'role' => [
                'required',
                Rule::exists(TeamRole::tableName(), TeamRole::primaryKeyName()),
            ],
        ];
    }

    public function dto(): InviteTeamMemberDto
    {
        return new InviteTeamMemberDto(
            team: $this->route('id'),
            email: $this->validated('email'),
            role: $this->validated('role')
        );
    }
}
