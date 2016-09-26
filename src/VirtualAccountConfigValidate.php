<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use ReflectionClass;

trait VirtualAccountConfigValidate
{
    /**
     * @return VirtualAccountBuilder
     */
    protected function validateConfig()
    {
        static::validateRequired([
            'type' => function($field) {
                if (!in_array($field, (new ReflectionClass(VerifyType::class))->getConstants())) {
                    throw new InvalidArgumentException('Generator not implemented.');
                }
            },
            'length' => function($field) {
                $field = intval($field);
                if (!in_array($field, [13, 14])) {
                    throw new InvalidArgumentException('Generator account length not defined.');
                }
            }
        ]);

        $validators = [
            VerifyType::NONE_BASE => 'NoneBase',
            VerifyType::SINGLE_BASE => 'SingleBase',
            VerifyType::DOUBLE_BASE => 'DoubleBase',
            VerifyType::SINGLE_AMOUNT => 'SingleAmount',
            VerifyType::DOUBLE_AMOUNT => 'DoubleAmount',
            VerifyType::SINGLE_AMOUNT_DATE => 'SingleAmountAndDate',
            VerifyType::DOUBLE_AMOUNT_DATE => 'DoubleAmountAndDate',
            VerifyType::SINGLE_AMOUNT_DATETIME => 'SingleAmountAndDateTime',
        ];

        if (!array_key_exists(static::getConfig('type'), $validators)) {
            throw new InvalidArgumentException('Validator method not defined.');
        }

        $method = 'validate'.$validators[static::getConfig('type')];

        if (!method_exists($this, $method)) {
            throw new InvalidArgumentException('Validator method not implement.');
        }

        $this->{$method}(static::getConfig('length'));

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateRequired($required)
    {
        $collection = Collection::make($required)->each(function($validator, $field_name) {
            $field_value = static::getConfig($field_name);
            if (!$field_value) {
                throw new InvalidArgumentException(strtr('config attribute [{field_name}] not found.', [
                    '{field_name}' => $field_name
                ]));
            }

            if (is_callable($validator)) {
                $validator($field_value);
            }
        });

        return $this;
    }

    private function validateFields($fields = [])
    {
        Collection::make($fields)->each(function ($field, $index) {
            switch ($field['type']) {
                case 'string':
                    if (strlen($field['value']) > $field['max'] or strlen($field['value']) < $field['min']) {
                        throw new InvalidArgumentException(
                            strtr('config attribute [{field_name}] length error.', [
                                '{field_name}' => $index,
                            ])
                        );
                    }
                    break;
                case 'integer':
                    if (($field['value'] > $field['max']) or ($field['value'] < $field['min'])) {
                        throw new InvalidArgumentException(
                            strtr('config attribute [{field_name}] length error.', [
                                '{field_name}' => $index,
                            ])
                        );
                    }
                    break;
                case 'timestamp':
                    $dte = Carbon::createFromTimestamp($field['value']);
                    if (!$dte) {
                        throw new InvalidArgumentException(
                            strtr('config attribute [{field_name}] format error.', [
                                '{field_name}' => $index,
                            ])
                        );
                    }
                    break;
                default:
                    break;
            }
        });
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateNoneBase($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 8 + ($length - 13),
                'min' => 8 + ($length - 13),
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateSingleBase($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 7 + ($length - 13),
                'min' => 7 + ($length - 13),
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateDoubleBase($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 6 + ($length - 13),
                'min' => 6 + ($length - 13),
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateSingleAmount($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 7 + ($length - 13),
                'min' => 7 + ($length - 13),
            ],
            'amount' => [
                'type' => 'integer',
                'value' => static::getConfig('amount'),
                'max' => (1e+8 - 1),
                'min' => 1,
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateDoubleAmount($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 6 + ($length - 13),
                'min' => 6 + ($length - 13),
            ],
            'amount' => [
                'type' => 'integer',
                'value' => static::getConfig('amount'),
                'max' => (1e+8 - 1),
                'min' => 1,
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateSingleAmountAndDate($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 3 + ($length - 13),
                'min' => 3 + ($length - 13),
            ],
            'amount' => [
                'type' => 'integer',
                'value' => static::getConfig('amount'),
                'max' => (1e+8 - 1),
                'min' => 1,
            ],
            'expired_at' => [
                'type' => 'timestamp',
                'value' => static::getConfig('expired_at'),
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateDoubleAmountAndDate($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 2 + ($length - 13),
                'min' => 2 + ($length - 13),
            ],
            'amount' => [
                'type' => 'integer',
                'value' => static::getConfig('amount'),
                'max' => (1e+8 - 1),
                'min' => 1,
            ],
            'expired_at' => [
                'type' => 'timestamp',
                'value' => static::getConfig('expired_at'),
            ],
        ]);

        return $this;
    }

    /**
     * @return VirtualAccountBuilder
     */
    private function validateSingleAmountAndDateTime($length)
    {
        static::validateFields([
            'number' => [
                'type' => 'string',
                'value' => static::getConfig('number'),
                'max' => 2 + ($length - 13),
                'min' => 2 + ($length - 13),
            ],
            'amount' => [
                'type' => 'integer',
                'value' => static::getConfig('amount'),
                'max' => (1e+8 - 1),
                'min' => 1,
            ],
            'expired_at' => [
                'type' => 'timestamp',
                'value' => static::getConfig('expired_at'),
            ],
        ]);

        return $this;
    }
}
