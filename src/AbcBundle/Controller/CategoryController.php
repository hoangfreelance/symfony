<?php
namespace AbcBundle\Controller;

use AbcBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller {
    
    /**
     * @Route("/category/create", name="create_category")
     */
    public function createAction(Request $request)
    {
        $category = new Category();
        
        $form = $this->createFormBuilder($category)
            ->add('name', 'text')
            ->add('parent', 'entity', array('class' => 'AbcBundle:Category', 'property' => 'getIndentedTitle'))
            ->add('save', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category');
        }
        
        return $this->render('AbcBundle:Category:create.html.twig', array('form'=>$form->createView()));
    }
    
    /**
     * @Route("/category", name="category")
     */
    public function listAction()
    {
        return $this->render('AbcBundle:Category:index.html.twig');
    }
}
