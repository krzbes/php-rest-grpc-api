<?php

namespace App\Infrastructure\Grpc;

use Schema\AuthorServiceInterface;
use Schema\CreateAuthorRequest;
use Schema\DefaultAuthorResponse;
use Schema\DeleteAuthorRequest;
use Schema\GetAuthorRequest;
use Schema\GetAuthorResponse;
use Schema\ListAuthorsRequest;
use Schema\UpdateAuthorRequest;
use Spiral\RoadRunner\GRPC;

class AuthorService implements AuthorServiceInterface
{

    /**
     * @inheritDoc
     */
    public function GetAuthor(GRPC\ContextInterface $ctx, GetAuthorRequest $in): GetAuthorResponse
    {
        return new GetAuthorResponse();
    }

    /**
     * @inheritDoc
     */
    public function CreateAuthor(GRPC\ContextInterface $ctx, CreateAuthorRequest $in): DefaultAuthorResponse
    {
        return new DefaultAuthorResponse();
    }

    /**
     * @inheritDoc
     */
    public function DeleteAuthor(GRPC\ContextInterface $ctx, DeleteAuthorRequest $in): DefaultAuthorResponse
    {
        return new DefaultAuthorResponse();
    }

    /**
     * @inheritDoc
     */
    public function UpdateAuthor(GRPC\ContextInterface $ctx, UpdateAuthorRequest $in): DefaultAuthorResponse
    {
        return new DefaultAuthorResponse();

    }

    /**
     * @inheritDoc
     */
    public function ListAuthors(GRPC\ContextInterface $ctx, ListAuthorsRequest $in): GetAuthorResponse
    {
        return new GetAuthorResponse();
    }
}