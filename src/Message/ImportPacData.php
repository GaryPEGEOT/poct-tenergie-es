<?php

namespace App\Message;

class ImportPacData
{
    private int $projectId;

    public function __construct(int $projectId)
    {
        $this->projectId = $projectId;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }
}
