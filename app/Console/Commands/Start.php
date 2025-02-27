<?php

namespace App\Console\Commands;

use App\Services\RoleService;
use App\Services\UserService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'help:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the database data';

    private RoleService $roleService;

    private UserService $userService;

    public function __construct(
        RoleService $roleService,
        UserService $userService
    ) {
        parent::__construct();
        $this->roleService = $roleService;
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $this->roleService->start();
            $this->userService->start();

            DB::commit();
            $this->info('The data in the database has been initialized');
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
