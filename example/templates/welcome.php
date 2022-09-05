<?php

use Battis\UserSession\Entities\UserEntityInterface;

if (!empty($user)) {
  /** @var UserEntityInterface $user */
  $username = $user->getIdentifier();
  echo "<div>Welcome $username!</div>";
}
