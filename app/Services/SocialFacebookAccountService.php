<?php

namespace App\Services;
use App\Models\SocialAccounts;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialFacebookAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    { 
        $account = SocialAccounts::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();        
        if ($account) 
        {
            
            return $account->user;
        } 
        else 
        {            
            $account = new SocialAccounts([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'facebook'
            ]);            
            $user = User::whereEmail($providerUser->getEmail())->first();            
            if (!$user) {                
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    'username' => strtolower(str_replace(' ','_',$providerUser->getName())),
                    'password' => bcrypt(rand(1,10000)),
                    'status' => 'active'
                ]);
            }            
            $account->user()->associate($user);
            $account->save();            
            return $user;
        }
    }
}