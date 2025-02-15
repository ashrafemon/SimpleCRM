<?php

if (! function_exists('serverError')) {
    function serverError($exception)
    {
        return response(['status' => 'serverError', 'statusCode' => 500, 'message' => $exception->getMessage()], 500);
    }
}

if (! function_exists('validateError')) {
    function validateError($data, $override = false)
    {
        $errors       = [];
        $errorPayload = ! $override ? $data->getMessages() : $data;
        foreach ($errorPayload as $key => $value) {
            $errors[$key] = $value[0];
        }
        return response(['status' => 'validateError', 'statusCode' => 422, 'data' => $errors], 422);
    }
}

if (! function_exists('messageResponse')) {
    function messageResponse($message = 'No data found', $statusCode = 404, $status = 'error')
    {
        return response(['status' => $status, 'statusCode' => $statusCode, 'message' => $message], $statusCode);
    }
}

if (! function_exists('entityResponse')) {
    function entityResponse($data = null, $statusCode = 200, $status = 'success', $message = null)
    {
        $payload = ['status' => $status, 'statusCode' => $statusCode, 'data' => $data];
        if ($message) {
            $payload['message'] = $message;
        }
        return response($payload, $statusCode);
    }
}

if (! function_exists('tenantRequestInputs')) {
    function tenantRequestInputs(): array
    {
        $payload = [];
        if (request()->has('company_id') && request()->input('company_id')) {
            $payload['company_id'] = request()->input('company_id');
        }
        if (request()->has('branch_id') && request()->input('branch_id')) {
            $payload['branch_id'] = request()->input('branch_id');
        }
        return $payload;
    }
}

if (! function_exists('paginate')) {
    function paginate($payload): array
    {
        return [
            'data'         => $payload['data'],
            'current_page' => $payload['current_page'],
            'last_page'    => $payload['last_page'],
            'per_page'     => $payload['per_page'],
            'from'         => $payload['from'],
            'to'           => $payload['to'],
            'total'        => $payload['total'],
        ];
    }
}
