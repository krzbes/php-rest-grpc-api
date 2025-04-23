<?php

namespace App\Interfaces\Grpc;

use Schema\CreateSongRequest;
use Schema\DefaultSongResponse;
use Schema\DeleteSongRequest;
use Schema\GetSongRequest;
use Schema\GetSongResponse;
use Schema\ListSongRequest;
use Schema\SongServiceInterface;
use Schema\UpdateSongRequest;
use Spiral\RoadRunner\GRPC\ContextInterface;

class SongService implements SongServiceInterface
{

    public function GetSong(ContextInterface $ctx, GetSongRequest $in): GetSongResponse
    {
        return new GetSongResponse();
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