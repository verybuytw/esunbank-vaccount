<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use VeryBuy\Payment\EsunBank\VirtualAccount\Generator\NoneVerifyCodeGenerator;
use VeryBuy\Payment\EsunBank\VirtualAccount\Generator\DoubleVerifyCodeGenerator;
use VeryBuy\Payment\EsunBank\VirtualAccount\Generator\SingleVerifyCodeGenerator;
use VeryBuy\Payment\EsunBank\VirtualAccount\Generator\VerifyCodeGeneratorContract;

class VirtualAccountBuilder
{
    use VirtualAccountConfigValidate;

    /**
     * @var string length:5
     */
    protected $companyId;

    /**
     * @var VerifyCodeGeneratorContract
     */
    protected $generator;

    /**
     * @param string $companyId
     * @param array  $config
     */
    public function __construct($companyId, array $config)
    {
        $this->setCompanyId($companyId)
            ->setConfig($config);
    }

    /**
     * @return VirtualAccount
     */
    protected function initVerifyCodeGenerator()
    {
        $generator = $this->buildGenerator(static::getConfig('type'));

        return $this->setGenerator($generator);
    }

    /**
     * @param int $type
     *
     * @return SingleVerifyCodeGenerator|DoubleVerifyCodeGenerator
     */
    protected function buildGenerator($type)
    {
        if (intval($type) === VerifyType::NONE_BASE) {
            return new NoneVerifyCodeGenerator;
        }

        return (intval($type) > 20) ? new DoubleVerifyCodeGenerator() : new SingleVerifyCodeGenerator();
    }

    /**
     * @param VerifyCodeGeneratorContract $generator
     *
     * @return VirtualAccount
     */
    protected function setGenerator(VerifyCodeGeneratorContract $generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * @return VerifyCodeGeneratorContract
     */
    protected function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @param string $companyId
     *
     * @return VirtualAccount
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param array $config
     *
     * @return VirtualAccountBuilder
     */
    public function setConfig(array $config)
    {
        $this->config = Collection::make($config);

        return $this->validateConfig()->initVerifyCodeGenerator();
    }

    /**
     * @param string|null $field
     * @param string|null $default
     *
     * @return mixed Collection|null
     */
    protected function getConfig($field = null, $default = null)
    {
        if (is_null($field)) {
            return $this->config;
        }

        return $this->config->has($field) ? $this->config->get($field) : $default;
    }

    public function make()
    {
        switch (static::getConfig('type')) {
            case VerifyType::NONE_BASE:
                return static::getGenerator()->buildWithBase(
                    static::getCompanyId(),
                    static::getConfig('number')
                );
            case VerifyType::SINGLE_BASE:
            case VerifyType::DOUBLE_BASE:
                return static::getGenerator()->buildWithBase(
                    static::getCompanyId(),
                    static::getConfig('number')
                );
            case VerifyType::SINGLE_AMOUNT:
            case VerifyType::DOUBLE_AMOUNT:
                return static::getGenerator()->buildWithAmount(
                    static::getCompanyId(),
                    static::getConfig('number'),
                    static::getConfig('amount')
                );
            case VerifyType::SINGLE_AMOUNT_DATE:
            case VerifyType::DOUBLE_AMOUNT_DATE:
                return static::getGenerator()->buildWithAmountAndDate(
                    static::getCompanyId(),
                    static::getConfig('number'),
                    static::getConfig('amount'),
                    static::getConfig('expired_at')
                );
            case VerifyType::SINGLE_AMOUNT_DATETIME:
                return static::getGenerator()->buildWithAmountAndDateTime(
                    static::getCompanyId(),
                    static::getConfig('number'),
                    static::getConfig('amount'),
                    static::getConfig('expired_at')
                );
            default:
                break;
        }

        throw new InvalidArgumentException('Undefined generator.');
    }
}
