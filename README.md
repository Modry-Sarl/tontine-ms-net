# virmo-cash
Virmo Cash - Clone de Virmo 2 par Laurette Mintoume

## Exigences
php >= 8.1
mysql >= 8.0

# Installation

1 - Cloner le projet 

```shell
git clone https://github.com/Modry-Sarl/tontine-ms-net.git virmocash
```

2 - Installer les dependances

```shell
cd virmocash

composer install
```

3 - Creer le fichier `.env` puis modifier la section BASE DE DONNES

```shell
cp .env.example .env
```

4 - Creer la base de donnees

```shell
php klinge db:create virmocash
```

5 - Creer la structure de la base de donnees

```shell
php klinge migrate --all
```

6 - Initialiser le compte d'utilisateur par défaut 

```shell
php klinge db:seed InitialUser
```

7 - Demarrer le serveur de developpement

```shell
php klinge serve
```

Le serveur demarre a l'adresse `http://localhost:3300`

Login: admin@virmocash.com 
Mot de passe: password