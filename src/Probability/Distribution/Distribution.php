<?php

    namespace MathPHP\Probability\Distribution;

    use MathPHP\Exception\BadDataException;
    use MathPHP\Exception\BadParameterException;
    use MathPHP\Exception\OutOfBoundsException;
    use MathPHP\Functions\Support;

    abstract class Distribution {
        // Overridden by implementing classes
        public const PARAMETER_LIMITS = [];

        /**
         * Constructor
         *
         * @param int|float ...$params
         */
        public function __construct(...$params)
        {
            $new_params = static::PARAMETER_LIMITS;
            $i = 0;

            foreach ($new_params as $key => $value)
            {
                $this->$key = $params[$i];
                $new_params[$key] = $params[$i];
                $i++;
            }
            try
            {
                Support::checkLimits(static::PARAMETER_LIMITS, $new_params);
            } catch (BadDataException|OutOfBoundsException|BadParameterException $e)
            {
            }
        }

        public static function stemAndLeafPlotPrint()
        {
        }

        public static function fractionalRankingDistributionSumOfAllRanks()
        {
        }
    }
