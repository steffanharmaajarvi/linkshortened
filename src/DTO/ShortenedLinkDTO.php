<?php

namespace App\DTO;

class ShortenedLinkDTO
{

    public function __construct(
        public readonly string $link
    ) {}

}