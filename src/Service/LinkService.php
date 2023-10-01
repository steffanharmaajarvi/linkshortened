<?php

namespace App\Service;

use App\DTO\ShortenedLinkDTO;
use App\Entity\Link;
use App\Repository\LinkRepository;

class LinkService
{

    public function __construct(
        private readonly string $domain,
        private readonly LinkRepository $linkRepository,
    ) {}

    public function create(string $url): ShortenedLinkDTO
    {
        $link = new Link();
        $link->setCreatedAt(new \DateTime());
        $link->setToken($this->generateToken());
        $link->setUrl($url);

        $this->linkRepository->persist($link);

        return new ShortenedLinkDTO(
            implode('/', [
                $this->domain,
                $link->getToken()
            ])
        );
    }

    private function generateToken(int $length = 7): string
    {
        return substr(md5(time()), 0, $length - 1);
    }

}