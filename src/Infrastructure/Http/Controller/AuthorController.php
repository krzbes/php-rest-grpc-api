<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Service\AuthorService;
use App\Infrastructure\Http\Mapper\HttpAuthorMapper;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthorController
{
    public function __construct(
        private readonly AuthorService $authorService,
        private readonly HttpAuthorMapper $authorMapper
    ) {
    }

    public function getAuthor(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->parseBody($request);

        if (!isset($data['id'])) {
            return new  Response(400, [], 'No id');
        }

        try {
            $result = $this->authorService->fetchAuthor($data['id']);
        } catch (ValidationException $e) {
            return new  Response(400, [], $e->getMessage());
        } catch (EntityNotFoundException $e) {
            return new  Response(404, [], $e->getMessage());
        }
        return new Response(200, [], json_encode($this->authorMapper->toArray($result), JSON_THROW_ON_ERROR));
    }


    public function listAuthors(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->authorService->fetchAllAuthors() as $author) {
            $songs[] = $this->authorMapper->toArray($author);
        }
        return new Response(200, [], json_encode($songs, JSON_THROW_ON_ERROR));
    }

    private function parseBody(ServerRequestInterface $request): array
    {
        return json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}