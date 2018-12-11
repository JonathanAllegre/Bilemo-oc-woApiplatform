<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get(
     *     path = "/api/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={"Default", "users":{"Default"}})
     */
    public function showAction(User $user, UserService $userService)
    {
        $customer       = $this->getUser();
        $representation = $userService->isUserHadCurrentCustomer($user, $customer);

        if (false === $representation) {
            throw new NotFoundHttpException('User not Found');
        }

        return $user;
    }

    /**
     * @Rest\Get(
     *     path = "/api/users",
     *     name = "app_user_list",
     * )
     *
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="desc",
     *     description="Sort Order (asc or desc)"
     * )
     *
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="5",
     *     description="Max number of products per page"
     * )
     *
     *
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The page number"
     * )
     *
     * @Rest\View(serializerGroups={"Default", "users":{"Default"}})
     */
    public function listAction(ParamFetcherInterface $paramFetcher, UserService $userService)
    {
        $customer = $this->getUser();

        $paginatedCollection = $userService->showUserList(
            $paramFetcher->get('limit'),
            $paramFetcher->get('order'),
            $paramFetcher->get('page'),
            $customer
        );

        return $paginatedCollection;
    }

    /**
     * @Rest\Post("/api/users", name="app_user_add")
     * @Rest\View(StatusCode = 201, serializerGroups={"create"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function addAction(User $user, ConstraintViolationListInterface $violations, UserService $userService)
    {
        if (count($violations)) {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $userService->addUser($user, $this->getUser());

        return [
            'code'    => Response::HTTP_CREATED,
            'message' => "User Created",
            'url'     => $this->generateUrl('app_user_show', [
                'id' => $user->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ];
    }

    /**
     * @Rest\Delete(
     *     path = "/api/users/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function deleteAction(User $user, UserService $userService)
    {
        if (!$userService->isUserHadCurrentCustomer($user, $this->getUser())) {
            throw new NotFoundHttpException('User not Found');
        }

        $userService->deleteUser($user);

        return [
            'code'    => Response::HTTP_OK,
            'message' => "User Deleted",
        ];
    }
}
