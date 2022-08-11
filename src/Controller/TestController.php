<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        return $this->render('test/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/test/{id}', name: 'app_test_link')]
    public function profile(ManagerRegistry $doctrine, $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        return $this->render('test/link.html.twig', [
            'user' => $user,
        ]);
    }

    // ***** CONTACT *****
    // INFO: Route is set intetionaly to '/temp/test/contact' for testing (it has to be fixed!)
    //        otherwise without '/temp' ther will be an error!
    #[Route('/temp/test/contact', name: 'app_test_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new stdClass();
        $contact->sent = false;
        $contact->name = "";
        $contact->email = "";
        $contact->mail_to = "example@mail.com";
        $contact->message = "";

        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $contact->sent = true;
            $contact->name = $data["name"];
            $contact->email = $data["email"];
            $contact->message = $data["message"];

            // *** INFO: We do not realy send an email, instead, we show a success Message/result below the form ***        
            // $headers = "FROM: ". $cls->email . "\r\n";
            // $mail_to = "example@mail.com";
            // if(mail($mail_to, "message coming from the contact form", $cls->msg, $headers)){
            //         echo "sent";
            // }else {
            //         echo "error";
            // }
        }

        return $this->render('test/contact.html.twig', [
            'contactform' => $form->createView(),
            'data' => $contact,    
        ]);
    }
    
}
