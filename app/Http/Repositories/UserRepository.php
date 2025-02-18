<?php
namespace App\Http\Repositories;

use App\Http\Contracts\RepositoryContract;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Gate;

class UserRepository implements RepositoryContract
{
    public function gets()
    {
        try {
            $offset    = request()->input('offset') ?? 10;
            $relations = [];
            $fields    = ['id', 'name', 'email', 'phone', 'role', 'status'];

            if (! Gate::allows('view-any', User::class)) {
                return messageResponse('You are not authorized to view this resource', 403);
            }

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            $docs = User::query()
                ->with($relations)
                ->select($fields)
                ->when(request()->input('role'), fn($q) => $q->where('role', request()->input('role')))
                ->when(request()->input('status'), fn($q) => $q->where('status', request()->input('status')))
                ->when(request()->input('search'), function ($q) {
                    return $q->where(fn() => $q->where('name', 'like', '%' . request()->input('search') . '%')->orWhere('email', 'like', '%' . request()->input('search') . '%'));
                });

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
            $fields    = ['id', 'name', 'email', 'phone', 'role', 'status'];

            if (request()->has('fields') && request()->input('fields')) {
                $fields = gettype(request()->input('fields')) === 'array' ? request()->input('fields') : explode(',', request()->input('fields'));
            }

            if (request()->has('relations') && request()->input('relations')) {
                $relations = gettype(request()->input('relations')) === 'array' ? request()->input('relations') : explode(',', request()->input('relations'));
            }

            if (! $doc = User::query()->with($relations)->select($fields)->where($condition)->first()) {
                return messageResponse();
            }

            if (! Gate::allows('view', $doc)) {
                return messageResponse('You are not authorized to view this resource', 403);
            }

            return entityResponse($doc);
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function store($request)
    {
        try {
            if (! Gate::allows('create', User::class)) {
                return messageResponse('You are not authorized to view this resource', 403);
            }

            User::query()->create($request->validated());
            return messageResponse('User added successfully', 201, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function update($request, $id)
    {
        try {
            if (! $doc = User::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            if (! Gate::allows('update', $doc)) {
                return messageResponse('You are not authorized to view this resource', 403);
            }

            $doc->update($request->validated());
            return messageResponse('User updated successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }

    public function destroy($id)
    {
        try {
            if (! $doc = User::query()->where(['id' => $id])->first()) {
                return messageResponse();
            }

            if (! Gate::allows('delete', $doc)) {
                return messageResponse('You are not authorized to view this resource', 403);
            }

            $doc->delete();
            return messageResponse('User deleted successfully', 200, 'success');
        } catch (Exception $e) {
            return serverError($e);
        }
    }
}
