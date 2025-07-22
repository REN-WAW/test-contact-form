<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return ([
            'last_name' => 'required'|'string'|'max:255',
            'first_name' => 'required'|'string'|'max:255',
            'gender' => 'required'|'integer'|'in:1,2,3',
            'email' => 'required'|'string'|'email'|'max:255',
            'tel1' => 'required'|'string'|'max:5'|'regex:/^[0-9]+$/',
            'tel2' => 'required'|'string'|'max:5'|'regex:/^[0-9]+$/',
            'tel3' => 'required'|'string'|'max:5'|'regex:/^[0-9]+$/',
            'address' => 'required'|'string'|'max:255',
            'building' => 'nullable'|'string'|'max:255',
            'category_id' => 'required'|'integer'|'exists:categories,id',
            'detail' => 'required'|'string'|'max:120',
            'password' => 'required',
        ]);
    }

    public function messages()
    {
        return [
            'last_name.required' => '姓を入力してください',
            'first_name.required' => '名を入力してください',
            'gender.required' => '性別を選択してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'tel1.required' => '電話番号を入力してください',
            'tel1.max' => '電話番号は5桁までの数字で入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel2.max' => '電話番号は5桁までの数字で入力してください',
            'tel3.required' => '電話番号を入力してください',
            'tel3.max' => '電話番号は5桁までの数字で入力してください',
            'address.required' => '住所を入力してください',
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問合せ内容は120文字以内で入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }
}
