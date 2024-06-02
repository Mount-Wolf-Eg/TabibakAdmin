<?php

namespace App\Console\Commands;

use App\Constants\ConsultationStatusConstants;
use App\Constants\ConsultationTypeConstants;
use App\Repositories\Contracts\ConsultationContract;
use Illuminate\Console\Command;

class CancelUrgentConsultationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consultations:cancel-urgent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'after one hour if urgent consultations has no action it will be cancelled';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->warn('start cancelling outdated consultations');
        $contract = resolve(ConsultationContract::class);
        $toBeCancelledConsultations = $contract->search([
            'type' => ConsultationTypeConstants::URGENT->value,
            'createdBeforeHour' => true,
            'status' => ConsultationStatusConstants::PENDING,
        ], [], ['limit' => 0, 'page' => 0]);
        $count = count($toBeCancelledConsultations);
        if ($count){
            $this->warn("you have $count consultations to be cancelled");
            foreach ($toBeCancelledConsultations as $consultation){
                $contract->update($consultation, ['status' => ConsultationStatusConstants::PATIENT_CANCELLED->value]);
                $this->info("consultation of #id $consultation->id cancelled successfully");
            }
        }else{
            $this->error("you don't have any consultations to be cancelled");
        }
        $this->warn('end cancelling outdated consultations');
    }
}
