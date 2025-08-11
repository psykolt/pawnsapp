<?php

namespace App\Console\Commands;

use App\Modules\Proxycheck\ProxycheckService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Proxytester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:proxytester';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var ProxycheckService $service */
        $service = App::make(ProxycheckService::class);

        //                $ip = '172.16.58.3'; // bad ip address
        $ip = '78.62.206.148';

        //        $isVpn = $service->isVpn($ip);
        $isVpn = $service->getCountryByIp($ip);

        dd($isVpn);
    }
}
