<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureQueueOutput();
    }

    protected function configureQueueOutput(): void
    {
        if (app()->runningInConsole()) {
            Event::listen(JobProcessing::class, fn (JobProcessing $event) => 
                $this->outputColored('▶ Processing: ' . class_basename($event->job->resolveName())));

            Event::listen(JobProcessed::class, fn (JobProcessed $event) => 
                $this->outputColored('✓ Completed: ' . class_basename($event->job->resolveName())));

            Event::listen(JobFailed::class, fn (JobFailed $event) => 
                $this->outputColored('✗ Failed: ' . class_basename($event->job->resolveName())));
        }
    }

    protected function outputColored(string $message): void
    {
        $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, true);
        
        $colorized = match(true) {
            str_starts_with($message, '▶') => "<fg=cyan;bold>{$message}</>",
            str_starts_with($message, '✓') => "<fg=green;bold>{$message}</>",
            str_starts_with($message, '✗') => "<fg=red;bold>{$message}</>",
            default => $message,
        };

        $output->writeln($colorized);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
