<?php

namespace App\Model;

use App\Entity\CompanyDetails;
use App\Repository\CompanyTurnoverRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;

class CompanyDetailsModel
{
    public const COMPANY_FINANCIAL_PAGE = "turnover/";
    public CompanyTurnoverRepository $companyTurnoverRepository;

    public function __construct(CompanyTurnoverRepository $companyTurnoverRepository)
    {
        $this->companyTurnoverRepository = $companyTurnoverRepository;
    }


    public function isRegistrationCodeValid(string $code): bool
    {
        return preg_match('/^[0-9]{9}$/', $code) === 1;
    }

    public function getNumberFromString(string $value): float
    {
        if (strpos($value, '€') !== false) {
            return str_replace(['€', ' '], '', $value);
        }
        if (strpos($value, '%') !== false) {
            $newValue = str_replace(['%', ' '], '', $value);
            return floatval(str_replace(',', '.', $newValue));
        }
    }

    public function storeTurnoverDetails(CompanyDetails $company, string $url): bool
    {
        $client = new HttpBrowser(HttpClient::create());
        $crawler = $client->request('GET', $url . self::COMPANY_FINANCIAL_PAGE);
        $tableHeader = $crawler
                    ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[2]/table/thead');
        $numberOfCol = $tableHeader->filter('th')->count();
        if ($numberOfCol == 0) {
            return false;
        }
        $yearStartsFrom = $crawler
            ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[2]/table/thead/tr/th[2]')
            ->innerText();
        $yearStartsFrom = (int)$yearStartsFrom;

        $tableBody = $crawler->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[2]/table');

        $numberOfRows = $tableBody->filter('tr')->count();

        for ($i = 2; $i <= $numberOfCol; $i++) {
            $k = 0;
            $data = [];
            for ($j = 2; $j <= $numberOfRows; $j++) {
                $selectedCell = $crawler->filter('table tr:nth-child(' . $j . ') td:nth-child(' . $i . ')')->text();
                $data[$k] = $selectedCell == "" ? null : $this->getNumberFromString($selectedCell) ;
                $k++;
            }
            $this->storeDetails($data, $yearStartsFrom, $company);
            $yearStartsFrom++;
        }
        return true;
    }
    public function storeDetails(array $values, int $year, CompanyDetails $company): void
    {
        $this->companyTurnoverRepository->createCompanyDetail(
            $company,
            strval($year),
            $values[0],
            $values[1],
            $values[2],
            $values[3],
            $values[4],
            $values[5],
            $values[6],
            $values[7],
            $values[8]
        );
    }
}
