<?php
namespace App\Exceptions;
class ContractError extends \RuntimeException {
 public function __construct(public int $status, public string $label, string $message) { parent::__construct($message); }
}
