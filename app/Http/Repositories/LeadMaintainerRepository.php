<?php
namespace App\Http\Repositories;

use App\Http\Contracts\RepositoryContract;
use App\Models\LeadMaintainer;
use Exception;

class LeadMaintainerRepository implements RepositoryContract
{
    public function gets()
    {
        try {
            $offset    = request()->input('offset') ?? 10;
            $relations = [];
            $fields    = ['id', 'lead_id', 'user_id', 'status'];

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            $docs = LeadMaintainer::query()
                ->with($relations)
                ->select($fields)
                ->when(request()->input('lead_id'), fn($q) => $q->where('lead_id', request()->input('lead_id')))
                ->when(request()->input('user_id'), fn($q) => $q->where('user_id', request()->input('user_id')))
                ->when(request()->input('status'), fn($q) => $q->where('status', request()->input('status')));

            if (request()->has('get_all') && (int) request()->input('get_all') === 1) {
                return entityResponse($docs->get());
            }

            return entityResponse(paginate($docs->paginate($offset)->toArray()));
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function get($id)
    {
        try {
            $relations = [];
            $fields    = ['id', 'lead_id', 'user_id', 'status'];

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            if (! $doc = LeadMaintainer::query()->with($relations)->select($fields)->first()) {
                return messageResponse();
            }

            return entityResponse($doc);
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function store($request)
    {
        try {
            LeadMaintainer::query()->create($request->validated());
            return messageResponse('Lead maintainer added successfully', 201, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function update($request, $id)
    {
        try {
            if (! $doc = LeadMaintainer::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            $doc->update($request->validated());
            return messageResponse('Lead maintainer updated successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function destroy($id)
    {
        try {
            if (! $doc = LeadMaintainer::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            $doc->delete();
            return messageResponse('Lead maintainer deleted successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }
}
