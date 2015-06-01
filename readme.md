fiWallet
============

Rozchození aplikace - JS
==========================

Je potreba si nainstalovat python, node. Pote v konzoli v rootu aplikace zavolat:

```
npm install
```

Pro instaci potrebnych JS balicku.

```
npm start
```

Pro sledovani zmen a kompilaci bundle.js

```
npm run-script debug
npm run-script release
```

Tohle vytvori jednorazove bundle.js - debug je na vyvoj, release na produkci (minifikovan)

Jeste zjistime, jak to zautomatizovat pres phpStorm

Rozchození aplikace - PHP
==========================

Je nutné si nainstalovat composer (v PATH) a PHP 5.6 a vyšší v systému.

Po stáhnutí repozitáře prve spustíte `composer install` v konzoli (v PhpStormu ji lze vyvolat pomocí kombinace Ctrl-Shift-X), čímž se vám stáhnou závislosti projektu.

Poté zkopírujte soubor `app\config\config.local.example.neon` jako `app\config\config.local.neon` a upravte jej podle vlastního nastavení databáze.

Aplikaci spustíte pomocí na portu 8888 (dostupná v prohlížeči jako http://localhost:8888) pomocí příkazu:

```
php -S localhost:8888 -t www
```

Je nutné mít Php v systémové PATH. Funkčnost toho, že vám to funguje, můžete udělat pomocí příkazu `php www\index.php`, který vám výpíše seznam dostupných konzolových příkazů pro aplikaci.

Pro update databáze do nejnovějšího schématu spustěte:

```
php www\index.php migrations:migrate
```

Nastavení PhpStorm
===================

Nainstalujte si následující pluginy:

- EditorConfig
- PHP Annotations

Pluginy lze najít přes: Settings -> Plugins -> Browse repositories

**FileWatchers (to immediate file sync si vypnete, dela to bordel) ->**

![2015-03-08 20_19_32-fiwallet - [D__fiwallet] - ..._package.json - PhpStorm 8.0.3.png](https://bitbucket.org/repo/aAB8d7/images/3777441086-2015-03-08%2020_19_32-fiwallet%20-%20%5BD__fiwallet%5D%20-%20..._package.json%20-%20PhpStorm%208.0.3.png)

Požadavky
=========

- PHP 5.6 a vyšší
- composer (https://getcomposer.org/)
- Python 2.7
- NodeJS
- phpStorm

Dodatečné informace
===================

Zkusit si vygenerovat rekurentní transakci pro dnešní den můžete pomocí

```
php www\index.php fiwallet:process-recurrent-transactions
```

Dodatky
=========
- http://timschlechter.github.io/bootstrap-tagsinput/examples/
- https://github.com/skratchdot/react-bootstrap-daterangepicker
