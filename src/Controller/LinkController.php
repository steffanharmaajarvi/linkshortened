<?php

namespace App\Controller;

use App\Repository\LinkRepository;
use App\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LinkController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly LinkService $linkService,
        private readonly LinkRepository $linkRepository,
    ) {

    }
    #[Route('/create', methods: ['POST'])]
    public function createLink(Request $request): JsonResponse
    {

        $requestData = $request->toArray();

        $violations = $this->validator->validate(
            $requestData,
            new Collection([
                'link' => new Sequentially([
                    new NotBlank(message: 'Link is required'),
                    new Url(message: 'Link must be a valid URL'),
                ]),
            ])
        );

        if ($violations->count() > 0) {
            return new JsonResponse([
                'error' => $violations->get(0)->getMessage()
            ], status: 400);
        }

        $link = $requestData['link'];

        return new JsonResponse([
            'link' => $this->linkService->create($link)->link
        ]);
    }

    #[Route('/{token}', methods: ['GET'])]
    public function openLink(string $token): Response
    {
        $link = $this->linkRepository->getLinkByToken($token);

        if ($link === null) {
            return new JsonResponse([
                'error' => 'Link not found'
            ], status: 404);
        }

        $response = new RedirectResponse($link->getUrl(), 302);

        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }

}