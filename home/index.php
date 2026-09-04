<?php
// Legacy compatibility route. Authentication now lives under /auth.
header('Location: /auth/login', true, 302);
exit;
