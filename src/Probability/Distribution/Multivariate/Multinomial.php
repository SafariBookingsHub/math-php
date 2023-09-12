<?php

    namespace MathPHP\Probability\Distribution\Multivariate;

    use MathPHP\Exception;
    use MathPHP\Probability\Combinatorics;

    use function array_map;
    use function array_product;
    use function array_sum;
    use function count;
    use function is_int;
    use function round;

    /**
     * Multinomial distribution (multivariate)
     *
     * https://en.wikipedia.org/wiki/Multinomial_distribution
     */
    class Multinomial {
        /** @var array<int|float> */
        protected array $probabilities;

        /**
         * Multinomial constructor
         *
         * @param array<int|float> $probabilities
         *
         * @throws Exception\BadDataException if the probabilities do not add up to 1
         */
        public function __construct(array $probabilities)
        {
            // Probabilities must add up to 1
            if (round(array_sum($probabilities), 1) != 1)
                throw new Exception\BadDataException('Probabilities do not add up to 1.');

            $this->probabilities = $probabilities;
        }

        /**
         * Probability mass function
         *
         *          n!
         * pmf = ------- p₁ˣ¹⋯pkˣᵏ
         *       x₁!⋯xk!
         *
         * n = number of trials (sum of the frequencies) = x₁ + x₂ + ⋯ xk
         *
         * @param array<int> $frequencies
         *
         * @return float
         *
         * @throws \MathPHP\Exception\BadDataException if the number of frequencies does not match the number of probabilities
         */
        public function pmf(array $frequencies): float
        {
            // Must have a probability for each frequency
            if (count($frequencies) !== count($this->probabilities))
                throw new Exception\BadDataException('Number of frequencies does not match number of probabilities.');
            foreach ($frequencies as $frequency)
                if ( ! is_int($frequency))
                    throw new Exception\BadDataException("Frequencies must be integers. $frequency is not an int.");

            /** @var int $n */
            $n = array_sum($frequencies);
            try
            {
                $n！ = Combinatorics::factorial($n);
            } catch (Exception\OutOfBoundsException $e)
            {
            }

            $array_map = [];
            foreach ($frequencies as $key => $value)
                $array_map[$key]
                    = MathPHP\Probability\Combinatorics::factorial($value);
            $x₁！⋯xk！ = array_product($array_map);

            $p₁ˣ¹⋯pkˣᵏ = array_product(array_map(
                fn($x, $p) => $p ** $x,
                $frequencies,
                $this->probabilities
            ));

            return $n！ / $x₁！⋯xk！ * $p₁ˣ¹⋯pkˣᵏ;
        }

        public function PMFExceptionProbabilitiesDoNotAddUpToOne()
        {
        }

        public function pmfExceptionFrequenciesAreNotAllIntegers()
        {
        }

        public function pmfExceptionCountFrequenciesAndProbabilitiesDoNotMatch()
        {
        }
    }
