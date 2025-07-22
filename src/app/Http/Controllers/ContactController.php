<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;


class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('contact.index', compact('categories'));
    }

    public function confirm(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' =>'required|integer|exists:categories,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|integer|in:1,2,3',
            'email' => 'required|email|max:255',
            'tel1' => 'required|string|max:5',
            'tel2' => 'required|string|max:5',
            'tel3' => 'required|string|max:5',
            'address' => 'required|string|max:255',
            'building'  => 'nullable|string|max:255',
            'detail' => 'required|string|max:120',
        ]);
        $message = [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレス形式で入力してください',
        ];

        $fullTel = ($validatedData['tel1'] ?? '') . '-' . ($validatedData['tel2'] ?? '') . '-' . ($validatedData['tel3'] ?? '');
        $validatedData['tel'] = $fullTel;
        $categoryName = Category::find($validatedData['category_id'])->content;
        $request->session()->put('contact_form_data', $validatedData);

            return view('contact.confirm', compact('validatedData', 'categoryName'));
    }

    public function send(Request $request)
    {
        $contactData = $request->session()->get('contact_form_data');
        if (!$contactData) {
            return redirect()->route('contact.index')->with('error', 'フォームデータが見つかりません。再度入力してください。');
        }
        Contact::create([
            'category_id' => $contactData['category_id'],
            'first_name' => $contactData['first_name'],
            'last_name' => $contactData['last_name'],
            'gender' => $contactData['gender'],
            'email' => $contactData['email'],
            'tel' => $contactData['tel1'] . '-' . $contactData['tel2'] . '-' . $contactData['tel3'],
            'address' => $contactData['address'],
            'building' => $contactData['building'],
            'detail' => $contactData['detail'],
        ]);
        $request->session()->forget('contact_form_data');
        return redirect()->route('contact.thanks');
    }

    public function thanks()
    {
        return view('contact.thanks');
    }

    public function store(ContactRequest $request)
    {
        return view ('auth.login');
    }
}


