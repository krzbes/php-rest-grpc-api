<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Service\SongService as AppSongService;
use App\Infrastructure\Http\Mapper\HttpSongMapper;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SongController
{
    public function __construct(
        private readonly AppSongService $songService,
        private readonly HttpSongMapper $httpSongMapper
    ) {
    }

    public function getSong(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->parseBody($request);

        if (!isset($data['id'])) {
            return new  Response(500, [], 'No id');
        }

        try {
            $result = $this->songService->fetchSong($data['id']);
        } catch (ValidationException $e) {
            return new  Response(400, [], $e->getMessage());
        } catch (EntityNotFoundException $e) {
            return new  Response(404, [], $e->getMessage());
        }
        return new Response(200, [], json_encode($this->httpSongMapper->toArray($result), JSON_THROW_ON_ERROR));
    }

    private function parseBody(ServerRequestInterface $request): array
    {
        return json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}