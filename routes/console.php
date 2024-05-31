<?php

use App\Console\Commands\UpdateFeedsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(UpdateFeedsCommand::class)->hourly();
