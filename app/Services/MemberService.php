<?php

namespace App\Services;

use App\Exceptions\ModelServiceException;
use App\Http\Requests\MemberCreateRequest;
use App\Http\Requests\MemberEditRequest;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class MemberService
{
    /**
     * @return array
     */
    public function paginate(): array
    {
        $request = request();

        $recordsTotal = Member::query()->select(DB::raw('count(*) count'))->value('count');

        $members = Member::query()
            ->select(
                'id',
                'first_name',
                'last_name',
                'email',
                'info',
                'image_path',
                'is_active',
                'created_at'
            )
            ->offset($request->input('start', 0))
            ->limit($request->input('length', 10))
            ->get();

        $response = [];
        $response['draw'] = $request->input('draw', 1);
        $response['data'] = $members;
        $response['recordsTotal'] = $recordsTotal;
        $response['recordsFiltered'] = $recordsTotal;

        return $response;
    }

    /**
     * @param MemberCreateRequest $request
     * @return Member
     * @throws ModelServiceException
     */
    public function store(MemberCreateRequest $request): Member
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('member_image')) {
                $location = '/members';
                Storage::disk('public')->makeDirectory($location); // patch for flysystem permission bug
                $data['image_path'] = $request->file('member_image')->store($location, 'public');
            }

            return Member::query()->create($data);
        } catch (\Throwable $e) {
            throw new ModelServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param MemberEditRequest $request
     * @param Member $member
     * @return void
     * @throws ModelServiceException
     */
    public function update(MemberEditRequest $request, Member $member): void
    {
        try {
            $member->update($request->validated());
        } catch (\Throwable $e) {
            throw new ModelServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
