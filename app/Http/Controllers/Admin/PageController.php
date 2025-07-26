<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.admin.pages.index');
    }

    public function create()
    {
        Page::create([
            'name' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'titles' => [
                'en' => 'Page title',
            ],
            'descriptions' => [
                'en' => 'Page title',
            ],
            'keywords' => [
                'en' => 'Page title',
            ],
            'contents' => [
                'en' => 'Name in English',
            ],
        ]);
        Page::create([
            'name' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'titles' => [
                'en' => 'Page title',
            ],
            'descriptions' => [
                'en' => 'Page title',
            ],
            'keywords' => [
                'en' => 'Page title',
            ],
            'contents' => [
                'en' => 'Name in English',
            ],
        ]);
        Page::create([
            'name' => 'Refund Policy',
            'slug' => 'refund-policy',
            'titles' => [
                'en' => 'Page title',
            ],
            'descriptions' => [
                'en' => 'Page title',
            ],
            'keywords' => [
                'en' => 'Page title',
            ],
            'contents' => [
                'en' => 'Name in English',
            ],
        ]);
        Page::create([
            'name' => 'Cookie Policy',
            'slug' => 'cookie-policy',
            'titles' => [
                'en' => 'Page title',
            ],
            'descriptions' => [
                'en' => 'Page title',
            ],
            'keywords' => [
                'en' => 'Page title',
            ],
            'contents' => [
                'en' => 'Name in English',
            ],
        ]);
    }

    public function edit(Page $page)
    {
        $existingLanguages = array_keys($page->getTranslations('contents'));

        $languages = Language::orderBy('name')->whereNoIn('key', $existingLanguages)->get();

        return view('pages.admin.pages.edit', compact('page', 'languages'));
    }

    public function update(Page $page, Request $request)
    {
        $page->name = $request->name;
        $page->setTranslations('contents', $request->get('contents', []));
        $page->save();

        return redirect()->back()->with('success', 'Saved.');
    }

    public function saveTranslation(Page $page, Request $request)
    {
        $request->validate([
            'language' => 'required',
            'content' => 'required',
        ]);

        $page->setTranslation('contents', $request->get('language'), $request->get('content'))->save();

        return redirect()->back()->with('success', 'Saved.');
    }
}
