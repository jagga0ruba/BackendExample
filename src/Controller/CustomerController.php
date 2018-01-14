<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 14/01/2018
 * Time: 13:45
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CustomerController
{
    /**
     * @Route("/Customer/addNew")
     */
    public function addNew( )
    {
        return new JsonResponse(
            [
                "WelcomeMessage"=>"Welcome to addNew",
                "Version" => "v1.0.0",
                "Available Functions" => "none"
            ]
        );
    }
}