<?php

namespace OHMedia\EmailBundle\Controller;

use OHMedia\BootstrapBundle\Service\Paginator;
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Repository\EmailRepository;
use OHMedia\EmailBundle\Security\Voter\EmailVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/emails', name: 'email_index', methods: ['GET'])]
    public function index(
        EmailRepository $emailRepository,
        Paginator $paginator
    ): Response {
        $newEmail = new Email();

        $this->denyAccessUnlessGranted(
            EmailVoter::INDEX,
            $newEmail,
            'You cannot access the list of emails.'
        );

        $qb = $emailRepository->createQueryBuilder('e');
        $qb->orderBy('e.id', 'desc');

        return $this->render('@OHMediaEmail/email_index.html.twig', [
            'pagination' => $paginator->paginate($qb, 20),
            'view_attribute' => EmailVoter::VIEW,
        ]);
    }

    #[Route('/email/{id}', name: 'email_view', methods: ['GET'])]
    public function view(Email $email): Response
    {
        $this->denyAccessUnlessGranted(
            EmailVoter::VIEW,
            $email,
            'You cannot view this email.'
        );

        return new Response($email->getHtml());
    }
}
