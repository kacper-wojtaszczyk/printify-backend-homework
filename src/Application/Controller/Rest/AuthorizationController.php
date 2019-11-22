<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Controller\Rest;

use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/authorize", methods={"POST"})
 * @codeCoverageIgnore
 */
final class AuthorizationController
{
    /**
     * @SWG\Tag(name="Autoryzacja")
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="",
     *     @SWG\Schema (ref="#/definitions/credentials")
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns JWT token",
     *     @SWG\Schema (ref="#/definitions/authentication")
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request",
     *     @SWG\Schema (ref="#/definitions/client_error")
     * )
     *
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @SWG\Schema (ref="#/definitions/client_error")
     * )
     */
    public function __invoke(): void
    {
        throw new NotAcceptableHttpException();
    }
}
