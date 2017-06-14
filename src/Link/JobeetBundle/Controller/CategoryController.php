<?php
namespace Link\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Link\JobeetBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package Link\JobeetBundle\Controller
 */
class CategoryController extends Controller
{
    public function showAction(Request $request, $slug, $page)
    {
        $format = $request->query->get('_format');
        if($format == ''){
            $format = $request->getRequestFormat();
        }

        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('LinkJobeetBundle:Category')->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $total_jobs = $em->getRepository('LinkJobeetBundle:Job')->countActiveJobs($category->getId());
        $jobs_per_page = $this->container->getParameter('max_jobs_on_category');

        $last_page = ceil($total_jobs / $jobs_per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        $category->setActiveJobs($em->getRepository('LinkJobeetBundle:Job')
            ->getActiveJobs($category->getId(), $jobs_per_page, ($page - 1) * $jobs_per_page));

        return $this->render('LinkJobeetBundle:Category:show.'.$format.'.twig', array(
            'category' => $category,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total_jobs' => $total_jobs,
            'feedId' => sha1($this->get('router')->generate('LinkJobeetBundle_category', array('slug' =>  $category->getSlug(), '_format' => 'atom'), true)),
        ));
    }
}