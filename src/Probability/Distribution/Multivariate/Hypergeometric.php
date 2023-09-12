<?php

    namespace MathPHP\Probability\Distribution\Multivariate;

    use MathPHP\Exception;
    use MathPHP\Functions\Support;
    use MathPHP\Probability\Combinatorics;

    use function array_map;
    use function array_product;
    use function array_sum;
    use function count;
    use function is_int;

    /**
     * Multivariate Hypergeometric distribution
     *
     * https://en.wikipedia.org/wiki/Hypergeometric_distribution#Multivariate_hypergeometric_distribution
     */
    class Hypergeometric {
        /**
         * Distribution parameter bounds limits
         * Kᵢ ∈ [1,∞)
         *
         * @var array{K: string}
         */
        public final const PARAMETER_LIMITS
            = [
                'K' => '[1,∞)',
            ];

        /**
         * Distribution parameter bounds limits
         * kᵢ ∈ [0,Kᵢ]
         *
         * @var array<string, array<string>>
         */
        protected array $supportLimits = [];

        /** @var array<int|float> */
        protected array $quantities;

        /**
         * Multivariate Hypergeometric constructor
         *
         * @param array<int|float> $quantities
         *
         * @throws \MathPHP\Exception\BadDataException if the quantities are not positive integers.
         */
        public function __construct(array $quantities)
        {
            if (count($quantities) === 0)
            {
                throw new Exception\BadDataException("Array cannot be empty.");
            }
            foreach ($quantities as $K)
            {
                if ( ! is_int($K))
                {
                    throw new Exception\BadDataException("Quantities must be positive integers.");
                }
                try
                {
                    Support::checkLimits(self::PARAMETER_LIMITS, ['K' => $K]);
                } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\BadParameterException $e)
                {
                }
                $this->supportLimits['k'][] = "[0,$K]";
            }
            $this->quantities = $quantities;
        }

        public static function boundsExceptions()
        {
        }

        public static function pmfException()
        {
        }

        public static function constructorException()
        {
        }

        public static function hypergeometric()
        {
        }

        /**
         * Probability mass function
         *
         * @param array<int|float> $picks
         *
         * @return float
         *
         * @throws \MathPHP\Exception\BadDataException if the picks are not whole numbers or greater than the corresponding quantity.
         * @throws \MathPHP\Exception\OutOfBoundsException
         */
        public function pmf(array $picks): float
        {
            // Must have a pick for each quantity
            if (count($picks) !== count($this->quantities))
            {
                throw new Exception\BadDataException('Number of quantities does not match number of picks.');
            }
            foreach ($picks as $i => $k)
            {
                if ( ! is_int($k))
                {
                    throw new Exception\BadDataException("Picks must be whole numbers.");
                }
                try
                {
                    Support::checkLimits(['k' => $this->supportLimits['k'][$i]],
                        ['k' => $k]);
                } catch (Exception\BadDataException|Exception\OutOfBoundsException|Exception\BadParameterException $e)
                {
                }
            }

            $n = array_sum($picks);
            $total = array_sum($this->quantities);

            $product = array_product(array_map(
            // @phpstan-ignore-next-line (Parameter #1 $callback of function array_map expects (callable(float|int, float|int): mixed)|null, Closure(int, int): float given.)
                fn(
                    int $quantity,
                    int $pick
                ) => Combinatorics::combinations($quantity, $pick),
                $this->quantities,
                $picks
            ));

            try
            {
                return $product / Combinatorics::combinations((int)$total,
                        (int)$n);
            } catch (Exception\OutOfBoundsException $e)
            {
            }
        }
    }
