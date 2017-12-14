<?php
/**
 * Created by PhpStorm.
 * User: bleri
 * Date: 11/15/2017
 * Time: 10:37 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\GroupTransactions;
use AppBundle\Entity\Transaction;
use AppBundle\Entity\UserWallet;
use AppBundle\Form\TransactionType;
use AppBundle\Form\UserWalletType;
use AppBundle\Repository\GroupTransactionsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;

class ExpensesManager extends Controller
{
    /**
     * @Route("/month/{month}", name="this_month")
     */
    public function thisMonthAction(Request $request, $month = null)
    {
        if (!$month) $month = date('m');

        $em = $this->getDoctrine()->getManager();
        $newtransaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $newtransaction);
        $form->handleRequest($request);
        //add wallet budget
        $newUserWallet = new UserWallet();
        $userWalletForm = $this->createForm(UserWalletType::class, $newUserWallet);
        $userWalletForm->handleRequest($request);

        $loger = $this->get('logger');
        $translation = $this->get('translator');
        //add wallet
        if ($userWalletForm->isSubmitted() && $userWalletForm->isValid()) {
            try {
                $newUserWallet->setUserId($this->getUser()->getId());
                $em->persist($newUserWallet);
                $em->flush();

                $this->addFlash('success', $translation->trans('budget.registered'));
            } catch (Exception $exception) {
                $loger->addError('Wallet is not registered', ['e' => $exception]);
                $this->addFlash('error', $translation->trans('budget.not_registered'));
            }
            return $this->redirectToRoute('this_month');
        }
        $budgetForMonth = $this->getDoctrine()->getRepository(UserWallet::class)
            ->budgetForMonth($this->getUser(), $month);
        $remainingExpenses = $this->getDoctrine()->getRepository(Transaction::class)
            ->countAllTransactionAction($this->getUser(), $month);
        $editform = $this->createForm(TransactionType::class, $newtransaction);
        $editform->handleRequest($request);
        //show all transactions for current month
        $expenses = $this->getDoctrine()->getRepository(Transaction::class)
            ->expenses($this->getUser(), $month);
        $spent = [];
        foreach ($expenses as $transaction) {
            $spent[$transaction->getCreateDate()->format('d')][] = $transaction;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $date = new \DateTime();
                $date->setTime(0, 0, 0);
                $newtransaction->setCreateDate($date);
                $newtransaction->setUserId($this->getUser());
                $em->persist($newtransaction);
                $em->flush();

                $this->addFlash('success', $translation->trans('transaction.registered'));
            } catch (\Exception $exception) {
                $loger->addError('Transaction is not registered', ['e' => $exception]);
                $this->addFlash('error', $translation->trans('transaction.not_registered'));
            }
            return $this->redirectToRoute('this_month');
        }
        return $this->render('expenses/thismonth.html.twig', [
            'form' => $form->createView(),
            'userWalletForm' => $userWalletForm->createView(),
            'days' => $spent,
            'moneyInWallets' => $budgetForMonth,
            'totalTransactions' => $remainingExpenses
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
        if (!$transaction) {
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
                $this->addFlash('success', $translation->trans('transaction.edit_msg'));
            } catch (\Exception $exception) {
                $loger = $this->get('logger');
                $loger->addError('Transaction is not registered', ['e' => $exception]);
                $this->addFlash('error', $translation->trans('transaction.not_edit_msg'));
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
            $this->addFlash('success', $translation->trans('transaction.deleted'));
        } catch (\Exception $exception) {
            $logger = $this->get('logger');
            $logger->addError('Transaction nut deleted', ['e' => $exception]);
            $this->addFlash('error', $translation->trans('translaction.not_deleted'));
        }
        return $this->redirectToRoute('this_month');
    }
}
