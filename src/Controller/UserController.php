<?php

namespace App\Controller;

use App\Entity\User;
use App\Representation\Users;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
     *
     */
    public function listAction(ParamFetcherInterface $paramFetcher, UserService $userService, SerializerInterface $serializer)
    {
        $customer = $this->getUser();

        $users = $userService->showUserList($customer);


        //$representation = new Users($users);

        $data = $serializer
            ->serialize($users, 'json', SerializationContext::create()->setGroups(array('list')));

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;


        //TODO: PARTIR SUR DE LA NON PAGINATION

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
    }
}
