Nubium Sandbox - test task
=================

How to run
---
Simply run `docker compose up -d`

If You are running app for the first time You need to install composer dependencies - run `docker compose exec php composer install`

Application is configured to un on address https://nubium-sandbox.test/, so you need to edit Your hosts file and add record for this virtualhost

On windows is located at `c:\Windows\System32\drivers\etc` and You need to add one line: `127.0.0.1 nubium-sandbox.test`

First user is admin with e-mail/login `admin@nubium-sandbox.test` and password `password`

Thoughts about improvements and simplifications
---
- design - I'm no UX, I just grabbed bootstrap that I don't really know
- solve of edge cases 
- remove some logic from presenters and components
- create more components for some UI elements - now are there some duplicate code fragments like rating buttons
- e-mail for confirmation after registration (with link with token)
- administration
- more use of ajax and better use of ajax in rating
- html perex and text of the article (now it is only plaintext)
- icons
- proper configuration for prod environment - this is only for dev and demonstration purposes - now it is simplified