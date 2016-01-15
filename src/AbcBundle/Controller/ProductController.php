<?php
namespace AbcBundle\Controller;

use AbcBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller {
    
    /**
     * @Route("/product/create", name="create_product")
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        
        $form = $this->createFormBuilder($product)
            ->add('name', 'text')
            ->add('price', 'text')
            ->add('description', 'textarea')
            ->add('category', 'entity', array('class' => 'AbcBundle:Category', 'property' => 'getIndentedTitle'))
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute('list');
        }
        
        return $this->render('AbcBundle:Product:create.html.twig', array('form'=>$form->createView()));
    }
    
    /**
     * @Route("/product", name="list")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager()->getRepository('AbcBundle:Product');
        $products = $em->findAll();
        
        return $this->render('AbcBundle:Product:index.html.twig', array('products'=>$products));
    }
}
