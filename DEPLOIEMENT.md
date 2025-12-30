# 🚀 Guide de Déploiement Portfolio sur o2switch

## 📋 Pré-requis
- Un compte o2switch actif
- Accès au cPanel
- Client FTP (FileZilla recommandé) ou utiliser le gestionnaire de fichiers cPanel

---

## 📂 Structure des fichiers à uploader

Tous les fichiers du dossier **Portfolio/** doivent être uploadés dans le dossier **public_html** de ton hébergement o2switch.

```
public_html/
├── index.html
├── send-email.php
├── .htaccess
├── messages.txt (sera créé automatiquement)
├── assets/
│   ├── css/
│   ├── scripts/
│   ├── img/
│   ├── fonts/
│   └── lib/
└── ...
```

⚠️ **IMPORTANT** : Ne pas créer de sous-dossier "Portfolio" dans public_html, copie directement le **contenu** du dossier Portfolio.

---

## 🔧 Étapes de déploiement

### 1. Connexion FTP ou cPanel
**Option A - Via FTP (FileZilla) :**
- Hôte : `ftp.tondomaine.com` ou `tondomaine.com`
- Utilisateur : ton nom d'utilisateur o2switch
- Mot de passe : ton mot de passe o2switch
- Port : 21

**Option B - Via cPanel :**
- Connecte-toi à `https://cpanel.o2switch.net/`
- Va dans "Gestionnaire de fichiers"

### 2. Upload des fichiers
1. Supprime le contenu par défaut de `public_html` (garde `cgi-bin` si présent)
2. Upload **tout le contenu** du dossier `Portfolio/` vers `public_html/`
3. Vérifie que `index.html` est bien à la racine de `public_html/`

### 3. Vérification des permissions
Dans cPanel > Gestionnaire de fichiers :
- `.htaccess` : 644
- `send-email.php` : 644
- `index.html` : 644
- Dossiers (assets, css, etc.) : 755

### 4. Configuration de l'email
Le fichier `send-email.php` est déjà configuré pour envoyer des emails.

**À personnaliser (ligne 57)** :
```php
$to = 'marie.rivier23@gmail.com';  // ← Ton email
```

**Optionnel** - Modifier l'expéditeur (lignes 115 et 194) :
```php
'From: Portfolio Contact <noreply@marie-rivier.com>'
```
Remplace `marie-rivier.com` par ton nom de domaine réel.

### 5. Activation HTTPS (une fois tout testé)
Dans `.htaccess`, décommente ces lignes (lignes 7-9) :
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 🧪 Tests post-déploiement

1. ✅ **Test de chargement** : Accède à `https://tondomaine.com`
   - Tu dois voir ton portfolio, PAS "Index of /"
   
2. ✅ **Test du formulaire de contact** :
   - Remplis et envoie le formulaire
   - Vérifie que tu reçois l'email sur `marie.rivier23@gmail.com`
   
3. ✅ **Test des liens** :
   - Clique sur tous les liens de navigation
   - Vérifie les liens vers LinkedIn, GitHub, Instagram

4. ✅ **Test responsive** :
   - Ouvre sur mobile/tablette
   - Vérifie le menu burger

---

## ⚠️ Dépannage

### Problème : "Index of /" s'affiche
**Solution** : 
- Vérifie que `index.html` est bien dans `public_html/`
- Vérifie que `.htaccess` est présent et uploadé correctement
- Vide le cache du navigateur (Ctrl + Shift + R)

### Problème : Le formulaire ne fonctionne pas
**Solutions** :
1. Vérifie les permissions de `send-email.php` (644)
2. Dans cPanel > PHP Version : utilise PHP 7.4 ou 8.0+
3. Regarde les logs d'erreur PHP dans cPanel > Erreurs
4. Vérifie que la fonction `mail()` est activée (normalement oui sur o2switch)

### Problème : Erreur 500
**Solution** : 
- Vérifie la syntaxe du `.htaccess`
- Désactive temporairement le `.htaccess` (renomme-le en `.htaccess.bak`)
- Consulte les logs d'erreur dans cPanel

### Problème : Les images ne s'affichent pas
**Solution** :
- Vérifie que le dossier `assets/img/` est bien uploadé
- Vérifie les permissions (755 pour dossiers, 644 pour images)
- Vérifie les chemins dans `index.html`

---

## 🔒 Sécurité

Le `.htaccess` inclus protège déjà :
- ✅ Désactive le listing des répertoires
- ✅ Protège `messages.txt` et autres fichiers sensibles
- ✅ Active les headers de sécurité (XSS, Clickjacking)
- ✅ Force HTTPS (à activer)
- ✅ Configure la compression et le cache

---

## 📧 Configuration avancée des emails (optionnel)

Si les emails ne passent pas (spam), configure un email o2switch :
1. Dans cPanel > Comptes email
2. Crée `noreply@tondomaine.com`
3. Modifie `send-email.php` ligne 115 :
```php
'From: Portfolio Contact <noreply@tondomaine.com>'
```

---

## 🌐 Nom de domaine personnalisé

Si tu veux un domaine personnalisé (ex: marie-rivier.com) :
1. Achète le domaine (chez o2switch ou ailleurs)
2. Dans cPanel > Domaines addon
3. Ajoute ton domaine
4. Pointe les DNS vers o2switch

---

## 📝 Checklist finale

- [ ] Tous les fichiers uploadés dans `public_html/`
- [ ] `index.html` présent à la racine
- [ ] `.htaccess` uploadé et actif
- [ ] Email configuré dans `send-email.php`
- [ ] Permissions correctes (644 fichiers, 755 dossiers)
- [ ] Site accessible via ton domaine
- [ ] Formulaire de contact testé et fonctionnel
- [ ] HTTPS activé (après tests)
- [ ] Cache navigateur vidé pour voir les changements

---

## 🆘 Support

**o2switch Support** : https://www.o2switch.fr/support/
**Documentation o2switch** : https://faq.o2switch.fr/

---

**Bonne mise en ligne ! 🎉**
