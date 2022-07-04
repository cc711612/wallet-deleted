<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Models\Wallets\Databases\Services\WalletDetailJobService;

class WalletUserRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        //
        $this
            ->onQueue('handle_register');
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        try {
            $wallet_id = Arr::get($this->params, 'wallet.id');
            $Wallet = (new WalletDetailJobService())
                ->updateAllSelectedWalletDetails($wallet_id);
            Log::channel('job')->info(sprintf("%s success params : %s", get_class($this),
                json_encode($this->params, JSON_UNESCAPED_UNICODE)));
        } catch (\Exception $exception) {
            Log::channel('job')->info(sprintf("%s Error params : %s", get_class($this),
                json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }
    }
}
