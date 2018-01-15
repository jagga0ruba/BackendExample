<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 15/01/2018
 * Time: 09:27
 */

namespace App\Model;


use App\Util\DatabaseConnection;

class ReportModel extends DatabaseConnection
{
    public function getReport() : array
    {
        return $this->getReportFromDatabase();
    }

    private function getReportFromDatabase() : array
    {
        $Sql = 'CALL sp_GetTotalDepositAndWithdrawals()';

        $Statement = $this->PDO->prepare( $Sql );

        $Statement->execute( );

        $Result = $Statement->fetchAll( $this->PDO::FETCH_ASSOC );

        return(
        [
            'Contents' => $Result
        ]
        );
    }

}