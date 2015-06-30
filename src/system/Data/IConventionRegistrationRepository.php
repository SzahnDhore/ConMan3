<?php

namespace Szandor\ConMan\Data;

interface IConventionRegistrationRepository
{
    public function getRegistrationByUserId($userId);
    public function addRegistration($registrationData);
    public function updateRegistration($registrationId, $numberOfUpdates, $registrationData);
    public function getNumberOfUnconfirmedPayments();
    public function getRegistrations();
    public function getRegistrationData();
    public function getAllEntranceTypesForAllPeriods();
    public function confirmPayment($paymentId);
    public function dismissPayment($paymentId);

    public function confirmVisit($userId, $visitType);
    public function getAllVisitorUserIds();

    // statistics part
    public function getTimeDifferenceRegistrationCreatedAndPaymentRegistered($daysBackInTime);
    public function getRegistrationsPerDay();
}
