<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class OwnerException extends Exception
{
    /**
     * @param string $message
     * @param string $description
     * @param array $payload []
     * @param array $logPayload
     * @param string $level warning
     * @param int $status 200
     * @return void
     */
    function __construct(string $message, private ?string $description = null, private ?array $payload = [], private ?array $logPayload = null, private ?string $level = "warning", private ?int $status = 400)
    {
        $this->message = $message;
    }

    public function report()
    {
        Log::channel('owners')->{$this->level}("owners: {$this->message}: {$this->description}", $this->logPayload ?? $this->payload);
    }

    public function render()
    {
        return response()->json(['message' => $this->message, 'status' => $this->status], $this->status);
    }
}
