<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Initier un paiement
     */
    public function initiatePayment(array $data): array;
    
    /**
     * Vérifier le statut d'un paiement
     */
    public function checkStatus(string $paymentId): array;
    
    /**
     * Confirmer un paiement
     */
    public function confirmPayment(array $data): array;
    
    /**
     * Annuler un paiement
     */
    public function cancelPayment(string $paymentId): array;
    
    /**
     * Obtenir les méthodes de paiement supportées
     */
    public function getSupportedMethods(): array;
    
    /**
     * Vérifier si la méthode est supportée
     */
    public function isMethodSupported(string $method): bool;
}