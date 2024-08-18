<?php

namespace Gmo\Framework\Http;

class Response
{
    public function __construct(
        private string $content,
        private int $status = 200,
        private array $headers = [],
    ) {
        http_response_code($this->status);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value));
        }
        echo $this->content;
    }

    public function setContent(mixed $content): Response
    {
        $this->content = $content;

        return $this;
    }
}
