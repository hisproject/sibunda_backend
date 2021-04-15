<?php

namespace App\Utils\Revocation;

use Dotenv\Exception\ValidationException;
use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class RevokeOldTokens
{
    /**
    * Create the event listener.
    *
    * @return void
    */

    /**
    * Handle the event.
    *
    * @param  AccessTokenCreated  $event
    * @return void
    */
    public function handle(AccessTokenCreated $event)
    {
        try{
            DB::table('oauth_access_tokens')
                ->where('id', '<>', $event->tokenId)
                ->where('user_id', $event->userId)
                ->where('client_id', $event->clientId)
                ->delete();
        }
        catch (ValidationException $e){}
        catch (Exception $e){}
    }
}
