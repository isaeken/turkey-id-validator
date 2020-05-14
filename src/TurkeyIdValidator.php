<?php
/**
 * @author İsa Eken <hello@isaeken.com.tr>
 * @license MIT
 * @version 1.0.0
 */

namespace IsaEken;

use GuzzleHttp\Client;

class TurkeyIdValidator
{
    /**
     * @example TurkeyIdValidator::VerifyId('12345678900')
     * @param $id
     * @return bool
     */
    public static function VerifyId($id) : bool
    {
        return (preg_match('/^[1-9]{1}[0-9]{9}[02468]{1}$/', $id) ? true : false);
    }

    /**
     * @example TurkeyIdValidator::VerifyName('İsa Eken')
     *
     * @param $name
     * @return bool
     */
    public static function VerifyName($name) : bool
    {
        return (preg_match('/^([a-zA-ZÇŞĞÜÖİçşğüöı ]+)$/', $name) ? true : false);
    }

    /**
     * @example TurkeyIdValidator::VerifyYear('1881')
     * @param $year
     * @return bool
     */
    public static function VerifyYear($year) : bool
    {
        if (is_integer($year)) return (($year > 999 && $year < 3000) ? true : false);
        else return (preg_match('/^\d{4}$/', $year) ? true: false);
    }

    /**
     * @example TurkeyIdValidator::Validate('xxxxxxxxxxx', 'first name', 'last name', '2000')
     * @param string $id
     * @param string $name
     * @param string $surname
     * @param string $birth_year
     * @return bool
     */
    public static function Validate($id, $name, $surname, $birth_year) : bool
    {
        /**
         * Check id is valid
         */
        if (!TurkeyIdValidator::VerifyId($id)) return false;

        /**
         * Check name and surname is valid
         */
        if (!TurkeyIdValidator::VerifyName($name)) return false;
        if (!TurkeyIdValidator::VerifyName($surname)) return false;

        /**
         * Check birth year is valid
         */
        if (!TurkeyIdValidator::VerifyYear($birth_year)) return false;

        /**
         * Request content
         */
        $request = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			<soap:Body>
				<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
					<TCKimlikNo>'.$id.'</TCKimlikNo>
					<Ad>'.$name.'</Ad>
					<Soyad>'.$surname.'</Soyad>
					<DogumYili>'.$birth_year.'</DogumYili>
				</TCKimlikNoDogrula>
			</soap:Body>
		</soap:Envelope>';

        /**
         * Guzzle client
         */
        $client = new Client([
            /**
             * Enable ssl verification
             */
            'verify' => true,

            /**
             * Disable http errors
             */
            'http_errors' => false,
        ]);

        /**
         * Send request to nvi.gov.tr
         */
        $response = $client->request('POST', 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx', [
            'headers' => [
                'POST' => '/Service/KPSPublic.asmx HTTP/1.1',
                'Host' => 'tckimlik.nvi.gov.tr',
                'Content-Type' => 'text/xml; charset=utf-8',
                'SOAPAction' => '"http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
                'Content-Length' => strlen($request)
            ],
            'body' => $request,
        ]);

        /**
         * Check and return response
         */
        if ($response->getStatusCode() !== 200) return false;
        else return (strip_tags($response->getBody()->getContents()) == 'true');
    }
}