<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path = "/api/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={"detail"})
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
     * @Rest\View(serializerGroups={"Default", "users":{"Default", "list"}})
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
     * @Rest\Post("/api/users")
     * @Rest\View(StatusCode = 201, serializerGroups={"create"})
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function addAction(User $user)
    {
        $user->setCustomer($this->getUser());

        $em = $this->getDoctrine()->getManager();

        $em->persist($user);
        $em->flush();

        return $user;

        //TODO: Refacto & add action
    }
}
