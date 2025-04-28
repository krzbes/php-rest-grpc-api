<?php

namespace App\Interfaces\Grpc;

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
use \App\Application\Services\SongService as AppSongService;

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
        if( !$id || $id < 0){
            return new GetSongResponse();
        }

        $result = $this->songService->fetchSong($in->getId());


        if (!$result) {
            return new GetSongResponse();
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