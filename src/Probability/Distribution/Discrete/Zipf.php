<?php

    namespace MathPHP\Probability\Distribution\Discrete;

    use MathPHP\Exception;
    use MathPHP\Functions\Support;
    use MathPHP\Sequence\NonInteger;

    /**
     * Zipf's Law
     * https://en.wikipedia.org/wiki/Zipf's_law
     */
    class Zipf extends Discrete {
        /**
         * Distribution parameter bounds limits
         * s ∈ [0,∞)
         * N ∈ [1,∞)
         *
         * @var string[]
         */
        const PARAMETER_LIMITS
            = [
                's' => '[0,∞)',
                'N' => '[1,∞)',
            ];

        /**
         * Distribution support bounds limits
         * Rank
         * k ∈ [1,∞)
         *
         * @var string[]
         */
        public final const SUPPORT_LIMITS
            = [
                'k' => '[1,∞)',
            ];

        /** @var int|float Characterizing exponent */
        protected int|float $s;

        /** @var int Number of elements */
        protected int $N;

        /**
         * Constructor
         *
         * @param int|float $s exponent
         * @param int       $N elements
         */
        public function __construct($s, int $N)
        {
            parent::__construct($s, $N);
        }

        /**
         * Mode of the distribution
         *
         * μ = 1
         *
         * @return int|float
         */
        public static function mode(): float|int
        {
            return 1;
        }

        public static function badCdfK()
        {
        }

        public static function badPmfK()
        {
        }

        /**
         * Probability mass function
         *
         *            1
         * pmf = -----------
         *         kˢ * Hₙ,ₛ
         *
         * @param int $k
         *
         * @return int|float
         *
         * @throws \MathPHP\Exception\OutOfBoundsException if k is > N
         */
        public function pmf(int $k): float|int
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\BadParameterException $e)
            {
            }
            if ($k > $this->N)
            {
                throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
            }
            $s = $this->s;
            $N = $this->N;
            $series = NonInteger::generalizedHarmonic($N, $s);
            $denominator = array_pop($series);

            return (1 / $k ** $s) / $denominator;
        }

        /**
         * Cumulative distribution function
         *
         *           Hₖ,ₛ
         * pmf = ---------
         *           Hₙ,ₛ
         *
         *
         * @param int $k
         *
         * @return int|float
         *
         * @throws \MathPHP\Exception\OutOfBoundsException if k is > N
         */
        public function cdf(int $k): float|int
        {
            try
            {
                Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);
            } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\BadParameterException $e)
            {
            }
            if ($k > $this->N)
            {
                throw new Exception\OutOfBoundsException('Support parameter k cannot be greater than N');
            }
            $s = $this->s;
            $N = $this->N;
            $num_series = NonInteger::generalizedHarmonic($k, $s);
            $numerator = array_pop($num_series);
            $den_series = NonInteger::generalizedHarmonic($N, $s);
            $denominator = array_pop($den_series);

            return $numerator / $denominator;
        }

        /**
         * Mean of the distribution
         *
         *       Hₖ,ₛ₋₁
         * μ = ---------
         *        Hₙ,ₛ
         *
         * @return int|float
         */
        public function mean(): float|int
        {
            $s = $this->s;
            $N = $this->N;

            $num_series = NonInteger::generalizedHarmonic($N, $s - 1);
            $numerator = array_pop($num_series);
            $den_series = NonInteger::generalizedHarmonic($N, $s);
            $denominator = array_pop($den_series);

            return $numerator / $denominator;
        }
    }
