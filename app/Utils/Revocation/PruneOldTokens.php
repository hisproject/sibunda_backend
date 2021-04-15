<?php
namespace App\Utils\Revocation;

use Dotenv\Exception\ValidationException;
use Laravel\Passport\Events\RefreshTokenCreated;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PruneOldTokens
{
    /**
     * Create the event listener.
     *
     * @return void
     */


    /**
     * Handle the event.
     *
     * @param  RefreshTokenCreated  $event
     * @return void
     */
    public function handle(RefreshTokenCreated $event)
    {
        try{
            $refresh_tokens = DB::table('oauth_access_tokens')
                ->where('user_id', DB::table('oauth_access_tokens')
                    ->where('id', $event->accessTokenId)
                    ->value('user_id'))
                ->where('id', '<>', $event->accessTokenId)
                ->pluck('id');

            DB::table('oauth_refresh_tokens')
                ->where('id', '<>', $event->refreshTokenId)
                ->orWhereIn('access_token_id', $refresh_tokens)
                ->delete();
        }
        catch (ValidationException $e){}
        catch (Exception $e){}
    }
}
