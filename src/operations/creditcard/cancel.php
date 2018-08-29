<?php

require '../../../vendor/autoload.php';
require '../../util/helperFunctions.php';
require '../../config.php';

use Wirecard\PaymentSdk\Transaction\CreditCardTransaction;
use Wirecard\PaymentSdk\Response\FailureResponse;
use Wirecard\PaymentSdk\Response\SuccessResponse;

$transactionId = htmlspecialchars($_POST['transactionId']);

$transaction = new CreditCardTransaction();
$transaction->setParentTransactionId($transactionId);

$service = createTransactionService('creditcard');
$response = $service->cancel($transaction);
    
if ($response instanceof SuccessResponse) {
    echo 'Payment successfully cancelled.<br>';
} elseif ($response instanceof FailureResponse) {
    echoFailureResponse($response);
}
