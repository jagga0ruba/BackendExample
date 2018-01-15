<?php
/**
 * Created by PhpStorm.
 * User: joaod
 * Date: 14/01/2018
 * Time: 18:40
 */

namespace App\Util;


class Validations
{

    static $GendersArray = ['male' , 'female' , 'other'];

    static $CountriesList = ["AF" ,"AL" ,"DZ" ,"AS" ,"AD" ,"AO" ,"AI" ,"AQ" ,"AG" ,"AR" ,"AM" ,"AW" ,"AU" ,"AT" ,"AZ" ,"BS" ,"BH" ,"BD" ,"BB" ,"BY" ,"BE" ,"BZ" ,"BJ" ,"BM" ,"BT" ,"BO" ,"BA" ,"BW" ,"BV" ,"BR" ,"IO","BN" ,"BG" ,"BF" ,"BI" ,"KH" ,"CM" ,"CA" ,"CV" ,"KY" ,"CF" ,"TD" ,"CL" ,"CN" ,"CX" ,"CC","CO" ,"KM" ,"CG" ,"CD","CK" ,"CR" ,"CI" ,"HR" ,"CU" ,"CY" ,"CZ" ,"DK" ,"DJ" ,"DM" ,"DO" ,"EC" ,"EG" ,"SV" ,"GQ" ,"ER" ,"EE" ,"ET" ,"FK" ,"FO" ,"FJ" ,"FI" ,"FR" ,"GF" ,"PF" ,"TF" ,"GA" ,"GM" ,"GE" ,"DE" ,"GH" ,"GI" ,"GR" ,"GL" ,"GD" ,"GP" ,"GU" ,"GT" ,"GN" ,"GW" ,"GY" ,"HT" ,"HM","VA","HN" ,"HK" ,"HU" ,"IS" ,"IN" ,"ID" ,"IR","IQ" ,"IE" ,"IL" ,"IT" ,"JM" ,"JP" ,"JO" ,"KZ" ,"KE" ,"KI" ,"KP","KR" ,"KW" ,"KG" ,"LA","LV" ,"LB" ,"LS" ,"LR" ,"LY" ,"LI" ,"LT" ,"LU" ,"MO" ,"MK","MG" ,"MW" ,"MY" ,"MV" ,"ML" ,"MT" ,"MH" ,"MQ" ,"MR" ,"MU" ,"YT" ,"MX" ,"FM","MD" ,"MC" ,"MN" ,"MS" ,"MA" ,"MZ" ,"MM" ,"NA" ,"NR" ,"NP" ,"NL" ,"AN" ,"NC" ,"NZ" ,"NI" ,"NE" ,"NG" ,"NU" ,"NF" ,"MP" ,"NO" ,"OM" ,"PK" ,"PW" ,"PS" ,"PA" ,"PG" ,"PY" ,"PE" ,"PH" ,"PN" ,"PL" ,"PT" ,"PR" ,"QA" ,"RE" ,"RO" ,"RU" ,"RW" ,"SH" ,"KN","LC" ,"PM","VC","WS" ,"SM" ,"ST","SA" ,"SN" ,"CS" ,"SC" ,"SL" ,"SG" ,"SK" ,"SI" ,"SB" ,"SO" ,"ZA" ,"GS","ES" ,"LK" ,"SD" ,"SR" ,"SJ" ,"SZ" ,"SE" ,"CH" ,"SY" ,"TW","TJ" ,"TZ","TH" ,"TL" ,"TG" ,"TK" ,"TO" ,"TT" ,"TN" ,"TR" ,"TM" ,"TC","TV" ,"UG" ,"UA" ,"AE" ,"GB" ,"US" ,"UM","UY" ,"UZ" ,"VU" ,"VE" ,"VN" ,"VG" ,"VI","WF","EH" ,"YE" ,"ZM" ,"ZW" ];


    public function filterEmail( $EmailAddressToBeFiltered ) : string
    {

        $SanitizedEmail = $this->sanitizeEmail( $EmailAddressToBeFiltered );

        if( !filter_var( $SanitizedEmail , FILTER_VALIDATE_EMAIL ) )
        {
            throw new \Exception( $EmailAddressToBeFiltered ." is not a valid email address");
        }

        return( $EmailAddressToBeFiltered );

    }

    public function filterString( $StringToBeFiltered ) : string
    {

        $SanitizedString = $this->sanitizeString( $StringToBeFiltered );

        if( $SanitizedString === '' )
        {
            throw new \Exception( 'All parameters need to be filled');
        }

        return( $SanitizedString );

    }

    public function filterInt( $IntToBeFiltered ) : int
    {

        $SanitizedInt = $this->sanitizeInt( $IntToBeFiltered);

        if( $SanitizedInt === 0 )
        {
            throw new \Exception( 'CustomerId need to be filled');
        }

        return( $SanitizedInt );

    }

    public function sanitizeString( string $StringToBeFiltered ) : string
    {

        return filter_var( $StringToBeFiltered, FILTER_SANITIZE_STRING );

    }

    public function sanitizeEmail( string $EmailAddressToBeFiltered ) : string
    {

        return filter_var( $EmailAddressToBeFiltered, FILTER_SANITIZE_EMAIL );

    }

    public function sanitizeInt( string $NumberToBeSanitized ) : int
    {

        return (int) filter_var( $NumberToBeSanitized, FILTER_SANITIZE_NUMBER_INT );

    }

    public function sanitizeFloat( string $NumberToBeSanitized ) : int
    {

        return filter_var( $NumberToBeSanitized, FILTER_SANITIZE_NUMBER_FLOAT , FILTER_FLAG_ALLOW_FRACTION );

    }

    public function getGenderIfValid( string $GenderToBeValidated ) : string
    {

        if( !in_array( $GenderToBeValidated , $this::$GendersArray ) )
        {
            throw new \Exception( $GenderToBeValidated . ' is not a valid Gender, please opt for male, female or other.' );
        }

        return $GenderToBeValidated;

    }

    public function getGenderIfValidOrEmpty( string $GenderToBeValidated ) : string
    {

        if( !in_array( $GenderToBeValidated , $this::$GendersArray ) && $GenderToBeValidated !== '' )
        {
            throw new \Exception( $GenderToBeValidated . ' is not a valid Gender, please opt for male, female or other.' );
        }

        return $GenderToBeValidated;

    }

    public function getCountryIfValid( string $CountryToBeValidated ) : string
    {

        if( !in_array( $CountryToBeValidated , $this::$CountriesList ) )
        {
            throw new \Exception( $CountryToBeValidated . ' is not a valid International Country code. Please modify accordingly' );
        }

        return $CountryToBeValidated;

    }

    public function getCountryIfValidOrEmpty( string $CountryToBeValidated ) : string
    {

        if( !in_array( $CountryToBeValidated , $this::$CountriesList ) && $CountryToBeValidated !== '' )
        {
            throw new \Exception( $CountryToBeValidated . ' is not a valid International Country code. Please modify accordingly' );
        }

        return $CountryToBeValidated;

    }
}