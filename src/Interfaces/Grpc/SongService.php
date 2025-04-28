<?php

namespace App\Interfaces\Grpc;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Schema\CreateSongRequest;
use Schema\DefaultSongResponse;
use Schema\DeleteSongRequest;
use Schema\GetSongRequest;
use Schema\GetSongResponse;
use Schema\ListSongRequest;
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
        return new DefaultSongResponse();
    }

    public function DeleteSong(ContextInterface $ctx, DeleteSongRequest $in): DefaultSongResponse
    {
        return new DefaultSongResponse();
    }

    public function UpdateSong(ContextInterface $ctx, UpdateSongRequest $in): DefaultSongResponse
    {
        return new DefaultSongResponse();
    }

    public function ListSongs(ContextInterface $ctx, ListSongRequest $in): GetSongResponse
    {
        return new GetSongResponse();
    }
}