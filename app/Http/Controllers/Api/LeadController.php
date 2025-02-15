<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\LeadRepository;
use App\Http\Requests\LeadRequest;

class LeadController extends Controller
{
    public function __construct(private LeadRepository $repository)
    {}

    public function index()
    {
        return $this->repository->gets();
    }

    public function show(string $id)
    {
        return $this->repository->get($id);
    }

    public function store(LeadRequest $request)
    {
        return $this->repository->store($request);
    }

    public function update(LeadRequest $request, string $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
