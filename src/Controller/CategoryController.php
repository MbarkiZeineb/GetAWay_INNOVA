<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/mobile/edit",name="edit_mobile_category")
     */
    public function editMobile(Request $request)
    {
        $id= $request->query->get("id");
        $name = $request->query->get("name");

        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['idCateg' => $id]);
        $category->setNomCateg($name);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new JsonResponse("category edited");
        } catch (Exception $e) {
            return new JsonResponse("error while editing category");
        }
    }

    /**
     * @Route("/", name="app_category_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="app_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index',array("formcategory" =>$form->createView()) [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCateg}", name="app_category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{idCateg}/edit", name="app_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCateg}", name="app_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getIdCateg(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/mobile/add", name="add_mobile_category")
     */
    public function addCategory(Request $request)
    {
        $name = $request->query->get("name");

        $category = new Category();
        $category->setNomCateg($name);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return new JsonResponse("category added");
        } catch (Exception $e) {
            return new JsonResponse("error while adding category : " . $e->getMessage());
        }
    }


    /**
     * @Route("/mobile/delete", name="delete_mobile_category")
     */
    public function deleteCategory(Request $request)
    {
        $id = $request->query->get('id');
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['idCateg' => $id]);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            return new JsonResponse("category deleted successfully");
        } catch (Exception $e) {
            return new JsonResponse("error on " . $e->getMessage());
        }
    }

    /**
     * @Route("/mobile/getAll",name="get_mobile_category")
     */
    public function showCategory(): Response
    {
        $category=$this->getDoctrine()->getRepository(Category::class)->findAll();
        $categoryList =[];
        foreach ($category as $cat ){
            $categoryList[] = [
                'id' => $cat->getIdCateg(),
                'name' => $cat->getNomCateg(),
            ];

        }
        return new Response(json_encode($categoryList));

    }
}
