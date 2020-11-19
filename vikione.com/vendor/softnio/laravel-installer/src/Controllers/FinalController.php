<?php

namespace Softnio\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use Softnio\LaravelInstaller\Helpers\EnvironmentManager;
use Softnio\LaravelInstaller\Helpers\FinalInstallManager;
use Softnio\LaravelInstaller\Helpers\InstalledFileManager;
use Softnio\LaravelInstaller\Events\LaravelInstallerFinished;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param \Softnio\LaravelInstaller\Helpers\InstalledFileManager $fileManager
     * @param \Softnio\LaravelInstaller\Helpers\FinalInstallManager $finalInstall
     * @param \Softnio\LaravelInstaller\Helpers\EnvironmentManager $environment
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $installed = $fileManager->update();

        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = isset($installed['message']) ? $installed['message'] : $installed;
        $finalEnvFile = $environment->getEnvContent();

        event(new LaravelInstallerFinished);

        if(isset($installed['extra']) && $installed['extra'] == 'warning'){
            session()->flash('install_errors', trans('installer_messages.installed.failed_to_write'));
        }

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }

    /**
     * Get the file | when failed to install
     * 
     * @return file
     */
    public function file()
    {
        $dateStamp = date("Y/m/d h:i:sa");
        $content = trans('installer_messages.installed.success_log_message') . $dateStamp . "\n";

        //offer the content of txt as a download (logs.txt)
        return response($content)
                        ->withHeaders([
                            'Content-Type' => 'text/plain',
                            'Cache-Control' => 'no-store, no-cache',
                            'Content-Disposition' => 'attachment; filename="installed',
                        ]);
    }
}
