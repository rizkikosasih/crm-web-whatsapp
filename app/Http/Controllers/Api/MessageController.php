<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Message;

class MessageController extends Controller
{
  public function receive(Request $request)
  {
    $request->validate([
      'from' => 'required|string',
      'content' => 'required|string',
    ]);

    $contact = Contact::firstOrCreate(['phone' => $request->from], ['name' => null]);

    $contact->messages()->create([
      'content' => $request->content,
      'direction' => 'in',
    ]);

    return response()->json(['status' => 'ok']);
  }
}
