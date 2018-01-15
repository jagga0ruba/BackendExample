<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 14/01/2018
 * Time: 13:45
 */

namespace App\Controller;

use App\Model\CustomerModel;
use App\Util\Validations;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Util\Database;

class CustomerController
{
    /**
     *
     * @Route( "/Customer/addNew/" )
     *
     *
     * @param Request $Request
     * @return JsonResponse
     */
    public function addNew( Request $Request )
    {

        try
        {
            $Validation = new Validations();

            $FirstName = $Validation->filterString( $Request->get( 'FirstName' ) ? : '' );

            $LastName = $Validation->filterString( $Request->get( 'LastName' ) ? : '' );

            $EmailAddress = $Validation->filterEmail( $Request->get( 'EmailAddress' ) ? : '' );

            $Country = $Validation->getCountryIfValid( $Request->get( 'Country' ) ? : '' );

            $Gender = $Validation->getGenderIfValid( $Request->get( 'Gender' ) ? : '' );

            $Customer = new CustomerModel();

            $Response = $Customer->createNewCustomer( $FirstName, $LastName , $EmailAddress , $Country , $Gender );
        }
        catch(\Exception $Exception)
        {
            return new JsonResponse( array( 'Success' => 'false' , 'ErrorMessage' => $Exception->getMessage( ) ) );
        }

        return new JsonResponse(
            [
                'Success' => 'true',
                'Contents' => $Response[ 'Contents' ]
            ]
        );

    }

    /**
     *
     * @Route( "/Customer/editUser/" )
     *
     *
     * @param Request $Request
     * @return JsonResponse
     */
    public function editUser( Request $Request)
    {
        try
        {
            $Validation = new Validations();

            $IdCustomer = $Validation->filterInt( $Request->get( 'IdCustomer' ) ? : '' );

            $FirstName = $Validation->sanitizeString( $Request->get( 'FirstName' ) ? : '' );

            $LastName = $Validation->sanitizeString( $Request->get( 'LastName' ) ? : '' );

            $EmailAddress = $Request->get( 'EmailAddress' ) ? : '';

            if( $EmailAddress !== '' )
            {
                $EmailAddress = $Validation->filterEmail( $EmailAddress );
            }

            $Country = $Validation->getCountryIfValidOrEmpty( $Request->get( 'Country' ) ? : '' );

            $Gender = $Validation->getGenderIfValidOrEmpty( $Request->get( 'Gender' ) ? : '' );

            $Customer = new CustomerModel();

            $Response = $Customer->editCustomer( $IdCustomer , $FirstName, $LastName , $EmailAddress , $Country , $Gender );
        }
        catch(\Exception $Exception)
        {
            return new JsonResponse( array( 'Success' => 'false' , 'ErrorMessage' => $Exception->getMessage( ) ) );
        }

        return new JsonResponse(
            [
                'Success' => 'true',
                'Contents' => $Response[ 'Contents' ]
            ]
        );

    }

    /**
     *
     * @Route( "/Customer/deposit/" )
     *
     *
     * @param Request $Request
     * @return JsonResponse
     */
    public function deposit( Request $Request )
    {
        try
        {
            $Validation = new Validations();

            $Customer = new CustomerModel();

            $CustomerID = $Validation->filterInt( $Request->get( 'CustomerId' ) ? : '' );

            $Amount = $Request->get( 'Amount' );

            if( strpos( $Amount , ',') === false )
            {
                $Amount = $Amount . ',00';
            }

            $Amount = $Validation->sanitizeFloat( $Amount );

            $Response = $Customer->deposit( $CustomerID, $Amount );
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

    /**
     *
     * @Route( "/Customer/withdraw/" )
     *
     *
     * @param Request $Request
     * @return JsonResponse
     */
    public function withdraw( Request $Request )
    {
        try
        {
            //Obviously this could all be put into a function for the sake of reusability but.. time constraints, I apologize.
            $Validation = new Validations();

            $Customer = new CustomerModel();

            $CustomerID = $Validation->filterInt( $Request->get( 'CustomerId' ) ? : '' );

            $Amount = $Request->get( 'Amount' );

            if( strpos( $Amount , ',') === false )
            {
                $Amount = $Amount . ',00';
            }

            $Amount = $Validation->sanitizeFloat( $Amount );

            $Response = $Customer->withdraw( $CustomerID, $Amount );
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