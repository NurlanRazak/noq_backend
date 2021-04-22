<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   protected $service;
   protected $phone;
   protected $text;

   public function __construct(string $service, string $phone, string $text)
   {
       $this->service = $service;
       $this->phone = $phone;
       $this->text = $text;
   }

   public function handle()
   {
       $this->service::send($this->phone, $this->text);
   }
}
