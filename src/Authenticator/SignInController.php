<?php


namespace Authenticator;


use Core\JsonResponse;
use Exception;
use Psr\Http\Message\ServerRequestInterface;

class SignInController
{

    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $input = new Input($request);
        $input->validate();

        return $this->authentication->authenticate($input->email(), $input->password())
            ->then(function (string $jwt) {
                return JsonResponse::ok(['token' => $jwt]);
            }
        )
            ->otherwise(
             function (BadCredentials $exception) {
                 return JsonResponse::unauthorised('Bad Credentials');
             }
         )
            ->otherwise(
             function (UserNotFound $exception) {
                 return JsonResponse::unauthorised('User not found');
             }
         )
            ->otherwise(
                function (Exception $exception) {
                    return JsonResponse::internalServerError($exception->getMessage());
                }
            );
    }
}