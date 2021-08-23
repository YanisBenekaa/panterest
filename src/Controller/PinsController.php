<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        $pins = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     * @IsGranted("PIN_CREATE")
     */
    public function create(Request $request, EntityManagerInterface $em, FlashyNotifier $flashy): Response
    {
        $url = $_SERVER['REQUEST_URI'];

        $pin = new Pin;

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $flashy->success('Pin successfully created !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'url' => $url,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin) : Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "POST", "PUT"})
     * @IsGranted("PIN_MANAGE", subject="pin")
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin, FlashyNotifier $flashy): Response
    {

        if ($pin->getUser() != $this->getUser()) {
            $flashy->error('Access Forbidden !');

            return  $this->redirectToRoute('app_home');
        }

        $url = $_SERVER['REQUEST_URI'];

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $flashy->success('Pin successfully updated !');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'url' => $url,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods={"POST", "DELETE"})
     * @IsGranted("PIN_MANAGE", subject="pin")
     */
    public function delete(Request $request, EntityManagerInterface $em, Pin $pin, FlashyNotifier $flashy): Response
    {

        if ($pin->getUser() != $this->getUser()) {
            $flashy->error('Access Forbidden !');

            return  $this->redirectToRoute('app_home');
        }

        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('csrf_token'))) {
            $em->remove($pin);
            $em->flush();

            $flashy->info('Pin successfully deleted !');
        }

        return $this->redirectToRoute('app_home');
    }
}
