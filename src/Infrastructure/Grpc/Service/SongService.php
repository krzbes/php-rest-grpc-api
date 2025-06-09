<?php

namespace App\Infrastructure\Grpc\Service;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\FailedToSaveSongException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Service\SongService as AppSongService;
use App\Infrastructure\Grpc\Authentication\JwtAuthenticator;
use App\Infrastructure\Grpc\Mapper\GrpcSongMapper;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Schema\CreateSongRequest;
use Schema\DefaultSongResponse;
use Schema\DeleteSongRequest;
use Schema\GetSongRequest;
use Schema\GetSongResponse;
use Schema\ListSongRequest;
use Schema\ListSongResponse;
use Schema\Song;
use Schema\SongServiceInterface;
use Schema\UpdateSongRequest;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class SongService implements SongServiceInterface
{
    public function __construct(
        private readonly AppSongService $songService,
        private readonly GrpcSongMapper $songMapper,
        private readonly JwtAuthenticator $jwtAuthenticator
    ) {
    }

    public function GetSong(ContextInterface $ctx, GetSongRequest $in): GetSongResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $id = $in->getId();
        if (!$id) {
            throw new GRPCException('No id provided', StatusCode::INVALID_ARGUMENT);
        }

        try {
            $result = $this->songService->fetchSong($id);
        } catch (ValidationException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::INVALID_ARGUMENT);
        } catch (EntityNotFoundException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::NOT_FOUND);
        }


        $response = new GetSongResponse();
        $response->setSong($this->songMapper->fromDomain($result));
        return $response;
    }

    public function CreateSong(ContextInterface $ctx, CreateSongRequest $in): DefaultSongResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $title = $in->getTitle();
        if (!$title) {
            throw new GRPCException('No title provided', StatusCode::INVALID_ARGUMENT);
        }

        $releaseYear = $in->getReleaseYear();
        if (!$releaseYear) {
            throw new GRPCException('No Release Year provided', StatusCode::INVALID_ARGUMENT);
        }
        $authorId = $in->getAuthorId();
        if (!$authorId) {
            throw new GRPCException('No Author Id provided', StatusCode::INVALID_ARGUMENT);
        }
        try {
            $this->songService->createSong($title, $releaseYear, $authorId);
        } catch (ValidationException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::INVALID_ARGUMENT);
        } catch (FailedToSaveSongException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::ABORTED);
        }

        $response = new DefaultSongResponse();
        $response->setMessage("song created");
        return $response;
    }

    public function DeleteSong(ContextInterface $ctx, DeleteSongRequest $in): DefaultSongResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $id = $in->getId();
        if (!$id) {
            throw new GRPCException('No id provided', StatusCode::INVALID_ARGUMENT);
        }
        try {
            $this->songService->deleteSong($id);
        } catch (EntityNotFoundException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::NOT_FOUND);
        } catch (ValidationException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::INVALID_ARGUMENT);
        }
        return new DefaultSongResponse();
    }

    public function UpdateSong(ContextInterface $ctx, UpdateSongRequest $in): DefaultSongResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $id = $in->getId();
        if (!$id) {
            throw new GRPCException('No id provided', StatusCode::INVALID_ARGUMENT);
        }


        $title = $in->getTitle();
        if (!$title) {
            throw new GRPCException('No title provided', StatusCode::INVALID_ARGUMENT);
        }

        $releaseYear = $in->getReleaseYear();
        if (!$releaseYear) {
            throw new GRPCException('No Release Year provided', StatusCode::INVALID_ARGUMENT);
        }

        try {
            $this->songService->updateSong($id, $title, $releaseYear);
        } catch (ValidationException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::INVALID_ARGUMENT);
        } catch (EntityNotFoundException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::NOT_FOUND);
        } catch (FailedToSaveSongException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::ABORTED);
        }
        return new DefaultSongResponse();
    }

    public function ListSongs(ContextInterface $ctx, ListSongRequest $in): ListSongResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $songs = new RepeatedField(GPBType::MESSAGE, Song::class);
        $response = new ListSongResponse();
        foreach ($this->songService->fetchAllSongs() as $song) {
            $songs[] = $this->songMapper->fromDomain($song);
        }
        $response->setSong($songs);
        return $response;
    }
}