<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:cron:global-stats')->dailyAt('23:59');
