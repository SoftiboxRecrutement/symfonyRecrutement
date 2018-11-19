<?php

namespace Sfb\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\EditUserType;
use Sfb\SiteBundle\Entity\Produit;
use Sfb\SiteBundle\Form\ProduitType;
use Sfb\SiteBundle\Form\EditProduitType;

class AdminController extends Controller
{
    public function searchAction($page, Request $req)
    {
        $query = $req->query->get('query');
        $em = $this->getDoctrine()->getManager();
        $results = $em->getRepository('SfbSiteBundle:Produit')->search($page,$query);
        return $this->render('@SfbSite/Default/search.html.twig',['query'=>$query,'page'=>$page,'results'=>$results,'results_count'=>count($results),'number_page'=>ceil(count($results)/5)]);
    }

	/**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function userListAction()
    {
    	if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
	        return $this->redirectToRoute('homepage');
	    }
    	$em = $this->getDoctrine()->getManager();
    	$users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('@SfbSite/Default/user_list.html.twig',['users'=>$users]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function user_addAction(Request $req)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(UserType::class,$user,array('action'=>$this->generateUrl('sfb_site_user_add')));
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user->setSalt(uniqid(mt_rand()));
            $password = $this->get('security.password_encoder')->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Le compte a été créé avec succès');
            return $this->redirectToRoute('sfb_site_user_list');
        }
        return $this->render('@SfbSite/Default/user_add.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function my_product_listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('SfbSiteBundle:Produit')->findAll();
        return $this->render('@SfbSite/Default/my_product_list.html.twig',['produits'=>$produits]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function product_listAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('SfbSiteBundle:Produit')->findAll();
        return $this->render('@SfbSite/Default/product_list.html.twig',['produits'=>$produits]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function product_userAction(User $user)
    {
        return $this->render("@SfbSite/Default/product_user.html.twig",['user'=>$user]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function product_addAction(Request $req)
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit,array('action'=>$this->generateUrl('sfb_site_product_add')));
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() && $this->captchaverify($req->get('g-recaptcha-response'))) {
            $em = $this->getDoctrine()->getManager();
            $produit->setUser($this->getUser());
            $em->persist($produit);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject("test-produit")
                ->setFrom('noreply@softibox.com')
                ->setTo("anthony@softibox.com")
                ->setBody("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
            $this->get('mailer')->send($message);
            $this->addFlash('success','Votre produit a été enregistré.');
            return $this->redirectToRoute('fos_user_profile_show');
        }

        if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($req->get('g-recaptcha-response'))){
                $this->addFlash('danger','Le Captcha est obligatoire');             
            }
        return $this->render('@SfbSite/Default/product_add.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function product_editAction(Produit $produit, Request $req)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') AND $produit->getUser() !== $this->getUser()) {
            $this->addFlash('success',"Vous n'avez pas le droit d'accès à cette page.");
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(EditProduitType::class,$produit,array('action'=>$this->generateUrl('sfb_site_product_edit',array('id'=>$produit->getId()))));
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() && $this->captchaverify($req->get('g-recaptcha-response'))) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $this->addFlash('success','Le produit a été modifié.');
            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('sfb_site_product_list');
            }
            return $this->redirectToRoute('sfb_site_my_product_list');
        }

        if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($req->get('g-recaptcha-response'))){
                $this->addFlash('danger','Le Captcha est obligatoire');             
            }
        return $this->render('@SfbSite/Default/product_edit.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function product_deleteAction(Produit $produit, Request $req)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') AND $produit->getUser() !== $this->getUser()) {
            $this->addFlash('success',"Vous n'avez pas le droit d'accès à cette page.");
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createFormBuilder()->getForm();
        if ($req->getMethod() == 'POST') {
            $form->handleRequest($req);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($produit);
                $em->flush();
                $this->addFlash('success',"Le produit a été supprimé");
                if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('sfb_site_product_list');
                }
                return $this->redirectToRoute('sfb_site_my_product_list');
            }
        }
        return $this->render('@SfbSite/Default/produit_delete.html.twig',['produit'=>$produit,'form'=>$form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function user_editAction(User $user, Request $req)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $this->addFlash('success',"Vous n'avez pas le droit d'accès à cette page.");
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(EditUserType::class,$user,array('action'=>$this->generateUrl('sfb_site_user_edit',array('id'=>$user->getId()))));
        $form->handleRequest($req);
        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','Le compte de @'.$user->getUsername().' a été mis à jour.');
            return $this->redirectToRoute('sfb_site_user_list');
        }
        return $this->render('@SfbSite/Default/user_edit.html.twig',['user'=>$user,'form'=>$form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function user_deleteAction(User $user, Request $req)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $this->addFlash('success',"Vous n'avez pas le droit d'accès à cette page.");
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createFormBuilder()->getForm();
        if ($req->getMethod() == 'POST') {
            $form->handleRequest($req);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $bool = $user === $this->getUser();
                var_dump($bool);
                die();
                $em->remove($user);
                $em->flush();
                $this->addFlash('success',"Le compte a été supprimé");
                if($bool){
                    return $this->redirectToRoute('fos_user_security_logout');    
                }
                return $this->redirectToRoute('sfb_site_user_list');
            }
        }
        return $this->render('@SfbSite/Default/user_delete.html.twig',['user'=>$user,'form'=>$form->createView()]);
    }

    public function latestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('SfbSiteBundle:Produit')->findBy(array('user'=>$this->getUser()),array('id'=>'DESC'),3,0);
        return $this->render('@SfbSite/Default/latest.html.twig',['produits'=>$produits]);
    }

    function captchaverify($recaptcha){
            $url = "https://www.google.com/recaptcha/api/siteverify";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                "secret"=>"6LdQgXsUAAAAAInC-DR50Y3e-Ofyr6zWV2B3Txn_","response"=>$recaptcha));
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response);        
        return $data->success;        
    }
}
