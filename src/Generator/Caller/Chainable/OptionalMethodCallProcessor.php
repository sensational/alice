<?php

/*
 * This file is part of the Alice package.
 *
 * (c) Nelmio <hello@nelm.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nelmio\Alice\Generator\Caller\Chainable;

use Faker\Generator as FakerGenerator;
use LogicException;
use Nelmio\Alice\Definition\MethodCall\OptionalMethodCall;
use Nelmio\Alice\Definition\MethodCallInterface;
use Nelmio\Alice\Generator\Caller\CallProcessorAwareInterface;
use Nelmio\Alice\Generator\Caller\CallProcessorInterface;
use Nelmio\Alice\Generator\Caller\ChainableCallProcessorInterface;
use Nelmio\Alice\Generator\GenerationContext;
use Nelmio\Alice\Generator\ResolvedFixtureSet;
use Nelmio\Alice\IsAServiceTrait;
use Nelmio\Alice\ObjectInterface;

final class OptionalMethodCallProcessor implements ChainableCallProcessorInterface, CallProcessorAwareInterface
{
    use IsAServiceTrait;

    /**
     * @var CallProcessorInterface
     */
    private $processor;

    /**
     * @var FakerGenerator
     */
    private $faker;

    public function __construct(FakerGenerator $faker, ?CallProcessorInterface $processor = null)
    {
        $this->faker = $faker;
        $this->processor = $processor;
    }

    public function withProcessor(CallProcessorInterface $processor): self
    {
        return new self($this->faker, $processor);
    }

    public function canProcess(MethodCallInterface $methodCall): bool
    {
        return $methodCall instanceof OptionalMethodCall;
    }

    /**
     * @param OptionalMethodCall $methodCall
     */
    public function process(
        ObjectInterface $object,
        ResolvedFixtureSet $fixtureSet,
        GenerationContext $context,
        MethodCallInterface $methodCall
    ): ResolvedFixtureSet {
        if (false === ($methodCall instanceof OptionalMethodCall)) {
            throw new LogicException('TODO');
        }

        if (null === $this->processor) {
            throw new LogicException('TODO');
        }

        $n = $this->faker->numberBetween(0, 99);
        if ($n >= $methodCall->getPercentage()) {
            return $fixtureSet;
        }

        return $this->processor->process(
            $object,
            $fixtureSet,
            $context,
            $methodCall->getOriginalMethodCall(),
        );
    }
}
