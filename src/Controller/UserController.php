<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends FOSRestController
{
    /**
     * @SWG\Get(
     *     tags={"User"},
     *     summary="Get The Detail of User",
     *     description="Return one user",
     *     @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @Model(
     *              type=User::class,
     *              groups={"Default", "users":{"Default"}}
     *          )
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Not Found",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=404
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User Not Found"
     *              )
     *          )
     *     ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=401
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example={"Expired JWT Token", "Invalid JWT Token"}
     *              )
     *          )
     *     ),
     * )
     *
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
     * @SWG\Get(
     *     tags={"User"},
     *     summary="Get the list of users",
     *     description="Return a paginated collection os users",
     *     @SWG\Response(
     *          response=200,
     *          description="Success",
     *          @SWG\Schema(
     *              @Model(
     *                  type=User::class,
     *                  groups={"Default", "users":{"Default"}}
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=401
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example={"Expired JWT Token", "Invalid JWT Token"}
     *              )
     *          )
     *     )
     * )
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
     * @SWG\Post(
     *     tags={"User"},
     *     summary="Add User",
     *     description="Add an User",
     *     @SWG\Parameter(
     *          in="body",
     *          required=true,
     *          name="body",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="last_name",
     *                  type="string",
     *                  example="Durand"
     *              ),
     *              @SWG\Property(
     *                  property="first_name",
     *                  type="string",
     *                  example="Martin"
     *              ),
     *              @SWG\Property(
     *                  property="email",
     *                  type="string",
     *                  example="martin@durand.hotmail.com"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string",
     *                  example="le-password"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Created",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=201
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User created"
     *              ),
     *              @SWG\Property(
     *                  property="url",
     *                  type="string",
     *                  example="http://bilemo.com/api/users/{userCreatedId}"
     *              )
     *          )
     *      ),
     *      @SWG\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=401
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example={"Expired JWT Token", "Invalid JWT Token"}
     *              )
     *          )
     *     )
     *
     * )
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
     * @SWG\Delete(
     *     tags={"User"},
     *     summary="Delete an user",
     *     description="Delete an user by Id",
     *     @SWG\Response(
     *          response=200,
 *              description="Success",
     *          @SWG\Schema(
     *               @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=200
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User Deleted"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="Not Found",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="code",
     *                  type="integer",
     *                  example=404
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  example="User Not Found"
     *              )
     *          )
     *     )
     * )
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
