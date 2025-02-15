<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct(private UserRepository $repository)
    {}

    public function index()
    {
        return $this->repository->gets();
    }

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
