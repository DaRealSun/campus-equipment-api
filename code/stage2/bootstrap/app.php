<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\ContractError;
return Application::configure(basePath: dirname(__DIR__))
 ->withRouting(api: __DIR__.'/../routes/api.php', commands: __DIR__.'/../routes/console.php', health:'/up')
 ->withMiddleware(function (Middleware $middleware): void {})
 ->withExceptions(function (Exceptions $exceptions): void {
  $exceptions->shouldRenderJsonWhen(fn(Request $r) => true);
  $exceptions->render(function(ContractError $e, Request $r) {
   return response()->json(['error'=>['code'=>$e->label,'message'=>$e->getMessage()]], $e->status);
  });
  $exceptions->render(function(\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e, Request $r) {
   return response()->json(['error'=>['code'=>'http_error','message'=> $e->getStatusCode()===404?'Endpoint not found.':'Request could not be processed.']], $e->getStatusCode());
  });
 })->create();
