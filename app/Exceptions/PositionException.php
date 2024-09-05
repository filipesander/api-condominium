<?php

namespace App\Exceptions;

use Exception;

class PositionException extends Exception
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

    public function report(){ \Log::channel('positions')->{$this->level}("positions: {$this->message}: {$this->description}", $this->logPayload ?? $this->payload); }

    public function render(){ return response()->json(['message' => $this->message, 'payload' => $this->payload, 'status' => $this->status ], $this->status); }
}
