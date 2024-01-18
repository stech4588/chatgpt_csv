<?php
// File: app/helpers.php

// Load all individual helper files from the 'helpers' directory
foreach (glob(__DIR__.'/helpers/*.php') as $helperFile) {
require_once $helperFile;
}
