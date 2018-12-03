<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path = "/api/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function showAction(User $user)
    {
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
     * @Rest\View()
     */
    public function listAction(ParamFetcherInterface $paramFetcher, UserService $userService)
    {
        $customer = $this->getUser();
        $params = [
            'limit' => $paramFetcher->get('limit'),
            'order' => $paramFetcher->get('order'),
            'page'  => $paramFetcher->get('page'),
        ];

        $paginatedCollection = $userService->showUserList($params, $customer);

        return $paginatedCollection;
    }

    public function addAction()
    {
        //TODO: Add Action
    }
}
