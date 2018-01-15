<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 14/01/2018
 * Time: 14:58
 */

namespace App\Model;


use App\Util\DatabaseConnection;

class CustomerModel extends DatabaseConnection
{
    protected $IdCustomer;

    protected $EmailAddress;

    protected $FirstName;

    protected $LastName;

    protected $Country;

    protected $Gender;

    public function __construct( )
    {
        parent::__construct( );
    }
    /**
     * @param string $FirstName    First Name
     * @param string $LastName     Last Name
     * @param string $EmailAddress Email Address
     * @param string $Country      International Country Code
     * @param string $Gender       Gender
     * @return array
     *
     * @throws \Exception If user already exists
     */
    public function createNewCustomer(string $FirstName ,
                                      string $LastName ,
                                      string $EmailAddress ,
                                      string $Country ,
                                      string $Gender )
    {

        $this->EmailAddress = $EmailAddress;

        $this->FirstName = $FirstName;

        $this->LastName = $LastName;

        $this->Country = $Country;

        $this->Gender = $Gender;

        return( $this->insertNewCustomerOnTheDatabase() );

    }

    /**
     * @return array
     * @throws \Exception
     */
    private function insertNewCustomerOnTheDatabase( )
    {
        $Sql = 'Call sp_CustomerAdd( ?, ?, ?, ?, ?)';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute(
            [
                $this->FirstName,
                $this->LastName,
                $this->EmailAddress,
                $this->Country,
                $this->Gender
            ]
        );

        $Result = $Statement->fetch( $this->PDO::FETCH_ASSOC);

        if( !isset( $Result['CustomerId'] ) )
        {
            throw new \Exception( 'A customer with this email already exists');
        }

        return(
            [
                'Contents' =>
                [
                    'CustomerId' => $Result['CustomerId'] ,
                    'EmailAddress' => $this->EmailAddress
                ]
            ]
        );
    }


    public function editCustomer(   int $Id,
                                    string $FirstName,
                                    string $LastName,
                                    string $EmailAddress,
                                    string $Country,
                                    string $Gender    ) : array
    {
        $this->setCustomerFromDatabaseWithId( $Id );

        $this->FirstName = ( $FirstName === '' ) ? $this->FirstName : $FirstName;

        $this->LastName = ( $LastName === '' ) ? $this->LastName : $LastName;

        $this->EmailAddress = ( $EmailAddress === '' ) ? $this->EmailAddress : $EmailAddress;

        $this->Country = ( $Country === '' ) ? $this->Country : $Country;

        $this->Gender = ( $Gender === '' ) ? $this->Gender : $Gender;

        return( $this->UpdateUser() );
    }

    private function setCustomerFromDatabaseWithId( $Id )
    {

        $Sql = 'Call sp_CustomerGetById(?)';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute( [ $Id ] );

        $Result = $Statement->fetch( $this->PDO::FETCH_OBJ );

        if( !isset( $Result->IdCustomer ) )
        {
            //did not have time to actually treat the errors from the db, sorry
            throw new \Exception( 'The Customer Id provided does not exist in the database' );
        }

        $this->IdCustomer = $Result->IdCustomer;

        $this->FirstName = $Result->FirstName;

        $this->LastName = $Result->LastName;

        $this->EmailAddress = $Result->EmailAddress;

        $this->Country = $Result->Country;

        $this->Gender = $Result->Gender;
    }

    private function updateUser( )
    {
        $Sql = 'Call sp_CustomerEdit(?,?,?,?,?,?)';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute(
            [
                $this->IdCustomer,
                $this->FirstName,
                $this->LastName,
                $this->EmailAddress,
                $this->Country,
                $this->Gender
            ]
        );

        $Result = $Statement->fetch( $this->PDO::FETCH_ASSOC );

        if( !isset( $Result['IdCustomer'] ) )
        {
            //did not have time to actually treat the errors from the db, sorry
            throw new \Exception( 'You can\'t change the email address to another one already existing in the database ' );
        }

        return(
            [
                'Contents' => $Result
            ]
        );
    }

    public function deposit( int $IdCustomer , int $Amount )
    {
        $this->IdCustomer = $IdCustomer;

        return $this->depositOnDatabase( $Amount );
    }

    private function depositOnDatabase( $Amount ) : array
    {
        $Sql = 'Call sp_Deposit(?,?)';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute(
            [
                $this->IdCustomer,
                $Amount
            ]
        );

        $Result = $Statement->fetch( $this->PDO::FETCH_ASSOC );

        if( !isset( $Result['IdCustomer'] ) )
        {
            //did not have time to actually treat the errors from the db, sorry
            throw new \Exception( 'Something is wrong with the database' );
        }

        return [
            'Contents' =>
            [
                'IdCustomer' => $Result['IdCustomer'],
                'TotalBalance' => substr_replace($Result['TotalBalance'], ',' , -2, 0),
                'BonusBalance' => substr_replace($Result['BonusBalance'], ',' , -2, 0)
            ]
        ];
    }

    public function withdraw( int $IdCustomer , int $Amount) : array
    {
        $this->IdCustomer = $IdCustomer;

        return $this->withdrawFromDatabase( $Amount );
    }

    private function withdrawFromDatabase( $Amount ) : array
    {
        $Sql = 'Call sp_Withdraw(?,?)';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute(
            [
                $this->IdCustomer,
                $Amount
            ]
        );

        $Result = $Statement->fetch( $this->PDO::FETCH_ASSOC );

        if( isset( $Result['Error'] ) )
        {
            throw new \Exception( $Result['Error'] );
        }

        if( !isset( $Result['IdCustomer'] ) )
        {
            //did not have time to actually treat the errors from the db, sorry
            throw new \Exception( 'Something is wrong with the database' );
        }

        return [
            'Contents' =>
                [
                    'IdCustomer' => $Result['IdCustomer'],
                    'TotalBalance' => substr_replace($Result['TotalBalance'], ',' , -2, 0),
                    'BonusBalance' => substr_replace($Result['BonusBalance'], ',' , -2, 0)
                ]
        ];

    }
}