<?php

namespace App\Controller;

use App\Entity\CompanyDetails;
use App\Form\CompanyDetailsType;
use App\Form\ScrapperFormType;
use App\Model\CompanyDetailsModel;
use App\Repository\CompanyDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

#[Route('/company/details')]
class CompanyDetailsController extends AbstractController
{
    private CompanyDetailsRepository $companyDetailsRepository;
    private CompanyDetailsModel $companyDetails;
    private const SEARCH_LINK = "https://rekvizitai.vz.lt/en/company-search/";
    private const SEARCHED_COMPANY = "https://rekvizitai.vz.lt/en/company-search/1/";
    private const COMPANY_REPORT_PAGE = "report/";
    private const NO_RESULT_FOUND = 0;
    private $httpClient;

    public function __construct(
        CompanyDetailsRepository $companyDetailsRepository,
        CompanyDetailsModel $companyDetails,
        HttpClientInterface $httpClient
    ) {
        $this->companyDetailsRepository = $companyDetailsRepository;
        $this->companyDetails = $companyDetails;
        $this->httpClient = $httpClient;
    }

    #[Route('/', name: 'app_company_details_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        CompanyDetailsRepository $companyDetailsRepository
    ): Response {
        $form = $this->createForm(ScrapperFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $registrationCode = $form->getData()['registrationCode'];
            $registrationCodes = explode(',', $registrationCode);
            return $this->render('company_details/index.html.twig', [
                'companyDetails' => $companyDetailsRepository->findBy([
                    'registrationCode' => $registrationCodes
                ]),
                'form' => $form
            ]);
        }
        return $this->render('company_details/index.html.twig', [
            'companyDetails' => $companyDetailsRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'app_company_details_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $companyDetail = new CompanyDetails();
        $form = $this->createForm(CompanyDetailsType::class, $companyDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($companyDetail);
            $entityManager->flush();

            return $this->redirectToRoute('app_company_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company_details/new.html.twig', [
            'company_detail' => $companyDetail,
            'form' => $form,
        ]);
    }

    #[Route('/fetch', name: 'app_company_details_fetch', methods: ['GET','POST'])]
    public function fetchCompanyDetails(Request $request): Response
    {
        $form = $this->createForm(ScrapperFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $registrationCode = $form->getData()['registrationCode'];
            $registrationCodes = explode(',', $registrationCode);
            foreach ($registrationCodes as $registrationCode) {
                if (!$this->companyDetails->isRegistrationCodeValid($registrationCode)) {
                    return $this->redirectToRoute('app_company_details_fetch');
                }
                $client = new HttpBrowser(HttpClient::create());
                $crawler = $client->request('GET', self::SEARCH_LINK);
                $companyForm = $crawler->filter('#formSearch')->form();
                $companyForm['code'] = $registrationCode;
                $client->submit($companyForm);

                $companyLinkCrawler = $client->request('GET', self::SEARCHED_COMPANY);
                $resultQuantity = $companyLinkCrawler
                ->filterXPath('//*[@id="divExpandSearch"]/div[2]/strong')
                ->innerText();
                if ($resultQuantity == self::NO_RESULT_FOUND) {
                    return $this->redirectToRoute('app_company_details_fetch');
                }
                $companyLink = $companyLinkCrawler
                ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div/div[2]/div/div[1]/a[1]')
                ->extract(['href']);
                $name = $companyLinkCrawler
                ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div/div[2]/div/div[1]/a[1]')
                ->innerText();
                $address = $companyLinkCrawler
                ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div/div[2]/div/div[2]')
                ->innerText();

                $companyDetailsCrawler = $client->request('GET', $companyLink[0]);
                $bankruptInfo = $companyDetailsCrawler
                ->filterXPath('//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[2]/div[2]');
                if (count($bankruptInfo) != 0) {
                    return $this->redirectToRoute('app_company_details_index');
                }

                $vatLabel = $companyDetailsCrawler
                ->filterXPath(
                    '//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[2]/div/table/tbody/tr[2]/td[2]'
                );
                if (count($vatLabel) != 0 && $vatLabel->innerText() == "VAT") {
                    $vat = $companyDetailsCrawler
                    ->filterXPath(
                        '//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[2]/div/table/tbody/tr[2]/td[3]'
                    )->innerText();
                } else {
                    $vat = null;
                }


                $companyReportCrawler = $client->request('GET', $companyLink[0] . self::COMPANY_REPORT_PAGE);
                $phone = $companyDetailsCrawler
                ->filterXPath(
                    '//*[@id="rekvizitai-app"]/div/div[2]/div/main/div[1]/div[3]/div[3]/table/tbody/tr[3]/td[3]/img'
                )
                ->attr('src');
                $company = $this->companyDetailsRepository->createCompanyDetail(
                    $name,
                    $vat,
                    $registrationCode,
                    $address,
                    $phone
                );
                $this->companyDetails->storeTurnoverDetails($company, $companyLink[0]);
            }
            return $this->redirectToRoute('app_company_details_index');
        }

        return $this->render('company_details/scrapper.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_company_details_show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $companyDetails = $this->companyDetailsRepository->find($id);
        return $this->render('company_details/show.html.twig', [
            'companyDetails' => $companyDetails,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_details_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        CompanyDetails $companyDetail,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CompanyDetailsType::class, $companyDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_company_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company_details/edit.html.twig', [
            'company_detail' => $companyDetail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_details_delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        EntityManagerInterface $entityManager
    ): Response {
        $company = $this->companyDetailsRepository->find($id);
            $entityManager->remove($company);
            $entityManager->flush();

        return $this->redirectToRoute('app_company_details_index', [], Response::HTTP_SEE_OTHER);
    }
}
