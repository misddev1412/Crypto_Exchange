<?php

namespace App\Services\Core;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class LanguageService
{
    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    private $disk;

    /**
     * Path to the language files.
     *
     * @var string
     */
    private $languageFilesPath;

    /**
     * Paths we will look inside to find translations.
     *
     * @var array
     */
    private $lookupPaths;

    /**
     * Available translations.
     *
     * @var array
     */
    private $translations = [];

    /**
     * Manager constructor.
     *
     * @param Filesystem $disk
     * @param string $languageFilesPath
     * @param array $lookupPaths
     */
    public function __construct(Filesystem $disk, $languageFilesPath, array $lookupPaths)
    {
        $this->disk = $disk;
        $this->languageFilesPath = $languageFilesPath;
        $this->lookupPaths = $lookupPaths;
    }

    /**
     * Add a new JSON language file.
     * @param $language
     * @return void
     */
    public function addLanguage($language)
    {
        file_put_contents($this->languageFilesPath . DIRECTORY_SEPARATOR . "$language.json",
            json_encode((object)[], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
        $this->sync();
    }

    /**
     * Synchronize the language keys from files.
     * @return array
     */
    public function sync()
    {
        $output = [];

        $translations = $this->getTranslations();

        $keysFromFiles = Arr::collapse($this->getTranslationsFromFiles());

        foreach (array_unique($keysFromFiles) as $fileName => $key) {
            foreach ($translations as $lang => $keys) {
                if (!array_key_exists($key, $keys)) {
                    $output[] = $key;
                    $translations[$lang][$key] = $key;
                }
            }
        }

        $output = array_values(array_unique($output));
        if (!empty($output)) {
            $this->saveTranslations($translations);
        }

        return [
            'type' => 'success',
            'message' => __('Sync completed. Newly :add keys added.', ['add' => count($output)]),
            'translations' => $this->getTranslations(true),
        ];

    }

    /**
     * Get all the available lines.
     * @param bool $reload
     * @return array
     */
    public function getTranslations($reload = false)
    {
        if ($this->translations && !$reload) {
            return $this->translations;
        }

        collect($this->disk->allFiles($this->languageFilesPath))
            ->filter(function ($file) {
                return $this->disk->extension($file) == 'json';
            })
            ->each(function ($file) {
                $this->translations[str_replace('.json', '', $file->getFilename())]
                    = json_decode($file->getContents(), true);
            });

        return $this->translations;
    }

    /**
     * Get found translation lines found per file.
     *
     * @return array
     */
    private function getTranslationsFromFiles()
    {
        /*
         * This pattern is derived from Barryvdh\TranslationManager by Barry vd. Heuvel <barryvdh@gmail.com>
         *
         * https://github.com/barryvdh/laravel-translation-manager/blob/master/src/Manager.php
         */
        $functions = ['__'];

        $pattern =
            // See https://regex101.com/r/jS5fX0/5
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode(',', $functions) . ')' .// Must start with one of the functions
            "\(" .// Match opening parentheses
            "[\'\"]" .// Match " or '
            '(' .// Start a new group to match:
            '.+' .// Must start with group
            ')' .// Close group
            "[\'\"]" .// Closing quote
            "[\),]"  // Close parentheses or new parameter
        ;

        $allMatches = [];

        foreach ($this->disk->allFiles($this->lookupPaths) as $file) {
            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches)) {
                $allMatches[$file->getRelativePathname()] = $matches[2];
            }
        }

        return $allMatches;
    }

    /**
     * Save the given translations.
     * @param $translations
     * @return void
     */
    public function saveTranslations($translations)
    {
        foreach ($translations as $lang => $lines) {
            $filename = $this->languageFilesPath . DIRECTORY_SEPARATOR . "$lang.json";
            ksort($lines);
            $this->translations[$lang] = $lines;
            file_put_contents($filename, json_encode($lines, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }

    public function rename($oldName, $newName)
    {
        $oldFilePath = $this->languageFilesPath . DIRECTORY_SEPARATOR . "$oldName.json";
        $newFilePath = $this->languageFilesPath . DIRECTORY_SEPARATOR . "$newName.json";
        return rename($oldFilePath, $newFilePath);
    }

    public function delete($language)
    {
        $filePath = $this->languageFilesPath . DIRECTORY_SEPARATOR . "$language.json";
        return unlink($filePath);
    }
}
