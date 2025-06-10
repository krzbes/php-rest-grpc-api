<?php

namespace App\Infrastructure\Grpc\Service;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Service\AuthorService as AppAuthorService;
use App\Infrastructure\Grpc\Authentication\JwtAuthenticator;
use App\Infrastructure\Grpc\Mapper\GrpcAuthorMapper;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Schema\Author;
use Schema\AuthorServiceInterface;
use Schema\CreateAuthorRequest;
use Schema\DefaultAuthorResponse;
use Schema\DeleteAuthorRequest;
use Schema\GetAuthorRequest;
use Schema\GetAuthorResponse;
use Schema\ListAuthorsRequest;
use Schema\ListAuthorsResponse;
use Schema\UpdateAuthorRequest;
use Spiral\RoadRunner\GRPC;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class AuthorService implements AuthorServiceInterface
{

    public function __construct(
        private readonly AppAuthorService $authorService,
        private readonly GrpcAuthorMapper $authorMapper,
        private readonly JwtAuthenticator $jwtAuthenticator
    ) {
    }


    /**
     * @inheritDoc
     */
    public function GetAuthor(GRPC\ContextInterface $ctx, GetAuthorRequest $in): GetAuthorResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);
        $id = $in->getId();
        if (!$id) {
            throw new GRPCException('No id provided', StatusCode::INVALID_ARGUMENT);
        }

        try {
            $result = $this->authorService->fetchAuthor($id);
        } catch (ValidationException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::INVALID_ARGUMENT);
        } catch (EntityNotFoundException $e) {
            throw new GRPCException($e->getMessage(), StatusCode::NOT_FOUND);
        }

        $response = new GetAuthorResponse();
        $response->setAuthor($this->authorMapper->fromDomain($result));

        return $response;
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
    public function ListAuthors(GRPC\ContextInterface $ctx, ListAuthorsRequest $in): ListAuthorsResponse
    {
        $this->jwtAuthenticator->authenticate($ctx);

        $authors = new RepeatedField(GPBType::MESSAGE, Author::class);
        $response = new ListAuthorsResponse();
        foreach ($this->authorService->fetchAllAuthors() as $author) {
            $authors[] = $this->authorMapper->fromDomain($author);
        }
        $response->setAuthor($authors);
        return $response;
    }
}