<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\LeadMaintainerRepository;
use App\Http\Requests\LeadMaintainerRequest;

class LeadMaintainerController extends Controller
{
    public function __construct(private LeadMaintainerRepository $repository)
    {}

    /**
     * @LRDparam lead_id    string
     * @LRDparam user_id    string
     * @LRDparam status     string|in:ASSIGNED,IN_PROGRESS,BAD_TIMING,NOT_INTERESTED,NOT_QUALIFIED,CONVERTED
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
