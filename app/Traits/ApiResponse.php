<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse(
        ?string $message = null,
        mixed $data = null,
        int $status = 200,
        bool $withOutWrapper = false
    ): JsonResponse {
        $response = [
            // "status" => (string) $status,
            "message" => $message,
        ];

        if (is_object($data) && method_exists($data, "response")) {
            $data = $data->response()->getData(true);
        } else {
            $data = ["data" => $data];
        }

        $response = array_merge($response, $data);

        if (
            $data === null
            || (is_array($data) && $data === [])
        ) {
            unset($response["data"]);
        }

        if ($response["message"] == null) {
            unset($response["message"]);
        }

        if ($withOutWrapper) {
            // Filter out the "status" and "message" keys
            $filteredRes = array_filter($response, function ($key) {
                return !in_array($key, ["status", "message"]);
            }, ARRAY_FILTER_USE_KEY);

            // Count how many keys are left after filtering
            if (count($filteredRes) > 1) {
                // If there is more than one key, return the whole filtered array
                $response = $filteredRes;
            } else {
                // If only one key (i.e., 'data'), return just the value of that key
                $response = $filteredRes["data"] ?? [];
            }
        }

        return response()->json($response, $status);
    }

    public function errorResponse(string $message, mixed $errors = null, int $status = 500): JsonResponse
    {
        $response = [
            // "status" => $status,
            "message" => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
