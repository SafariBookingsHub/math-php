<?php

    namespace MathPHP\Statistics\Regression;

    use function array_map;
    use function count;

    /**
     * Base class for regressions.
     */
    abstract class Regression {

        /**
         * X values of the original points
         *
         * @var array<float>
         */
        protected array $xs;

        /**
         * Y values of the original points
         *
         * @var array<float>
         */
        protected array $ys;

        /**
         * Number of points
         *
         * @var int
         */
        protected int $n;

        /**
         * Constructor - Prepares the data arrays for regression analysis
         *
         * @param array<array{float, float}> $points [ [x, y], [x, y], ... ]
         */
        public function __construct(protected array $points)
        {
            $this->n = count($points);

            // Get list of x points and y points.
            // This will be fine for linear or polynomial regression, where there is only one x,
            // but if expanding to multiple linear, the format will have to change.
            $array_map3 = [];
            foreach ($points as $key => $point)
            {
                $array_map3[$key] = $point[0];
            }
            $array_map1 = $array_map3;
            $this->xs = $array_map1;
            $array_map2 = [];
            foreach ($points as $key => $point)
            {
                $array_map2[$key] = $point[1];
            }
            $array_map = $array_map2;
            $this->ys = $array_map;
        }

        public static function bugCenterFalseScaleFalseLoadings()
        {
        }

        public static function sumOfSquaresEqualsSumOfSQuaresRegressionPlusSumOfSquaresResidual(
        )
        {
        }

        public static function sumOfSquareResidual()
        {
        }

        public static function sumOfSquaresRegression()
        {
        }

        public static function sumOfSquaresTotal()
        {
        }

        public static function toString()
        {
        }

        public static function r2()
        {
        }

        public static function coefficientOfDetermination()
        {
        }

        public static function r()
        {
        }

        public static function correlationCoefficient()
        {
        }

        /**
         * Evaluate the regression equation at x
         *
         * @param float $x
         *
         * @return float
         */
        abstract public function evaluate(float $x): float;

        /**
         * Get points
         *
         * @return array<array{float, float}>
         */
        public function getPoints(): array
        {
            return $this->points;
        }

        /**
         * Get Xs (x values of each point)
         *
         * @return array<float> of x values
         */
        public function getXs(): array
        {
            return $this->xs;
        }

        /**
         * Get Ys (y values of each point)
         *
         * @return array<float> of y values
         */
        public function getYs(): array
        {
            return $this->ys;
        }

        /**
         * Get sample size (number of points)
         *
         * @return int
         */
        public function getSampleSize(): int
        {
            return $this->n;
        }

        /**
         * Ŷ (yhat)
         * A list of the predicted values of Y given the regression.
         *
         * @return array<float>
         */
        public function yHat(): array
        {
            return array_map([$this, 'evaluate'], $this->xs);
        }
    }
