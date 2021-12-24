<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberCreateRequest;
use App\Http\Requests\MemberEditRequest;
use App\Models\Member;
use App\Services\MemberService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MemberController extends Controller
{
    /**
     * @var MemberService
     */
    private $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * Returns a listing of the members in paginated DataTable json format.
     *
     * @return array
     * @throws Exception MemberService $memberService
     */
    public function list(): array
    {
        return $this->memberService->paginate();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->list();
        }

        return view('members.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create()
    {
        $data['member'] = new Member;
        $data['formAction'] = route('members.store');

        return view('members.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MemberCreateRequest $request
     * @return Response
     */
    public function store(MemberCreateRequest $request): Response
    {
        $errorMessage = 'User Created successfully';
        $errorCode = 200;

        try {
            $validatedData = $request->validated();

            if ($request->hasFile('member_image')) {
                $validatedData['image_path'] = $request->member_image->store('public/members');
                $validatedData['image_path'] = str_replace('public/members/', 'storage/members/', $validatedData['image_path']);
            }

            Member::create($validatedData);
        } catch (QueryException $e) {
            $errorMessage = json_encode($e->errorInfo);
            $errorCode = 400;

            if ($e->errorInfo[1] === 1062) {
                $errorMessage = explode(" for ", $e->errorInfo[2])[0];
                $errorCode = 419;
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorCode = 500;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Member $member
     * @return Response|View|Factory
     */
    public function show(Request $request, Member $member)
    {
        if ($request->expectsJson()) {
        }

        $data['member'] = $member;

        return view('members.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Member $member
     * @return View|Factory
     */
    public function edit(Member $member)
    {
        $data['member'] = $member;
        $data['formAction'] = route('members.update', $member->id);

        return view('members.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MemberEditRequest $request
     * @param Member $member
     * @return Response
     */
    public function update(MemberEditRequest $request, Member $member): Response
    {
        $errorMessage = 'User Updated successfully';
        $errorCode = 200;

        try {
            $member->update($request->validated());
        } catch (QueryException $e) {
            $errorMessage = json_encode($e->errorInfo);
            $errorCode = 400;

            if ($e->errorInfo[1] === 1062) {
                $errorMessage = explode(" for ", $e->errorInfo[2])[0];
                $errorCode = 419;
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response($errorMessage, $errorCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Member $member
     * @return Response
     */
    public function destroy(Member $member): Response
    {
        $errorMessage = 'User Deleted successfully';
        $errorCode = 200;

        try {
            $member->delete();
        } catch (QueryException $e) {
            $errorMessage = json_encode($e->errorInfo);
            $errorCode = 400;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return response($errorMessage, $errorCode);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showTable(Request $request): Response
    {
        $id = $request->input('id', 0);
        $table = "
            <table class='table table-striped'>
            <tr>
                <td>something: $id</td>
                <td>something</td>
                <td>something</td>
                <td>something</td>
            </tr>
            </table>
        ";

        return response($table);
    }
}
