<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';

use Wirecard\PaymentSdk\Entity\AccountHolder;
use Wirecard\PaymentSdk\Entity\Mandate;
use Wirecard\PaymentSdk\Transaction\SepaCreditTransferTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;
use Wirecard\PaymentSdk\Exception\MalformedResponseException;

$accountHolderLastName = htmlspecialchars($_POST['accountHolderLastName']);
$accountHolderFirstName = htmlspecialchars($_POST['accountHolderFirstName']);
$mandateId = htmlspecialchars($_POST['mandateId']);

$transaction = new SepaCreditTransferTransaction();

$accountHolder = new AccountHolder();
$accountHolder->setLastName($accountHolderLastName);
$accountHolder->setFirstName($accountHolderFirstName);
$transaction->setAccountHolder($accountHolder);

$mandate = new Mandate($mandateId);
$transaction->setMandate($mandate);

if (array_key_exists('parentTransactionId', $_POST)) {
	$transaction->setParentTransactionId($_POST['parentTransactionId']);
}

$service = createTransactionService('sofortbanking');

try {
	$response = $service->credit($transaction);
	if ($response instanceof SuccessResponse) {
     echo 'Refund via SEPA Credit Transfer successfully completed.<br>';
	} elseif ($response instanceof FailureResponse) {
    	echoFailureResponse($response);
	}
} catch(MalformedResponseException $e) {
	echo $e->getTraceAsString() . '<br>';
	echo $e->getMessage();
}
