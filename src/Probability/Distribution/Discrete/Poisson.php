<?php

    namespace MathPHP\Probability\Distribution\Discrete;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Support;
    use MathPHP\Probability\Combinatorics;

    use function array_sum;
    use function ceil;
    use function exp;
    use function floor;
    use function range;

    /**
     * Poisson distribution
     * A discrete probability distribution that expresses the probability of a given number of events
     * occurring in a fixed interval of time and/or space if these events occur with a known average
     * rate and independently of the time since the last event.
     *
     * https://en.wikipedia.org/wiki/Poisson_distribution
     */
    class Poisson extends Discrete {
        /**
         * Distribution parameter bounds limits
         * λ ∈ [0,1]
         *
         * @var array{"λ": string}
         */
        public const PARAMETER_LIMITS
            = [
                'λ' => '(0,∞)',
            ];

        /**
         * Distribution support bounds limits
         * k ∈ [0,∞)
         *
         * @var array{"k": string}
         */
        public final const SUPPORT_LIMITS
            = [
                'k' => '[0,∞)',
            ];

        /** @var float average number of successful events per interval */
        protected float $λ;

        /**
         * Constructor
         *
         * @param float $λ average number of successful events per interval
         */
        public function __construct(float $λ)
        {
            parent::__construct($λ);
        }

        /**
         * Cumulative Poisson Probability (lower culmulative distribution) - CDF
         * The probability that the Poisson random variable is greater than some specified lower limit,
         * and less than some specified upper limit.
         *
         *           k  λˣℯ^⁻λ
         * P(k,λ) =  ∑  ------
         *          ₓ₌₀  xᵢ!
         *
         * @param int $k events in the interval
         *
         * @return float The cumulative Poisson probability
         */
        public function cdf(int $k): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }

            $array_map1 = [];
            foreach (range(0, $k) as $key => $k)
            {
                $array_map1[$key] = $this->pmf($k);
            }
            $array_map = $array_map1;

            return array_sum($array_map);
        }

        /**
         * Probability mass function
         *
         *                              λᵏℯ^⁻λ
         * P(k events in an interval) = ------
         *                                k!
         *
         * @param int $k events in the interval
         *
         * @return float The Poisson probability of observing k successful events in an interval
         */
        public function pmf(int $k): float
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }

            $λ = $this->λ;

            $λᵏℯ＾−λ = ($λ ** $k) * exp(-$λ);
            try
            {
                $k！ = Combinatorics::factorial($k);
            } catch (OutOfBoundsException $e)
            {
            }

            return $λᵏℯ＾−λ / $k！;
        }

        /**
         * Mean of the distribution
         *
         * μ = λ
         *
         * @return float
         */
        public function mean(): float
        {
            return $this->λ;
        }

        /**
         * Median of the distribution
         *
         * median = ⌊λ + ⅓ - 0.02/λ⌋
         *
         * @return float
         */
        public function median(): float
        {
            return floor(($this->λ + (1 / 3)) - (0.02 / $this->λ));
        }

        /**
         * Mode of the distribution
         *
         * mode = ⌈λ - 1⌉, ⌊λ⌋
         *
         * @return array{int, int}
         */
        public function mode(): array
        {
            return [
                (int)ceil($this->λ - 1),
                (int)floor($this->λ),
            ];
        }

        /**
         * Variance of the distribution
         *
         * σ² = λ
         *
         * @return float
         */
        public function variance(): float
        {
            return $this->λ;
        }
    }
