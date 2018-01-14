<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 14/01/2018
 * Time: 13:35
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ApiController
{
    /**
     * @Route /
     */
    public function index( )
    {
        return new JsonResponse(
            [
                "WelcomeMessage"=>"Welcome to the Customer API",
                "Version" => "v1.0.0",
                "Available Functions" => "none"
            ]
        );
    }
}