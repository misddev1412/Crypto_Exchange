<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'order_type' => [
                'required',
                Rule::in(array_keys(order_type()))
            ],
            'category' => [
                'required',
                Rule::in(array_keys(order_categories()))
            ],
            'trade_pair' => [
                'required',
                Rule::exists('coin_pairs', 'name')->where('is_active', ACTIVE)
            ],
            'price' => [
                Rule::requiredIf(function () {
                    return $this->get('category') !== ORDER_CATEGORY_MARKET;
                }),
                'numeric',
                'min:0.00000001',
                'decimal_scale:11,8'
            ],
            'amount' => [
                Rule::requiredIf(function () {
                    if ($this->get('category') === ORDER_CATEGORY_MARKET && $this->get('order_type') === ORDER_TYPE_BUY) {
                        return false;
                    }
                    return true;
                }),
                'numeric',
                'min:0.00000001',
                'decimal_scale:11,8'
            ],
            'total' => [
                Rule::requiredIf(function () {
                    if ($this->get('category') === ORDER_CATEGORY_MARKET && $this->get('order_type') === ORDER_TYPE_SELL) {
                        return false;
                    }
                    return true;
                }),
                'numeric',
                'decimal_scale:11,8'
            ]
        ];

        if ($this->get('category') === ORDER_CATEGORY_STOP_LIMIT) {
            $rules['stop'] = [
                'required',
                'numeric',
                'min:0.00000001',
                'decimal_scale:11,8'
            ];
        }

        return $rules;
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->after(function () use ($validator) {
            if ($this->get('category') === ORDER_CATEGORY_MARKET && $this->get('order_type') === ORDER_TYPE_SELL) {
                return;
            }

            if ($this->get('category') === ORDER_CATEGORY_MARKET && $this->get('order_type') === ORDER_TYPE_BUY) {
                $total = $this->get('total');
            } else {
                $total = bcmul($this->get('amount'), $this->get('price'));
                $this->merge(['total' => $total]);
            }

            if (bccomp($total, $this->input('total')) !== 0) {
                $validator->errors()->add('total', __('Invalid total amount'));
            }
        });

        return $validator;
    }
}
