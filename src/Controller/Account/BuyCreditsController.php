<?php

namespace App\Controller\Account;

use App\Entity\CreditsPurchaseRegister;
use App\Repository\CreditsPurchaseRegisterRepository;
use App\Repository\UserRepository;
use App\Service\CreditsDatasProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BuyCreditsController extends AbstractController
{

    private CreditsDatasProvider $creditsDatasProvider;
    private $creditsPurchaseRegister;
    private $emi;
    private $user;


    public function __construct(CreditsDatasProvider $creditsDatasProvider, CreditsPurchaseRegisterRepository $creditsPurchaseRegister, EntityManagerInterface $emi, UserRepository $user)
    {
        $this->creditsDatasProvider = $creditsDatasProvider;
        $this->creditsPurchaseRegister = $creditsPurchaseRegister;
        $this->emi = $emi;
        $this->user = $user;
    }


    #[Route('/achat-credits/{motif}', name: 'app_account_buy_credits', defaults: ['motif' => null])]
    public function index($motif): Response
    {
        $user = $this->getUser();

        if ($motif === 'annulation') {
            $this->addFlash('info', ' Achat annulé. Sélectionne la quantité qui te convient !');
            $user->setStripeSessionId(null);
            $this->emi->persist($user);
            $this->emi->flush();
        }

        $creditsDatas = $this->creditsDatasProvider->getCreditsDatas();

        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/buy_credits/index.html.twig', [
            'creditsDatas' => $creditsDatas,
        ]);
    }
    #[Route('/acheter-{number}-credits', name: 'app_account_purchase_credits')]
    public function purchaseCredits($number): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        $creditsDatas = $this->creditsDatasProvider->getCreditsDatas();

        $selectedCredits = array_filter($creditsDatas, function ($creditsData) use ($number) {
            return $creditsData['credits'] == $number;
        });

        $selectedCredits = reset($selectedCredits);

        // dd($selectedCredits);

        if (!$selectedCredits) {
            throw $this->createNotFoundException("Le produit choisi n'existe pas.");
        }
        // Récupérer le domaine
        $domain = $_ENV['DOMAIN'];

        // Créer la session Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);


        // dd($selectedCredits, $domain, $_ENV['STRIPE_SECRET_KEY']);
        // dd($selectedCredits['purchase_id']);

        $checkout_session = Session::create([
            'line_items' => [[
                'price' => $selectedCredits['purchase_id'],
                'quantity' => 1
            ]],
            'mode' => 'payment',
            'success_url' => $domain . '/merci-pour-votre-confiance/{CHECKOUT_SESSION_ID}/',
            'cancel_url' => $domain . '/achat-credits/annulation',
            'metadata' => [
                'nb_credits' => $selectedCredits['credits']
            ]
        ]);

        $user->setStripeSessionId($checkout_session->id);
        $this->emi->persist($user);
        $this->emi->flush();


        return $this->redirect($checkout_session->url);
    }

    #[Route('/merci-pour-votre-confiance/{stripeSessionId}', name: 'app_account_purchase_credits_success')]
    public function purchaseCreditsSuccess($stripeSessionId): Response
    {

        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour voir cette page.');
            return $this->redirectToRoute('app_login');
        }

        // Vérifier avec Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $session = Session::retrieve($stripeSessionId);

        $userPurchaser = $this->user->findOneBy(['StripeSessionId' => $stripeSessionId]);
        if (!$userPurchaser || $userPurchaser != $this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        // dd($session, $session->payment_status, $session->metadata["nb_credits"]);

        if (!$session->payment_status === 'paid') {
            $this->addFlash('warning', 'Problème rencontré lors du règlement.');
            $user->setStripeSessionId(null);
            $this->emi->persist($user);
            $this->emi->flush();
            return $this->redirectToRoute('app_account_buy_credits');
        }

        $creditsQuantity = $session->metadata["nb_credits"];

        if ($user->isFirstPurchase() == 1) {
            $creditsQuantity = $creditsQuantity * 1.1;
        }
        $newPurchase = new CreditsPurchaseRegister();
        $newPurchase->setUser($user);
        $newPurchase->setPurchasedAt(new \DateTimeImmutable());
        $newPurchase->setCreditsQuantity($creditsQuantity);
        $newPurchase->setPurchaseAmount($session->amount_total / 100);

        $user->setCredits($user->getCredits() + $creditsQuantity);
        $user->setFirstPurchase(0);
        $user->setStripeSessionId(null);

        $this->emi->persist($newPurchase);
        $this->emi->persist($user);
        $this->emi->flush();



        return $this->render('account/buy_credits/success.html.twig', [
            'creditsQuantity' => $user->getCredits(),
            'pseudo' => $user->getPseudo(),
        ]);
    }
}
