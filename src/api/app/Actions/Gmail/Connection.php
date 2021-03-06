<?php
namespace App\Actions\Gmail;

use Google_Client; 
// use Google_Service_Gmail;
use Google\Service\Gmail;
use Exception;
use InvalidArgumentException;


// Copyright 2017 DAIMTO ([Linda Lawton](https://twitter.com/LindaLawtonDK)) :  [www.daimto.com](http://www.daimto.com/)
//
// Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
// the License. You may obtain a copy of the License at
//
// http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on
// an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the
// specific language governing permissions and limitations under the License.
//------------------------------------------------------------------------------
// <auto-generated>
//     This code was generated by DAIMTO-Google-apis-Sample-generator 1.0.0
//     Template File Name:  Oauth2Authentication.tt
//     Build date: 2017-10-08
//     PHP generator version: 1.0.0
//     
//     Changes to this file may cause incorrect behavior and will be lost if
//     the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------  
// About 
// 
// Unofficial sample for the gmail v1 API for PHP. 
// This sample is designed to be used with the Google PHP client library. (https://github.com/google/google-api-php-client)
// 
// API Description: Access Gmail mailboxes including sending user email.
// API Documentation Link https://developers.google.com/gmail/api/
//
// Discovery Doc  https://www.googleapis.com/discovery/v1/apis/gmail/v1/rest
//
//------------------------------------------------------------------------------
// Installation
//
// The preferred method is via https://getcomposer.org. Follow the installation instructions https://getcomposer.org/doc/00-intro.md 
// if you do not already have composer installed.
//
// Once composer is installed, execute the following command in your project root to install this library:
//
// composer require google/apiclient:^2.0
//
//------------------------------------------------------------------------------  
// require_once __DIR__ . '/vendor/autoload.php';
/**
 * Gets the Google client refreshing auth if needed.
 * Documentation: https://developers.google.com/identity/protocols/OAuth2
 * Initializes a client object.
 * @return A google client object.
 */


class Connection {
	public function getGoogleClient() {
		
		$credentialsPath=base_path().'/app/Actions/Gmail/client_secrets.json';
	
		$client = $this->getOauth2Client();
	    // dd($client);
		// Refresh the token if it's expired.
		// if ($client->isAccessTokenExpired()) {
		// 	$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
		// 	file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
		// }
		
	   return $client;
	}
	
	/**
	 * Builds the Google client object.
	 * Documentation: https://developers.google.com/identity/protocols/OAuth2
	 * Scopes will need to be changed depending upon the API's being accessed.
	 * Example:  array(Google_Service_Analytics::ANALYTICS_READONLY, Google_Service_Analytics::ANALYTICS)
	 * List of Google Scopes: https://developers.google.com/identity/protocols/googlescopes
	 * @return A google client object.
	 */
	public function buildClient(){
		
		$client = new Google_Client();
		$client->setAccessType("offline");        // offline access.  Will result in a refresh token
		$client->setIncludeGrantedScopes(true);   // incremental auth
		$client->setAuthConfig(__DIR__ . '/client_secrets.json');
		
		$client->addScope(Gmail::GMAIL_READONLY);
		$client->setRedirectUri($this->getRedirectUri());	
	
		return $client;
	}
	
	/**
	 * Builds the redirect uri.
	 * Documentation: https://developers.google.com/api-client-library/python/auth/installed-app#choosingredirecturi
	 * Hostname and current server path are needed to redirect to oauth2callback.php
	 * @return A redirect uri.
	 */
	public function getRedirectUri(){
		
		//Building Redirect URI
		$url = $_SERVER['REQUEST_URI'];                    //returns the current URL
		// dd($url);
		if(strrpos($url, '?') > 0)
			$url = substr($url, 0, strrpos($url, '?') );  // Removing any parameters.
		$folder = substr($url, 0, strrpos($url, '/') );   // Removeing current file.
	   return  (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . $folder. '/callback';
	}
	
	
	/**
	 * Authenticating to Google using Oauth2
	 * Documentation:  https://developers.google.com/identity/protocols/OAuth2
	 * Returns a Google client with refresh token and access tokens set. 
	 *  If not authencated then we will redirect to request authencation.
	 * @return A google client object.
	 */
	public function getOauth2Client() {
		try {
			session_start();
			$client = $this->buildClient();
			
			// dd($_SESSION['refresh_token']);
			// Set the refresh token on the client.	
			if (isset($_SESSION['refresh_token']) && $_SESSION['refresh_token']) {
			
				$client->refreshToken($_SESSION['refresh_token']);
			}
			
			// If the user has already authorized this app then get an access token
			// else redirect to ask the user to authorize access to Google Analytics.
			if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
				// dd('sdsd');
				// Set the access token on the client.
				$client->setAccessToken($_SESSION['access_token']);					
				
				// Refresh the access token if it's expired.
				if ($client->isAccessTokenExpired()) {				
					$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
					$client->setAccessToken($client->getAccessToken());	
					$_SESSION['access_token'] = $client->getAccessToken();				
				}			
				return $client;	
			} else {
				
				//dd('Location: ' . filter_var( $client->getRedirectUri(), FILTER_SANITIZE_URL));
				header('Location: ' . filter_var( $client->getRedirectUri(), FILTER_SANITIZE_URL));
			}
		} catch (Exception $e) {
			print "An error occurred: " . $e->getMessage();
		}
	}
}

?>