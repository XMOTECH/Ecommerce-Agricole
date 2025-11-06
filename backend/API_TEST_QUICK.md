# Guide Rapide de Test API

## 🚀 Démarrage Rapide

### 1. Démarrer le serveur
```bash
cd backend
php artisan serve
```

### 2. Tester l'endpoint de base
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/test"
```

## 📋 Tests Essentiels

### Test 1: Endpoint de test (GET)
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/test" -Method Get
```
**Résultat attendu** : `{"message": "API works!"}`

### Test 2: Liste des produits (GET)
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/products" -Method Get
```

### Test 3: Inscription (POST)
```powershell
$body = @{
    name = "Test User"
    email = "test$(Get-Random)@example.com"
    phone = "+221771234567"
    password = "password123"
    password_confirmation = "password123"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://localhost:8000/api/register" -Method Post -Body $body -ContentType "application/json"
$token = $response.token
Write-Host "Token: $token"
```

### Test 4: Connexion (POST)
```powershell
$loginBody = @{
    email = "admin@agrimarket.com"
    password = "password"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" -Method Post -Body $loginBody -ContentType "application/json"
$token = $response.token
```

### Test 5: Utilisateur connecté (GET - Authentifié)
```powershell
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}
Invoke-RestMethod -Uri "http://localhost:8000/api/user" -Method Get -Headers $headers
```

### Test 6: Liste des commandes (GET - Authentifié)
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/orders" -Method Get -Headers $headers
```

## ✅ Checklist de Vérification

- [ ] Serveur Laravel démarré (`php artisan serve`)
- [ ] Base de données migrée (`php artisan migrate`)
- [ ] Rôles et permissions créés (`php artisan db:seed --class=RolePermissionSeeder`)
- [ ] Endpoint `/api/test` répond
- [ ] Endpoint `/api/products` répond
- [ ] Inscription fonctionne
- [ ] Connexion fonctionne
- [ ] Token d'authentification reçu
- [ ] Routes protégées accessibles avec token

## 🔧 Comptes de Test

**Admin** :
- Email: `admin@agrimarket.com`
- Password: `password`

**Client** :
- Email: `client@agrimarket.com`
- Password: `password`

## 📝 Notes

- Les tokens expirent après 7 jours
- Utilisez le header `Authorization: Bearer {token}` pour les routes protégées
- Toutes les réponses sont en JSON



