<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\LeadMaintainerRepository;
use App\Http\Requests\LeadMaintainerRequest;

class LeadMaintainerController extends Controller
{
    public function __construct(private LeadMaintainerRepository $repository)
    {}

    public function index()
    {
        return $this->repository->gets();
    }

    public function show(string $id)
    {
        return $this->repository->get($id);
    }

    public function store(LeadMaintainerRequest $request)
    {
        return $this->repository->store($request);
    }

    public function update(LeadMaintainerRequest $request, string $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
