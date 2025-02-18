<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct(private UserRepository $repository)
    {}

    /**
     * @LRDparam role   string|in:ADMIN,COUNSELOR
     * @LRDparam status string|in:active,inactive
     * @LRDparam search string
     * @LRDparam fields string
     * @LRDparam relations string
     * @LRDparam get_all int|in:0,1
     */
    public function index()
    {
        return $this->repository->gets();
    }

    /**
     * @LRDparam fields string
     * @LRDparam relations string
     */
    public function show(string $id)
    {
        return $this->repository->get($id);
    }

    public function store(UserRequest $request)
    {
        return $this->repository->store($request);
    }

    public function update(UserRequest $request, string $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
