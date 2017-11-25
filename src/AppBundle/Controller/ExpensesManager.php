<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 10:37 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Transaction;
use AppBundle\Entity\User;
use AppBundle\Form\TransactionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExpensesManager extends Controller
{
    /**
     * @Route("/thismonth", name="this_month")
     */
    public function thisMonthAction(Request $request)
    {
        $newtransaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $newtransaction);
        $form->handleRequest($request);
        $translation = $this->get('translator');

        $editform = $this->createForm(TransactionType::class,$newtransaction);
        $editform->handleRequest($request);

        //show all transactions for current month
        $transactions = $this->getDoctrine()->getRepository(Transaction::class)->searchAction();

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($newtransaction);
                $em->flush();
                $this->addFlash('error', $translation->trans('transaction.registered'));
            } catch (\Exception $exception) {
                $loger = $this->get('logger');
                $loger->addError('Transaction is not registered',['e'=>$exception]);
                $this->addFlash('error', $translation->trans('transaction.not_registered'));
            }
            return $this->redirectToRoute('this_month');
        }
        return $this->render('expenses/thismonth.html.twig', [
            'form' => $form->createView(),
            'transactions' => $transactions,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/edit/{id}", name="edit_transaction")
     */
    public function editTransactionAction(Request $request, Transaction $transaction)
    {
        $translation = $this->get('translator');
        if (!$transaction){
            $this->addFlash('error', $translation->trans('transaction.not_exist'));
            return $this->redirectToRoute('this_month');
        }
        $editTransaction = $this->createForm(TransactionType::class, $transaction);
        $editTransaction->handleRequest($request);

        if ($editTransaction->isValid() && $editTransaction->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($transaction);
                $em->flush();
                $this->addFlash('error', $translation->trans('transaction.edited'));
            } catch (\Exception $exception) {
                $loger = $this->get('logger');
                $loger->addError('Transaction is not registered',['e'=>$exception]);
                $this->addFlash('error', $translation->trans('transaction.not_edited'));
            }
            return $this->redirectToRoute('this_month');
        }

        return $this->render('expenses/includes/edit_transaction.html.twig', [
            'form' => $editTransaction->createView()
        ]);
    }

    /**
     * @param Transaction|null $transaction
     * @Route("/remove/{id}", name="remove_transaction")
     * Request $request
     */
    public function removeTransactionAction(Transaction $transaction = null)
    {
        $translation = $this->get('translator');
        if (!$transaction) {
            $this->addFlash('error', $translation->trans('transaction.not_exist'));
            return $this->redirectToRoute('this_month');
        }
        $em = $this->getDoctrine()->getManager();
        try {
            $em->remove($transaction);
            $em->flush();
            $this->addFlash('success', $translation->trans('transaction.successfully_deleted'));
        } catch (\Exception $exception) {
            $logger = $this->get('logger');
            $logger->addError('Transaction nut deleted', ['e' => $exception]);
            $this->addFlash('error', $translation->trans('translaction.not_deleted'));
        }
        return $this->redirectToRoute('this_month');
    }

    /**
     * @Route("/lastmonth", name="last_month")
     */
    public function lastMonthAction()
    {
        return $this->render('expenses/lastmonth.html.twig');
    }
}
