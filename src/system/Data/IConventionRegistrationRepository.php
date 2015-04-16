<?php

namespace Szandor\ConMan\Data;

interface IConventionRegistrationRepository
{
    public function getRegistrationByUserId($userId);
    public function addRegistration($registrationData);
    public function updateRegistration($registrationId, $numberOfUpdates, $registrationData);
    public function getNumberOfUnconfirmedPayments();
    public function getRegistrations();
    public function confirmPayment($paymentId);
    public function dismissPayment($paymentId);
}
