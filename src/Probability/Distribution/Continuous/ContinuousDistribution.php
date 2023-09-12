<?php

    namespace MathPHP\Probability\Distribution\Continuous;

    /**
     * Interface ContinuousDistribution
     */
    interface ContinuousDistribution {
        /**
         * Probability density function
         *
         * @param float $x
         *
         * @return mixed
         */
        public function pdf(float $x): mixed;

        /**
         * Cumulative distribution function
         *
         * @param float $x
         *
         * @return int|float
         */
        public function cdf(float $x): float|int;

        /**
         * Mean average
         *
         * @return int|float
         */
        public function mean(): float|int;
    }
