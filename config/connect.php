<?php

ini_set('session.gc_maxlifetime', 43200);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(43200);
//echo $_ENV['DATABASE_SERVER'];
session_start();