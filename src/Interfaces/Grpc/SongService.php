<?php

namespace App\Interfaces\Grpc;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\FailedToSaveSongException;
use App\Application\Exception\ValidationException;
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
use \App\Application\Service\SongService as AppSongService;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class SongService implements SongServiceInterface
{
    public function __construct(
        private readonly AppSongService $songService,
        private readonly GrpcSongMapper $songMapper
    ) {
    }

    public function GetSong(ContextInterface $ctx, GetSongRequest $in): GetSongResponse
    {
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
        return new DefaultSongResponse();
    }

    public function UpdateSong(ContextInterface $ctx, UpdateSongRequest $in): DefaultSongResponse
    {
        return new DefaultSongResponse();
    }

    public function ListSongs(ContextInterface $ctx, ListSongRequest $in): ListSongResponse
    {
        $songs = new RepeatedField(GPBType::MESSAGE, Song::class);
        $response = new ListSongResponse();
        foreach ($this->songService->fetchAllSongs() as $song) {
            $songs[] = $this->songMapper->fromDomain($song);
        }
        $response->setSong($songs);
        return $response;
    }
}