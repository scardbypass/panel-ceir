<?php
// Legacy compatibility route. Password reset now lives under /auth.
header('Location: /auth/lupa-password', true, 302);
exit;
