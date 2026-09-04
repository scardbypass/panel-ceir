<?php
// Legacy compatibility route. Registration now lives under /auth.
header('Location: /auth/register', true, 302);
exit;
