<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\GoogleTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Storage;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('name', 'asc')->get();

        return view('pages.admin.languages.index', compact('languages'));
    }

    public function generateSitemap(Language $language)
    {
        Artisan::queue('app:gen:sitemaps '.$language->key)
            ->onQueue('sitemaps');

        return redirect()->back()->with('success', 'Generating sitemap, it will take a minute or two. Once done you will be able to see it from the language list.');
    }

    public function store(Request $request)
    {
        Language::create([
            'name' => $request->name,
            'key' => Str::slug($request->key, '_'),
            'phrases' => self::generateEmptyLanguageData(),
            'is_published' => $request->is_published,
            'is_rtl' => $request->is_rtl,
        ]);

        return redirect()->back()->with('success', 'New language added.');
    }

    protected static function generateEmptyLanguageData()
    {
        $language = Language::where('phrases', '!=', null)->first();
        $data = [];

        if ($language) {
            foreach ($language->phrases as $key => $value) {
                $data[$key] = '';
            }

            uksort($data, 'strnatcasecmp'); // Shorts keys alaphbetically
        }

        return $data;
    }

    public function edit(Language $language)
    {
        $pages = ['home_page', 'radio_playing_page'];

        $englishTranslations = Language::where('key', 'en')->first()->phrases;

        return view('pages.admin.languages.edit', compact('language', 'pages', 'englishTranslations'));
    }

    public function autoTranslate(Language $language, Request $request)
    {
        $translator = app(GoogleTranslationService::class);

        $englishLanguage = Language::where('key', 'en')->first();

        $siteMeta = collect($englishLanguage->site_meta)->flatMap(function ($pageMetaItems, $key) {
            return collect($pageMetaItems)->flatMap(function ($item, $itemKey) use ($key) {
                return [$key.'.'.$itemKey => $item];
            });
        })->toArray();

        $metaFlipped = array_flip($siteMeta);

        $translations = $translator->translate(array_keys($metaFlipped), 'en', $language->key);

        $translatedMeta = [];

        foreach ($translations as $translation) {
            $translatedMeta[$metaFlipped[$translation['input']]] = $translation['text'];
        }

        $translatedMeta = collect($translatedMeta)->groupBy(function ($item, $key) {
            return explode('.', $key)[0];
        }, preserveKeys: true)
            ->map(function ($item, $key) {
                return collect($item)->flatMap(function ($item, $itemKey) {
                    return [explode('.', $itemKey)[1] => $item];
                });
            })->toArray();

        $phrasesFlipped = array_flip($englishLanguage->phrases);

        $phrasesFlippedChunks = collect($phrasesFlipped)
            ->chunk(128);

        $translations = [];
        foreach ($phrasesFlippedChunks as $phrasesFlippedChunk) {
            $response = $translator->translate(array_keys($phrasesFlippedChunk->toArray()), 'en', $language->key);

            $translations = array_merge($translations, $response);
        }

        $translatedPhrases = [];

        foreach ($translations as $translation) {
            $translatedPhrases[$phrasesFlipped[$translation['input']]] = $translation['text'];
        }

        try {
            $language->update([
                'phrases' => $translatedPhrases,
                'site_meta' => $translatedMeta,
            ]);

            return redirect()->back()->with('success', 'Auto translated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function translate(Request $request)
    {
        $translator = app(GoogleTranslationService::class);

        $translations = $translator->translate([$request->phrase], $request->get('source_language', 'en'), $request->target_language);

        return $translations[0]['text'];
    }

    public function update(Language $language, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'key' => 'required',
            'is_published' => ['required'],
            'translations' => 'required',
            'site_meta.*.title' => ['required', 'max:160'],
            'site_meta.*.description' => ['required', 'max:160'],
        ]);

        try {
            $language->update([
                'name' => $request->name,
                'key' => $request->key,
                'iso' => $request->iso,
                'is_published' => $request->is_published == 'true',
                'phrases' => $request->translations,
                'site_meta' => $request->site_meta,
            ]);

            return redirect()->back()->with('success', 'Language updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    public function addNewPhrase(Request $request)
    {
        $phrases = $request->phrases;

        foreach ($phrases as $langKey => $phrase) {
            $lang = Language::where('key', $langKey)->first();

            $ops = $lang->phrases;
            $ops = (array) $ops;

            $np = [Str::snake($request->key) => $phrase];

            $rp = array_merge($ops, $np);
            uksort($rp, 'strnatcasecmp');

            Language::where('key', $langKey)
                ->update([
                    'phrases' => $rp,
                ]);
        }

        return redirect()->back()->with('success', 'New phrase added.');
    }

    public function import(Request $request)
    {
        $file = Storage::path($request->file('import_file')->storeAs('imports', 'import.csv'));

        $phrases = [];
        $languages = [];
        $availableLanguages = Language::select('key')->get()->pluck('key')->toArray();

        $phrases = Language::get()->pluck('phrases', 'key')->toArray();

        (new FastExcel)->import($file, function ($line) use ($languages, &$phrases, $availableLanguages) {
            $line = collect($line);

            if (count($languages) == 0) {
                $languages = $line->keys()
                    ->except([0])
                    ->merge($availableLanguages)
                    ->unique()
                    ->toArray();
            }

            foreach ($languages as $languageKey) {
                if (! isset($phrases[$languageKey])) {
                    continue;
                }
                $phraseKey = Str::snake($line->get('key'));

                $phrases[$languageKey][$phraseKey] = $line->get($languageKey, isset($phrases[$languageKey][$phraseKey]) ? $phrases[$languageKey][$phraseKey] : null);
            }
        });

        foreach ($phrases as $key => $words) {
            Language::where('key', $key)->update([
                'phrases' => $words,
            ]);
        }

        return redirect()->back()->with('success', 'Tranlations imported.');
    }
}
