<?php

    namespace MathPHP\Statistics\Regression\Models;

    use JetBrains\PhpStorm\ArrayShape;

    use function sprintf;

    trait LinearModel {
        /** @var int b parameter index */
        protected static int $B = 0;

        /** @var int m parameter index */
        protected static int $M = 1;

        /**
         * Evaluate the model given all the model parameters
         * y = mx + b
         *
         * @param float             $x
         * @param array<int, float> $params
         *
         * @return float y evaluated
         */
        public static function evaluateModel(float $x, array $params): float
        {
            $m = $params[self::$M];
            $b = $params[self::$B];

            return ($m * $x) + $b;
        }

        /**
         * Get regression parameters (coefficients)
         * m = slope
         * b = y intercept
         *
         * @param array<int, float> $params
         *
         * @return array{m: float, b: float} [ m => number, b => number ]
         */
        #[ArrayShape([
            'm' => "float",
            'b' => "float",
        ])] public function getModelParameters(array $params): array
        {
            return [
                'm' => $params[self::$M],
                'b' => $params[self::$B],
            ];
        }

        /**
         * Get regression equation (y = mx + b)
         *
         * @param array<int, float> $params
         *
         * @return string
         */
        public function getModelEquation(array $params): string
        {
            return sprintf('y = %fx + %f', $params[self::$M],
                $params[self::$B]);
        }
    }
