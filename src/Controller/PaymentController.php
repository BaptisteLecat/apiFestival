<?php

namespace App\Controller;

use App\Entity\Barcode;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentController extends AbstractController
{

    private $params;

    private $serializer;
    private $em;

    /**
     * Constructor
     *
     * @param ParameterBagInterface $params
     * 
     */
    public function __construct(ParameterBagInterface $params, SerializerInterface$serializer, EntityManagerInterface $entityManager)
    {
        $this->params               = $params;
        $this->serializer           = $serializer;
        $this->rm = $entityManager;
    }

    /**
     * @Route("/payment/stripe-hook", name="payment_stripe_hook",  methods={"GET","POST"})
     */
    public function stripeHook(Request $request, PaymentRepository $paymentRepository)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            throw new \Exception('Bad JSON body from Stripe!');
        }

        // Identifiant permettant de faire la liaison entre le payment stripe et le payment windle
        if (!isset($data['data']['object']['id'])) {
            throw new \Exception('Bad JSON body from Stripe : no payment_intent !');
        }

        $sessionIntent = $data['data']['object']['id'];

        // Rechercher et récupérer un objet payment
        $payment = $paymentRepository->findOneBy([
            'session' => $sessionIntent,
        ]);

        if ($payment) {
            // Mauvais montant
            if (strval($data['data']['object']['amount']) != strval($payment->amount * 100)) {
                throw new \Exception('Bad Amount!' . $data['data']['object']['amount'] . ' / ' . ($payment->amount * 100));
            }

            if ($data['type'] != 'payment_intent.succeeded') {
                throw new \Exception('Notified on bad or unknown event !');
            }

            $charges = reset($data['data']['object']['charges']['data']);

            if (isset($charges['paid']) && ($charges['paid'] == true)) {
                // Valide le paiement
                $payment->setStatus(1);
                $this->em->persist($payment);
                $this->em->flush();

                $barcode = new Barcode();
                $barcode->setCode($this->generateEAN(200));
                $barcode->setEvent($payment->getEvent());
                $barcode->setUser($payment->getUser());
                $barcode->setLastname($payment->getUser()->getName());
                $barcode->setFirstname($payment->getUser()->getFirstname());

                $this->em->persist($barcode);
                $this->em->flush();

                return new JsonResponse(['ok'], Response::HTTP_OK);
            } else {
                throw new \Exception('Notification charges paid error !');
            }
        } else {
            // Payment introuvable
            throw new \Exception('Payment Intent not found!');
        }
    }

    private function generateEAN($number)
    {
        $code = '200' . str_pad($number, 9, '0');
        $weightflag = true;
        $sum = 0;
        // Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit. 
        // loop backwards to make the loop length-agnostic. The same basic functionality 
        // will work for codes of different lengths.
        for ($i = strlen($code) - 1; $i >= 0; $i--) {
            $sum += (int)$code[$i] * ($weightflag ? 3 : 1);
            $weightflag = !$weightflag;
        }
        $code .= (10 - ($sum % 10)) % 10;
        return $code;
    }
}
