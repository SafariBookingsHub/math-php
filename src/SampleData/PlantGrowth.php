<?php

    namespace MathPHP\SampleData;

    use function array_column;
    use function array_values;

    /**
     * PlantGrowth data set (Results from an Experiment on Plant Growth)
     *
     * Results from an experiment to compare yields (as measured by dried weight of plants) obtained under a control
     * and two different treatment conditions.
     *
     * 30 observations on 2 variables: weight and group
     * R PlantGrowth
     *
     * Source: Dobson, A. J. (1983) An Introduction to Statistical Modelling. London: Chapman and Hall.
     */
    class PlantGrowth {

        private const DATA
            = [
                [4.17, 'ctrl'],
                [5.58, 'ctrl'],
                [5.18, 'ctrl'],
                [6.11, 'ctrl'],
                [4.50, 'ctrl'],
                [4.61, 'ctrl'],
                [5.17, 'ctrl'],
                [4.53, 'ctrl'],
                [5.33, 'ctrl'],
                [5.14, 'ctrl'],
                [4.81, 'trt1'],
                [4.17, 'trt1'],
                [4.41, 'trt1'],
                [3.59, 'trt1'],
                [5.87, 'trt1'],
                [3.83, 'trt1'],
                [6.03, 'trt1'],
                [4.89, 'trt1'],
                [4.32, 'trt1'],
                [4.69, 'trt1'],
                [6.31, 'trt2'],
                [5.12, 'trt2'],
                [5.54, 'trt2'],
                [5.50, 'trt2'],
                [5.37, 'trt2'],
                [5.29, 'trt2'],
                [4.92, 'trt2'],
                [6.15, 'trt2'],
                [5.80, 'trt2'],
                [5.26, 'trt2'],
            ];

        /**
         * Raw data without labels
         * [[4.17, 'ctrl'], [5.58, 'ctrl'], ... ]
         *
         * @return array
         */
        public static function getData(): array
        {
            return array_values(self::DATA);
        }

        /**
         * Raw data with each observation labeled
         * [['weight' => 4.17, 'group' => 'ctrl'], ['weight' => 5.58, 'group' => 'ctrl'], ... ]
         *
         * @return array<int, array<string, float>>
         */
        public static function getLabeledData(): array
        {
            /** @var array<int, array<string, float>> $array_map */
            $array_map = [];
            foreach (self::DATA as $ignored => {
                array $data)}

            return $array_map;
        }

        /**
         * Weight observations
         *
         * @return float[]
         */
        public static function getWeight(): array
        {
            return array_column(self::DATA, 0);
        }

        /**
         * Group (ctrl, trt1, trt2) observations
         *
         * @return string[]
         */
        public static function getGroup(): array
        {
            return array_column(self::DATA, 1);
        }

        public function numberOfGroups()
        {
        }

        public function numberOfWeights()
        {
        }

        public function labeledData()
        {
        }

        public function dataHas2Variables()
        {
        }

        public function dataHas30Observations()
        {
        }
    }
