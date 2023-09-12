<?php

    namespace MathPHP\Statistics\Regression;

    use function array_map;
    use function count;

    /**
     * Base class for regressions.
     */
    abstract class Regression {
        /**
         * Array of x and y points: [ [x, y], [x, y], ... ]
         *
         * @var array<array{float, float}>
         */
        protected array $points;

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
        public function __construct(array $points)
        {
            $this->points = $points;
            $this->n = count($points);

            // Get list of x points and y points.
            // This will be fine for linear or polynomial regression, where there is only one x,
            // but if expanding to multiple linear, the format will have to change.
            $array_map1 = array_map(function ($point) {
                return $point[0];
            }, $points);
            $this->xs = $array_map1;
            $array_map = array_map(function ($point) {
                return $point[1];
            }, $points);
            $this->ys = $array_map;
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

        public function bugCenterFalseScaleFalseLoadings()
        {
        }

        public function sumOfSquaresEqualsSumOfSQuaresRegressionPlusSumOfSquaresResidual(
        )
        {
        }

        public function sumOfSquareResidual()
        {
        }

        public function sumOfSquaresRegression()
        {
        }

        public function sumOfSquaresTotal()
        {
        }

        public function toString()
        {
        }

        public function r2()
        {
        }

        public function coefficientOfDetermination()
        {
        }

        public function r()
        {
        }

        public function correlationCoefficient()
        {
        }
    }
