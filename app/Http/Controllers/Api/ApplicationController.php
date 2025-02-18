<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ApplicationRepository;
use App\Http\Requests\ApplicationRequest;

class ApplicationController extends Controller
{
    public function __construct(private ApplicationRepository $repository)
    {}

    /**
     * @LRDparam lead_id    string
     * @LRDparam status     string|in:IN_PROGRESS,APPROVED,REJECTED
     * @LRDparam search     string
     * @LRDparam fields     string
     * @LRDparam relations  string
     * @LRDparam get_all    int|in:0,1
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

    public function store(ApplicationRequest $request)
    {
        return $this->repository->store($request);
    }

    public function update(ApplicationRequest $request, string $id)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->repository->destroy($id);
    }
}
