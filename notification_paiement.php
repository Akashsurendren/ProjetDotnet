<?php
// notification_paiement.php - Le gardien du coffre-fort
require_once('db.php');

// 1. Récupération des données envoyées par CinetPay (POST)
$id_transaction = $_POST['cpm_trans_id'] ?? null;
$site_id = $_POST['cpm_site_id'] ?? null;
$signature = $_POST['cpm_hmac'] ?? null; // La clé de sécurité

if ($id_transaction) {
    // 2. ON NE CROIT PAS LE POST SUR PAROLE ! 
    // On appelle l'API de CinetPay pour vérifier le statut réel
    $apiKey = "TON_API_KEY_CINETPAY"; 
    $siteId = "TON_SITE_ID";

    // Simulation de la vérification (en réel, on utilise cURL)
    $statut_reel = "ACCEPTED"; // Ce que CinetPay nous répond après vérification

    if ($statut_reel === 'ACCEPTED') {
        
        // 3. On cherche la transaction dans TA table
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE reference_interne = ? AND statut = 'PENDING'");
        $stmt->execute([$id_transaction]);
        $maTrans = $stmt->fetch();

        if ($maTrans) {
            // A. On valide la transaction
            $up = $pdo->prepare("UPDATE transactions SET statut = 'SUCCESS' WHERE reference_interne = ?");
            $up->execute([$id_transaction]);

            // B. ON CRÉDITE LE CLIENT (La thune arrive !)
            $addSolde = $pdo->prepare("UPDATE utilisateurs SET solde = solde + ? WHERE pseudo = ?");
            $addSolde->execute([$maTrans['montant'], $maTrans['pseudo']]);
            
            // C. On log dans l'historique pour la transparence
            $insH = $pdo->prepare("INSERT INTO historique (pseudo, details, total) VALUES (?, ?, ?)");
            $insH->execute([$maTrans['pseudo'], "Recharge Mobile Money", $maTrans['montant']]);
        }
    }
}
// 4. On répond à CinetPay que c'est reçu
header("HTTP/1.1 200 OK");
?>