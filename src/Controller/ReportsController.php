<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 15/01/2018
 * Time: 07:37
 */

namespace App\Controller;

use App\Model\ReportModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReportsController
{
    /**
     *
     * @Route( "/Report/" )
     *
     * @return JsonResponse
     */
    public function Report( )
    {
        try
        {
            $Reports = new ReportModel();

            $Response = $Reports->getReport();
        }
        catch(\Exception $Exception)
        {
            return new JsonResponse( array( 'Success' => 'false' , 'ErrorMessage' => $Exception->getMessage( ) ) );
        }

        return new JsonResponse(
            [
                'Success' => 'true',
                'Contents' =>  $Response[ 'Contents' ]
            ]
        );
    }
}