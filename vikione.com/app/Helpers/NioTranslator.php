<?php 
namespace App\Helpers;

use Illuminate\Translation\Translator;
/**
 * NioTranslator
 */
class NioTranslator extends Translator
{
	
    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string|null  $locale
     * @param  bool  $fallback
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {

        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load that file
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$key] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        if (! isset($line)) {
            [$namespace, $group, $item] = $this->parseKey($key);

            // Here we will get the locale that should be used for the language line.
            $locales = $fallback ? $this->localeArray($locale) : [$locale];

            foreach ($locales as $locale) {
                if (! is_null($line = $this->getLine(
                    $namespace, $group, $locale, $item, $replace
                ))) {
                    return $line ?? $key;
                }
            }
        }

        // If the line doesn't exist, we will return back the key which was requested
        return $this->makeReplacements($line ?: $key, $replace);
    }

}