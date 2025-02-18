<?php
namespace App\Http\Repositories;

use App\Http\Contracts\RepositoryContract;
use App\Models\Application;
use Exception;
use Illuminate\Support\Facades\Gate;

class ApplicationRepository implements RepositoryContract
{
    public function gets()
    {
        try {
            $offset    = request()->input('offset') ?? 10;
            $relations = [];
            $fields    = ['id', 'lead_id', 'user_id', 'name', 'status'];

            if (! Gate::inspect('view-any', Application::class)->allowed()) {
                return messageResponse("Sorry, You can't access this resource", 403);
            }

            $userRole = auth()->guard('api')->check() ? auth()->guard('api')->user()->role : null;

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            $docs = Application::query()
                ->with($relations)
                ->select($fields)
                ->when(request()->input('lead_id'), fn($q) => $q->where('lead_id', request()->input('lead_id')))
                ->when($userRole === 'COUNSELOR', fn($q) => $q->where('user_id', auth()->guard('api')->id()))
                ->when(request()->input('status'), fn($q) => $q->where('status', request()->input('status')))
                ->when(request()->input('search'), fn($q) => $q->where('name', 'like', '%' . request()->input('search') . '%'));

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
            $condition = ['id' => $id];
            $relations = [];
            $fields    = ['id', 'lead_id', 'user_id', 'name', 'description', 'status'];

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            if (! $doc = Application::query()->with($relations)->select($fields)->where($condition)->first()) {
                return messageResponse();
            }

            if (! Gate::inspect('view', $doc)->allowed()) {
                return messageResponse("Sorry, You can't access this application", 403);
            }

            return entityResponse($doc);
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function store($request)
    {
        try {
            if (! Gate::inspect('create', Application::class)->allowed()) {
                return messageResponse("Sorry, You can't access this resource", 403);
            }

            Application::query()->create($request->validated());
            return messageResponse('Application added successfully', 201, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function update($request, $id)
    {
        try {
            if (! $doc = Application::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            if (! Gate::inspect('update', $doc)->allowed()) {
                return messageResponse("Sorry, You can't access this application", 403);
            }

            $doc->update($request->validated());
            return messageResponse('Application updated successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function destroy($id)
    {
        try {
            if (! $doc = Application::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            if (! Gate::inspect('delete', $doc)->allowed()) {
                return messageResponse("Sorry, You can't access this application", 403);
            }

            $doc->delete();
            return messageResponse('Application deleted successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }
}
