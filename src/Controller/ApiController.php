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
use Symfony\Component\HttpFoundation\Response;

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
                "Available Functions" =>
                [
                    "Customer/addNew"=>"Adds New Customer",
                    "Customer/editUser"=>"Edits the Customer",
                    "Customer/deposit"=>"Increases the account balance for a user",
                    "Customer/withdraw"=>"Adds New Customer",
                    "Report"=>"Adds New Customer",
                ]
            ]
       );
    }
}