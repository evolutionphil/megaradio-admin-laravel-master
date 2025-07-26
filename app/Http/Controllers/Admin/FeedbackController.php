<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('pages.admin.feedbacks.index');
    }

    public function show(Feedback $feedback)
    {
        return view('pages.admin.feedbacks.show', compact('feedback'));
    }
}
