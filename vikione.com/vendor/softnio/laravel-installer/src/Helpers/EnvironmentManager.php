<?php

namespace Softnio\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (!file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath() {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath() {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = ['response' => true,'message' => trans('installer_messages.environment.success')];

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        }
        catch(Exception $e) {
            $message = ['response' => false,'message' => trans('installer_messages.environment.errors')];
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function saveFileWizard(Request $request)
    {
        $results = ['response' => true,'message' => trans('installer_messages.environment.success')];

        $envFileData = $this->fileData($request);

        try {
            file_put_contents($this->envPath, $envFileData);
        } catch (Exception $e) {
            $results = ['response' => false,'message' => trans('installer_messages.environment.errors')];
        }

        return $results;
    }

    /**
     * Get content of the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function fileData(Request $request)
    {
        $key = is_writable($this->envPath) ? "base64:hcd7LG5XWs+r30DggGLIesbPjaqGHq9ng7mGN557T2U=\n" : $this->getNewKey();
        $data = 'APP_NAME=\'' . $request->app_name . "'\n" .
        'APP_VERSION=\'' . config('app.version') . "'\n" . // Added
        'APP_ENV=' . $request->environment . "\n" .
        'APP_KEY=' . $key .
        'APP_DEBUG=' . $request->app_debug . "\n" .
        'APP_LOG_LEVEL=' . $request->app_log_level . "\n" .
        'APP_URL=' . $request->app_url . "\n\n" .
        'FORCE_HTTPS=' . $request->is_https . "\n" . // Added
        'DB_CONNECTION=' . $request->database_connection . "\n" .
        'DB_HOST=' . $request->database_hostname . "\n" .
        'DB_PORT=' . $request->database_port . "\n" .
        'DB_DATABASE=' . $request->database_name . "\n" .
        'DB_USERNAME=' . $request->database_username . "\n" .
        'DB_PASSWORD=' . $request->database_password . "\n\n" .
        'BROADCAST_DRIVER=' . $request->broadcast_driver . "\n" .
        'CACHE_DRIVER=' . $request->cache_driver . "\n" .
        'SESSION_DRIVER=' . $request->session_driver . "\n" .
        'QUEUE_DRIVER=' . $request->queue_driver . "\n\n" .
        'REDIS_HOST=' . $request->redis_hostname . "\n" .
        'REDIS_PASSWORD=' . $request->redis_password . "\n" .
        'REDIS_PORT=' . $request->redis_port . "\n\n" .
        'MAIL_DRIVER=' . $request->mail_driver . "\n" .
        'MAIL_HOST=' . $request->mail_host . "\n" .
        'MAIL_PORT=' . $request->mail_port . "\n" .
        'MAIL_USERNAME=' . $request->mail_username . "\n" .
        'MAIL_PASSWORD=' . $request->mail_password . "\n" .
        'MAIL_ENCRYPTION=' . $request->mail_encryption . "\n\n" .
        'PUSHER_APP_ID=' . $request->pusher_app_id . "\n" .
        'PUSHER_APP_KEY=' . $request->pusher_app_key . "\n" .
        'PUSHER_APP_SECRET=' . $request->pusher_app_secret;

        return $data;
    }

    /**
     * Generate a new Application Key
     *
     * @param Request $request
     * @return string
     */
    public function getNewKey()
    {
        $outputLog = new BufferedOutput();
        Artisan::call('key:generate', ["--show"=> true], $outputLog);
        return $outputLog->fetch();
    }
}
